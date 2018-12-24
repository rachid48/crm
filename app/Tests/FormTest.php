<?php
/**
 * Created by PhpStorm.
 * User: TOUHAMI Rachid
 * Email: r.touhami90@gmail.com
 * Website: rachidtouhami.com
 * Date: 24-Dec-18
 * Time: 12:35 AM
 */
require './../Services/FormValidateService.php';

//\App\Autoloader::register();

class FormTest extends \PHPUnit\Framework\TestCase
{

    /**@test */
    public function testStringNotPalindrome()
    {
        $formValidator = new \App\Services\FormValidateService();
        $this->assertEquals(false, $formValidator->isPalindrome('touhami'));
    }


    /**@test */
    public function testStringPalindrome()
    {
        $formValidator = new \App\Services\FormValidateService();
        $this->assertEquals(true, $formValidator->isPalindrome('DAD'));
    }

    /**@test */
    public function testWrongEmail()
    {
        $baseUrl = "http://127.0.0.1/crm/";
        $email = "r.touhami90.com";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $baseUrl . 'index.php?p=api.checkemail&email=' . $email);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($result);
        $this->assertEquals(false, $response->response->valide);
    }

    /**@test */
    public function testGoodEmail()
    {
        $baseUrl = "http://127.0.0.1/crm/";
        $email = "r.touhami90@gmail.com";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $baseUrl . 'index.php?p=api.checkemail&email=' . $email);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($result);
        $this->assertEquals(true, $response->response->valide);
    }
}