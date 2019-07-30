<?php
 
use Phalcon\Mvc\Model\Criteria;


class ContactsController extends ControllerBase
{

    /**
     * Creates a new contact
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => 'contacts',
                'action' => 'list'
            ]);

            return;
        }

        $contact = new Contacts();
        $contact->setFirstName($this->request->getPost('first_name'));
        $contact->setLastName($this->request->getPost('last_name'));
        $contact->setEmail($this->request->getPost('email', 'email'));
        $contact->setBirthdate($this->request->getPost('birthdate'));
        $contact->setPhone($this->request->getPost('phone'));

        if (!$contact->save()) {
            foreach ($contact->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => 'contacts',
                'action' => 'new'
            ]);

            return;
        }

        $this->flash->success('Contact was created successfully');

        $this->dispatcher->forward([
            'controller' => 'contacts',
            'action' => 'list'
        ]);
    }

}
