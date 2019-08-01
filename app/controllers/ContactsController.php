<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Validators\Contacts\Create as CreateValidator;
use \Validators\Contacts\Save as SaveValidator;

class ContactsController extends ControllerBase
{

    /**
     * Searches for contacts
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $params = [];
            if ($this->request->hasPost('search')) {
                $searchText = $this->request->getPost('search');
                $params['first_name'] = $searchText;
                $params['last_name'] = $searchText;
                $params['email'] = $searchText;
            }

            $query = Criteria::fromInput($this->di, 'Contacts', $params, 'OR');
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery('page', 'int');
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = [];
        }
        $parameters['order'] = 'last_name ASC';

        $contacts = Contacts::find($parameters);

        $paginator = new Paginator([
            'data' => $contacts,
            'limit' => 10,
            'page' => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a contact and displays the edit form
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $contact = Contacts::findFirstByid($id);
            if (!$contact) {
                $this->flash->error('contact was not found');

                $this->dispatcher->forward([
                    'controller' => 'contacts',
                    'action' => 'search'
                ]);

                return;
            }

            $this->view->id = $contact->getId();
            if ($this->request->isAjax()) {
                $this->view->contact = $contact;
            }

            $this->tag->setDefault('id', $contact->getId());
            $this->tag->setDefault('first_name', $contact->getFirstName());
            $this->tag->setDefault('last_name', $contact->getLastName());
            $this->tag->setDefault('email', $contact->getEmail());
            $this->tag->setDefault('birthdate', $contact->getBirthdate());
            $this->tag->setDefault('phone', $contact->getPhone());
        }
    }

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
                $name = $contact->getFullName();
                $this->flash->success("Contact '$name' created");
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

    /**
     * Saves a contact edited
     *
     */
    public function saveAction()
    {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            try {
                $validator = new SaveValidator();
                $validator->validate($this->request->getPost());

                $contact = Contacts::findFirstByid($id);
                if (!$contact instanceof Contacts) {
                    throw new Exception("contact does not exist {$id}");
                }
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
                $name = $contact->getFullName();
                $this->flash->success("Contact '$name' updated");
            } catch (Exception $e) {
                do {
                    $this->flash->error($e->getMessage());
                } while ($e = $e->getPrevious());
                $this->dispatcher->forward([
                    'controller' => 'contacts',
                    'action' => 'edit',
                    'params' => [$id]
                ]);
                return;
            }
        }
        $this->dispatcher->forward([
            'controller' => 'contacts',
            'action' => 'search'
        ]);
    }

    /**
     * Deletes a contact
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $id = intval($id);
        if ($id > 0) {
            $contact = Contacts::findFirstByid($id);
            if ($contact instanceof Contacts) {
                $name = $contact->getFullName();
                if ($contact->delete()) {
                    $this->flash->success("Contact '$name' was deleted");
                } else {
                    foreach ($contact->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                }
            } else {
                $this->flash->error('Contact was not found');
            }
        } else {
            $this->flash->error('Invalid contact ID');
        }
        $this->dispatcher->forward([
            'controller' => 'contacts',
            'action' => 'search'
        ]);
    }

}