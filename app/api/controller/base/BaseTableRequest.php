<?php
/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/5/1
 * Time: 12:16
 */

namespace app\api\controller\base;


class BaseTableRequest extends Item
{
    /**
     * @var int 默认分页查询第一页
     */
    public $page = 1;

    /**
     * @var int 默认不分页查询
     */
    public $limit = 0;

    /**
     * @var array 列表排序 TODO
     */
    public $order;

    public function __construct()
    {
        // table条件字段映射关系
        $key = array(
            'page' => 'pa',
            'limit' => 'li',
            'order' => 'or'
        );
        $this->setOption('key', $key);

        // 参数默认值
        /*$default = array(
            'page' => 1,
            'limit' => 0
        );
        $this->setOption('default', $default);*/

        // 字段校验
        $filter = array(
            'page' => array(
                'rule' => 'integer',
                'msg' => array(
                    'integer' => STATUS_PARAMETERS_INCORRECT
                )
            ),
            'limit' => array(
                'rule' => 'integer',
                'msg' => array(
                    'integer' => STATUS_PARAMETERS_INCORRECT
                )
            )
        );
        $this->setOption('filter', $filter);
    }
}