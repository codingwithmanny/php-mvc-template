<?php

/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-02-23
 * Time: 2:36 PM
 */
namespace App\Controllers;

use App\Core\Controller;

class UsersController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->model_name = 'users';
        $this->template_dir = 'templates';
        $model = new \App\Models\UsersModel();
        parent::__construct($model);
    }

    /**
     *
     */
    public function index()
    {
        //request
        $query = [
            'params' => $this->model->helper_paramscleanup($_GET)
        ];

        if(array_key_exists('q', $_GET) && $_GET['q'] != null) {
//            $query['query'] = 'FROM :table WHERE email LIKE %' . $_GET['q'] . '% OR first_name LIKE %' . $_GET['q'] . '% OR last_name LIKE %' . $_GET['q'] . '%';
            $query['query'] = 'FROM :table WHERE email LIKE \'%' . $_GET['q'] . '%\' OR first_name LIKE \'%' . $_GET['q'] . '%\' OR last_name LIKE \'%' . $_GET['q'] . '%\'';
        }
        $results = $this->model->all($query);

        //load view
        $parent_template = ($this->json_request()) ? 'json' : $this->parent_template;
        $this->load_view($this->template_dir . '/all', $parent_template, $results);
    }

    /**
     *
     */
    public function create_form()
    {
        //load view
        $this->load_view($this->template_dir . '/create', $this->parent_template, ['fields' => $this->model->helper_required_options(true)]);
    }

    /**
     *
     */
    public function create()
    {
        $parent_template = $this->parent_template;
        $post = $_POST;

        if($this->json_request()) {
            $parent_template = 'json';
            $request_body = file_get_contents('php://input');
            $post = json_decode($request_body, true);
        }
        $post = $this->model->helper_fieldscleanup($post);
        $post['created'] = $post['modified'] = date('Y-m-d H:i:s', time());
        if(array_key_exists('password', $post)) {
            $post['password'] = hash_hmac('sha256', $post['password'], SECRET);
        }

        $results = $this->model->create($post);

        if(array_key_exists('errors', $results) && !$this->json_request()) {
            $errors = [];
            foreach($results['errors'] as $key => $value) {
                foreach ($value as $k => $v) {
                    array_push($errors, $k);
                }
            }
            $errors = implode(',', $errors);
            header('Location: /' . $this->model_name . '/create?errors='.$errors);
        } else if(array_key_exists('data', $results) && $results['data'] == true && !$this->json_request()) {
            header('Location: /' . $this->model_name);
        } else {
            $this->load_view($this->template_dir . '/create', $parent_template, $results);
        }
    }

    /**
     * @param null $id
     */
    public function read($id = null)
    {
        //request
        $query = [
            'where' => [
                ['id', '=', $id]
            ]
        ];
        $results = $this->model->read($query);

        //load view
        $parent_template = ($this->json_request()) ? 'json' : $this->parent_template;
        $this->load_view($this->template_dir . '/read', $parent_template, $results);
    }

    /**
     * @param $id
     */
    public function update_form($id)
    {
        //request
        $query = [
            'where' => [
                ['id', '=', $id]
            ]
        ];
        $results = $this->model->read($query);

        $results = array_merge($results, ['fields' => $this->model->helper_required_options(true)]);

        //load view
        $this->load_view($this->template_dir . '/edit', $this->parent_template, $results);
    }

    /**
     * @param null $id
     */
    public function update($id = null)
    {
        $parent_template = $this->parent_template;
        $post = $_POST;

        if($this->json_request()) {
            $parent_template = 'json';
            $request_body = file_get_contents('php://input');
            $post = json_decode($request_body, true);
        }
        $post = $this->model->helper_fieldscleanup($post);
        $post['modified'] = date('Y-m-d H:i:s');
        if(array_key_exists('password', $post)) {
            $post['password'] = hash_hmac('sha256', $post['password'], SECRET);
        }

        //request
        $query = [
            'where' => [
                ['id', '=', $id]
            ]
        ];

        $results = $this->model->update($query, $post);

        if(array_key_exists('errors', $results) && !$this->json_request()) {
            $errors = [];
            foreach($results['errors'] as $key => $value) {
                foreach ($value as $k => $v) {
                    array_push($errors, $k);
                }
            }
            $errors = implode(',', $errors);
            header('Location: /' . $this->model_name . '/' . $id . '/edit?errors='.$errors);
        } else if(array_key_exists('data', $results) && $results['data'] == true && !$this->json_request()) {
            header('Location: /' . $this->model_name . '/' . $id . '/edit');
        } else {
            $this->load_view($this->template_dir . '/' . $id . '/edit', $parent_template, $results);
        }
    }

    /**
     * @param null $id
     */
    public function delete($id = null)
    {
        //request
        $query = [
            'where' => [
                ['id', '=', $id]
            ]
        ];
        $results = $this->model->delete($query);

        if(array_key_exists('errors', $results) && !$this->json_request()) {
            $errors = [];
            foreach($results['errors'] as $key => $value) {
                foreach ($value as $k => $v) {
                    array_push($errors, $k);
                }
            }
            $errors = implode(',', $errors);
            header('Location: /' . $this->model_name . '/?errors='.$errors);
        } else if(array_key_exists('data', $results) && $results['data'] == true && !$this->json_request()) {
            header('Location: /' . $this->model_name);
        } else {
            $parent_template = ($this->json_request()) ? 'json' : $this->parent_template;
            $this->load_view($this->template_dir, $parent_template, $results);
        }
    }
}