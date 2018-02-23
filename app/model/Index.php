<?php

namespace app\model;




use Emilia\mvc\Model;

class Index extends Model
{
    public function getList()
    {
        return $this->select();
    }
}