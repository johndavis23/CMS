<?php

namespace App\Classes\Form;

use App\Classes\Request;
use App\Classes\View;
interface FormElement {
    public function validate();
    public function sanitize();
    public function render();
}

class Element
{
    protected $regularExpression= "";
    public $name             = "";
    protected $required         = false;
    protected $display          = "";
    protected $values           = "";
    protected $filter           = null;
    protected $error            = [];


    function __construct($view, $params, $validateRegEx, $required = false)
    {
        $this->name                 = $params['name'];
        $this->required             = $required;
        $this->regularExpression    = $validateRegEx;
        $this->display              = $view;
        $this->params               = $params;
        $this->errorMessage         = "Validation Error on '$this->display '";
        $this->filter               = null;
    }

    public function validate()
    {
        $return = $this->validateRegex();
        $return = $return && $this->validateRequired();
        return $return;
    }

    private function validateRegex() {
        if (!empty($this->regularExpression)) {
            if (!empty(Request::post($this->params["name"]))) {
                if (!preg_match(
                    $this->regularExpression,
                    Request::post($this->params["name"])
                )
                ) {
                    return false;
                }
            }
        }
        return true;
    }

    private function validateRequired() {
        if ($this->required && empty($_POST[$this->params["name"]])) {
            return false;
        }
        return true;
    }

    public function sanitize()
    {
        if ($this->filter != null) {
            return filter_var(
                Request::post($this->name),
                $this->filter
            );
        }
        return Request::post($this->name);
    }

    public function render($return = false)
    {
        if ($return) {
            ob_start();
        }
        
        $obj = $this->params;

        if (Request::post($this->name)) {
            $obj['value'] = Request::post($this->name);
        }

        $obj['required'] = $this->required;
        $obj['error']    =  $this->error;

        (new View())->render($this->display, ['obj'=>$obj]);

        if ($return) {
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

    }
}

class UniqueCodeElement extends Element
{

}



class TextAreaElement extends Element
{
    function __construct($view, $params, $validateRegEx, $required = false)
    {
        parent::__construct("TextArea", $params, "/[a-z\d\-_\s]+/i", $required);

        $this->errorMessage = "Please only use letters, numbers, spaces, and '-'.";
    }
}

class PhoneElement extends Element
{
    protected $required = false;
    function __construct($params,$required = false)
    {
        parent::__construct("Phone",
            $params,
            null,//"/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/",
            $required);
        $this->required = $required;
        $this->errorMessage = "Please enter a valid telephone number.";

    }
}

class EmailElement extends Element
{
    function __construct($params, $required = false)
    {
        parent::__construct("Email",
            $params,
            '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',
            $required
        );

        $this->errorMessage = "Please enter a valid email address.";

    }

}


class NumberElement extends Element
{
    function __construct($params, $required = false)
    {
        parent::__construct("Number",
            $params,
            null,
            $required);
        $this->errorMessage = "Please enter a number.";

    }
    public function validate($post)
    {
        if (!is_numeric($post)) {
            return $this->errorMessage;
        }
        return true;
    }
}

class SelectElement extends Element
{
    function __construct($params, $required = false)
    {
        parent::__construct(
            "Select",
            $params,
            null,
            $required
        );
        $this->errorMessage = "Please enter a valid selection.";

    }
    public function validate($post)
    {
        if (!in_array($post, $this->params['values'])) {
            return $this->errorMessage;
        }
        return true;
    }
}

class HTMLElement extends Element
{
    function __construct($html)
    {
        $params = ["html"=>$html];
        parent::__construct(
            "HTML",
            $params,
            null,
            false
        );

    }
    public function validate($post)
    {
        return true;
    }
}

