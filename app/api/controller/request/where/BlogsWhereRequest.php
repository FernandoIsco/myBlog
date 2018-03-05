<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/3
 * Time: 11:10
 */

namespace app\api\controller\request\where;


use app\api\controller\base\BaseWhereRequest;

class BlogsWhereRequest extends BaseWhereRequest
{
    public $user_id;

    public $name;

    public function __construct()
    {
        $key = array(
            'user_id' => 'uid',
            'name' => 'username'
        );
        $this->setOption('key', $key);

        $joinKey = array(
            'name' => 'u.name',
        );
        $this->setOption('joinKey', $joinKey);
    }
}