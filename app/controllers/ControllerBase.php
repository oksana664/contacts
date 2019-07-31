<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    public function afterExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        if ($this->request->isAjax() == true) {
            $this->view->disable();

            $this->response->setContentType('application/json', 'UTF-8');
            $data = $this->view->getParamsToView();

            if (is_array($data)) {
                $data['message'] = $this->flash->getMessages(true);
                if (isset($data['message']['error'])) {
                    $this->response->setRawHeader('HTTP/1.1 500 Internal Server Error');
                    $data['success'] = false;
                } else {
                    $data['success'] = true;
                }
            }

            if (isset($data['page']->items)) {
                if (is_object($data['page']->items) && method_exists($data['page']->items, 'toArray')) {
                    $data['page']->items = $data['page']->items->toArray();
                }
                if (!is_array($data['page']->items)) $data['page']->items = [];
                $data['page']->items = array_map(function ($value) {
                    if (is_object($value) && method_exists($value, 'toArray')) {
                        return $value->toArray();
                    }
                    return $value;
                }, $data['page']->items);
            }
            $this->response->setContent(json_encode($data));
        }
        return $this->response->send();
    }

}
