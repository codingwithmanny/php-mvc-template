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
    protected $parent_template = 'default';

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

}