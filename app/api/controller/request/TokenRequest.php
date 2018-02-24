<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/24
 * Time: 17:36
 */

namespace app\api\controller\request;


use app\api\controller\base\BaseRequest;

class TokenRequest extends BaseRequest
{
    public $token;

    public function __construct()
    {
        parent::__construct();
        $methods = array(
            'post' => 'setToken'
        );
        $this->setOption('methods', $methods);
    }
}