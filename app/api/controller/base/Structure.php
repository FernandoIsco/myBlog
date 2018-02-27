<?php
/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/5/6
 * Time: 09:58
 */

namespace app\api\controller\base;


class Structure extends Item
{
    public $namespace;

    public $session;

    public $lastTimestamp;

    public $sign;

    public $identity;

    public $source;

    public $method;

    public $query = null;

    public function __construct()
    {
        // 参数名称对应关系
        $key = array(
            'namespace' => 'n',
            'session' => 's',
            'lastTimestamp' => 't',
            'sign' => 'si',
            'identity' => 'i',
            'source' => 'sr',
            'query' => 'q',
        );
        $this->setOption('key', $key);

        // 参数默认值
        $default = array(
            'method' => 'get',
        );
        $this->setOption('default', $default);

        // 参数值要求
        $filter = array(
            'namespace' => [
                'rule' => 'require',
                'msg' => [
                    'require' => STATUS_PARAMETERS_INCOMPLETE
                ]
            ]
        );
        $this->setOption('filter', $filter);
    }
}