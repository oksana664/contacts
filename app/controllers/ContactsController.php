<?php

use Validators\Contacts\Create as CreateValidator;

class ContactsController extends ControllerBase
{

    /**
     * Creates a new contact
     */
    public function createAction()
    {
        if ($this->request->isPost()) {
            try {
                $validator = new CreateValidator();
                $validator->validate($this->request->getPost());

                $contact = new Contacts();
                $contact->setFirstName($validator->getValue('first_name'));
                $contact->setLastName($validator->getValue('last_name'));
                $contact->setEmail($validator->getValue('email'));
                $contact->setBirthdate($validator->getValue('birthdate'));
                $contact->setPhone($validator->getValue('phone'));

                if (!$contact->save()) {
                    foreach ($contact->getMessages() as $message) {
                        throw new Exception($message);
                    }
                }
                $this->flash->success('Contact was created successfully');
            } catch (Exception $e) {
                do {
                    $this->flash->error($e->getMessage());
                } while ($e = $e->getPrevious());
                $this->dispatcher->forward([
                    'controller' => 'contacts',
                    'action' => 'new'
                ]);
                return;
            }
        }

        if (!$this->request->isAjax()) {
            $this->dispatcher->forward([
                'controller' => 'contacts',
                'action' => 'search'
            ]);
        }
    }

}
