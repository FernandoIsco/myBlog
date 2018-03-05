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
    /**
     * @var string 初始预留定义变量
     */
    public $id = '';

    /**
     * @var string 初始预留定义变量
     */
    public $action = '';

    /**
     * @var BaseWhereRequest
     */
    public $where;

    /**
     * @var BaseTableRequest
     */
    public $table;

    public function __construct()
    {
        // update, insert, delete数据字段, 和table, where条件字段映射关系
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

        // 设置连表字段映射关系
        $joinKey = array();
        $this->setOption('joinKey', $joinKey);

        // 过滤验证
        $filter = array(
            'id' => [
                'rule' => ['integer'],
                'msg' => [
                    'integer' => STATUS_PARAMETERS_INCORRECT,
                ],
            ]
        );
        $this->setOption('filter', $filter);

        // 函数处理
        $function = array();
        $this->setOption('function', $function);

        $this->table = new BaseTableRequest();
        $this->where = new BaseWhereRequest();
    }

    /**
     * 自定义设置查询字段
     * 默认查询所有字段。
     *
     * @return array
     */
    public function field()
    {
        return array('*');
    }

    /**
     * 自定义设置链表信息
     * table字段必存在和不为空。
     *
     * @return array
     */
    public function join()
    {
        return array('type' => 'left', 'table' => '', 'condition' => '');
    }
}