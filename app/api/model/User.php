<?php

namespace app\api\model;




class User extends BaseModel
{
    public function login($name, $password)
    {
        $userInfo = $this->getRow(array('name' => $name));

        if (!empty($userInfo) && $userInfo->password == $password) {
            return $userInfo;
        }

        return false;
    }
}