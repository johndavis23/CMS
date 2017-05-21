<?php
namespace App\Controllers;

use App\Classes\Controller;
use App\Classes\Login;
use App\Classes\View;
use App\Classes\FormBuilder;
use App\Classes\Request;
use App\Classes\Form\Elements\TextElement;
use App\Classes\Form\Element;
use App\Util\UrlUtils;
use App\Classes\Model\ModelFactory;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

class AdminController extends Controller
{
    protected $login;
    protected $validActions = ["Dashboard", "Read", "Create",  "Update", "Delete"];
    protected $request;
    protected $user;

    function __construct()
    {
        $this->view    = new View();
        $this->request = Request::current();
        if (! $this->user = Sentinel::getUser() )
            $this->view->redirectController("Login");
    }

    public function Create()
    {
        $model_name = $this->request->popNextParameter();
        $model      = ModelFactory::build($model_name); //TODO Replace with PermissionedModelFactory

        $form       = $model->getForm();
        $form->setTarget(UrlUtils::getControllerUrl('Admin/Create'));

        if ($post = $form->post()) {
            $model->create($post);
            $this->view->redirectController("Admin/Read/$model_name");
        } else {
            $html = $form->render(true);
            $this->view->render(
                'Admin/html',
                [
                    'user'              => ['name'=>$this->user->email],
                    'content'           => $html,
                    'page_description'  => "Create",
                    "page_header"       => $model_name
                ]
            );
        }
    }

    public function Read()
    {

        $model_name = $this->request->popNextParameter();
        $model      = ModelFactory::build($model_name);//TODO Replace with PermissionedModelFactory
        $all        = $model->readAll(); //todo paginate

        $out = [];
        foreach ($all as $array) {
            $out[] = $model::toListItem($array);
        }
        $all = $out;

        $this->view->render(
            'Admin/html',
            [
                'user'              => ['name'=>$this->user->email],
                'elements'          => $all,
                'page_description'  => "List",
                "page_header"       => $model_name
            ]
        );
    }

    public function Update()
    {
        $model_name = $this->request->popNextParameter();
        $id         = $this->request->popNextParameter();
        $model      = ModelFactory::build($model_name); //TODO Replace with PermissionedModelFactory
        $values     = $model->readWithId($id);
        $form       = $model->getForm($values);
        $form->setTarget(UrlUtils::getControllerUrl('Admin/Update'));

        if ($post = $form->post()) {
            $model->edit($post,['id'=>$id]);
            $this->view->redirectController("Admin/Read/$model_name");
        } else {
            $html = $form->render(true);
            $this->view->render(
                'Admin/html',
                [
                    'user'              => ['name'=>$this->user->email],
                    'content'           => $html,
                    'page_description'  => "Update",
                    "page_header"       => $model_name
                ]
            );
        }
    }

    public function Delete()
    {
        $model_name = $this->request->popNextParameter();
        $id         = $this->request->popNextParameter();

        PermissionedModelFactory::build($model_name)->delete(['id'=>$id]);
    }

    public function Dashboard()
    {
        $user = Sentinel::getUser();
        if ($user) {
            $user = [
                'name' => $user->email
            ];
            $this->view->render('Admin/html',['user'=>$user]);
            //some view
        } else {
            $this->view->redirectController("Login");
        }


    }

    protected function form() {

    }

}







