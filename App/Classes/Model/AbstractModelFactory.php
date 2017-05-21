<?php
namespace App\Classes\Model;

abstract class AbstractModelFactory  {
    static abstract function build($name,$args);
}