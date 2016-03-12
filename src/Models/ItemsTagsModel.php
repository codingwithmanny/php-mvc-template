<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-03-11
 * Time: 10:05 AM
 */

namespace App\Models;

use App\Core\Model;

class ItemsTagsModel extends Model
{
    /**
     * @var array
     */
    protected $fields_all = ['id', 'item_id', 'tag_id'];

    /**
     * @var array
     */
    protected $fields_required = ['item_id', 'tag_id'];

    /**
     * @var array
     */
    protected $fields_required_options = [
        'item_id' => ['type' => 'text', 'attributes' => ['class' => 'livesearch form-control', 'placeholder' => 'Item Id', 'data-model' => 'items', 'data-id' => 'id', 'data-label' => 'name']],
        'tag_id' => ['type' => 'text', 'attributes' => ['class' => 'livesearch form-control', 'placeholder' => 'Tag Id', 'data-model' => 'tags', 'data-id' => 'id', 'data-label' => 'name']],
    ];

    /**
     * @var array
     */
    protected $fields_viewable = ['id', 'item_id', 'tag_id'];

    /**
     * ItemsTagsModel constructor.
     */
    public function __construct()
    {
        parent::__construct('items_tags');
    }
}