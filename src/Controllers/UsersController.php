<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-02-22
 * Time: 9:50 PM
 */

namespace App\Controllers;

use App\Core\Model;

class UsersController extends Model
{
    /**
     * @var array
     */
    protected $fields_all = ['id', 'email', 'password', 'created', 'modified'];

    /**
     * @var array
     */
    protected $fields_required = ['email', 'password'];

    /**
     * @var array
     */
    protected $fields_editable = [];

    /**
     * @var array
     */
    protected $fields_viewable = ['id', 'email'];

    /**
     * @var array
     */
    protected $fields_searchable = [];

    /**
     * @var array
     */
    protected $params_default = ['page' => 1, 'limit' => 100, 'order' => 'id', 'sort' => 'asc'];

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        parent::__construct('users');
    }
}