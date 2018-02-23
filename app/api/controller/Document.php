<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/8
 * Time: 16:33
 */

namespace app\api\controller;


class Document extends Api
{
    public function info()
    {
        return $this->getDocumentModel()->getList();
    }

    public function add()
    {
        $this->getDocumentModel()->add();
    }
}