<?php

namespace app\api\controller;


use app\api\controller\request\UserRequest;
use app\api\controller\request\UserWhereRequest;

class User extends Common
{
    public function __construct()
    {
        $this->myRequest = new UserRequest();
        $this->myWhereRequest = new UserWhereRequest();
        parent::__construct();
    }

    public function info()
    {
        $whereRequest = $this->getApiWhereRequest();

        $columns = array('id', 'name', 'comments', 'favorable', 'image');

        if ($whereRequest->id) {
            $info = $this->getModel('user')->getRow(array('id' => $whereRequest->id), $columns);

            return array('userInfo' => $info);
        } else {
            $list = $this->getModel('user')->getList(array('admin|neq' => '1'), $columns);

            return array('userList' => $list);
        }
    }

    public function add()
    {
        $request = $this->getApiRequest();
        $response = $this->getApiResponse();

        $info = $this->getModel('user')->getRow(array('name' => $request->name), array('id'));

        if ($info) {
            return STATUS_USER_EXISTS;
        }

        $param = array(
            'name' => $request->name,
            'password' => $request->password,
            'ip' => $request->ip,
        );

        try {
            $response->id = $this->getModel('user')->add($param);
        } catch (\Exception $e) {
            $response->status = $e->getCode();
            $response->description = $e->getMessage();
        }

        return $response;
    }

    public function edit()
    {
        $request = $this->getApiRequest();
        $response = $this->getApiResponse();

        $param = array();
        $request->name && $param['name'] = $request->name;
        $request->password && $param['password'] = $request->password;

        $where = array(
            'id' => $request->id
        );

        try {
            if ($param) {
                $this->getModel('user')->edit($param, $where);
            }

            $response->status =  STATUS_SUCCESS;

        } catch (\Exception $e) {
            $response->status = $e->getCode();
            $response->description = $e->getMessage();
        }

        return $response;
    }

    public function remove()
    {
        return STATUS_SUCCESS;
    }
}