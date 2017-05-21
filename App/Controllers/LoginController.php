<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Classes\Controller;
use App\Classes\Login;
use App\Classes\View;
use App\Classes\FormBuilder;
use App\Classes\Request;
use App\Classes\Form\Elements\TextElement;
use App\Classes\Form\Elements\PasswordElement;

use App\Classes\Form\Elements\CheckboxElement;
use App\Classes\Form\Element;
use App\Util\UrlUtils;

use Cartalyst\Sentinel\Native\Facades\Sentinel;


class LoginController extends Controller
{
    protected $login;
    protected $validActions = ["Index","Login", "Register","Logout"];

    function __construct()
    {
        $this->view = new View();
    }


    //Our Pages
    function Index()
    {
        if (Sentinel::check()) {
            $this->view->redirectController('Admin');

            return;
        } else {

            $this->renderForms();
            return;
        }
    }



    protected function getLoginForm()
    {
        $fb = new FormBuilder();

        $fb->setTarget(UrlUtils::getControllerUrl('Login/Login'));

        $fb->add(
            new TextElement(
                [
                    "placeholder" => "email@domain.com",
                    //"label" => "Email:",
                    'name'  => "email"
                ]
            )
        )->add(
            new PasswordElement(
                [
                    "placeholder" => "p@ssw0rd",
                    'name'  => "pass"
                ]
            )
        )->add(
            new CheckboxElement(
                [
                    "label" => "Remember Me",
                    'name'  => "remember"
                ]
            )
        );
        return $fb;
    }



    protected function getRegisterForm()
    {
        $fb = new FormBuilder();

        $fb->setTarget(UrlUtils::getControllerUrl('Login/Register'));

        $fb->add(
            new TextElement(
                [
                    "placeholder" => "email@domain.com",
                  //  "label" => "Email:",
                    'name'  => "email"
                ]
            )
        )->add(
            new PasswordElement(
                [
                    "placeholder" => "Password",
                 //   "label" => "Password:",
                    'name'  => "pass"
                ]
            )
        );
        return $fb;
    }



    protected function renderForms()
    {
        $form = $this->getLoginForm()->render(true);
     //   $form.= $this->getRegisterForm()->render(true);
        $this->view->render('Login/html', ['form'=>$form]);

    }



    function Login()
    {
        if ($this->getLoginForm()->post()) {
            $username = filter_var(Request::post('email'), FILTER_SANITIZE_STRING);
            $password = filter_var(Request::post('pass'),  FILTER_SANITIZE_STRING);

            $credentials = [
                'email'    => $username,
                'password' => $password,
            ];

            Sentinel::authenticate($credentials);

            if (Sentinel::check()) {
                $this->view->redirectController("Admin");
                return;
            }
        }
        $this->log_out();
    }



    function Logout()
    {
        Sentinel::logout();
        $this->view->redirectController("Login");
    }



    protected function log_out()
    {
        Sentinel::logout();
        $this->view->redirectController("Login");
    }



    function Register()
    {
        //$username   = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password   = filter_var(Request::post('pass'),     FILTER_SANITIZE_STRING);
        $email      = filter_var(Request::post('email'),    FILTER_SANITIZE_EMAIL);

        Sentinel::register(
            [
                'email'    => $email,
                'password' => $password,
            ]
        );

        $this->view->redirectController("Login");
    }



}
