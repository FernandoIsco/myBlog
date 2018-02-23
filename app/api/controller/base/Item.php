<?php
/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/4/30
 * Time: 21:12
 */

namespace app\api\controller\base;


class Item
{
    private $_option = array();

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
}