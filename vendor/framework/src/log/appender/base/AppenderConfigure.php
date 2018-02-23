<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9
 * Time: 11:42
 */

namespace Emilia\log\appender\base;


use Emilia\config\Config;

class AppenderConfigure
{
    /**
     * 默认配置
     *
     * @author lzl
     *
     * @return array
     */
    private function getDefaultConfigure()
    {
        return array(
            'FileAppender' => array(

                'levels' => array('*'),

                'file' => LOG_PATH . 'log.txt',

                'layout' => "%date [%level]: '%message' in file %file on line %line %newline",
            ),
        );
    }

    /**
     * 读取配置 TODO
     *
     * @author lzl
     *
     * @param string $appender 日志驱动
     * @param array  $extends  自定义配置
     *
     * @return array
     */
    public function configure($appender = 'FileAppender', $extends = array())
    {
        $configure = Config::getConfig('appender.' . $appender);

        !empty($extends) && $configure = array_merge($configure, $extends);

        empty($configure) && $configure = self::getDefaultConfigure();

        return $configure;
    }
}