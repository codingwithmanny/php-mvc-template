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
    protected $fields_all = ['id', 'email', 'password', 'role', 'first_name', 'last_name', 'created', 'modified'];

    /**
     * @var array
     */
    protected $fields_required = ['email', 'password', 'first_name', 'last_name'];

    /**
     * @var array
     */
    protected $fields_required_options = [
        'email' => ['type' => 'email', 'attributes' => ['min' => 6, 'max' => 250, 'class' => 'form-control', 'placeholder' => 'Email', 'required' => 'required']],
        'password' => ['type' => 'password', 'attributes' => ['min' => 8, 'max' => 250, 'class' => 'form-control', 'placeholder' => 'Password', 'required' => 'required']],
        'first_name' => ['type' => 'text', 'attributes' => ['min' => 1, 'max' => 250, 'class' => 'form-control', 'placeholder' => 'First Name', 'required' => 'required']],
        'last_name' => ['type' => 'text', 'attributes' => ['min' => 1, 'max' => 250, 'class' => 'form-control', 'placeholder' => 'Last Name', 'required' => 'required']]
    ];

    /**
     * @var array
     */
    protected $fields_viewable = ['id', 'email', 'role', 'first_name', 'last_name'];


    /**
     * UsersModel constructor.
     */
    public function __construct()
    {
        parent::__construct('users');
    }
}