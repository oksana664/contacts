<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Validators\Contacts\Create as CreateValidator;

class ContactsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

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

                if ($contact->delete()) {
                    $this->flash->success('Contact was deleted successfully');
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