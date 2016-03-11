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
        'item_id' => ['type' => 'select', 'attributes' => ['class' => 'form-control', 'placeholder' => 'Item Id']],
        'tag_id' => ['type' => 'select', 'attributes' => ['class' => 'form-control', 'placeholder' => 'Tag Id']]
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
        $this->fields_required_options['item_id']['options'] = $this->helper_getmodelvalues('ItemsModel', ['id', 'name']);
        $this->fields_required_options['item_id']['option_id'] = 'id';
        $this->fields_required_options['item_id']['option_name'] = 'name';

        $this->fields_required_options['tag_id']['options'] = $this->helper_getmodelvalues('TagsModel', ['id', 'name']);
        $this->fields_required_options['tag_id']['option_id'] = 'id';
        $this->fields_required_options['tag_id']['option_name'] = 'name';
        parent::__construct('items_tags');
    }
}