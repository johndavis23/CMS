<?php

class PermissionedModelFactory extends AbstractModelFactory implements Factory {
    static function build($name) {
        $model   = ModelFactory($name);
        $wrapper = new PermissionedModelProxy($model);
        return $wrapper;
    }
}