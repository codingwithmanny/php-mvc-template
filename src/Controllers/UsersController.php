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


    public function index()
    {
        return $this->model->all();
        /*
        echo 'result: '.$this->json_request();
        echo '<pre>';
        var_dump(getallheaders());
        echo '</pre>';
        */
    }
}