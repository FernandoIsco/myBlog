<?php

/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/4/30
 * Time: 16:55
 */
namespace app\api\controller\request;

use app\api\controller\base\BaseRequest;

class UserRequest extends BaseRequest
{
    public $name;

    public $password;

    public $admin;

    public $ip;

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
                        'between' => STATUS_PARAMETERS_INCORRECT
                    ]
                ]
            ],
            'put' => [
                'id' => [
                    'rule' => ['require'],
                    'msg' => [
                        'require' => STATUS_PARAMETERS_INCOMPLETE,
                    ],
                ],
            ],
        );
        $this->setOption('filter', $filter);

        // 默认值
        $default = array(
            'ip' => 'getIp',
        );
        $this->setOption('default', $default);

        // 函数处理
        $function = array(
            'password' => ['md5'],
        );
        $this->setOption('function', $function);
    }
}