<?php
namespace App\Controllers;

use App\Classes\Controller;
use App\Classes\Login;
use App\Classes\View;
use App\Classes\FormBuilder;
use App\Classes\Request;
use App\Classes\Model;
use App\Classes\Form\Elements\TextElement;
use App\Classes\Form\Elements\BBCodeElement;
use App\Classes\Form\Element;
use App\Classes\Model\ModelFactory;

use App\Models\PostModel;
use App\Models\ThreadModel;
use App\Models\BoardModel;

use App\Util\UrlUtils;
use App\Classes\Registry;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class ForumController extends Controller
{
    protected $login;
    protected $validActions = ["Index", "Thread", "Board", "PostSubmit", "TEST"];

    function __construct()
    {
        $this->view     = new View();
        //$this->login    = new Login();
    }

    public function TEST()
    {
        echo "<pre>";
        $model = new Model('smf_log_errors', 'id_error', 'smf');
        $results = $model->read(['id_member'=>-1]);


        var_dump($results);
        echo "</pre>";
    }

    //Our Pages
    public function All()
    {
        $pm = new PostModel();
        $posts = $pm->readAll();

        $this->view->render("Post/Index", ['posts'=>$posts]);

        return;
    }

    public function Index()
    {
        $model = new Model("smf_boards", 'id_board', 'smf');
        $results = $model->readAll();

        $this->view->render("Post/Forum", ['boards'=>$results]);
    }

    public function Board($request)
    {
        $id    = (int) $request->popNextParameter();
        $throwaway = (string) $request->popNextParameter();
        $page  = $_GET['page'];//(int) $request->popNextParameter();
        $model = new Model("smf_topics", 'id_topic', 'smf');
        $where = ["id_board"=>$id];

        $count = $model->count($where);
        $pages = $count/20;

        $results = $model->read($where, 'id_topic DESC', 20,20*$page );

        foreach ($results as &$result) {
            $pm = new PostModel();
            $result['message'] = $pm->readWithID($result['id_first_msg']);
        }

        $model  = new Model("smf_boards", 'id_board', 'smf');
        $boards = $model->read(["id_board"=>$id]);

        $this->view->render("Post/Board", ['pages'=>$pages, 'board'=>$boards[0], 'posts'=>$results]);
    }
    public function Thread($request)
    {
        $id = (int) $request->popNextParameter();
        $pm = new PostModel();

        $posts = $pm->read(["id_topic"=>$id]);
        $parser = $this->getParser();


        $html_form = $this->getPostForm($id)->render(true);

        $this->view->render("Post/Thread", ['posts'=>$posts, 'parser'=>$parser, 'form'=>$html_form]);
    }
    protected function getPostForm($id)
    {
            $fb = new FormBuilder();

            $fb->setTarget(UrlUtils::getControllerUrl('Forum/PostSubmit'));

            $fb->add(
                new TextElement(
                    [
                        "placeholder" => "Post Title",
                        //"label" => "Email:",
                        'name'  => "title"
                    ]
                )
            )->add(
                new TextElement(
                    [
                        "placeholder" => "Thread",
                        //"label" => "Email:",
                        'name'  => "thread",
                        'value' => $id
                    ]
                )
            )->add(
                new BBCodeElement(
                    [
                        'name'  => "content",
                        'id'    => "bbcode_post"
                    ]
                )
            );
            return $fb;

    }
    protected function PostSubmit()
    {

        $post = $this->getPostForm()->post();


        if ($post)
        if (!(Sentinel::check()) ) {
            $user = Sentinel::getUser();

            $title   = $post['title'];//filter_var(Request::post('title'), FILTER_SANITIZE_STRING);
            $content = $post['content'];//filter_var(Request::post('bbcode_post'),  FILTER_SANITIZE_STRING);
            $topic   = $post['thread'];
            $board   = 18;//$post['board'];
            $member_id = 5562;

            //test if thread is in board
            //test if thread exists
            //test if icon is valid

            $pm = new PostModel();
            $insert_id = $pm->create(
                [
                    "id_topic"        => $topic,
                    "id_board"        => $board,
                    "id_member"       => $member_id,

                    "subject"         => $title,
                    "body"            => $content,
                    "icon"            => "xx",
                    "approved"        => 1,

                    "poster_name"     => "John ",
                    "poster_email"    => "johnericdavis@google.com",
                    "poster_ip"       => $_SERVER['REMOTE_ADDR'],
                    "poster_time"     => time(),

                    "id_msg_modified" => $topic,
                    "modified_time"   => 0,
                    "modified_name"   => '',
                ]
            );
            if ($insert_id) {
                $bm = (new BoardModel());
                $bm->update(
                    [
                        'id_last_msg'    => $insert_id,
                        'id_msg_updated' => $insert_id
                    ],
                    [
                        'id_board' => $board,
                    ]
                );

                $tm = new ThreadModel();
                $tm->update(
                    [
                        'id_last_msg'    => $insert_id,
                        'id_msg_updated' => $insert_id
                    ],
                    [
                        'id_topic'       => $topic,
                    ]
                );
                
                $this->view->redirectController("Forum/Thread/$topic");
            }
            var_dump($post);
        }
        /*
         * MARK AS READ:
         * 		$smcFunc['db_query']('', '
			UPDATE {db_prefix}log_topics
			SET id_msg = {int:id_msg}
			WHERE id_member = {int:current_member}
				AND id_topic = {int:id_topic}',
			array(
				'current_member' => $user_info['id'],
				'id_msg' => $modSettings['maxMsgID'],
				'id_topic' => $topicOptions['id'],
			)
		);
         */

    }
    protected function getParser() {
        $parser = new \JBBCode\Parser();
        $builder = new \JBBCode\CodeDefinitionBuilder(
            'quote',
            '<div class="quote">{param}</div>'
        );
        $parser->addCodeDefinition($builder->build());

        $builder = new \JBBCode\CodeDefinitionBuilder(
            'code',
            '<pre class="code">{param}</pre>'
        );
        $builder->setParseContent(false);
        $parser->addCodeDefinition($builder->build());
        $builder = new \JBBCode\CodeDefinitionBuilder(
            'size',
            '<span style="text-size:{option};">{param}</span>'
        );
        $parser->addCodeDefinition($builder->build());
        $builder = new \JBBCode\CodeDefinitionBuilder(
            'hr',
            '<hr>'
        );

        $parser->addCodeDefinition($builder->build());


        $parser->addCodeDefinitionSet(new \JBBCode\DefaultCodeDefinitionSet());

        return $parser;
    }
}
