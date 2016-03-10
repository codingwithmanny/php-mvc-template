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
        $this->model_name = 'Users';
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
        $results = $this->model->all($query);

        //load view
        $parent_template = ($this->json_request()) ? 'json' : $this->parent_template;
        $this->load_view($this->model_name . '/all', $parent_template, $results);
    }

    /**
     *
     */
    public function create_form()
    {
        //load view
        $this->load_view($this->model_name . '/edit', $this->parent_template);
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
        $post['created'] = $post['modified'] = date('Y-m-d H:i:s');

        $results = $this->model->create($post);

        if(array_key_exists('data', $results) && $results['data'] == true && !$this->json_request()) {
            header('Location: /' . $this->model_name);
        } else {
            $this->load_view($this->model_name . '/edit', $parent_template, $results);
        }
    }

    /**
     *
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
        $this->load_view($this->model_name . '/read', $parent_template, $results);
    }

    /**
     *
     */
    public function update_form()
    {
        //load view
        $this->load_view($this->model_name . '/edit', $this->parent_template);
    }

    //@TODO: update_form
    //@TODO: update
    //@TODO: delete
}