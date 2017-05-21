<?php
include_once("Config/config.php");
include_once("Classes/Model.php");
include_once("Models/MemberModel.php");
#################################
// Model
#################################
class PostModel extends Model
{
    function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        parent::__construct('smf_messages', 'id_msg');
    }
    public function read(array $where) {
        $results = parent::read($where);

        $mb      = new MemberModel();
        foreach ($results as &$result) {
            $result['body']     = $this->stripBBCode($result['body'] );
            $result['member']   = $mb->readWithID($result['id_member']);
        }
        //$result['all_members']= $mb->readAll();
        return $results;
    }
    public function readAll() {
        $results = parent::readAll();

        $mb      = new MemberModel();
        foreach ($results as &$result) {
            $result['body']     = $this->stripBBCode($result['body'] );
            $result['member']   = $mb->readWithID($result['id_member']);
        }
        //$result['all_members']= $mb->readAll();
        return $results;
    }
    public function stripBBCode($in)
    {
        $out = $in;
        
        return $out;
    }
    public function getMessagesForTopic($topic_id) {

    }
    public function getNewestMessageOnBoard($board_id) {
        
    }
}