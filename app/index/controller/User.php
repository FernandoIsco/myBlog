<?php

namespace app\index\controller;


use app\api\controller\Index;

class User
{
    public function userInfo()
    {
        $data = ['n' => 'User', 'q' => ['id' => 12]];
        $api = new Index();
        $api->index($data);
    }
}