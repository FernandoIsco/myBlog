<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 11:02
 */

namespace Emilia\http;


use Emilia\Hook;

class Response
{
    /**
     * @var object 对象实例
     */
    public static $instance = null;

    /**
     * @var array 输出数据
     */
    protected $data = array();

    /**
     * @var array 额外参数
     */
    protected $options = array();

    /**
     * @var int 响应状态码
     */
    protected $code = 200;

    /**
     * @var array 头部信息
     */
    protected $header = array();

    /**
     * @var string 字符集
     */
    protected $charset = 'utf-8';

    /**
     * @var string 响应数据格式
     */
    protected $contentType = 'text/html';

    /**
     * Response constructor.
     * 架构函数
     *
     * @param array $data 输出数据
     * @param array $options 额外参数
     * @param int   $code 状态码
     * @param array $header 头部信息
     *
     */
    public function __construct($data = array(), $options = array(), $code, $header = array())
    {
        $this->data = $data;
        $this->code($code);
        !empty($header) && $this->header = array_merge($this->header, $header);
        !empty($options) && $this->options = $options;
    }

    /**
     * 对象实例
     *
     * @param array|string $data 输出数据
     * @param array $options 额外参数
     * @param int   $code 状态码
     * @param array $header 头部信息
     *
     * @return object|static
     */
    public static function instance($data = '', $options = array(), $code = 200, array $header = array())
    {
        if (class_exists($class = get_called_class())) {
            self::$instance = new $class($data, $options, $code, $header);
        } else {
            self::$instance = new static($data, $options, $code, $header);
        }

        return self::$instance;
    }

    /**
     * 数据输出
     *
     */
    public function output()
    {
        $output = $this->checkOutput($this->getData());

        if (!headers_sent() && !empty($this->header)) {
            http_response_code($this->code);
            foreach ($this->header as $name => $val) {
                header($name . ':' . $val);
            }
        }

        echo $output;

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        Hook::runtime();
    }

    /**
     * 检查类型是否合法
     *
     * @param string $output
     *
     * @return string
     */
    public function checkOutput($output)
    {
        if (!is_null($output) && !is_string($output) && !is_numeric($output) && is_callable($output, '__toString')) {
            throw new \InvalidArgumentException(sprintf('variable type error： %s', gettype($output)));
        }

        return (string)$output;
    }

    /**
     * 获取数据
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 头部信息设置
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function header($key, $value)
    {
        $this->header[$key] = $value;
        return $this;
    }

    /**
     * 发送HTTP状态
     * @param integer $code 状态码
     * @return $this
     */
    public function code($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * LastModified
     * @param string $time
     * @return $this
     */
    public function lastModified($time)
    {
        $this->header['Last-Modified'] = $time;
        return $this;
    }

    /**
     * Expires
     * @param string $time
     * @return $this
     */
    public function expires($time)
    {
        $this->header['Expires'] = $time;
        return $this;
    }

    /**
     * ETag
     * @param string $eTag
     * @return $this
     */
    public function eTag($eTag)
    {
        $this->header['ETag'] = $eTag;
        return $this;
    }

    /**
     * 页面缓存控制
     * @param string $cache 状态码
     * @return $this
     */
    public function cacheControl($cache)
    {
        $this->header['Cache-control'] = $cache;
        return $this;
    }

    /**
     * 页面输出类型
     * @param string $contentType 输出类型
     * @param string $charset 输出编码
     * @return $this
     */
    public function contentType($contentType, $charset = 'utf-8')
    {
        $this->header['Content-Type'] = $contentType . '; charset=' . $charset;
        return $this;
    }
}