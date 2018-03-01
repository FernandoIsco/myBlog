<?php

namespace app\api\controller;


use app\api\controller\request\UserLoginRequest;

class UserLogin extends Common
{
    public function __construct()
    {
        $this->myRequest = new UserLoginRequest();
        parent::__construct();
    }

    public function login()
    {
        $request = $this->getApiRequest();

        $userInfo = $this->getModel('user')->login($request->name, $request->password);
        if (!$userInfo) return STATUS_USER_NOT_EXISTS;

        $status = $this->getModel('token')->updateUser($userInfo, $this->getSession());
        if (!$status) return STATUS_SESSION_TIMEOUT;

        return array('userInfo' => $userInfo);
    }

    public function logout()
    {
        $status = $this->getModel('token')->removeUser($this->getSession());

        return $status ? STATUS_SUCCESS : STATUS_UNKNOWN;
    }
}