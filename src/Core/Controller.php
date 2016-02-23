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
     * @param null $view
     * @param null $data
     * @return \App\Core\View
     */
    public function load_view($view = null, $data = null)
    {
        return new View($view, $data);
    }

    /**
     * @return bool
     */
    public function json_request()
    {
        $headers = getallheaders();
        if(array_key_exists('Accept', $headers) && strpos('application/json', $headers['Accept']) > -1) {
            return false;
        }

        return true;
    }

}