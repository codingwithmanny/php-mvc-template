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
        'user_id' => ['type' => 'select', 'attributes' => ['class' => 'form-control', 'placeholder' => 'User Id']],
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
        $this->fields_required_options['user_id']['options'] = $this->helper_getmodelvalues('UsersModel', ['id', 'email'])['data'];
        $this->fields_required_options['user_id']['option_id'] = 'id';
        $this->fields_required_options['user_id']['option_name'] = 'email';
        parent::__construct('items');
    }
}