<?php
/**
 * Created by PhpStorm.
 * User: manuelpineault
 * Date: 2016-02-23
 * Time: 2:33 PM
 */

namespace App\Core;


class View
{
    /**
     * @var array|null
     */
    private $page_vars = array();

    /**
     * @var string
     */
    private $template;

    /**
     * View constructor.
     * @param $template
     * @param null $data
     */
    public function __construct($template = null, $parent_template = null, $data = null)
    {
        $this->template = APP .'Views/';
        $load = ($template != null) ? $template . '.php' : null;
        $load = ($parent_template != null) ? $parent_template . '.php' : $load;
        $this->template .= $load;

        if(!file_exists($this->template)) {
            echo 'Template "'. $this->template . '" does not exist.';
            die();
        }

        if($data != null) {
            $this->page_vars = ['data' => $data];
        }

        if($data != null && array_key_exists('fields', $data)) {
            $field_data = (array_key_exists('data', $data)) ? $data['data'] : null;
            $this->page_vars['form_fields'] = $this->form_builder($data['fields'], $field_data);
        }

        if($parent_template != null && $template != null) {
            $this->page_vars['template'] = $template;
        }

        $model_url = array_values(array_filter(explode('/', explode('?', $_SERVER['REQUEST_URI'], 2)[0])));
        $this->page_vars['model_url'] = implode('/', $model_url);
        $form_url = array_values($model_url);
        unset($form_url[count($form_url) - 1]);
        $form_url = '/' . implode('/', $form_url);

        $url = '/';
        $header_url = '';
        $item_url = '/';
        foreach($model_url as $key => $value) {
            $header_url .= (($key+1) != count($model_url)) ? '<a href="' . $form_url . '">' : '';
            $header_url .= $value;
            $header_url .= (($key+1) != count($model_url)) ? '</a>' : '';
            $header_url .= ($key+1 == count($model_url)) ? '' : '&nbsp;<small>/</small>&nbsp;';
            if($key == (count($model_url) - 1) || $key == (count($model_url))) {
                $item_url .= $value;
                $item_url .= ($key + 1 == count($model_url)) ? '' : '/';
            }
            $url .= $value;
            $url .= ($key + 1 == count($model_url)) ? '' : '/';
        }

        $this->page_vars['url'] = $url;
        $this->page_vars['item_url'] = $item_url;
        $this->page_vars['header_url'] = $header_url;

        $this->render();
    }

    /**
     * @param $fields
     * @param null $data
     * @return array
     */
    function form_builder($fields, $data = null)
    {
        $form_fields = [];
        foreach($fields as $key => $value) {
            switch($value['type']) {
                default:
                    $form_fields[$key] = '<input type="' . $value['type'] . '" ';
                    $form_fields[$key] .= 'name="' . $key . '" ';
                    if(array_key_exists('attributes', $value)) {
                        foreach($value['attributes'] as $k => $v) {
                            $form_fields[$key] .= $k . '="' . $v . '" ';
                        }
                    }
                    if($data != null && array_key_exists($key, $data)) {
                        $form_fields[$key] .= 'value="' . $data[$key] . '"';
                    }
                    $form_fields[$key] .= '/>';
                break;
            }
        }
        return $form_fields;
    }

    /**
     *
     */
    public function render()
    {
        extract($this->page_vars);
        ob_start();
        require($this->template);
        echo ob_get_clean();
    }
}