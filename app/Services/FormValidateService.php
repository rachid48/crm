<?php
/**
 * Created by PhpStorm.
 * User: TOUHAMI Rachid
 * Email: r.touhami90@gmail.com
 * Website: rachidtouhami.com
 * Date: 23-Dec-18
 * Time: 5:28 PM
 */

namespace App\Services;

/**
 * Class FormValidateService
 * @package App\Services
 */
class FormValidateService
{

    /**
     * FormValidateService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string|null $email
     * @return bool
     */
    public function validateEmail(string $email = null)
    {
        if (isset($email) && !empty($email)) {
            if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $email)) {
                if ($email === strtolower($email)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $string
     * @return bool
     */
    public function validateUcfirst($string)
    {
        if (isset($string) && !empty($string)) {
            $stringTmp = strtolower($string);
            $stringTmp = ucfirst($stringTmp);
            if ($stringTmp === $string) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $string
     * @return bool
     */
    public function validateUpcase($string)
    {
        if (isset($string) && !empty($string)) {
            $stringTmp = strtoupper($string);
            if ($stringTmp === $string) {
                return true;
            }
        }
        return false;
    }


    /**
     * @param $string
     * @return bool
     */
    public function isPalindrome($string)
    {
        $string = strtolower($string);
        if (strrev($string) === $string) {
            return true;
        }
        return false;
    }

}