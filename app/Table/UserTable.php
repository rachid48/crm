<?php
/**
 * Created by PhpStorm.
 * User: TOUHAMI Rachid
 * Email: r.touhami90@gmail.com
 * Website: rachidtouhami.com
 * Date: 21-Dec-18
 * Time: 12:58 AM
 */

namespace App\Table;


use Core\Table\Table;

/**
 * Class UserTable
 * @package App\Table
 */
class UserTable extends Table
{

    /**
     * @param $login
     * @return mixed
     */
    public function findByLogin($login)
    {
        return $this->query("SELECT * FROM user WHERE login=?", [$login], true);
    }
}