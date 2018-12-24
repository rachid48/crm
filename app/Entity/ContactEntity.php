<?php
/**
 * Created by PhpStorm.
 * User: TOUHAMI Rachid
 * Email: r.touhami90@gmail.com
 * Website: rachidtouhami.com
 * Date: 22-Dec-18
 * Time: 12:14 AM
 */

namespace App\Entity;

use Core\Entity\Entity;

class contactEntity extends Entity
{
    public $id;
    public $name;
    public $first_name;
    public $email;
    private $table = '';
    public function __construct()
    {
    }

    public function persist(){
        $app = \App::getInstance();
    }
}