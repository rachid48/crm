<?php
/**
 * Created by PhpStorm.
 * User: xhost
 * Date: 19-Dec-18
 * Time: 6:24 PM
 */

namespace Core\Database;

use \PDO;

/**
 * Class MysqlDatabase
 * @package Core\Database
 */
class MysqlDatabase extends Database
{

    /**
     * @var
     */
    private $db_name;
    /**
     * @var string
     */
    private $db_user;
    /**
     * @var string
     */
    private $db_pass;
    /**
     * @var string
     */
    private $db_host;
    /**
     * @var
     */
    private $pdo;

    /**
     * Database constructor.
     * @param $db_name
     * @param $db_user
     * @param $db_pass
     * @param $db_host
     */
    public function __construct($db_name, $db_user = 'root', $db_pass = '', $db_host = 'localhost')
    {
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
    }

    /**
     * @return PDO
     */
    private function getPDO()
    {

        if ($this->pdo === null) {
            $pdo = new PDO('mysql:dbname=' . $this->db_name .';host=' . $this->db_host, $this->db_user, $this->db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }
        return $this->pdo;
    }

    /**
     * @param $statement
     * @param null $class_name
     * @param bool $one
     * @return array|bool|mixed|\PDOStatement
     */
    public function query($statement, $class_name = null, $one = false)
    {

        $req = $this->getPDO()->query($statement);
        if(
            strpos($statement, 'UPDATE') === 0 ||
            strpos($statement, 'INSERT') === 0 ||
            strpos($statement, 'DELETE') === 0
        ) {
            return $req;
        }

        if ($class_name === null) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }

        if ($one) {
            $datas = $req->fetch();
        } else {
            $datas = $req->fetchAll();
        }

        return $datas;
    }

    /**
     * @param $statement
     * @param $attributes
     * @param null $class_name
     * @param bool $one
     * @return array|bool|mixed
     */
    public function prepare($statement, $attributes, $class_name = null, $one = false)
    {

        $req = $this->getPDO()->prepare($statement);
        $res = $req->execute($attributes);

        if(
            strpos($statement, 'UPDATE') === 0 ||
            strpos($statement, 'INSERT') === 0 ||
            strpos($statement, 'DELETE') === 0
        ) {
            return $res;
        }

        if ($class_name === null) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        }

        if ($one) {
            $datas = $req->fetch();
        } else {
            $datas = $req->fetchAll();
        }

        return $datas;
    }


}