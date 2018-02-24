<?php

namespace app\api\controller;


use Emilia\config\Config;

class Index extends Api
{
    public function index($query = array())
    {
        $cors = Config::getConfig('cors');

        $origin = '';
        if (in_array('*', $cors['origins']) || in_array($_SERVER['HTTP_ORIGIN'], $cors['origins'])) {
            $origin = $_SERVER['HTTP_ORIGIN'];
        }

        header('Access-Control-Allow-Origin: '  . $origin);
        header('Access-Control-Allow-Methods: ' . implode(', ', $cors['methods']));
        header('Access-Control-Allow-Headers: ' . implode(', ', $cors['headers']));

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

                    // ps: post,put,delete方式请求:
                    // 1. content_type以form-urlencoded形式提交
                    //    1)post 以$_POST接收参数
                    //    2)put,delete  parse_str($this->input, $parameters);
                    // 2. content_type以application/json形式提交
                    //    1)post,put,delete json_decode($this->input, true);
                    break;
                case 'options':
                    $this->setApiResponse(STATUS_SUCCESS);
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
        try {
            if (in_array($action, get_class_methods($obj))) {
                $res = $obj->$action();
            }
        } catch (\Exception $exception) {
            $res = array(
                'status' => $exception->getCode(),
                'description' => $exception->getMessage()
            );
        }

        $this->setApiResponse($res);
    }
}