<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-10
 * Time: 10:50 PM
 */

namespace App\Controllers;

use App\Core\Controller;

class TagsController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->model_name = 'tags';
        $this->template_dir = 'templates';
        $model = new \App\Models\TagsModel();
        parent::__construct($model);
    }

    /**
     *
     */
    public function index()
    {
        $q = null;
        $query = [];
        if(array_key_exists('q', $_GET) && $_GET['q'] != null) {
            $query = [
                'where' => [
                    ['name', 'LIKE', '%' . $_GET['q'] . '%'],
                ]
            ];
        }

        $this->_index($query);
    }

    /**
     *
     */
    public function create_form()
    {
        $this->_create_form();
    }

    /**
     *
     */
    public function create()
    {
        $args = $this->get_payload();
        $args['created'] = $args['modified'] = date('Y-m-d H:i:s', time());
        $this->_create($args);
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
        $this->_read($query);
    }


    /**
     * @param $id
     */
    public function update_form($id)
    {
        $query = [
            'where' => [
                ['id', '=', $id]
            ]
        ];
        $this->_update_form($query);
    }

    /**
     * @param null $id
     */
    public function update($id = null)
    {
        $query = [
            'where' => [
                ['id', '=', $id]
            ]
        ];

        $args = $this->get_payload();
        $args['modified'] = date('Y-m-d H:i:s', time());
        if(array_key_exists('password', $args)) {
            $args['password'] = hash_hmac('sha256', $args['password'], SECRET);
        }
        $this->_update($query, $args);
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

        $this->_delete($query);
    }
}