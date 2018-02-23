<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/7/16
 * Time: 16:58
 */

namespace Emilia\exception;


class DatabaseException extends \Exception
{
    public function __construct($message, $sql = '', $config = array(), $code = '4500', Exception $previous = null)
    {
        $content = 'ERROR MESSAGE: ' . $message . '; ';
        
        !empty($sql) && $content .= 'ERROR SQL: ' . $sql . '; ';

        !empty($config) && $content .= 'CONFIG : ' . json_encode($config) . '; ';

        parent::__construct($content, $code, $previous);
    }
}