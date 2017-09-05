<?php
namespace App\Models;

use App\Classes\Model;
use App\Util\UrlUtils;
use App\Classes\FormBuilder;

use App\Classes\Form\Elements\TextElement;
include_once("Config/config.php");


#################################
// Model
#################################
class ThreadModel extends Model
{
    function __construct()
    {
        parent::__construct('smf_topics', 'id_topic', 'smf');
    }
    public static function toActionItems($array)
    {
        $id = $array['id_board'];
        return [
            "Edit" => [
                "class" => "btn btn-default",
                "url"   => UrlUtils::getControllerUrl("Admin/Update/Thread/$id")
            ],
        ];
    }

    public static function toListItem($array)
    {
        return [
            "ID"      => $array['id_thread'],
            "Name"    => $array['name'],
            "Actions" => self::toActionItems($array)
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
        );

        return $fb;
    }
}