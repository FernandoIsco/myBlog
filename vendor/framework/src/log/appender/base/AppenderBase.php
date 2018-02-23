<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8
 * Time: 10:00
 */

namespace Emilia\log\appender\base;


class AppenderBase
{
    /**
     * @var null|AppenderLayout 日志模板
     */
    protected $layout = null;

    /**
     * @var array 日志配置
     */
    protected $configure = array();

    public function __construct($configure)
    {
        $this->configure = (array) $configure;

        empty($this->layout) && $this->layout = new AppenderLayout();
    }

    /**
     * 检查错误等级
     *
     * @author lzl
     *
     * @param string $level TODO 错误等级设计
     *
     * @return bool
     */
    protected function checkLevel($level)
    {
        $this->layout->setLevel($level);

        if (!isset($this->configure['levels']) || $level == '*') return true;

        $levels = $this->configure['levels'];
        if (is_string($levels)) {
            $levels = explode(',', $levels);
        }

        if (is_array($levels) && in_array($level, $levels)) {
            return true;
        }

        return false;
    }

    /**
     * 获取日志内容
     *
     * @author lzl
     *
     * @param string|\Exception $content 日志内容或exception
     *
     * @return mixed
     */
    protected function getContent($content)
    {
        return $this->layout->getContent($content, $this->configure['layout']);
    }

    /**
     * 错误警告
     *
     * @author lzl
     *
     * @param string $message 错误信息
     */
    public function warn($message)
    {
        trigger_error($message);
    }
}