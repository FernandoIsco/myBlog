<?php

namespace app\api\controller;




use Emilia\config\Config;

class Index extends Api
{
    public function index($query = array())
    {
        // TODO 源应该以数组形式配置
        $cors = Config::getConfig('CORS');
        if ($cors) {
            foreach ($cors as $key => $item) {
                header($key . ': ' . $item);
            }
        }

        $myQuery = $query;
        if (!$query) {
            switch ($this->getMethod()) {
                case 'get':
                    $myQuery = $this->request->fromGet();
                    break;
                case 'post':
                    $postQuery = $this->request->fromPost();
                    $ajaxQuery = $this->request->fromAjax();
                    $myQuery = array_merge((array)$postQuery, (array)$ajaxQuery);
                    break;
                case 'put':
                    $myQuery = $this->request->fromPut();
                    break;
                case 'delete':
                    $myQuery = $this->request->fromDelete();
                    break;
            }
        }

        if (!$myQuery) {
            $this->setApiResponse(STATUS_NODATA);
        }

        $request = $this->setApiRequest($myQuery)->getApiStructure();

        $obj = null;
        $action = '';
        $className = isset($request->namespace) ? $this->getNamespace() . $request->namespace : '';

        if (class_exists($className)) {
            $obj = new $className();
            $obj->setApiRequest($myQuery);
        } else {
            $this->setApiResponse(STATUS_NO_PROTOCOL);
        }

        if (is_object($obj) && !($action = $obj->getRequestAction())) {
            $this->setApiResponse(STATUS_ERROR_REQUEST_METHOD);
        }

        $res = STATUS_NO_REQUEST_ACTION;
        if (in_array($action, get_class_methods($obj))) {
            $res = $obj->setApiRequest($myQuery)->$action();
        }

        $this->setApiResponse($res);
    }
}