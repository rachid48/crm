<?php
/**
 * Created by PhpStorm.
 * User: xhost
 * Date: 19-Dec-18
 * Time: 5:38 PM
 */

namespace App\Controller;

/**
 * Class ContactController
 * @package App\Controller
 */
class ContactController extends AppController
{
    /**
     * ContactController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('contact');
        $this->loadModel('address');
    }

    /**
     * @param null $messages
     * @return bool
     */
    public function index($messages = null)
    {
        $user_id = $this->getUserId();
        $contacts = $this->contact->findByUser($user_id);
        return $this->render('contact/index', ['contacts' => $contacts, 'messages' => $messages]);
    }

    /**
     * @param null $messages
     * @return bool
     */
    public function create($messages = null)
    {
        $data = [];
        if (isset($_POST['name'])) {
            // cas d'ajout d'un nouveau contact
            $name = $_POST['name'];
            $first_name = $_POST['first_name'];
            $email = $_POST['email'];

            $messagesErrors = $this->fieldsValidate($_POST);
            if (!empty($messagesErrors)) {
                $data['messages'] = $messagesErrors;
                return $this->render('contact/create', $data);
            }

            $contact = $this->contact->contactExist($email, $this->getUserId());
            if ($contact) {
                $messages[] = [
                    "text" => "L'adresse email déja existe",
                    "type" => "danger"
                ];
                $data['contact'] = $contact;
                $data['messages'] = $messages;
                return $this->render('contact/create', $data);
            } else {

                $data = [
                    "name" => $name,
                    "first_name" => $first_name,
                    "email" => $email,
                    "user_id" => $this->getUserId(),
                ];
                $this->contact->create($data);
                $messages[] = [
                    "text" => "Votre contact à été bien ajouter",
                    "type" => "success"
                ];
                return $this->index($messages);
            }
        } else {

            return $this->render('contact/create', $data);
        }
    }

    /**
     * @param null $id
     * @return bool
     */
    public function edit($id = null)
    {
        $data = [];
        $messages = [];
        if (isset($_POST['id'])) {

            $id = $_POST['id'];
            $name = $_POST['name'];
            $first_name = $_POST['first_name'];
            $email = $_POST['email'];

            $messagesErrors = $this->fieldsValidate($_POST);
            if (!empty($messagesErrors)) {
                $contact = $this->contact->find($id);
                $data['messages'] = $messagesErrors;
                $data ['contact'] = $contact;
                return $this->render('contact/edit', $data);
            }
            $contact = $this->contact->find($id);
            if ($contact->user_id === $this->getUserId()) {
                $contact->name = $name;
                $contact->first_name = $first_name;
                $contact->email = $email;
                $this->contact->update($id, [
                    'name' => $contact->name,
                    'first_name' => $contact->first_name,
                    'email' => $contact->email,
                ]);

                $messages[] = [
                    "text" => "Modification de votre contact réussite",
                    "type" => "success"
                ];

                return $this->index($messages);
            }
        } elseif (isset($id)) {
            $contact = $this->contact->find($id);
            if ($contact) {

                if ($contact->user_id === $this->getUserId()) {
                    $data ['contact'] = $contact;
                } else {
                    $messages[] = [
                        "text" => "Vous n'avez pas access à ce contact !",
                        "type" => "warning"
                    ];
                }
            }
        }
        $data['messages'] = $messages;
        return $this->render('contact/edit', $data);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $data = [];
        $messages = [];
        $contact = $this->contact->find($id);
        if ($contact) {
            if ($contact->user_id === $this->getUserId()) {
                $res = $this->contact->delete($id);
                if ($res) {
                    $messages[] = [
                        'text' => 'Contact supprimer',
                        'type' => 'success'
                    ];
                    return $this->index($messages);
                }
            }
        }
        $messages[] = [
            'text' => 'Une erreur est survenu veuillez vérifier que vous avez bien les droits néssaisair',
            'type' => 'warning'
        ];
        return $this->index($messages);
    }

    /**
     * @param $id
     * @param null $messages
     * @return bool
     */
    public function details($id, $messages = null)
    {
        $data = [];
        if (isset($messages)) {
            $data['messages'] = $messages;
        }
        if (isset($id)) {
            $data['contact'] = $this->contact->find($id);
            $data['addresses'] = $this->address->findByContactId($id);
        }
        return $this->render('contact/details', $data);
    }

    /**
     * @param $fields
     * @return array
     */
    public function fieldsValidate($fields)
    {
        $messages = [];
        if (isset($fields['name'])) {
            $res = $this->formValidator->validateUcfirst($fields['name']);
            if (!$res) {
                $messages[] = [
                    'text' => 'Nom non valid ! la première lettre du sous-titre doit être en majuscule le reste en miniscule',
                    'type' => 'danger'
                ];
            }
        }
        if (isset($fields['name'])) {
            $res = $this->formValidator->isPalindrome($fields['name']);
            if ($res) {
                $messages[] = [
                    'text' => 'Le nom du contact ne peut pas être un palindrome',
                    'type' => 'danger'
                ];
            }
        }
        if (isset($fields['first_name'])) {
            $res = $this->formValidator->validateUcfirst($fields['first_name']);
            if (!$res) {
                $messages[] = [
                    'text' => 'Prénom non valid ! la première lettre du sous-titre doit être en majuscule le reste en miniscule',
                    'type' => 'danger'
                ];
            }
        }
        if (isset($fields['email'])) {
            $res = $this->formValidator->validateEmail($fields['email']);
            if (!$res) {
                $messages[] = [
                    'text' => 'Email non valid !',
                    'type' => 'danger'
                ];
            }
        }
        return $messages;
    }

}