<?php

namespace App\Core;

require(CONFIG . 'config.php');

class Model
{
    /**
     * @var null
     */
    public $table = null;

    /**
     * @var array
     */
    protected $fields_all = [];

    /**
     * @var array
     */
    protected $fields_required = [];


    /**
     * @var array
     */
    protected $fields_viewable = ['*'];


    /**
     * @var array
     */
    protected $params_default = ['page' => 1, 'limit' => 100, 'order' => 'id', 'sort' => 'asc'];

    /**
     * @var \PDO
     */
    protected $db;

    /**
     * Model constructor.
     * @param string $table
     */
    public function __construct($table = null)
    {
        try {
            $this->db = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
        }
        catch(\PDOException $e) {
            echo $e->getMessage();
        }
        $this->table = $table;
    }

    /**
     * @param $query_args
     * @param bool $select_all
     * @return array|string
     */
    public function all($query_args = [], $select_all = false)
    {
        $query = 'SELECT ';
        $query .= ($select_all) ? '* ' : $this->helper_viewable() . ' ';
        $values = [':table' => $this->table];
        $params = [];
        $errors = [];

        //Custom query
        if(array_key_exists('query', $query_args)) {
            $query .= $query_args['query'];
            if(stripos($query_args['query'], ':table') === false || stripos($query_args['query'], 'FROM') === false) {
                array_push($errors, [':table' => 'Variable missing from query.']);
            }
        } else {
            $query .= 'FROM :table';
        }

        //Simple query
        if(array_key_exists('where', $query_args)) {
            $get = $this->helper_query($query_args);

            $query .= $get['query'];
            $values = $get['values'];
            $errors = $get['errors'];
        }

        //Parameters
        if(array_key_exists('params', $query_args)) {
            //add new params
            foreach ($query_args['params'] as $key => $value) {
                if(array_key_exists($key, $this->params_default)) {
                    $params[$key] = $value;
                } else {
                    array_push($errors, [$key => 'Invalid field.']);
                }
            }
        }

        //add remaining default params
        foreach ($this->params_default as $key => $value) {
            if(!array_key_exists($key, $params)) {
                $params[$key] = $value;
            }
        }

        //append to query
        $query_end = ' ORDER BY ' . $params['order'] . ' ' . $params['sort'] . ' LIMIT ' . $params['limit'] . ' OFFSET ' . (($params['page'] - 1) * $params['limit']);

        //add table name
        $query = str_replace(':table', $this->table, $query);
        unset($values[':table']);

        if((count($errors) > 0)) return ['errors' => $errors];

        //count results
        $count_results = $this->db->prepare($query);
        $count_results->execute($values);

        //execute results
        $results = $this->db->prepare(($query . $query_end));
        $results->execute($values);
        $count = $count_results->rowCount();

        //pagination
        $pagination = [
            'page' => $params['page'],
            'limit' => $params['limit'],
            'order' => $params['order'],
            'sort' => $params['sort'],
            'count' => $count,
            'has_next' => ($count - ($params['page'] * $params['limit']) > 0) ? true : false,
            'has_prev' => ($params['page'] > 1 && (($params['page']-1)*$params['limit'] < $count)) ? true : false
        ];

        return ['data' => $results->fetchAll(\PDO::FETCH_ASSOC), 'pagination' => $pagination];
    }

    /**
     * @param array $data_args
     * @return array
     */
    public function create($data_args = [])
    {

        if(count($data_args) == 0) {
            header('HTTP/1.1 412 Precondition Failed');
            return ['errors' => ['Arguments' => 'Missing data arguments.']];
        }

        $errors = [];
        $values = [];
        $data_keys = '';
        $data_vars = '';

        foreach ($data_args as $key => $value) {
            if(!in_array($key, $this->fields_all)) {
                array_push($errors, [$key => 'Invalid field.']);
            }
        }
        if((count($errors) > 0)) {
            header('HTTP/1.1 412 Precondition Failed');
            return ['errors' => $errors];
        }

        foreach ($this->fields_required as $key => $value) {
            if(!array_key_exists($value, $data_args)) {
                array_push($errors, [$value => 'Required field missing.']);
            } else {
                $data_keys .= $value . ', ';
                $data_vars .= ':' . $value . ', ';
                $values[':' . $value] = $data_args[$value];
            }
        }
        if((count($errors) > 0)) {
            header('HTTP/1.1 412 Precondition Failed');
            return ['errors' => $errors];
        }

        $data_keys = substr($data_keys, 0, -2);
        $data_vars = substr($data_vars, 0, -2);
        $query = 'INSERT INTO ' . $this->table . ' (' . $data_keys . ') VALUES (' . $data_vars . ')';

        $results = $this->db->prepare($query);
        $result = $results->execute($values);
        $error_info = $results->errorInfo();
        if(count($error_info) >= 3) {
            if($error_info[1] != null) {
                $error_msg = ($error_info[1] == 1062) ? ['Duplicate' => 'Entry already exists.'] : ['Other' => $error_info[2]];
                header('HTTP/1.1 409 Conflict');
                return ['errors' => $error_msg];
            }
        }

        return ['data' => $result];
    }

