<?php

namespace app\api\controller;


use Emilia\Application;
use Emilia\config\Config;
use Emilia\log\Logger;

class Index extends Api
{
    public function index($query = array())
    {
        $cors = Config::getConfig('cors');

        $http_origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        $origin = '';
        if (in_array('*', $cors['origins']) || (in_array($http_origin, $cors['origins']))) {
            $origin = $http_origin ?: '*';
        }

        header('Access-Control-Allow-Origin: '  . $origin);
        header('Access-Control-Allow-Methods: ' . implode(', ', $cors['methods']));
        header('Access-Control-Allow-Headers: ' . implode(', ', $cors['headers']));

        $myQuery = $query;
        if (!$query) {
            switch ($this->getMethod()) {
                case 'get':
                    $myQuery = $this->request->fromGet();
                    $myQuery = json_decode($myQuery['query'], true); // 或者直接使用$_GET，不做过滤
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

        // 身份校验
        $validateFlag = $this->validateToken($myQuery);
        if (!$validateFlag) {
            $this->setApiResponse(STATUS_MD5);
        }

        try {
            // 将数据转化为apiRequest对象
            $request = $this->setApiRequest($myQuery)->getApiStructure();

            // 实例对应的类
            $obj = null;
            $action = '';
            $className = isset($request->namespace) ? $this->getNamespace() . $request->namespace : '';
            if (class_exists($className)) {
                $obj = new $className();
                $obj->setApiRequest($myQuery);
            } else {
                $this->setApiResponse(STATUS_NO_PROTOCOL);
            }

            // 判断类是否有该方法，并执行返回结果
            if (is_object($obj) && !($action = $obj->getRequestAction())) {
                $this->setApiResponse(STATUS_ERROR_REQUEST_METHOD);
            }

            $res = STATUS_NO_REQUEST_ACTION;
            if (in_array($action, get_class_methods($obj))) {
                $this->request->sessionStart($request->session);
                $res = $obj->$action();
            }
        } catch (\Exception $exception) {
            Logger::record($exception);
            $res = STATUS_SERVICE_ERROR;
        }

        $this->setApiResponse($res);
    }
}