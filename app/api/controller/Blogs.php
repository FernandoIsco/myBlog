<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/2
 * Time: 16:40
 */

namespace app\api\controller;


use app\api\controller\request\BlogsRequest;
use app\api\controller\request\where\BlogsWhereRequest;

class Blogs extends Common
{
    public function __construct()
    {
        $this->myRequest = new BlogsRequest();
        $this->myWhereRequest = new BlogsWhereRequest();
        parent::__construct();
    }

    public function info()
    {
        return $this->getModel('blogs')->getApiPage();
    }
}