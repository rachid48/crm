<?php
/**
 * Created by PhpStorm.
 * User: xhost
 * Date: 19-Dec-18
 * Time: 7:36 PM
 */

use \Core\Database\MysqlDatabase;
use \Core\Config;

/**
 * Class App
 */
class App
{

    /**
     * @var
     */
    private static $_instance;
    /**
     * @var
     */
    private $db_instance;

    /**
     * @return App
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new App();
        }
        return self::$_instance;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getTable($name)
    {
        $class_name = 'App\Table\\' . ucfirst($name) . 'Table';
        return new $class_name($this->getDb());
    }

    /**
     * @return MysqlDatabase
     */
    public function getDb()
    {
        $config = Config::getInstance(ROOT . '/config/config.php');
        if (is_null($this->db_instance)) {
            $this->db_instance = new MysqlDatabase($config->get('db_name'), $config->get('db_user'), $config->get('db_pass'), $config->get('db_host'));
        }
        return $this->db_instance;
    }

    /**
     *
     */
    public static function load()
    {
        session_start();
        require ROOT . '/app/Autoloader.php';
        \App\Autoloader::register();

        require ROOT . '/core/Autoloader.php';
        \Core\Autoloader::register();
    }

    /**
     *
     */
    public function logout(){
        $auth = new \Core\Auth\DBAuth($this->getDb());
        $auth->logout();
        header('Location: index.php');
    }


}