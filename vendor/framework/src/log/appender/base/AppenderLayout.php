<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/11
 * Time: 15:08
 */

namespace Emilia\log\appender\base;


use Emilia\exception\Exception;

class AppenderLayout
{
    /**
     * @var string 日志信息
     */
    private $content = '';

    /**
     * @var string 最后写日志的文件
     */
    private $file = '';

    /**
     * @var string 最后写日志的行数
     */
    private $line = 0;

    /**
     * @var string 最后写日志的类名
     */
    private $class = '';

    /**
     * @var string 最后写日志的方法名
     */
    private $method = '';

    /**
     * @var string 日志错误等级
     */
    private $level = '*';

    /**
     * 从debug_backtrace中获取日志信息
     *
     */
    public function debugTrace()
    {
        $trace = debug_backtrace();
        $traceInfo = array_pop($trace);

        while ($traceInfo != null) {
            if (isset($traceInfo['class']) && $traceInfo['class'] == 'Emilia\log\Logger') {
                $this->file = $traceInfo['file'];
                $this->line = $traceInfo['line'];
                break;
            }

            $preTrace = $traceInfo;
            $traceInfo = array_pop($trace);
        }

        if (!empty($preTrace['args'][0]) && ($traceObj = $preTrace['args'][0]) instanceof \Exception) {
            $exceptionTrace = $traceObj->getTrace();
            $exceptionTraceItem = array_pop($exceptionTrace);

            while ($exceptionTraceItem != null) {
                if (isset($exceptionTraceItem['class']) && $exceptionTraceItem['class'] == 'Emilia\exception\Exception') {
                    break;
                }

                $preTrace = $exceptionTraceItem;
                $exceptionTraceItem = array_pop($exceptionTrace);
            }
        }

        $this->class = isset($preTrace['class']) ? $preTrace['class'] : '';
        if (isset($preTrace['function']) && !in_array($preTrace['function'], array('include', 'include_once', 'require', 'require_once'))) {
            $this->method = $preTrace['function'];
        } else {
            $this->method = '';
        }
    }

    /**
     * 获取经过变量替换的日志内容
     *
     * @param Exception|string|array $content 日志内容
     * @param string $layout 日志模板
     *
     * @return mixed
     */
    public function getContent($content, $layout)
    {
        $this->debugTrace();

        $this->checkContent($content);

        return $this->getFormatContent($layout);
    }

    /**
     * 获取日志信息
     *
     * @param Exception|string|array $content 日志内容
     */
    public function checkContent($content)
    {
        if ($content instanceof \Exception) {
            $this->content = $content->getMessage();
            $this->file = $content->getFile();
            $this->line = $content->getLine();
        } elseif (is_string($content)) {
            $this->content = $content;
        } elseif (is_array($content)) {
            $this->content = json_encode($content);
        }
     }


    /**
     * 替换模板上面的变量
     *
     * @author lzl
     *
     * @param string $layout 日志模板
     *
     * @return mixed
     */
    public function getFormatContent($layout)
    {
        return preg_replace_callback('/%([a-zA-Z]+)/i', function ($matches) {
            $function = 'get' . ucfirst($matches[1]);
            if (method_exists($this, $function)) {
                return $this->$function();
            } else {
                return '';
            }
        }, $layout);
    }

    /**
     * 设置等级
     *
     * @param string $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * 获取日志时间
     *
     */
    public function getDate()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * 获取最后写日志的类名
     *
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * 获取最后写日志所在的方法
     *
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * 获取日志信息
     *
     */
    public function getMessage()
    {
        return $this->content;
    }

    /**
     * 获取最后写日志的文件
     *
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * 获取最后写日志的行数
     *
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * 换行符
     *
     */
    public function getNewline()
    {
        return PHP_EOL;
    }

    /**
     * 获取日志错误等级
     *
     */
    public function getLevel()
    {
        return $this->level;
    }
}