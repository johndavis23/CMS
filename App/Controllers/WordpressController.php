<?php
/*
 * Wordpress Bridge.
 *
 */
include_once "Classes/Controller.php";
include_once "Models/UserModel.php";
include_once "Classes/Login.php";
include_once "Classes/View.php";
include_once "Util/UrlUtils.php";

class WordpressControlller extends Controller
{
    protected $login;
    protected $validActions = ["Index","View"];

    function __construct()
    {
        $this->view     = new View();
        $this->login    = new Login();
    }

    //Our Pages
    public function Index()
    {
        //let's adhoc this for now...
        $model = new Model('wp_pp_posts', 'ID');
        $posts = $model->read(["post_status" => "publish"]);

        $this->view->render("Blog/Index", ['posts'=>$posts]);

        $model = new Model('wp_pp_pages', 'ID');
        //  var_dump($model->read([]));

        // $this->view->render("Blog/Index", ['posts'=>$posts]);
        return;
    }


    public function ViewPage($request)
    {
        //let's adhoc this for now...
        $model = new Model('wp_pp_posts', 'ID');
        $posts = $model->read(["post_status" => "publish", "post_type"=>"page"]);

        $this->view->render("Blog/Index", ['posts'=>$posts]);

        $model = new Model('wp_pp_pages', 'ID');
        var_dump($model->read([]));

        // $this->view->render("Blog/Index", ['posts'=>$posts]);
        return;
    }

    public function View( $request)
    {
        $id = $request->popNextParameter();

        if($id )
        {
            $model = new Model('wp_pp_posts', 'ID');
            $posts = $model->readWithID((int) $id);
            $this->view->render("Blog/Post", ['post'=>$posts[0]]);
        }
    }

    public function Tags()
    {
        $model = new Model('wp_pp_terms', 'term_id');
        $term_relationship_model = new Model('wp_term_relationships', 'object_id');
        //$terms =
        $tags  = $model->read([]);
    }


}
