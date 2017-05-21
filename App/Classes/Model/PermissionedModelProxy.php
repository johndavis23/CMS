<?php
namespace App\Classes\Model;

use Model;



class PermissionException extends \Exception {}


/* A proxy? A decorator? Somewhere in between.
-- A procorator! It takes care of stuff and laws down the law. Ha. */

class PermissionedModelProxy implements ModelInterface {

    private $wrapped_model;
    private $permission_list = [
        "create"=> [
            "create"
        ],
        "read" => [
            "read",
            "exists",
            "count",
            "readOffset",
            "readWithID",
            "readAll",
            "readAllIds"
        ],
        "update" => [
            "update"
        ],
        "delete" => [
            "delete"
        ]
    ];
    private $generated_list = [];
    private $user = null;
    private $model_name = "";

    function __construct(\Model $model)
    {
        foreach ($this->permission_list as $permission => $functions) {
            foreach ($functions as $function) {
                $this->generated_list[$function] = $permission;
            }
        }

        $this->wrapped_model = $model;
        $this->model_name    = get_class($model);

        $this->user = Sentinel::getUser();

    }

    private function verifyPermission($function_name = null)
    {
        if ($function_name === null) {
            $function_name = debug_backtrace()[1]['function'];
        }

        if( !$this->user->hasAccess([$this->model_name.".".$this->generated_list[$function_name]])) {
            throw new PermissionException();
        }
    }

    public function create(array $fieldValuePairs)
    {
        $this->verifyPermission();
        $this->wrapped_model->create($fieldValuePairs);
    }

    public function read(array $where)
    {
        $this->verifyPermission();
        return $this->wrapped_model->read($where);
    }

    public function update(array $update, array $where)
    {
        $this->verifyPermission();
        $this->wrapped_model->update($update, $where);
    }

    public function delete(array $where, $limit = null)
    {
        $this->verifyPermission();
        $this->wrapped_model->delete($where, $limit);
    }

    public function exists(array $where)
    {
        $this->verifyPermission();
        return $this->wrapped_model->exists($where);
    }

    public function count(array $where)
    {
        $this->verifyPermission();
        return $this->wrapped_model->count($where);
    }

    public function readOffset(array $where,  $limit = null, $offset = null)
    {
        $this->verifyPermission();
        return $this->readOffset($where, $limit, $offset);
    }

    public function readWithID($id)
    {
        $this->verifyPermission();
        return $this->wrapped_model->readWithID($id);
    }

    public function readAll()
    {
        $this->verifyPermission();
        return $this->wrapped_model->readAll();
    }

    public function readAllIds()
    {
        $this->verifyPermission();
        return $this->wrapped_model->readAllIds();
    }
}