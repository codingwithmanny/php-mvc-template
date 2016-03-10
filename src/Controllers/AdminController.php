<?php

/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-02-23
 * Time: 2:36 PM
 */
namespace App\Controllers;

use App\Core\Controller;

class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->model_name = 'admin';
        $model = new \App\Models\UsersModel();
        parent::__construct($model);
    }

    /**
     *
     */
    public function dashboard()
    {
        //load view
        $token = (isset($_SESSION['WEBTOKEN'])) ? $_SESSION['WEBTOKEN'] : null;
//        @TODO: Revise token
        $this->load_view($this->model_name . '/dashboard', $this->parent_template, ['webtoken' => $token]);
    }
}