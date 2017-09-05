<?php
namespace App\Models;

use App\Classes\Model;
use App\Util\UrlUtils;
use App\Classes\FormBuilder;
use App\Models\PostModel;
use App\Models\ThreadModel;
use App\Classes\Form\Elements\TextElement;
include_once("Config/config.php");


#################################
// Model
#################################
class BoardModel extends Model
{
    function __construct()
    {
        parent::__construct('pages', 'id', 'cms');
    }


    public static function toActionItems($array)
    {
        $id = $array['id_board'];
        return [
            "Edit" => [
                "class" => "btn btn-default",
                "url"   => UrlUtils::getControllerUrl("Admin/Update/Board/$id")
            ],
        ];
    }


    public static function toListItem($array)
    {
        $tm = new PostModel();
        $results = $tm->read(['id_msg'=>$array['id_last_msg']])[0]['subject'];
        // var_dump($results);
        return [
            "ID"      => $array['id_board'],
            "Name"    => $array['name'],
            "Last Message" => $results,
            "Actions" => self::toActionItems($array),
        ];
    }

    
    public function getForm($values = null)
    {

        $fb = new FormBuilder();
        $fb->add(
            new TextElement(
                [
                    "id"          =>"name",
                    "placeholder" => "Board Name",
                    "label"       => "Name:",
                    "value"       => @$values['name'],
                    'name'        => "name"
                ]
            )
        )->add(
            new TextElement(
                [
                    "id"          =>"last_msg",
                    "label"       => "Last Message:",
                    "value"       => @$values['id_last_msg'],
                    'name'        => "last_msg"
                ]
            )
        );

        return $fb;
    }
}