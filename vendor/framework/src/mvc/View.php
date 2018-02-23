<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/6/29
 * Time: 21:48
 */

namespace Emilia\mvc;


use Emilia\config\Config;
use Emilia\exception\TemplateNotFoundException;
use Emilia\route\Uri;

class View
{
    /**
     * @var array 渲染模板参数
     */
    private $assign = [];

    public function __construct()
    {
    }

    /**
     * 模板文件是否存在
     *
     * @author lzl
     *
     * @param string $template 模板文件
     *
     * @return bool|string
     */
    public function exists($template)
    {
        if (!is_file($template)) {
            $template = VIEW_PATH . $template;

            if (!is_file($template)) {
                return false;
            }
        }

        return $template;
    }

    /**
     * 模板参数
     *
     * @author lzl
     *
     * @param string|array $name 变量名或一组变量名
     * @param string $value 变量值
     *
     * @return $this
     */
    public function assign($name, $value = '')
    {
        if (is_array($name)) {
            $this->assign = array_merge($this->assign, $name);
        } else {
            $this->assign[$name] = $value;
        }

        return $this;
    }

    /**
     * 模板输出
     *
     * @author lzl
     *
     * @param string $file 模板文件
     */
    public function display($file)
    {
        if (false === $template = $this->exists($file)) {
            $template = VIEW_PATH . $file;

            throw new TemplateNotFoundException("Template {$template} not Found!");
        }

        $this->assign('this', $this);
        extract($this->assign, EXTR_OVERWRITE);
        include $template;
    }

    /**
     * 构造url
     *
     * @author lzl
     *
     * @param string $method 请求方式
     * @param array $vars 构造url参数
     *
     * @return string
     */
    public function buildUrl($method, $vars)
    {
        return Uri::instance()->buildUrl($method, $vars);
    }

    /**
     * 魔术方法，用于在模板调用方法
     *
     * @author lzl
     *
     * @param string $name 模板名称
     * @param array $arguments 模板变量
     */
    public function __call($name, $arguments)
    {
        $config = Config::getConfig('views');

        $template = isset($config[$name]) ? $this->exists($config[$name]) : '';

        if ($template) {
            foreach ($arguments as $argument) {
                extract($argument, EXTR_OVERWRITE);
            }

            include $template;
        }
    }
}