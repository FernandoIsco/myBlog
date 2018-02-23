<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9
 * Time: 10:07
 */

namespace Emilia;


use Emilia\log\Logger;

class Hook
{
    protected static $time;

    /**
     * 记录运行时间
     *
     * @author lzl
     *
     * @param string $sign 记录标识
     */
    public static function runtime($sign = 'system')
    {
        if (empty(self::$time[$sign])) {
            self::$time[$sign] = microtime(true);
        } else {
            Logger::record($sign . ' runtime : '. number_format((microtime(true) - self::$time[$sign]), 6));
        }
    }
}