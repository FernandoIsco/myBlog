<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9
 * Time: 10:44
 */

return array(
    /*
    |--------------------------------------------------------------------------
    | 日志驱动
    |--------------------------------------------------------------------------
    | FileAppender 日志写入文件:
    |   levels: 错误等级
    |   file:   日志文件
    |   layout: 日志模板
    |
    | PDOAppender 日志写入数据库:
    |   levels: 错误等级
    |   table:  数据表
    |   schema: 设置某些字段自动填充内容
    */
    'appender' => array(
        'FileAppender' => array(

            'levels' => array('warn'),

            'file' => LOG_PATH . 'log.txt',

            'layout' => "%date [%level]: '%message' in file %file , [class] %class, [method] %method ' on line %line %newline",
        ),
        'PDOAppender' => array(

            'levels' => array('warn'),

            'table' => 'logs',

            'schema' => array(
                '%date' => 'createtime',
                '%level' => 'level',
                '%message' => 'content',
                '%file' => 'file',
                '%class' => 'class',
                '%method' => 'method',
                '%line' => 'line'
            )
        ),
        /*'SocketAppender' => array(),
        'ConsoleAppender' => array(),*/
    ),
);