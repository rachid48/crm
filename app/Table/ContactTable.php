<?php
/**
 * Created by PhpStorm.
 * User: xhost
 * Date: 19-Dec-18
 * Time: 7:21 PM
 */

namespace App\Table;


use App;
use Core\Config;
use Core\Table\Table;

/**
 * Class ContactTable
 * @package App\Table
 */
class ContactTable extends Table
{

    /**
     * @param $user_id
     * @return mixed
     */
    public function findByUser($user_id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE user_id = ?", [$user_id], false);
    }

    /**
     * @param $data
     */
    public function create($data)
    {
        $this->insert($data);
    }

    /**
     * @param string $email
     * @param int $user_id
     * @return mixed
     */
    public function contactExist(string $email, int $user_id){

        $contact =  $this->query("SELECT * FROM {$this->table} WHERE user_id = ? AND email=?", [$user_id, $email], true);
        return $contact;
    }


}