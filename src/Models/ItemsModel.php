<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-10
 * Time: 3:00 PM
 */

namespace App\Models;

use App\Core\Model;

class ItemsModel extends Model
{
    /**
     * @var array
     */
    protected $fields_all = ['id', 'user_id', 'name', 'description', 'lat', 'lng', 'created', 'modified'];

    /**
     * @var array
     */
    protected $fields_required = ['user_id', 'name'];

    /**
     * @var array
     */
    protected $fields_required_options = [
        'user_id' => ['type' => 'text', 'attributes' => ['class' => 'livesearch form-control', 'placeholder' => 'User Id', 'data-model' => 'users', 'data-id' => 'id', 'data-label' => 'email']],
        'name' => ['type' => 'text', 'attributes' => ['min' => 8, 'max' => 250, 'class' => 'form-control', 'placeholder' => 'Name']],
        'description' => ['type' => 'textarea', 'attributes' => ['min' => 1, 'max' => 250, 'class' => 'form-control', 'placeholder' => 'Description']],
        'lat' => ['type' => 'number', 'attributes' => ['class' => 'form-control', 'placeholder' => 'Latitude']],
        'lng' => ['type' => 'number', 'attributes' => ['class' => 'form-control', 'placeholder' => 'Longitude']]
    ];

    /**
     * @var array
     */
    protected $fields_optional = ['description', 'lat', 'lng', 'created', 'modified'];

    /**
     * @var array
     */
    protected $fields_viewable = ['id', 'user_id', 'name', 'description', 'lat', 'lng'];

    /**
     * @var array
     */
    protected $related_models = ['tags'];

    /**
     * UsersModel constructor.
     */
    public function __construct()
    {
        parent::__construct('items');
    }
}