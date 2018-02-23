<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/7/9
 * Time: 17:34
 */

namespace Emilia\exception;


class ApplicationException extends \Exception
{
    public function __construct($message, $code = null, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}