<?php

/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/4/30
 * Time: 16:55
 */
namespace app\api\controller\request;

use app\api\controller\base\BaseRequest;

class BlogsRequest extends BaseRequest
{
    public function field()
    {
        return array('b.*', 'u.name' => 'username');
    }

    public function join()
    {
        return array(
            'type' => 'left',
            'table' => array('user' => 'u'),
            'condition' => 'u.id = b.user_id',
        );
    }
}