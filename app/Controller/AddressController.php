<?php
/**
 * Created by PhpStorm.
 * User: TOUHAMI Rachid
 * Email: r.touhami90@gmail.com
 * Website: rachidtouhami.com
 * Date: 22-Dec-18
 * Time: 9:28 PM
 */

namespace App\Controller;


use App\Entity\AddressEntity;

/**
 * Class AddressController
 * @package App\Controller
 */
class AddressController extends AppController
{

    /**
     * AddressController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('contact');
        $this->loadModel('address');
    }

    /**
     * @param $contact_id
     * @return bool|void
     */
    public function create($contact_id)
    {
        $data = ['contact_id' => $contact_id];
        if (!isset($contact_id)) {
            if (!isset($_POST['contact_id'])) {
                return $this->notFound();
            } else {
                $contact_id = $_POST['contact_id'];
            }
        }

        if (isset($_POST['address_1'])) {

            $aAddress['address_1'] = (isset($_POST['address_1'])) ? $_POST['address_1'] : null;
            $aAddress['address_2'] = (isset($_POST['address_2'])) ? $_POST['address_2'] : null;
            $aAddress['city'] = (isset($_POST['city'])) ? $_POST['city'] : null;
            $aAddress['zipcode'] = (isset($_POST['zipcode'])) ? $_POST['zipcode'] : null;
            $aAddress['country'] = (isset($_POST['country'])) ? $_POST['country'] : null;
            $aAddress['contact_id'] = (isset($_POST['contact_id'])) ? $_POST['contact_id'] : null;

            $messagesErrors = $this->fieldsValidate($_POST);

            if (!empty($messagesErrors)) {
                $data['messages'] = $messagesErrors;
                return $this->render('address/create', $data);
            } else {
                $this->address->insert($aAddress);
                $messages[] = [
                    "text" => "L'adresse à été bien ajouter",
                    "type" => "success"
                ];
                $data['messages'] = $messages;
            }
        }
        return $this->render('address/create', $data);
    }

    /**
     * @param $id
     * @param array $messages
     * @return bool
     */
    public function edit($id, $messages = [])
    {
        $data = [];

        if (!isset($id)) {

            $aAddress['id'] = (isset($_POST['id'])) ? $_POST['id'] : null;
            $aAddress['address_1'] = (isset($_POST['address_1'])) ? $_POST['address_1'] : null;
            $aAddress['address_2'] = (isset($_POST['address_2'])) ? $_POST['address_2'] : null;
            $aAddress['city'] = (isset($_POST['city'])) ? $_POST['city'] : null;
            $aAddress['zipcode'] = (isset($_POST['zipcode'])) ? $_POST['zipcode'] : null;
            $aAddress['country'] = (isset($_POST['country'])) ? $_POST['country'] : null;

            $messagesErrors = $this->fieldsValidate($aAddress);
            if (!empty($messagesErrors)) {
                return $this->edit($aAddress['id'], $messagesErrors);
            }
            $address = $this->address->find($aAddress['id']);

            if ($address) {
                $contact = $this->contact->find($address->contact_id);
                if ($contact->user_id === $this->getUserId()) {

                    $this->address->update($aAddress['id'], $aAddress);
                    $address = $this->address->find($aAddress['id']);
                    $data ['address'] = $address;
                    $messages[] = [
                        "text" => "La modification d'adresse est réussite",
                        "type" => "success"
                    ];
                } else {
                    return $this->forbidden();
                }
            } else {
                return $this->notFound();
            }
        } elseif (isset($id)) {
            $address = $this->address->find($id);
            if ($address) {
                $contact = $this->contact->find($address->contact_id);

                if ($contact->user_id === $this->getUserId()) {
                    $data ['address'] = $address;
                } else {
                    $messages[] = [
                        "text" => "Vous n'avez pas access à ce contact !",
                        "type" => "warning"
                    ];
                }
            }
        }

        $data['messages'] = $messages;
        return $this->render('address/edit', $data);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $address = $this->address->find($id);
        $contactController = new ContactController();

        if (!is_null($address)) {
            $contact = $this->contact->find($address->contact_id);
            if ($contact) {
                if ($contact->user_id === $this->getUserId()) {
                    $this->address->delete($id);
                    $messages[] = [
                        "text" => "Adresse supprimer !",
                        "type" => "success"
                    ];
                } else {
                    $messages[] = [
                        "text" => "Vous n'avez pas access",
                        "type" => "warning"
                    ];

                }
                return $contactController->details($contact->id, $messages);
            } else {
                $messages[] = [
                    "text" => "Vous n'avez pas access",
                    "type" => "warning"
                ];
            }
        } else {
            $messages[] = [
                "text" => "Adresse non trouvé",
                "type" => "warning"
            ];
        }
        return $contactController->index($messages);
    }

    /**
     * @param array $fields
     * @return array
     */
    public function fieldsValidate(array $fields)
    {
        $messages = [];
        if (isset($fields['address_1'])) {
            $res = $this->formValidator->validateUpcase($fields['address_1']);
            if (!$res) {
                $messages[] = [
                    'text' => 'Numéro et libellé de la voie non valid! le champ doit être en majuscule',
                    'type' => 'danger'
                ];
            }
        }
        if (isset($fields['address_2'])) {
            $res = $this->formValidator->validateUpcase($fields['address_2']);
            if (!$res) {
                $messages[] = [
                    'text' => 'Complement d\'adresse non valid! le champ doit être en majuscule',
                    'type' => 'danger'
                ];
            }
        }

        if (isset($fields['city'])) {
            $res = $this->formValidator->validateUpcase($fields['city']);
            if (!$res) {
                $messages[] = [
                    'text' => 'Ville non valid! le champ doit être en majuscule',
                    'type' => 'danger'
                ];
            }
        }
        if (isset($fields['zipcode'])) {
            $res = $this->formValidator->validateUpcase($fields['zipcode']);
            if (!$res) {
                $messages[] = [
                    'text' => 'Code postale non valid! le champ doit être en majuscule',
                    'type' => 'danger'
                ];
            }
        }
        if (isset($fields['country'])) {
            $res = $this->formValidator->validateUpcase($fields['country']);
            if (!$res) {
                $messages[] = [
                    'text' => 'Pays non valid! le champ doit être en majuscule',
                    'type' => 'danger'
                ];
            }
        }
        return $messages;
    }

}