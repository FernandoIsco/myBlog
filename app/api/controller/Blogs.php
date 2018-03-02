<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/2
 * Time: 16:40
 */

namespace app\api\controller;


class Blogs extends Common
{
    public function info()
    {
        return $this->getModel('blogs')->getPage();
    }
}