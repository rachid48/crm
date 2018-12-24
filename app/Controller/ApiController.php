<?php
/**
 * Created by PhpStorm.
 * User: TOUHAMI Rachid
 * Email: r.touhami90@gmail.com
 * Website: rachidtouhami.com
 * Date: 23-Dec-18
 * Time: 10:48 PM
 */

namespace App\Controller;


use App\Services\FormValidateService;

/**
 * Class ApiController
 * @package App\Controller
 */
class ApiController extends AppController
{

    /**
     * @param $email
     */
    public function checkEmail($email)
    {
        $validatorService = new FormValidateService();
        $res = $validatorService->validateEmail($email);

        header('Content-type: application/json');
        if ($res) {
            echo json_encode(array('response' => [
                'valide' => true,
                'message' => 'Email valide',
                'email' => $email,
            ]));
        } else {
            echo json_encode(array('response' => [
                'valide' => false,
                'message' => 'Email invalide',
                'email' => $email,
            ]));
        }
        die();
    }
}