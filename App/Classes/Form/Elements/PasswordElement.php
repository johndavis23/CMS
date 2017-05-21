<?php
namespace App\Classes\Form\Elements;

use App\Classes\Form\Element;

class PasswordElement extends Element
{
    protected $filter = FILTER_SANITIZE_STRING;
    protected $errorMessage = "Please only use letters, numbers, spaces, and '-'.";
    function __construct($params, $required = false)
    {
        parent::__construct("Forms/Elements/Password", $params, null, $required);
        $this->filter       = FILTER_SANITIZE_STRING;
        $this->required     = $required;
        $this->errorMessage = "Please only use letters, numbers, spaces, and '-'.";
    }
}