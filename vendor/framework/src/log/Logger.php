<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9
 * Time: 11:13
 */

namespace Emilia\log;


use Emilia\log\appender\base\AppenderConfigure;
use Emilia\log\appender\FileAppender;
use Emilia\log\appender\PDOAppender;

class Logger
{
    /**
     * 写入文件
     */
    const FILE_APPENDER = 1;

    /**
     * 写入数据库
     */
    const PDO_APPENDER = 2;

    /**
     * 写入socket TODO
     */
    const SOCKET_APPENDER = 4;

    const CONSOLE_APPENDER = 8;

    /**
     * @var array 系统已设置的日志驱动
     */
    private static $sysAppender = array(
        self::FILE_APPENDER => 'FileAppender',
        self::PDO_APPENDER => 'PDOAppender',
        self::SOCKET_APPENDER => 'SocketAppender',
        self::CONSOLE_APPENDER => 'ConsoleAppender',
    );

    /**
     * @var array 日志驱动
     */
    private static $appender = array();

    /**
     * 获取用户已配置的日志驱动
     *
     * @author lzl
     *
     * @param string $appender 日志驱动
     * @param array  $extends  自定义配置
     *
     * @return FileAppender|PDOAppender
     */
    public static function getAppender($appender = 'FileAppender', $extends = array())
    {
        $class = __NAMESPACE__ . "\\appender\\" . $appender ;

        $sign = md5(json_encode($extends));

        if (empty(self::$appender[$appender][$sign])) {
            if (class_exists($class)) {
                $appenderConfigure = new AppenderConfigure();

                $configure = $appenderConfigure->configure($appender, $extends);

                self::$appender[$appender][$sign] = new $class($configure);
            } else {
                self::$appender[$appender][$sign] = null;
            }
        }

        return self::$appender[$appender][$sign];
    }

    public static function debug($content = null, $appender = '')
    {
        self::record($content, __FUNCTION__, $appender);
    }

    public static function warn($content = null, $appender = '')
    {
        self::record($content, __FUNCTION__, $appender);
    }

    public static function error($content = null, $appender = '')
    {
        self::record($content, __FUNCTION__, $appender);
    }

    public static function fatal($content = null, $appender = '')
    {
        self::record($content, __FUNCTION__, $appender);
    }

    /**
     * 日志写入
     *
     * @author lzl
     *
     * @param string $content 日志内容
     * @param string $level 日志等级 debug|warn|error|fatal  // TODO 错误等级设计
     * @param int $sysAppender 指定日志写入驱动
     * @param array $extends 自定义配置
     */
    public static function record($content, $level = '*', $sysAppender = self::FILE_APPENDER, $extends = array())
    {
        $appender = isset(self::$sysAppender[$sysAppender]) ? self::$sysAppender[$sysAppender] : '';

        $appenderClass = self::getAppender($appender, $extends);

        if (method_exists($appenderClass, 'write')) {
            $appenderClass->write($content, $level);
        }
    }
}