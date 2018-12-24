<?php
/**
 * Created by PhpStorm.
 * User: TOUHAMI Rachid
 * Email: r.touhami90@gmail.com
 * Website: rachidtouhami.com
 * Date: 22-Dec-18
 * Time: 12:30 AM
 */

namespace Core\Entity;

/**
 * Class Entity
 * @package Core\Entity
 */
class Entity
{
    /**
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        $methode = 'get' . ucfirst($key);
        $this->$key = $this->$methode();
        return $this->$key;
    }

}