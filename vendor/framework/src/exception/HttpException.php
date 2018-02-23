<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/7/16
 * Time: 16:02
 */

namespace Emilia\exception;


class HttpException extends \RuntimeException
{
    public function __construct($message, $code, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}