<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-11
 * Time: 10:07 AM
 */

namespace App\Controllers;


use App\Core\Controller;

class ItemsTagsController extends Controller
{
    /**
     * @var
     */
    private $tags;

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->model_name = 'itemstags';
        $this->template_dir = 'templates';
        $model = new \App\Models\ItemsTagsModel();
        $this->tags = new \App\Models\TagsModel();
        parent::__construct($model);
    }

    /**
     *
     */
    public function index()
    {
        $this->_index();
    }

    /**
     * @param $id
     */
    public function itemstags($id)
    {
        $query = ['query' => 'FROM :table INNER JOIN tags as t ON :table.tag_id = t.id WHERE :table.item_id='. $id];
        $this->_index($query, ['t.id', 'name']);
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