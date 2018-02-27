<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/24
 * Time: 16:57
 */

namespace app\api\model;



class Token extends BaseModel
{
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
            $this->update(array('token' => $token, 'last_modify' => time()), array('token_id' => $tokenInfo->token_id));
        }

        return $token;
    }
}