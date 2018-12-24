<?php
/**
 * Created by PhpStorm.
 * User: TOUHAMI Rachid
 * Email: r.touhami90@gmail.com
 * Website: rachidtouhami.com
 * Date: 21-Dec-18
 * Time: 5:21 PM
 */

namespace App\Controller;


use App\Services\FormValidateService;
use Core\Auth\DBAuth;
use Core\Controller\Controller;

/**
 * Class AppController
 * @package App\Controller
 */
class AppController extends Controller
{

    /**
     * @var string
     */
    protected $template = 'default';
    /**
     * @var \App
     */
    protected $app;
    /**
     * @var FormValidateService
     */
    protected $formValidator;

    /**
     * AppController constructor.
     */
    public function __construct()
    {
        $this->viewPath = ROOT . '/app/Views/';
        $this->app = \App::getInstance();
        $this->formValidator = new FormValidateService();
    }

    /**
     * @param $model_name
     */
    protected function loadModel($model_name)
    {
        $this->$model_name = $this->app->getTable($model_name);
    }

    /**
     * @return bool
     */
    protected function getUserId(){
        $auth = new DBAuth($this->app->getDb());
        return $auth->getUserId();
    }

    /**
     * @return bool|void
     */
    public function notFound()
    {
        parent::notFound();
        $data = [];
        return $this->render('templates/404', $data);
    }


}