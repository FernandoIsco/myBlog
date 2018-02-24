<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/24
 * Time: 17:57
 */

namespace app\api\model;


use Emilia\http\Request;
use Emilia\mvc\Model;

class BaseModel extends Model
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct();
    }

    final function add($data)
    {
        $this->insert($data);

        return $this->getLastId();
    }

    final function getRow($where, $field = array())
    {
        $list = $this->getList($where, $field, 1);

        return !empty($list) ? $list[0] : array();
    }

    final function getList($where = array(), $field = array(), $limit = 0, $order = array())
    {
        return $this->order($order)->limit($limit)->select($where, $field);
    }
}