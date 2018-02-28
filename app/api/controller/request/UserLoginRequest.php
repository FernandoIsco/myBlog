<?php

/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/4/30
 * Time: 16:55
 */
namespace app\api\controller\request;

use app\api\controller\base\BaseRequest;

class UserLoginRequest extends BaseRequest
{
    public $name;

    public $password;

    public function __construct()
    {
        parent::__construct();
        // 过滤验证
        $filter = array(
            'post' => [
                'name' => [
                    'rule' => ['require'],
                    'msg' => [
                        'require' => STATUS_PARAMETERS_INCOMPLETE,
                    ],
                ],
                'password' => [
                    'rule' => ['require', 'between:6,18'],
                    'msg' => [
                        'require' => STATUS_PARAMETERS_INCOMPLETE,
                        'between' => STATUS_USER_NOT_EXISTS
                    ]
                ]
            ]
        );
        $this->setOption('filter', $filter);

        // 函数处理
        $function = array(
            'password' => ['md5'],
        );
        $this->setOption('function', $function);

        // 不同请求方式对应不同的方法
        $methods = array(
            'post' => 'login',
            'delete'  => 'logout'
        );
        $this->setOption('methods', $methods);
    }
}