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

        $userInfo = $this->getUserModel()->login($request->name, $request->password);

        echo '<pre>';
        print_r($userInfo);
        die;
    }

    public function logout()
    {
        $request = $this->getApiRequest();
        $response = $this->getApiResponse();

        $info = $this->getUserModel()->getRow(array('name' => $request->name), array('id'));

        if ($info) {
            return STATUS_USER_EXISTS;
        }

        $param = array(
            'name' => $request->name,
            'password' => $request->password,
            'ip' => $request->ip,
        );

        try {
            $response->id = $this->getUserModel()->add($param);
        } catch (\Exception $e) {
            $response->status = $e->getCode();
            $response->description = $e->getMessage();
        }

        return $response;
    }
}