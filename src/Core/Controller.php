<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-02-23
 * Time: 2:29 PM
 */

namespace App\Core;

use App\Core\View;

class Controller
{
    /**
     * @var
     */
    protected $model;

    /**
     * @var
     */
    protected $model_name;

    /**
     * @var
     */
    protected $template_dir;

    /**
     * @var
     */
    protected $parent_template = 'default';

    /**
     * @var bool
     */
    protected $middleware = false;

    /**
     * Controller constructor.
     * @param null $model
     */
    public function __construct($model = null)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if($model != null) {
            $this->model = $model;
        }
    }

    /**
     * @param array $query
     * @param bool $select
     * @param bool $return
     * @return mixed
     */
    public function _index($query = [], $select = false, $return = false)
    {
        //request
        $query['params'] = $this->model->helper_paramscleanup($_GET);

        $results = $this->model->all($query, $select);

        $role = false;
        if($this->middleware != false) {
            $role = $this->middleware->get_role();
        }
        $results = array_merge($results, ['role' => $role]);
        if($return) {
            return $results;
        } else {
            //load view
            $parent_template = ($this->json_request()) ? 'json' : $this->parent_template;

            $this->load_view($this->template_dir . '/all', $parent_template, $results);
        }
    }

    /**
     * @param null $template
     * @param null $parent_template
     */
    public function _create_form($template = null, $parent_template = null)
    {
        $template = ($template == null) ? $this->template_dir . '/create' : $template;
        $parent_template = ($parent_template == null) ? $this->parent_template : $parent_template;

        //load view
        $role = false;
        if($this->middleware != false) {
            $role = $this->middleware->get_role();
        }
        $this->load_view($template, $parent_template, ['fields' => $this->model->helper_required_options(true, $role), 'role' => $role]);
    }

    /**
     * @param array $args
     * @param null $template
     * @param null $parent_template
     */
    public function _create($args = [], $template = null, $parent_template = null)
    {
        $template = ($template == null) ? $this->model_name . '/create' : $template;
        $parent = ($this->json_request()) ? 'json' : $this->parent_template;
        $parent_template = ($parent_template == null) ? $parent : $parent_template;

        $results = $this->model->create($args);
        $role = false;
        if($this->middleware != false) {
            $role = $this->middleware->get_role();
        }
        $results = array_merge($results, ['role' => $role]);

        if(array_key_exists('errors', $results) && !$this->json_request()) {
            $errors = [];
            foreach($results['errors'] as $key => $value) {
                if(gettype($value) == 'string') {
                    array_push($errors, $value);
                } else {
                    foreach ($value as $k => $v) {
                        array_push($errors, $k);
                    }
                }
            }
            $errors = implode(',', $errors);
            header('Location: /' . $template . '?errors='.$errors);
        } else if(array_key_exists('data', $results) && $results['data'] == true && !$this->json_request()) {
            header('Location: /' . $this->model_name);
        } else {
            $this->load_view($template, $parent_template, $results);
        }
    }

    /**
     * @param null $query
     * @param null $template
     * @param null $parent_template
     * @param bool $return
     * @return mixed
     */
    public function _read($query = null, $template = null, $parent_template = null, $return = false)
    {
        $results = $this->model->read($query);
        $role = false;
        if($this->middleware != false) {
            $role = $this->middleware->get_role();
        }
        $results = array_merge($results, ['role' => $role]);

        if($return) {
            return $results;
        } else {
            //load view
            $template = ($template == null) ? $this->template_dir . '/read' : $template;
            $parent = ($this->json_request()) ? 'json' : $this->parent_template;
            $parent_template = ($parent_template == null) ? $parent : $parent_template;
            $this->load_view($template, $parent_template, $results);
        }
    }

    /**
     * @param null $query
     * @param null $template
     * @param null $parent_template
     */
    public function _update_form($query = null, $template = null, $parent_template = null)
    {
        $results = $this->model->read($query);

        $role = false;
        if($this->middleware != false) {
            $role = $this->middleware->get_role();
        }

        $results = array_merge($results, ['fields' => $this->model->helper_required_options(true, $role)]);
        $results = array_merge($results, ['role' => $role]);

        //load view
        $template = ($template == null) ? $this->template_dir . '/edit' : $template;
        $parent_template = ($parent_template == null) ? $this->parent_template : $parent_template;

        $this->load_view($template, $parent_template, $results);
    }

    /**
     * @param null $query
     * @param array $args
     * @param null $template
     * @param null $parent_template
     */
    public function _update($query = null, $args = [], $template = null, $parent_template = null)
    {
        $template = ($template == null) ? $this->model_name . '/' . $query['where'][0][2] . '/edit' : $template;
        $parent = ($this->json_request()) ? 'json' : $this->parent_template;
        $parent_template = ($parent_template == null) ? $parent : $parent_template;

        $role = false;
        if($this->middleware != false) {
            $role = $this->middleware->get_role();
        }

        $results = $this->model->update($query, $args, $role);

        $role = false;
        if($this->middleware != false) {
            $role = $this->middleware->get_role();
        }
        $results = array_merge($results, ['role' => $role]);

        if(array_key_exists('errors', $results) && !$this->json_request()) {
            $errors = [];
            foreach($results['errors'] as $key => $value) {
                if(gettype($value) == 'string') {
                    array_push($errors, $value);
                } else {
                    foreach ($value as $k => $v) {
                        array_push($errors, $k);
                    }
                }
            }
            $errors = implode(',', $errors);
            header('Location: /' . $template . '?errors='.$errors);
        } else if(array_key_exists('data', $results) && $results['data'] == true && !$this->json_request()) {
            header('Location: /' . $template);
        } else {
            $this->load_view($template, $parent_template, $results);
        }
    }

    /**
     * @param null $query
     * @param null $template
     * @param null $parent_template
     */
    public function _delete($query = null, $template = null, $parent_template = null)
    {
        $template = ($template == null) ? $this->model_name : $template;
        $parent = ($this->json_request()) ? 'json' : $this->parent_template;
        $parent_template = ($parent_template == null) ? $parent : $parent_template;

        $results = $this->model->delete($query);
        $role = false;
        if($this->middleware != false) {
            $role = $this->middleware->get_role();
        }
        $results = array_merge($results, ['role' => $role]);

        if(array_key_exists('errors', $results) && !$this->json_request()) {
            $errors = [];
            foreach($results['errors'] as $key => $value) {
                foreach ($value as $k => $v) {
                    array_push($errors, $k);
                }
            }
            $errors = implode(',', $errors);
            header('Location: /' . $template . '/?errors='.$errors);
        } else if(array_key_exists('data', $results) && $results['data'] == true && !$this->json_request()) {
            header('Location: /' . $template);
        } else {
            $this->load_view($template, $parent_template, $results);
        }
    }

    /**
     * @return mixed
     */
    function get_payload($all = false)
    {
        $post = $_POST;

        if($this->json_request()) {
            $request_body = file_get_contents('php://input');
            $post = json_decode($request_body, true);
        }

        return ($all) ? $post : $this->model->helper_fieldscleanup($post);
    }

    /**
     * @param null $view
     * @param null $data
     * @return \App\Core\View
     */
    public function load_view($view = null, $parent_view = false, $data = null)
    {
        $parent = ($parent_view != false) ? $parent_view : $this->parent_template;
        $parent = 'Layouts/' . $parent;
        return new View($view, $parent, $data);
    }

    /**
     * @return bool
     */
    public function json_request()
    {
        $headers = getallheaders();
        if(
        (array_key_exists('Accept', $headers) && strpos('application/json', $headers['Accept']) > -1)
        || (array_key_exists('Content-Type', $headers) && strpos('application/json', $headers['Content-Type']) > -1)) {
            return true;
        }

        return false;
    }

    /**
     * @param $one
     * @param $two
     * @return array
     */
    function date_diff($one, $two)
    {
        $from_time = strtotime($one);
        $to_time = strtotime($two);

        return round(abs($to_time - $from_time) / 60,2);
    }

}