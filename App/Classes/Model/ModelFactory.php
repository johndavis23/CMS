<?php

namespace App\Classes\Model;

class ModelFactory extends AbstractModelFactory {
    const MODEL_NAMESPACE = "App\\Models\\";
    const MODEL_POSTFIX   = "Model";
    static function build($name, $args) {

        $args = func_get_args();
        $name = ucfirst(strtolower(array_shift($args)));
        $fullClassName = self::MODEL_NAMESPACE.$name.self::MODEL_POSTFIX;

        if (ctype_alnum($name) && class_exists($fullClassName)) {
            return new $fullClassName(/*...$args*/);
        } else {
            throw new \InvalidArgumentException("Invalid Class Name");
        }
    }
}