<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/8
 * Time: 16:33
 */

namespace app\api\controller;


use app\api\controller\request\DocumentRequest;

class Document extends Common
{
    public function __construct()
    {
        $this->myRequest = new DocumentRequest();
        parent::__construct();
    }

    public function info()
    {
        $whereRequest = $this->getApiWhereRequest();

        if ($whereRequest->searchKey) {
            $fields = array('id', 'parent_id');
            $where['render|like'] = $whereRequest->searchKey;

            $ids = $this->getModel('document')->getList($where, $fields);
            return array('ids' => $ids);
        }

        $documents = $this->getModel('document')->getDocuments();

        return array('documents' => $documents);
    }

    public function edit()
    {
        $request = $this->getApiRequest();

        if (false == $userId = $this->checkLogin()) return STATUS_USER_NOT_LOGIN;

        $userInfo = $this->getModel('user')->getRow(array('id' => $userId), array('admin'));

        if (!$userInfo->admin) return STATUS_NOT_UPDATE;

        $this->getModel('document')->edit(array('content' => $request->value, 'render' => $request->render), array('id' => $request->id));

        return STATUS_SUCCESS;
    }
}