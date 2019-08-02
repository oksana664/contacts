<?php

namespace Validators\Contacts;

use Exception;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Callback as CallbackValidator;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\PresenceOf;

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
            new CallbackValidator(
                [
                    'message' => 'The field "birthday" is invalid. Should have the format Y-M-D.',
                    'callback' => function ($data) {
                        if (empty($data['birthdate'])) {
                            return true;
                        }
                        if (preg_match('/^[0-9]{4}[-\/](0[1-9]|1[12])[-\/](0[1-9]|[12][0-9]|3[01])$/', $data['birthdate'], $matches) && $matches[0] == $data['birthdate']) {
                            return true;
                        }
                        return false;
                    }
                ]
            )
        );

        $this->add(
            'email',
            new EmailValidator(['allowEmpty' => true,
                'message' => 'The e-mail is not valid',])
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