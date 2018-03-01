<?php

namespace app\api\controller\traits;

trait Validate
{
    public function check($value, $rule, $msg_arr = [])
    {
        $param = array();
        if (strpos($rule, ':')) {
            list($rule, $param) = explode(':', $rule, 2);

            if (strpos($param, ',')) {
                $param = explode(',', $param);
            }
        }

        if ($rule != 'require' && !$value) {
            return true;
        }

        if (method_exists($this, $rule)) {
            $res = $this->$rule($value, $param);
        } else {
            $res = $this->checkItem($value, $rule);
        }

        if (!$res) {
            return key_exists($rule, $msg_arr) ? $msg_arr["$rule"] : '';
        }
        return $res;
    }

    public function gt($value, $param = 0)
    {
        return bcsub($value, $param) > 0;
    }

    public function egt($value, $param = 0)
    {
        return bcsub($value, $param) >= 0;
    }

    public function lt($value, $param = 0)
    {
        return bcsub($value, $param) < 0;
    }

    public function elt($value, $param = 0)
    {
        return bcsub($value, $param) <= 0;
    }

    public function eq($value, $param)
    {
        return $value == $param;
    }

    public function length($value, $param)
    {
        if (is_array($value)) {
            $count = count($value);
        } else {
            $count = strlen((string) $value);
        }

        return $count == $param;
    }

    public function max($value, $param)
    {
        if (is_array($value)) {
            $count = count($value);
        } else {
            $count = strlen((string) $value);
        }

        return $count <= $param;
    }

    public function min($value, $param)
    {
        if (is_array($value)) {
            $count = count($value);
        } else {
            $count = strlen((string) $value);
        }

        return $count >= $param;
    }

    public function in($value, $param = array())
    {
        return in_array($value, (array)$param);
    }

    public function notIn($value, $param = array())
    {
        return !in_array($value, (array)$param);
    }

    public function between($value, $param)
    {
        if (is_string($param)) {
            $param = explode(',', $param, 2);
        }

        if (is_array($value)) {
            $count = count($value);
        } else {
            $count = strlen((string) $value);
        }

        list($min, $max) = $param;
        return $count >= $min && $count <= $max;
    }

    public function notBetween($value, $param)
    {
        if (is_string($param)) {
            $param = explode(',', $param, 2);
        }

        if (is_array($value)) {
            $count = count($value);
        } else {
            $count = strlen((string) $value);
        }

        list($min, $max) = $param;
        return $count < $min || $count > $max;
    }

    protected function checkItem($value, $rule)
    {
        if ((is_array($value) || is_object($value)) && in_array($rule, array('accepted', 'date', 'alpha', 'alphaNum', 'alphaDash', 'chs', 'chsAlpha', 'chsAlphaNum', 'chsDash', 'activeUrl', 'float', 'number', 'integer', 'email', 'boolean'))) {
            return false;
        }

        switch ($rule) {
            case 'require':
                // 必须
                $result = !empty($value) || '0' == $value;
                break;
            case 'accepted':
                // 接受
                $result = in_array($value, ['1', 'on', 'yes']);
                break;
            case 'date':
                // 是否是一个有效日期
                $result = false !== strtotime($value);
                break;
            case 'alpha':
                // 只允许字母
                $result = $this->regex($value, '/^[A-Za-z]+$/');
                break;
            case 'alphaNum':
                // 只允许字母和数字
                $result = $this->regex($value, '/^[A-Za-z0-9]+$/');
                break;
            case 'alphaDash':
                // 只允许字母、数字和下划线 破折号
                $result = $this->regex($value, '/^[A-Za-z0-9\-\_]+$/');
                break;
            case 'chs':
                // 只允许汉字
                $result = $this->regex($value, '/^[\x{4e00}-\x{9fa5}]+$/u');
                break;
            case 'chsAlpha':
                // 只允许汉字、字母
                $result = $this->regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z]+$/u');
                break;
            case 'chsAlphaNum':
                // 只允许汉字、字母和数字
                $result = $this->regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u');
                break;
            case 'chsDash':
                // 只允许汉字、字母、数字和下划线_及破折号-
                $result = $this->regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\_\-]+$/u');
                break;
            case 'activeUrl':
                // 是否为有效的网址
                $result = checkdnsrr($value);
                break;
            case 'float':
                // 是否为float
                $result = $this->filter($value, FILTER_VALIDATE_FLOAT);
                break;
            case 'number':
                $result = is_numeric($value);
                break;
            case 'integer':
                // 是否为整型
                $result = $this->filter($value, FILTER_VALIDATE_INT);
                break;
            case 'email':
                // 是否为邮箱地址
                $result = $this->filter($value, FILTER_VALIDATE_EMAIL);
                break;
            case 'boolean':
                // 是否为布尔值
                $result = in_array($value, [true, false, 0, 1, '0', '1'], true);
                break;
            case 'array':
                // 是否为数组
                $result = is_array($value);
                break;
            default:
                $result = true;  //当不存在任何的匹配规则，直接返回true
        }
        return $result;
    }

    /**
     * 正则匹配
     * @param string|int $value 字段值
     * @param string $pattern 验证规则
     * @return bool
     */
    protected function regex($value, $pattern)
    {
        if (0 !== strpos($pattern, '/') && !preg_match('/\/[imsU]{0,4}$/', $pattern)) {
            $pattern = '/^' . $pattern . '$/';
        }

        return 1 === preg_match($pattern, $value);
    }

    /**
     * 使用filter_var方式验证
     * @access protected
     * @param mixed $value  字段值
     * @param mixed $rule  验证规则
     * @return bool
     */
    protected function filter($value, $rule)
    {
        if (is_string($rule) && strpos($rule, ',')) {
            list($rule, $param) = explode(',', $rule);
        } elseif (is_array($rule)) {
            $param = isset($rule[1]) ? $rule[1] : null;
        } else {
            $param = null;
        }
        return false !== filter_var($value, is_int($rule) ? $rule : filter_id($rule), $param);
    }

    /**
     * 验证http请求
     * @param $method
     * @return bool
     */
    public function inHttpMethod($method)
    {
        if(in_array($method, array('get', 'post', 'put', 'patch', 'delete'))) {
            return true;
        }
        return false;
    }
}