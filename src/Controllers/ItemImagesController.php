<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-12
 * Time: 11:25 AM
 */

namespace App\Controllers;

use App\Core\Controller;

class ItemImagesController extends Controller
{
    /**
     * ItemsController constructor.
     */
    public function __construct()
    {
        $this->model_name = 'itemimages';
        $this->template_dir = 'templates';
        $model = new \App\Models\ItemImagesModel();
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
                    ['caption', 'LIKE', '%' . $_GET['q'] . '%']
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

        if(array_key_exists('url', $args)) {
            $this->move_upload($args['url']);
        }
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

        $args = $this->get_payload(true);
        $args['modified'] = date('Y-m-d H:i:s', time());

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

    /**
     *
     */
    public function upload()
    {
        $header = $_SERVER['SERVER_PROTOCOL'] . ' 412 Precondition Failed';
        $json = ['data' => false];
        if(isset($_FILES)) {
            $target_dir = ROOT. '/public/tmp/';
            $target_name = time() . '_' . basename($_FILES['file']['name']);
            $target_name = str_replace(' ', '_', $target_name);
            $target_file = $target_dir . $target_name;
            if (!file_exists($target_file)) {
                if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
                    $header = $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
                    $json['data'] = $target_name;

                }
            }
        }
        header($header);
        header('Content-Type: application/json');
        echo json_encode($json);
    }

    /**
     *
     */
    public function delete_upload()
    {
        $header = $_SERVER['SERVER_PROTOCOL'] . ' 412 Precondition Failed';
        $json = ['data' => false];
        $args = $this->get_payload(true);
        if(array_key_exists('file', $args) && file_exists(ROOT . '/public/tmp/' . $args['file'])) {
            unlink(ROOT . '/public/tmp/' . $args['file']);
            $header = $_SERVER['SERVER_PROTOCOL'] . ' 200 OK';
            $json['data'] = true;
        }
        header($header);
        header('Content-Type: application/json');
        echo json_encode($json);
    }

    /**
     * @param $file_name
     */
    public function move_upload($file_name)
    {
        $origin_dir = ROOT. '/public/tmp/';
        $target_dir = ROOT. '/public/uploads/';

        if(file_exists($origin_dir . $file_name)) {
            rename($origin_dir . $file_name, $target_dir . $file_name);
        }
    }
}