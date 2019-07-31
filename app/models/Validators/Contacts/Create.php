<?php

namespace Validators\Contacts;

use Exception;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex as RegexValidator;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Create extends Validation
{
    public function initialize()
    {
        $required = [];
        $required['first_name'] = 'First Name';
        $required['last_name'] = 'Last Name';

        foreach ($required as $field => $label) {
            $this->add(
                $field,
                new PresenceOf(['message' => "The field '$label' is required"])
            );
        }

        $this->add(
            'birthday',

            new RegexValidator([
                'allowEmpty' => true,
                'pattern' => '/^[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/',
                'message' => "The field 'birthday' is invalid. Should have the format YYYY-MM-DD"
            ])
        );

        $this->add(
            'email',
            new EmailValidator([
                "message" => [
                    "email" => "The e-mail is not valid",
                ],
            ])
        );

        foreach (array_keys($_REQUEST) as $field) {
            $this->setFilters($field, ['trim']);
        }
    }

    public function validate($data = null, $entity = null)
    {
        $messages = parent::validate($data, $entity);
        if (count($messages)) {
            $prev = null;
            foreach ($messages as $message) {
                $prev = new Exception($message, 0, $prev);
            }
            throw $prev;
        }
    }
}