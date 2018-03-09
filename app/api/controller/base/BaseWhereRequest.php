<?php
/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/5/1
 * Time: 08:52
 */

namespace app\api\controller\base;


class BaseWhereRequest extends Item
{
    /**
     * @var string 主键，预留字段
     */
    public $id;

    /**
     * @var string 搜索关键词，预留字段
     */
    public $searchKey;

    public function __construct()
    {
        $key = array(
            'searchKey' => 'sk',
        );
        $this->setOption('key', $key);

        $filter = array(
            'searchKey' => array(
                'rule' => 'chsDash',
                'msg' => array(
                    'chsDash' => STATUS_PARAMETERS_INCORRECT
                )
            )
        );
        $this->setOption('filter', $filter);
    }
}