    /**
     * @param array $query_args
     * @param bool $select_all
     * @return array
     */
    public function read($query_args = [], $select_all = false)
    {
        if(count($query_args) == 0) {
            header('HTTP/1.0 404 Not Found');
            return ['errors' => ['Arguments' => 'Missing arguments.']];
        }

        $query = 'SELECT ';
        $query .= ($select_all) ? '* ' : $this->helper_viewable() . ' ';
        $values = [];
        $query .= 'FROM ' .$this->table;
        $errors = [];

        //Simple query
        if(array_key_exists('where', $query_args)) {
            $get = $this->helper_query($query_args);

            $query .= $get['query'];
            $values = $get['values'];
            $errors = $get['errors'];
        }

        if((count($errors) > 0)) {
            header('HTTP/1.0 404 Not Found');
            return ['errors' => $errors];
        }

        //execute results
        $results = $this->db->prepare(($query));
        $results->execute($values);
        $results = $results->fetch(\PDO::FETCH_ASSOC);

        if(!$results) {
            header('HTTP/1.0 404 Not Found');
            array_push($errors, ['Not Found' => 'Entry does not exist.']);
            return ['errors' => $errors];
        }

        return ['data' => $results];
    }

    /**
     * @param array $data_args
     * @param array $query_args
     * @return array
     */
    public function update($data_args = [], $query_args = [])
    {
        if(count($data_args) == 0) {
            header('HTTP/1.1 412 Precondition Failed');
            return ['errors' => ['Arguments' => 'Missing data arguments.']];
        }
        if(count($query_args) == 0) {
            header('HTTP/1.1 412 Precondition Failed');
            return ['errors' => ['Arguments' => 'Missing arguments.']];
        }
        if(!array_key_exists('data', $this->read($query_args))) {
            header('HTTP/1.0 404 Not Found');
            return ['errors' => ['Not Found' => 'Entry does not exist.']];
        }

        $query = 'UPDATE ';
        $values = [];
        $query .= $this->table . ' SET ';
        $errors = [];

        //Data arguments
        foreach($data_args as $key => $value) {
            if (in_array($key, $this->fields_all)) {
                $query .= $key . ' = :' . $key . ', ';
                $values[':' . $key] = $value;
            } else if (!in_array($key, $this->fields_all)) {
                array_push($errors, [$key => 'Invalid field.']);
            }
        }

        $query = substr($query, 0, -2);

        //Simple query
        if(array_key_exists('where', $query_args)) {
            $get = $this->helper_query($query_args);

            $query .= $get['query'];
            $values = array_merge($values, $get['values']);
            $errors = array_merge($errors, $get['errors']);
        }

        if((count($errors) > 0)) return ['errors' => $errors];

        $results = $this->db->prepare($query);
        return ['data' => $results->execute($values)];
    }

    /**
     * @param array $query_args
     * @return array
     */
    public function delete($query_args = [])
    {
        if(count($query_args) == 0) {
            header('HTTP/1.1 412 Precondition Failed');
            return ['errors' => ['Arguments' => 'Missing arguments.']];
        }
        if(!array_key_exists('data', $this->read($query_args))) {
            header('HTTP/1.0 404 Not Found');
            return ['errors' => ['Not Found' => 'Entry does not exist.']];
        }

        $query = 'DELETE ';
        $values = [];
        $query .= 'FROM ' .$this->table;

        //Simple query
        if(array_key_exists('where', $query_args)) {
            $get = $this->helper_query($query_args);

            $query .= $get['query'];
            $values = $get['values'];
            $errors = $get['errors'];
        }

        if((count($errors) > 0)) return ['errors' => $errors];

        //execute results
        $results = $this->db->prepare(($query));
        return ['data' => $results->execute($values)];
    }

    /**
     * @return string
     */
    private function helper_viewable()
    {
        return implode(', ', $this->fields_viewable);
    }

    /**
     * @param $query_args
     * @return array
     */
    private function helper_query($query_args)
    {
        $query = '';
        $values = [];
        $errors = [];
        foreach ($query_args['where'] as $key => $value) {
            if (in_array($value[0], $this->fields_all) && count($value) == 3) {
                $query .= ' WHERE ' . $value[0] . ' ' . $value[1] . ' :' . $value[0];
                $values[':' . $value[0]] = $value[2];
            } else if(count($value) == 3) {
                array_push($errors, [$value[0] => 'Invalid field.']);
            } else {
                array_push($errors, ['Format' => 'Invalid query format.']);
                break;
            }
        }

        return ['query' => $query, 'values' => $values, 'errors' => $errors];
    }

    /**
     * @param array $query_args
     * @param array $compare_args
     * @return array
     */
    public function helper_cleanup($query_args = [], $compare_args = [], $compare_keys = true, $all_required = false)
    {
        if(count($query_args) == 0) return [];
        $new_query_args = [];

        if($compare_keys) {
            foreach ($compare_args as $key => $value) {
                if (array_key_exists($key, $query_args) && $query_args[$key] != null) {
                    $new_query_args[$key] = $query_args[$key];
                }
            }

            if($all_required) {
                foreach ($compare_args as $key => $value) {
                    if (!array_key_exists($key, $query_args)) {
                        $new_query_args = [];
                        break;
                    }
                }
            }
        } else {
            foreach ($compare_args as $key => $value) {
                if (array_key_exists($value, $query_args)) {
                    if($query_args[$value] != null) {
                        $new_query_args[$value] = $query_args[$value];
                    }
                }
            }

            if($all_required) {
                foreach ($compare_args as $key => $value) {
                    if (!array_key_exists($value, $query_args)) {
                        $new_query_args = [];
                        break;
                    }
                }
            }
        }

        return $new_query_args;
    }

    /**
     * @param array $query_args
     * @return array
     */
    public function helper_paramscleanup($query_args = [])
    {
        if(count($query_args) == 0) return [];
        return $this->helper_cleanup($query_args, $this->params_default);
    }

    /**
     * @param array $query_args
     * @return array
     */
    public function helper_fieldscleanup($query_args = [])
    {
        if(count($query_args) == 0) return [];
        return $this->helper_cleanup($query_args, $this->fields_required, false);
    }


}