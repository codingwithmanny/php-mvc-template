<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-02-22
 * Time: 9:50 PM
 */

namespace App\Models;

use App\Core\Model;

class UsersModel extends Model
{
    /**
     * @var array
     */
    protected $fields_all = ['id', 'email', 'password', 'first_name', 'last_name', 'created', 'modified'];

    /**
     * @var array
     */
    protected $fields_required = ['email', 'password', 'first_name', 'last_name'];

    /**
     * @var array
     */
    protected $fields_viewable = ['id', 'email', 'first_name', 'last_name'];

    /**
     * UsersModel constructor.
     */
    public function __construct()
    {
        parent::__construct('users');
    }
}