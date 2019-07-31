<?php

namespace Validators\Contacts;

use Phalcon\Validation\Validator\PresenceOf;

class Save extends Create
{
    public function initialize()
    {
        parent::initialize();
        $this->add(
            'id',
            new PresenceOf(['message' => "The field 'id' is required"])
        );

    }
}