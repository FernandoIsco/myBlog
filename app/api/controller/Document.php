<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/8
 * Time: 16:33
 */

namespace app\api\controller;


use app\api\controller\request\DocumentRequest;

class Document extends Api
{
    public function __construct()
    {
        $this->myRequest = new DocumentRequest();
        parent::__construct();
    }

    public function info()
    {
        return $this->getModel('document')->getDocuments();
    }

    public function edit()
    {
        $request = $this->getApiRequest();

        $this->getModel('document')->edit(array('content' => $request->value, 'render' => $request->render), array('id' => $request->id));

        return STATUS_SUCCESS;
    }
}