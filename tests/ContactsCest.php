<?php

class ContactsCest
{
    protected $contactId = null;

    public function tryContactSearch(ApiTester $I)
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendGET('/');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function tryContactCreate(ApiTester $I)
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('/contacts/create', [
            'first_name' => 'Codeception Create Test',
            'last_name' => 'Test',
            'email' => 'test@fake.com',
            'birthdate' => '1984-03-18',
            'phone' => '1554545'
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":true');
        $this->contactId = $I->grabDataFromResponseByJsonPath('$.id')[0];
    }

    public function tryContactCreateWithoutFirstName(ApiTester $I)
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('/contacts/create', [
            'first_name' => '',
            'last_name' => 'Test',
            'email' => 'test@fake.com',
            'birthdate' => '1984-03-18',
            'phone' => '1554545'
        ]);
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":false');
        $I->seeResponseContains('The field \'First Name\' is required');
    }

    public function tryContactCreateWithoutLastName(ApiTester $I)
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('/contacts/create', [
            'first_name' => 'not empty',
            'last_name' => '',
            'email' => 'test@fake.com',
            'birthdate' => '1984-03-18',
            'phone' => '1554545'
        ]);
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":false');
        $I->seeResponseContains('The field \'Last Name\' is required');
    }

    public function tryContactCreateInvalidEmail(ApiTester $I)
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('/contacts/create', [
            'first_name' => 'not empty',
            'last_name' => 'not empty',
            'email' => 'testfake.com',
            'birthdate' => '1984-03-18',
            'phone' => '1554545'
        ]);
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":false');
        $I->seeResponseContains('The e-mail is not valid');
    }

    public function tryContactCreateEmpty(ApiTester $I)
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('/contacts/create', [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'birthdate' => '',
            'phone' => ''
        ]);
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":false');
        $I->seeResponseContains('The field \'Last Name\' is required');
        $I->seeResponseContains('The field \'First Name\' is required');
    }

    public function tryContactSave(ApiTester $I)
    {
        if (empty($this->contactId)) return false;
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');

        $I->sendPOST('/contacts/save', [
            'id' => $this->contactId,
            'first_name' => 'First Name Updated',
            'last_name' => 'Last Name Updated',
            'email' => 'updated-test@fake.com',
            'birthdate' => '1984-03-18',
            'phone' => '1524-fake'
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":true');
        $I->seeResponseContains('"id":"'.$this->contactId.'"');
    }

    public function tryContactSaveWithoutId(ApiTester $I)
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('/contacts/save', [
            'first_name' => 'not empty',
            'last_name' => 'Test',
            'email' => 'test@fake.com',
            'birthdate' => '1984-03-18',
            'phone' => '1554545'
        ]);
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":false');
        $I->seeResponseContains('The field \'id\' is required');
    }

    public function tryContactSaveWithoutFirstName(ApiTester $I)
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('/contacts/save', [
            'first_name' => '',
            'last_name' => 'Test',
            'email' => 'test@fake.com',
            'birthdate' => '1984-03-18',
            'phone' => '1554545'
        ]);
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":false');
        $I->seeResponseContains('The field \'First Name\' is required');
    }

    public function tryContactSaveWithoutLastName(ApiTester $I)
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('/contacts/save', [
            'first_name' => 'not empty',
            'last_name' => '',
            'email' => 'test@fake.com',
            'birthdate' => '1984-03-18',
            'phone' => '1554545'
        ]);
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":false');
        $I->seeResponseContains('The field \'Last Name\' is required');
    }

    public function tryContactSaveInvalidEmail(ApiTester $I)
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('/contacts/save', [
            'first_name' => 'not empty',
            'last_name' => 'not empty',
            'email' => 'testfake.com',
            'birthdate' => '1984-03-18',
            'phone' => '1554545'
        ]);
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":false');
        $I->seeResponseContains('The e-mail is not valid');
    }

    public function tryContactSaveEmpty(ApiTester $I)
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('/contacts/save', [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'birthdate' => '',
            'phone' => ''
        ]);
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":false');
        $I->seeResponseContains('The field \'Last Name\' is required');
        $I->seeResponseContains('The field \'First Name\' is required');
    }

    public function tryContactDelete(ApiTester $I)
    {
        if (empty($this->contactId)) return false;
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendGET("/contacts/delete/{$this->contactId}/");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":true');
    }

    public function tryContactDeleteWithoutId(ApiTester $I)
    {
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendGET("/contacts/delete/");
        $I->seeResponseCodeIs(500);
        $I->seeResponseIsJson();
        $I->seeResponseContains('"success":false');
        $I->seeResponseContains('"Invalid contact ID"');
    }
}