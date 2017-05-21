<?php

namespace App\Classes;

use App\Classes\Form\Element;

class FormBuilder {
    private $name            = "default";
    private $elements        = [];
    private $token           = "";
    const UNIQUE_CODE_LENGTH = 10;

    public function __construct()
    {
        setToken();
    }

    public function add(Element $element) {
        if ($element)
            $this->elements[] = $element;
        return $this;
    }

    public function post()
    {
        if(!empty($_POST))
        {
            $this->validateToken();
            foreach($this->elements as $element)
            {
                if(!$element->validate())
                {
                    $element->renderError();
                    return false;
                }
                $element->sanitize();
            }
            return true;
        }
        return false;
    }

    private function setToken()
    {
        $token = bin2hex(
            openssl_random_pseudo_bytes(static::UNIQUE_CODE_LENGTH)
        );
        $this->token      = $token;
        $this->elements[] = new UniqueCodeElement($code);
        $_SESSION['form_tokens'][] = $token;
    }

    private function validateToken()
    {
        if(isset($_POST['uc'])) {
            $token = $_POST['uc'];
        } else {
            return false;
        }
        return true;
    }
}