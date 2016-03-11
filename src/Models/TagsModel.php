<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-10
 * Time: 10:45 PM
 */

namespace App\Models;

use App\Core\Model;

class TagsModel extends Model
{
    /**
     * @var array
     */
    protected $fields_all = ['id', 'name', 'created', 'modified'];

    /**
     * @var array
     */
    protected $fields_required = ['name'];

    /**
     * @var array
     */
    protected $fields_required_options = [
        'name' => ['type' => 'text', 'attributes' => ['min' => 8, 'max' => 250, 'class' => 'form-control', 'placeholder' => 'Name']],
    ];

    /**
     * @var array
     */
    protected $fields_optional = ['created', 'modified'];

    /**
     * @var array
     */
    protected $fields_viewable = ['id', 'name', 'created', 'modified'];

    /**
     * UsersModel constructor.
     */
    public function __construct()
    {
        parent::__construct('tags');
    }
}