<?php

//require(CONFIG . 'config.php');

namespace App\Core;

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
    protected $fields_editable = [];

    /**
     * @var array
     */
    protected $fields_viewable = ['*'];

    /**
     * @var array
     */
    protected $fields_searchable = [];

    /**
     * @var array
     */
    protected $params_default = ['page' => 1, 'limit' => 100, 'order' => 'id', 'sort' => 'asc'];

    /**
     * @var array
     */
    protected $params_model = [];

    /**
     * @var bool
     */
    protected $params_default_fallback = false;

    /**
     * @var array
     */
    protected $errors = [];

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
//            $this->db = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
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
    public function all($query_args, $select_all = false)
    {

        $query = 'SELECT ';
        $query .= ($select_all) ? '* ' : $this->get_viewable().' ';

        if(array_key_exists('query', $query_args)) {
            $query .= $query_args['query'];
            if(stripos($query_args['query'], ':table') === false) {
                array_push($this->errors, [':table' => 'Variable missing from query.']);
            }
        } else {
            $query .= 'FROM :table ';
        }

        foreach($query_args['where'] as $key => $value) { //'where' => [['field', '=', 'value']];
            if(in_array($value[0], $this->fields_all)) {
                $query .= ' WHERE ' . $value[0] . ' ' . $value[1] . ':' . $value[0];
            } else {
                array_push($this->errors, [$value[0] => 'Invalid field.']);
            }
        }

        //@TODO: if errors do not perform the query

        $results = $query;

        //@TODO: finish full query
        //@TODO: get total count
        //@TODO: if empty $results returns empty array
        //@TODO: parameter/fields array
        //@TODO: pagination results

        return (count($this->errors) > 0) ? $this->errors : $results;
    }

    public static function create()
    {

    }

    public static function read($key)
    {

    }

    public static function update($key)
    {

    }

    public static function delete($key)
    {

    }

    /**
     * @return string
     */
    private function get_viewable()
    {
        return implode(', ', $this->fields_viewable);
    }


}