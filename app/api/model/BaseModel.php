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

    /**
     * 新增记录
     *
     * @param array $data
     * @return bool
     */
    final function add($data)
    {
        if (empty($data)) {
            return false;
        }

        $data = array_merge($data, array('create_at' => time()));

        $this->insert($data);

        return $this->getLastId();
    }

    /**
     * 编辑记录
     *
     * @param array $data
     * @param array $where
     * @return bool
     */
    final function edit($data, $where)
    {
        if (empty($where)) {
            return false;
        }

        $data = array_merge($data, array('last_modify' => time()));

        return $this->update($data, $where);
    }

    /**
     * 删除记录
     *
     * @param array $where
     * @return bool
     */
    final function remove($where)
    {
        if (empty($where)) {
            return false;
        }

        return $this->delete($where);
    }

    /**
     * 获取一条记录
     *
     * @param array $where
     * @param array $field
     * @param array $order
     * @return array
     */
    final function getRow($where, $field = array(), $order = array())
    {
        $list = $this->getList($where, $field, 1, $order);

        return !empty($list) ? $list[0] : array();
    }

    /**
     * 获取多条记录
     *
     * @param array $where
     * @param array $field
     * @param int   $limit
     * @param array $order
     * @return mixed
     */
    final function getList($where = array(), $field = array(), $limit = 0, $order = array())
    {
        return $this->order($order)->limit($limit)->select($where, $field);
    }
}