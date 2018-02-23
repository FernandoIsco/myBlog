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
    public $searchKey;

    public function __construct()
    {
        $key = array(
            'searchKey' => 'sk',
        );
        $this->setOption('key', $key);
    }
}