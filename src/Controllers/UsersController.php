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
        $model = new \App\Models\UsersModel();
        parent::__construct($model);
    }

    /**
     *
     */
    public function index()
    {
        $results = $this->model->all();

        $template = ($this->json_request()) ? 'Users/all' : null;
        $this->load_view($template, $results);
    }
}