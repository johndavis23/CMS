<?php
namespace App\Classes\Form\Elements;

use App\Classes\Form\Element;

class BBCodeELement extends Element
{
    protected $filter = FILTER_SANITIZE_STRING;
    protected $errorMessage = "Your BBCode submission was invalid.";
    function __construct($params, $required = false)
    {
        parent::__construct("Forms/Elements/BBCode", $params, null, $required);
        $this->filter       = FILTER_SANITIZE_STRING;
        $this->required     = $required;
        $this->errorMessage = "Your BBCode submission was invalid.";
    }
}