<?php
/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/4/30
 * Time: 10:41
 */

namespace app\api\controller\base;

class BaseRequest extends Item
{
    public $id = '';

    public $action = '';

    public $where;

    public $table;

    public function __construct()
    {
        $key = array(
            'action' => 'a',
            'table' => 'ta',
            'where' => 'w',
        );
        $this->setOption('key', $key);

        // 请求方式对应请求方法
        $methods = array(
            'get' => 'info',
            'post' => 'add',
            'put' => 'edit',
            'delete' => 'remove'
        );
        $this->setOption('methods', $methods);
    }
}