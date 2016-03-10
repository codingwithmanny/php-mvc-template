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
    public function __construct($template = null, $data = null, $parent_template = null)
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

        if($parent_template != null && $template != null) {
            $this->page_vars['template'] = $template;
        }

        $this->page_vars['model_url'] = array_values(array_filter(explode('/', explode('?', $_SERVER['REQUEST_URI'], 2)[0])));

        $this->render();
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