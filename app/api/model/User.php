<?php

namespace app\api\model;



use Emilia\mvc\Model;

class User extends Model
{
    public function add($data)
    {
        $this->insert($data);

        return $this->getLastId();
    }

    public function getRow($where, $field = array())
    {
        $list = $this->getList($where, $field, 1);

        return !empty($list) ? $list[0] : array();
    }

    public function getList($where = array(), $field = array(), $limit = 0, $order = array())
    {
        return $this->order($order)->limit($limit)->select($where, $field);
    }

    public function edit($param, $where)
    {
        return $this->update($param, $where);
    }

    public function login($name, $password)
    {
        $userInfo = $this->getRow(array('name' => $name));

        if (!empty($userInfo) && $userInfo['admin'] && $userInfo['password'] == $password) {
            return $userInfo;
        }

        return false;
    }
}