<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-10
 * Time: 12:44 PM
 */

namespace App\Models;

use App\Core\Model;

class ResetModel extends Model
{
    /**
     * @var array
     */
    protected $fields_all = ['id', 'user_id', 'token', 'created', 'modified'];

    /**
     * @var array
     */
    protected $fields_required = ['user_id', 'token'];

    /**
     * @var array
     */
    protected $fields_viewable = ['id', 'user_id', 'token', 'created', 'modified'];

    /**
     * @var array
     */
    protected $fields_optional = ['created', 'modified'];

    /**
     * UsersModel constructor.
     */
    public function __construct()
    {
        parent::__construct('user_reset');
    }
}