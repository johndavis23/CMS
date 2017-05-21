<?php
include_once("Config/config.php");
include_once("Classes/Model.php");

#################################
// Model
#################################
class MemberModel extends Model
{
    function __construct()
    {

        if (session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
        parent::__construct('smf_members', 'id_member');
    }
    /*public function read() { TODO FIND OUT OUR SIGNATURE

    }
    public function getMessagesForTopic($topic_id) {

    }
    public function getNewestMessageOnBoard($board_id) {

    }*/
}