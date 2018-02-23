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
    public $page;

    public $limit;

    public $orderBy;

    public $orderType;

    public function __construct()
    {
        $key = array(
            'page' => 'pa',
            'limit' => 'li',
            'orderBy' => 'ob',
            'orderType' => 'ot',
        );
        $this->setOption('key', $key);
    }
}