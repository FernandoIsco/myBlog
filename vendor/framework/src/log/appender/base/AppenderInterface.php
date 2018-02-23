<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/9
 * Time: 17:23
 */

namespace Emilia\log\appender\base;



interface AppenderInterface
{
    /**
     * 日志写入
     *
     * @author lzl
     *
     * @param string|array|\Exception $content 日志内容
     * @param string $level 错误等级
     *
     * @return mixed
     */
    public function write($content, $level);
}