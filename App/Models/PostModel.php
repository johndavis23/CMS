<?php
namespace App\Models;

use App\Classes\Model;
use App\Classes\FormBuilder;
use App\Classes\Form\Elements\TextElement;
use App\Classes\Form\Elements\PasswordElement;
use App\Classes\Form\Elements\Checkbox;
use App\Util\UrlUtils;
use App\Models\MemberModel;

include_once("Config/config.php");
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
        parent::__construct('smf_messages', 'id_msg', 'smf');
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