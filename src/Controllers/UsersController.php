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
        $this->parent_template = 'default';
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
        $this->load_view($this->model_name . '/all', $results, $parent_template);
    }

    /**
     *
     */
    public function create_form()
    {
        //load view
        $this->load_view($this->model_name . '/edit', $results, $this->parent_template);
    }

    public function create()
    {
        $template = 'Users/create';
//        if($this->json_request()) {
//
//        }
//        $results = $this->model->create($this->model->helper_fieldscleanup($_POST))
    }
}