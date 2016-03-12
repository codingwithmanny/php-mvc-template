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
        'email' => ['type' => 'email', 'attributes' => ['min' => 6, 'max' => 250, 'class' => 'form-control', 'placeholder' => 'Email']],
        'password' => ['type' => 'password', 'attributes' => ['min' => 8, 'max' => 250, 'class' => 'form-control', 'placeholder' => 'Password']],
        'role' => ['type' => 'select', 'role'=>'admin', 'options' => [['id'=>'admin','name'=>'admin'], ['id'=>'other','name'=>'other']], 'option_id' => 'id', 'option_name' => 'name', 'attributes' => ['class' => 'form-control', 'placeholder' => 'Role']],
        'first_name' => ['type' => 'text', 'attributes' => ['min' => 1, 'max' => 250, 'class' => 'form-control', 'placeholder' => 'First Name']],
        'last_name' => ['type' => 'text', 'attributes' => ['min' => 1, 'max' => 250, 'class' => 'form-control', 'placeholder' => 'Last Name']]
    ];

    /**
     * @var array
     */
    protected $fields_optional = ['created', 'modified'];

    /**
     * @var array
     */
    protected $fields_viewable = ['id', 'email', 'role', 'first_name', 'last_name'];

    /**
     * @var array
     */
    protected $related_models = ['items'];

    /**
     * UsersModel constructor.
     */
    public function __construct()
    {
        parent::__construct('users');
    }
}