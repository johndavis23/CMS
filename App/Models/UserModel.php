<?php
namespace App\Models;
use App\Classes\Model;
use App\Classes\FormBuilder;
use App\Classes\Form\Elements\TextElement;
use App\Classes\Form\Elements\PasswordElement;
use App\Classes\Form\Elements\Checkbox;
use App\Util\UrlUtils;

include_once("Config/config.php");

class UserModel extends Model
{
    function __construct()
    {
        if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
        parent::__construct('users', 'id');
    }

    public static function toActionItems($array)
    {
        $id = $array['id'];
        return [
                "Edit" => [
                    "class" => "btn btn-default",
                    "url"   => UrlUtils::getControllerUrl("Admin/Update/User/$id")
                ],
                "Delete" => [
                    "class" => "btn btn-danger",
                    "url"   => UrlUtils::getControllerUrl("Admin/Delete/User/$id")
                ]
        ];
    }

    public static function toListItem($array)
    {
        return [
            "ID"      => $array['id'],
            "Email"   => $array['email'],
            "Actions" => self::toActionItems($array)
        ];
    }

    public function getForm($values = null)
    {

        $fb = new FormBuilder();
        $fb->add(
            new TextElement(
                [
                    "id"          =>"email",
                    "placeholder" => "email@domain.com",
                    "label"       => "Email:",
                    "value"       => @$values['email'],
                    'name'        => "email"
                ]
            )
        )->add(
            new PasswordElement(
                [
                    "id"            => "pass",
                    "label"         => "Password:",
                    "placeholder"   => "p@ssw0rd",
                    "value"         => @$values['password'],
                    'name'          => "pass"
                ]
            )
        );

        return $fb;
    }
}