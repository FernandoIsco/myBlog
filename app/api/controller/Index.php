<?php

namespace app\api\controller;


class Index extends Api
{
    public function index($query = [])
    {
        header("Access-Control-Allow-Origin: *");

        $myQuery = $query;
        if (!$query) {
            switch ($this->getMethod()) {
                case 'get':
                    $myQuery = $this->request->fromGet();
                    break;
                case 'post':
                case 'put':
                case 'delete':
                    $myQuery = $this->request->fromAjax();
                    break;
            }
        }

        if (!$myQuery) {
            $this->setApiResponse(STATUS_NODATA);
        }

        $request = $this->setApiRequest($myQuery)->getApiStructure();

        $className = isset($request->namespace) ? $this->getNamespace() . $request->namespace : '';

        if (class_exists($className)) {
            $obj = new $className();
        } else {
            $this->setApiResponse(STATUS_NO_PROTOCOL);
        }

        if (!($action = $this->getRequestAction())) {
            $this->setApiResponse(STATUS_ERROR_REQUEST_METHOD);
        }

        $res = STATUS_NO_REQUEST_ACTION;
        if (in_array($action, get_class_methods($obj))) {
            $res = $obj->setApiRequest($myQuery)->$action();
        }

        $this->setApiResponse($res);
    }
}