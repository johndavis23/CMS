<?php
namespace App\Models;

use App\Classes\Model;
include_once("Config/config.php");


#################################
// Model
#################################
class MemberModel extends Model
{
    function __construct()
    {

        parent::__construct('smf_members', 'id_member', 'smf');
    }
    /*public function read() { TODO FIND OUT OUR SIGNATURE

    }
    public function getMessagesForTopic($topic_id) {

    }
    public function getNewestMessageOnBoard($board_id) {

    }*/
}