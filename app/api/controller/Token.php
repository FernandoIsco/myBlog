<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/24
 * Time: 16:44
 */

namespace app\api\controller;


use app\api\controller\request\TokenRequest;

class Token extends Api
{
    public function __construct()
    {
        $this->myRequest = new TokenRequest();
        parent::__construct();
    }

    public function setToken()
    {
        $token = $this->getModel('token')->setToken($this->getSession());

        return array('token' => $token);
    }
}