<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/6/29
 * Time: 21:48
 */

namespace Emilia\route;


class Token
{
    private $token;

    public function setToken($code, $value = null, $line = null)
    {
        if (is_array($code) )
        {
            list($value, $code, $line) = array_pad($code, 3, null);
        }

        $this->token = [
            'code' => $code,
            'value' => $value,
            'line' => $line,
        ];
    }
    
    public function getCode()
    {
        return $this->token['code'];
    }

    public function getValue()
    {
        return $this->token['value'];
    }

    public function isFunc()
    {
        return $this->getValue() == T_FUNCTION;
    }

    public function isWhitespace()
    {
        return $this->getValue() == T_WHITESPACE;
    }

    public function isVariable()
    {
        return $this->getValue() == T_VARIABLE;
    }

    public function isString()
    {
        return $this->getValue() == T_STRING;
    }

    public function isBodyStart()
    {
        return $this->getCode() == '{';
    }

    public function isBodyEnd()
    {
        return $this->getCode() == '}';
    }
}