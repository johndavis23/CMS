<?php

namespace App\Classes\View;


class Registry
{
    protected static $instance;
    protected $js ='';

    function __construct()
    {
        if (null === static::$instance) {
            static::$instance = new Registry();
        }

        return static::$instance;
    }
    static function get()
    {
        return new Registry();
    }
    function registerJS($js)
    {
        $this->js .=$js;
    }
    function printJS()
    {
        echo "<script>";
        echo $this->js;
        echo "</script>";
    }
}
