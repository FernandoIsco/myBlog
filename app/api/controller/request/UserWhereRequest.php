<?php

/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/4/30
 * Time: 16:55
 */
namespace app\api\controller\request;


use app\api\controller\base\BaseWhereRequest;

class UserWhereRequest extends BaseWhereRequest
{
    public $id;

    public $name;

    public function __construct()
    {
        parent::__construct();
        $key = array(
            'id' => 'id',
            'name' => 'name',
        );
        $this->setOption('key', $key);
    }

}