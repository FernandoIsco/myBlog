<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/7/16
 * Time: 16:40
 */

namespace Emilia\exception;


class RouteNotFoundException extends HttpException
{
    public function __construct($message)
    {
        parent::__construct($message, 404);
    }
}