<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-10
 * Time: 4:44 PM
 */

namespace App\Controllers;

use App\Controllers\ItemsController;

class UserItemsController extends ItemsController
{
    private $user;

    /**
     * UserItemsController constructor.
     */
    public function __construct()
    {
        $this->model_name = 'items';
        $this->template_dir = 'templates';
        $model = new \App\Models\ItemsModel();
        $this->user = new \App\Models\UsersModel();
        parent::__construct($model);
    }

    /**
     * @param null $user_id
     */
    public function index($user_id = null)
    {
        $q = null;
        if(array_key_exists('q', $_GET) && $_GET['q'] != null) {
            $q = 'FROM :table WHERE name LIKE \'%' . $_GET['q'] . '%\' OR description LIKE \'%' . $_GET['q'] . '%\'';
        }

        $query = [
            'where' => [
                ['user_id', '=', $user_id]
            ]
        ];
        $this->_index($q, $query);
    }


}