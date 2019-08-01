<?php

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
class CustomFlash extends Phalcon\Flash\Session
{

    public function output($remove = true)
    {
        $types = $this->getMessages(null, $remove);
        if (is_array($types)) {
            foreach ($types as $type => $messages) {
                foreach ($messages as $message) {
                    echo <<<HTML
<div class="text-center {$this->_cssClasses[$type]}">
    $message
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
HTML;
                }
            }
        }
    }
}