<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/7/16
 * Time: 16:44
 */

namespace Emilia\exception;


class ClassNotFoundException extends HttpException
{
    private $class;

    public function __construct($message, $class)
    {
        $this->class = $class;

        parent::__construct($message, 404);
    }

    public function getClass()
    {
        return $this->class;
    }
}