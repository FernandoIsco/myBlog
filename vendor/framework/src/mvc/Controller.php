<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/6/29
 * Time: 21:48
 */

namespace Emilia\mvc;


use Emilia\config\Config;
use Emilia\http\Request;
use Emilia\http\Response;
use \Emilia\http\response\View as ViewResponse;
use \Emilia\http\response\Json as JsonResponse;
use \Emilia\http\response\Redirect as RedirectResponse;
use Emilia\route\Uri;

class Controller
{
    /**
     * @var null|object|Request Request对象实例
     */
    protected $request = null;

    /**
     * @var array 渲染到视图的参数
     */
    protected $viewParam = array();

    /**
     * Controller constructor.
     * 架构函数
     *
     * @param Request|null $request Request对象实例
     */
    public function __construct(Request $request = null)
    {
        $this->request = is_null($request) ? Request::instance() : $request;
    }

    /**
     * 视图参数
     *
     * @author lzl
     *
     * @param string|array $name 变量名或一组变量名
     * @param string $value 变量值
     */
    public function assign($name, $value = '')
    {
        if (is_array($name)) {
            $this->viewParam = array_merge($this->viewParam, $name);
        } else {
            $this->viewParam[$name] = $value;
        }
    }

    /**
     * 输出视图
     *
     * @author lzl
     *
     * @param string $template 模板名称
     *
     * @return Response
     */
    public function display($template)
    {
        $data = array();
        $data['template'] = $template;

        return ViewResponse::instance($data, $this->viewParam);
    }

    /**
     * 以json格式输出
     *
     * @author lzl
     *
     * @param array $data   数组
     * @param array $header 头部信息
     *
     * @return Response
     */
    public function json($data, $header = array())
    {
        return JsonResponse::instance($data, array(), 200, $header);
    }

    /**
     * 重定向
     *
     * @author lzl
     *
     * @param array $vars 构造url参数
     * @param array $options url额外参数
     * @param array $header  头部信息
     *
     * @return Response
     */
    public function redirect($vars, $options = array(), $header = array())
    {
        return RedirectResponse::instance($this->buildUrl($vars), $options, 302, $header);
    }

    /**
     * 构建url
     *
     * @author lzl
     *
     * @param array $vars    构造url参数
     * @param string $method 请求方式
     *
     * @return string
     */
    public function buildUrl($vars, $method = 'get')
    {
        return Uri::instance()->buildUrl($method, $vars);
    }
}