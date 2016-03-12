<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-10
 * Time: 3:04 PM
 */

namespace App\Controllers;

use App\Core\Controller;

class ItemsController extends Controller
{
    /**
     * ItemsController constructor.
     */
    public function __construct()
    {
        $this->model_name = 'items';
        $this->template_dir = 'templates';
        $model = new \App\Models\ItemsModel();
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
                    ['name', 'LIKE', '%' . $_GET['q'] . '%', 'OR'],
                    ['description', 'LIKE', '%' . $_GET['q'] . '%', 'OR']
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
//        var_dump($args);
//        die();
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