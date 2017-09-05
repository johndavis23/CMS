<?php

namespace App\Classes;


class Registry
{
    protected static $instance;
    protected $js ='';

    function __construct()
    {

    }
    static function get()
    {
        if (null === static::$instance) {
            static::$instance = new Registry();
        }

        return static::$instance;
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
