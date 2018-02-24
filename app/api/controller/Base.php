<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 18:27
 */

namespace app\api\controller;


use app\api\controller\traits\Validate;
use Emilia\mvc\Controller;

class Base extends Controller
{
    use Validate;

    /**
     * 输出对象的形式
     *
     * @param object|string|array $param
     * @return bool|\stdClass
     */
    function parseObject($param)
    {
        $type = gettype($param);
        switch ($type) {
            case 'object':
                return $param;
            case 'string':
                return json_decode($param);
            case 'array':
                return $this->array2Object($param);
            default :
                break;
        }

        return false;
    }

    /**
     * 数组转对象
     *
     * @param array $param
     * @return \stdClass
     */
    function array2Object($param)
    {
        $obj = new \stdClass();
        foreach ($param as $key => $value) {
            if (gettype($value) == 'array') {
                $obj->$key = $this->array2Object($value);
            } else {
                $obj->$key = $value;
            }
        }
        return $obj;
    }

    /**
     * 对象转数组
     *
     * @param object $param
     * @return mixed
     */
    function object2Array($param)
    {
        return json_decode(json_encode($param), true);
    }

    /**
     * 获取IP地址
     *
     * @return mixed
     */
    public function getIp()
    {
        return $this->request->getIp();
    }
}