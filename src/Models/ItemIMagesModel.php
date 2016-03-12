<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-12
 * Time: 11:15 AM
 */

namespace App\Models;

use App\Core\Model;

class ItemImagesModel extends Model
{
    /**
     * @var array
     */
    protected $fields_all = ['id', 'item_id', 'caption', 'url', 'created', 'modified'];

    /**
     * @var array
     */
    protected $fields_required = ['item_id', 'url'];

    /**
     * @var array
     */
    protected $fields_required_options = [
        'item_id' => ['type' => 'text', 'attributes' => ['class' => 'livesearch form-control', 'placeholder' => 'Item Id', 'data-model' => 'items', 'data-id' => 'id', 'data-label' => 'name']],
        'caption' => ['type' => 'text', 'attributes' => ['class' => 'form-control', 'placeholder' => 'Caption']],
        'url' => ['type' => 'text', 'attributes' => ['class' => 'file form-control', 'placeholder' => 'Image', 'data-accept' => 'image/*', 'data-model' => 'itemimages']],
    ];

    /**
     * @var array
     */
    protected $fields_optional = ['caption', 'created', 'modified'];

    /**
     * @var array
     */
    protected $fields_viewable = ['id', 'item_id', 'url', 'caption'];

    /**
     * ItemsTagsModel constructor.
     */
    public function __construct()
    {
        parent::__construct('item_images');
    }
}