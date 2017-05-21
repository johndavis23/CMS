<?php

namespace App\Classes;

use App\Classes\Form\Element;

class FormBuilder {
    public  $target          = "";
    private $name            = "default";
    private $elements        = [];
    private $token           = "";
    const UNIQUE_CODE_LENGTH = 10;

    public function __construct()
    {
        $this->setToken();
    }

    public function setTarget($location) 
    {
        $this->target = $location;
    }

    public function getTarget() 
    {
        return $this->target;
    }

    public function add(Element $element) 
    {
        if ($element) {
            $this->elements[] = $element;
        }
        
        return $this;
    }

    public function post()
    {
        $out = [];
        if (!empty($_POST)) {
            $this->validateToken();
            foreach ($this->elements as $element) {
                if (!$element->validate()) {
                    return false;
                }
                
                $out[$element->name] = $element->sanitize();
            }
            return $out;
        }
        return false;
    }

    public function render($string = false)
    {
        if ($string) {
            ob_start();
        }
        $elements_render = "";

        foreach ($this->elements as $element) {
            $elements_render .=  $element->render(true);
        }

        (new View())->render(
            'Forms/Form',
            [
                'elements' => $elements_render,
                'target'   => $this->getTarget()
            ]
        );

        if ($string) {
            $return = ob_get_contents();
            ob_clean();
            return $return;
        }
    }
    
    private function setToken()
    {
        $token = bin2hex(
            openssl_random_pseudo_bytes(static::UNIQUE_CODE_LENGTH)
        );
        $this->token      = $token;
        //  $this->elements[] = new UniqueCodeElement($code);
        $_SESSION['form_tokens'][] = $token;
    }

    private function validateToken()
    {
        return true;
        if(isset($_POST['uc'])) {
            $token = $_POST['uc'];
        } else {
            return false;
        }
        return true;
    }
}