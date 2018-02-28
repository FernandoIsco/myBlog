<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/24
 * Time: 16:57
 */

namespace app\api\model;


use Emilia\config\Config;

class Token extends BaseModel
{
    public function checkLogin($token)
    {
        if (empty($token)) {
            return false;
        }

        $data = array('token' => $token, 'status' => LOGIN_STATUS);

        $tokenInfo = $this->getRow($data, array('user_id'));

        return $tokenInfo ? $tokenInfo->user_id : false;
    }

    /**
     * 创建token
     *
     * @param string $token
     * @return mixed
     */
    public function setToken($token)
    {
        if (empty($token)) {
            $this->request->sessionStart();
            $token = $this->request->session->sessionId();
        }

        $tokenInfo = $this->getRow(array('token' => $token));

        if (empty($tokenInfo)) {
            $this->add(array('token' => $token, 'last_modify' => time(), 'create_at' => time()));
        } else {
            $this->edit(array('token' => $token, 'last_modify' => time()), array('token_id' => $tokenInfo->token_id));
        }

        return $token;
    }

    /**
     * 更新token用户信息
     *
     * @param object $userInfo  用户信息
     * @param string $token     token
     * @return bool
     */
    public function updateUser($userInfo, $token)
    {
        if (empty($token) || empty($userInfo->id)) {
            return false;
        }

        $multi_login = Config::getConfig('multi_login');

        if (!$multi_login) {
            $this->edit(array(
                'status' => LOGOUT_STATUS,
            ), array(
                'user_id' => $userInfo->id
            ));
        }

        $this->edit(array(
            'user_id' => $userInfo->id,
            'status' => LOGIN_STATUS
        ), array(
            'token' => $token
        ));

        return true;
    }

    /**
     * 更新用户登出状态
     *
     * @author lzl
     * @param string $token
     * @return false
     */
    public function removeUser($token)
    {
        return $this->edit(array(
            'status' => LOGOUT_STATUS,
        ), array(
            'token' => $token
        ));
    }
}