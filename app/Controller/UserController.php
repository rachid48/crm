<?php
/**
 * Created by PhpStorm.
 * User: TOUHAMI Rachid
 * Email: r.touhami90@gmail.com
 * Website: rachidtouhami.com
 * Date: 21-Dec-18
 * Time: 6:55 PM
 */

namespace App\Controller;

use \Core\Auth\DBAuth;
use Core\Database\MysqlDatabase;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AppController
{
    /**
     * @var DBAuth
     */
    private $dbAuthService;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->dbAuthService = new DBAuth($this->app->getDb());
        $this->loadModel('user');
    }

    /**
     * @return bool
     */
    public function index()
    {
        if ($this->isLogged()) {
            $data = [];
            $this->render('user/index', $data);
        } else {

            if (isset($_POST['username']) && isset($_POST['password'])) {

                $auth = new DBAuth(\App::getInstance()->getDb());

                if ($auth->login($_POST['username'], $_POST['password'])) {
                    $contactController = new ContactController();
                    return $contactController->index();

                }
            } else {
                $this->login();
            }
        }
    }

    /**
     * @return bool
     */
    public function login()
    {
        $messages = [];
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $auth = new DBAuth(\App::getInstance()->getDb());
            if ($auth->login($_POST['username'], $_POST['password'])) {
                $contactController = new ContactController();
                return $contactController->index();
            } else {
                $messages[] = [
                    "text" => "Identifiants non reconnus, veuillez vérifier votre identifiant et votre mot de passe.",
                    "type" => "danger"
                ];
            }
        }
        $data['messages'] = $messages;
        $this->render('user/login', $data);
    }

    /**
     * @return bool
     */
    public function logout()
    {
        $this->dbAuthService->logout();
        return $this->index();
    }

    /**
     * @return bool
     */
    public function create()
    {
        $data = [];
        $messages = [];
        if( isset($_POST['login'] )){

            $login = $_POST['login'];
            $user = $this->user->findByLogin($login);
            if($user){
                $messages[] = [
                    "text" => "Login déja utilisé",
                    "type" => "danger"
                ];
                $data['messages'] = $messages;
                return $this->render('user/login', $data);
            }

            if (!is_null($_POST['password']) && !is_null($_POST['password2'])){
                $password = $_POST['password'];
                $password2 = $_POST['password2'];
                if($password === $password2){
                    $aUser = [
                      'login' => $login,
                      'password' => sha1($password),
                    ];
                    $user = $this->user->insert($aUser);
                    $messages[] = [
                        "text" => "Votre compte à étais creer",
                        "type" => "success"
                    ];
                    $data['messages'] = $messages;
                    return $this->render('user/login', $data);
                }
            }
        }
    }
}





















