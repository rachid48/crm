<?php
/**
 * Created by PhpStorm.
 * User: TOUHAMI Rachid
 * Email: r.touhami90@gmail.com
 * Website: rachidtouhami.com
 * Date: 22-Dec-18
 * Time: 8:44 PM
 */

namespace App\Table;


use Core\Table\Table;

/**
 * Class AddressTable
 * @package App\Table
 */
class AddressTable extends Table
{
    /**
     * @param $contact_id
     * @return mixed
     */
    public function findByContactId($contact_id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE contact_id=?",[$contact_id]);
    }

}