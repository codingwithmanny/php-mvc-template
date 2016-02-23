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
    public function __construct($template = null, $data = null)
    {
        $this->template = APP .'Views/';
        $this->template .= ($template != null) ? $template . '.php' : 'Templates/json.php';

        if(!file_exists($this->template)) {
            echo 'Template "'. $this->template . '" does not exist.';
            die();
        }

        if($data != null) {
            $this->page_vars = ['data' => $data];
        }
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