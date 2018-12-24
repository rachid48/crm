<?php
/**
 * Created by PhpStorm.
 * User: xhost
 * Date: 19-Dec-18
 * Time: 5:16 PM
 */

define('ROOT', dirname(__FILE__));
require ROOT . '/app/App.php';
require_once './vendor/autoload.php';

App::load();
$app = App::getInstance();

use \Core\Auth\DBAuth;
use \App\Controller\UserController;
use \App\Controller\ContactController;
use \App\Controller\AddressController;
use \App\Controller\ApiController;

if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 'contact.index';
}

if (isset($_POST['logout'])) {
    $app->logout();
}

$auth = new DBAuth($app->getDb());

if (!$auth->logged()) {

    if ($page === "api.checkemail") {
        $email = (isset($_GET['email'])) ? $_GET['email'] : null;
        $controller = new  ApiController();
        $controller->checkEmail($email);
    } else {
        $controller = new UserController();
        if (isset($_POST['password2'])) {
            $controller->create();
        } else {
            $controller->login();
        }
    }

} else {

    /*
    TODO Build Rooting sys
    $root_part = explode('.', $page);
    if(!is_null($root_part[0])){
        $controllerName = '\App\Controller\\' . ucfirst($root_part[0])."Controller";
        $controller = new $controllerName();
        $controller->$root_part[2]();
    }*/

    if ($page === 'contact.index') {
        $controller = new ContactController();
        $controller->index();
    } elseif ($page === "contact.add") {
        $controller = new ContactController();
        $controller->create();
    } elseif ($page === "contact.edit") {
        $controller = new ContactController();
        $id = (isset($_GET['id'])) ? $_GET['id'] : null;
        $controller->edit($id);
    } elseif ($page === "contact.delete") {
        $controller = new ContactController();
        $id = (isset($_GET['id'])) ? $_GET['id'] : null;
        $controller->delete($id);
    } elseif ($page === "contact.details") {
        $controller = new ContactController();
        $id = (isset($_GET['id'])) ? $_GET['id'] : null;
        $controller->details($id);
    } elseif ($page === "address.create") {
        $controller = new AddressController();
        $id = (isset($_GET['id'])) ? $_GET['id'] : null;
        $controller->create($id);
    } elseif ($page === "address.edit") {
        $controller = new AddressController();
        $id = (isset($_GET['id'])) ? $_GET['id'] : null;
        $controller->edit($id);
    } elseif ($page === "address.delete") {
        $controller = new AddressController();
        $id = (isset($_GET['id'])) ? $_GET['id'] : null;
        $controller->delete($id);
    } elseif ($page === "user.logout") {
        $controller = new UserController();
        $controller->logout();
    } elseif ($page === "user.create") {
        $controller = new UserController();
        $controller->create();
    } elseif ($page === "api.checkemail") {
        $email = (isset($_GET['email'])) ? $_GET['email'] : null;
    $controller = new  ApiController();
    $controller->checkEmail($email);
    } else {
        $controller = new \App\Controller\AppController();
        $controller->notFound();
    }
}




