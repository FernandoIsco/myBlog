<?php
/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/4/30
 * Time: 21:12
 */

namespace app\api\controller\base;


abstract class Item
{
    private $_option = array();

    /**
     * @param string $key
     * @param mixed $value
     * @param string $default
     *
     * setOption 预定义的有：
     * (1) key      字段映射关系
     *              用于使请求字段名称简短
     *              使用无限制
     *
     * (2) filter   过滤验证
     *              用于自动校验请求参数
     *              使用无限制
     *
     * (3) function 函数处理
     *              用于自动处理某些请求参数，例如密码md5
     *              使用无限制
     *
     * (4) methods  请求方式与请求方法的对应关系
     *              规定后端对每个对象实现增删查改四种方法
     *              在baseRequest设置有用
     *
     * (5) joinKey  连表字段映射关系
     *              用于解决模型逻辑层链表查询重复字段导致的错误
     *              在baseWhereRequest设置有用
     *
     */
    public function setOption($key, $value, $default = '')
    {
        if(isset($this->_option[$key])) {
            if (empty($value)) {
                $this->_option[$key] = $default;
            } else {
                if(is_array($value)) {
                    $this->_option[$key] = array_merge($this->_option[$key], $value);
                } else {
                    $this->_option[$key] = $value;
                }
            }
        } else {
            $this->_option[$key] = $default ? $default : $value;
        }
    }

    public function getOption($key = '', $default = '')
    {
        if(!$key) {
            return $this->_option;
        }
        return isset($this->_option[$key]) ? $this->_option[$key] : $default;
    }

    public function __call($method, $args)
    {
        if (isset($this->$method)) {
            $func = $this->$method;
            $func($args);
        }
    }
}