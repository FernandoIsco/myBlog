<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 11:42
 */

namespace app\api\controller;


use app\api\model\User;
use app\api\model\Document;
use Emilia\mvc\Controller;


class Table extends Controller
{
    protected $userModel;

    protected $documentModel;

    public function getUserModel()
    {
        if (empty($this->userModel)) {
            $this->userModel = new User();
        }

        return $this->userModel;
    }

    public function getDocumentModel()
    {
        if (empty($this->documentModel)) {
            $this->documentModel = new Document();
        }

        return $this->documentModel;
    }
}