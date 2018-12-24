<?php
/**
 * Created by PhpStorm.
 * User: TOUHAMI Rachid
 * Email: r.touhami90@gmail.com
 * Website: rachidtouhami.com
 * Date: 21-Dec-18
 * Time: 1:42 AM
 */

namespace Core\Controller;

use Core\Auth\DBAuth;

/**
 * Class Controller
 * @package Core\Controller
 */
abstract class Controller
{
    /**
     * @var
     */
    protected $viewPath;
    /**
     * @var
     */
    protected $template;
    /**
     * @var
     */
    protected $twig_instance;

    /**
     * @return mixed
     */
    abstract protected function index();

    /**
     * @param $view
     * @param null $variables
     * @return bool
     */
    protected function render($view, $variables = null)
    {
        if (is_null($this->twig_instance)) {
            $loader = new \Twig_Loader_Filesystem($this->viewPath);
            $this->twig_instance = new \Twig_Environment($loader, [
                'cache' => false,
            ]);
        }
        $view = $view . '.twig';
        try{
            echo  $this->twig_instance->render( $view, $variables);
            return true;
        } catch (\Exception $exception) {
            echo $exception;
            return false;
        }
    }

    /**
     *
     */
    protected function forbidden()
    {
        header('HTTP/1.0 403 Forbidden');
        die('Acces interdit');
    }

    /**
     *
     */
    protected function notFound()
    {
        header('HTTP/1.0 404 Not Found');
    }

    /**
     * @return bool
     */
    protected function isLogged()
    {
        $auth = new DBAuth(\App::getInstance()->getDb());
        return $auth->logged();
    }

}