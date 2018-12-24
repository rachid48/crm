<?php
/**
 * Created by PhpStorm.
 * User: xhost
 * Date: 19-Dec-18
 * Time: 5:47 PM
 */

namespace Core;

/**
 * Class Autoloader
 * @package Core
 */
class Autoloader
{

    /**
     *
     */
    static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * @param $class
     */
    static function autoload($class)
    {
        $class = str_replace(__NAMESPACE__.'\\', '', $class);
        $class = str_replace('\\', '/', $class);
        require $class. '.php';
    }

}