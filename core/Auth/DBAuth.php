<?php
/**
 * Created by PhpStorm.
 * User: xhost
 * Date: 19-Dec-18
 * Time: 10:46 PM
 */

namespace Core\Auth;

use Core\Database\Database;
use Core\Database\MysqlDatabase;

/**
 * Class DBAuth
 * @package Core\Auth
 */
class DBAuth
{

    /**
     * @var MysqlDatabase
     */
    private $db;

    /**
     * DBAuth constructor.
     * @param MysqlDatabase $db
     */
    public function __construct(MysqlDatabase $db)
    {
        $this->db = $db;
    }

    /**
     * @param $login
     * @param $password
     * @return boolean
     */
    public function login($login, $password)
    {
        $user = $this->db->prepare('SELECT * FROM user WHERE login = ?', [$login], null, true);
        if ($user) {
            if ($user->password === sha1($password)) {
                $_SESSION['auth'] = $user->id;
                return true;
            }

        }
        return false;
    }

    /**
     * @return bool
     */
    public function getUserId()
    {
        if ($this->logged()) {

            return $_SESSION['auth'];
        }
        return false;
    }

    /**
     * @return bool
     */
    public function logged()
    {
        return isset($_SESSION['auth']);
    }

    /**
     *
     */
    public function logout()
    {
        unset($_SESSION['auth']);
    }

}