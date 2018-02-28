<?php

namespace app\api\controller;


use app\api\controller\request\UserLoginRequest;

class UserLogin extends Api
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

        $status = $this->getModel('token')->updateUser($userInfo, $this->getSession());

        if (!$status) {
            return STATUS_SESSION_TIMEOUT;
        }

        return $userInfo ? array('userInfo' => $userInfo) : STATUS_USER_NOT_EXISTS;
    }

    public function logout()
    {
        $status = $this->getModel('token')->removeUser($this->getSession());

        return $status ? STATUS_SUCCESS : STATUS_UNKNOWN;
    }
}