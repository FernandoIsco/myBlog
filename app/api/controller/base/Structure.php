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
    /**
     * @var string 协议名称，固定字段
     */
    public $namespace;

    /**
     * @var string 会话session，固定字段
     */
    public $session;

    /**
     * @var string 请求毫秒级时间，固定字段
     */
    public $lastTimestamp;

    /**
     * @var string 签名，固定字段
     */
    public $sign;

    /**
     * @var string 身份，固定字段
     */
    public $identity;

    /**
     * @var string 来源，预留字段
     */
    public $source;

    /**
     * @var null 请求体，固定字段
     */
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

        // 参数值要求
        $filter = array(
            'namespace' => [
                'rule' => 'require',
            ],
            'sign' => [
                'rule' => ['require', 'length:32'],
                'msg' => [
                    'length' => STATUS_PARAMETERS_INCORRECT
                ]
            ],
            'identity' => [
                'rule' => ['require']
            ],
        );
        $this->setOption('filter', $filter);
    }
}