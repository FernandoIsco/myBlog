<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/6/29
 * Time: 21:48
 */

namespace Emilia\route;


use Emilia\config\Config;
use Emilia\exception\ClassNotFoundException;
use Emilia\exception\HttpException;
use Emilia\http\Request;
use Emilia\http\Response;

class Router implements \Iterator
{
    /**
     * @var object 对象实例
     */
    private $container;

    /**
     * @var Request Request对象实例
     */
    private $request;

    /**
     * @var array 符合当前请求路径的路由数组
     */
    private $routes;

    /**
     * @var string|\Closure 路由对应的执行方法
     */
    private $action;

    /**
     * @var string 模块
     */
    private $module;

    /**
     * @var string 控制器
     */
    private $class;

    /**
     * @var string 方法
     */
    private $method;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var string 默认控制器
     */
    private $defaultController;

    /**
     * @var string 默认方法
     */
    private $defaultMethod;

    /**
     * Router constructor.
     * 架构函数，获取符合请求路径的所有路由
     *
     * @param Request $request Request对象实例
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->container = RouteContainer::instance();

        $this->routes = $this->lookUpDictionary();
    }

    /**
     * 解析路由
     *
     * @author lzl
     *
     * @return Response
     */
    public function resolveRoute()
    {
        $route = $this->lookUpRoute();

        $action = $route ? $this->getAction($route) : $this->request->getUri();

        if ($action instanceof \Closure) {
            return Response::instance(call_user_func($action));
        } elseif (is_string($action)) {
            $this->action = explode('/', ltrim($action, '/'));

            $modules = Config::getConfig('modules');

            if ($modules && $modules['division']) {
                $module = $this->current();

                $moduleList = array_diff($modules['modules'], $modules['deny']);
                if (in_array($module, $moduleList)) {
                    $this->setModule($module);
                    $this->next();
                } else {
                    $this->setModule($modules['default']);
                }
            }

            $defaults = $this->getDefaults($route);
            $defaultAction = Config::getConfig('defaultsAction');
            if ($this->getModule()) {
                $this->setDefaults(
                    isset($modules['defaultsAction'][$this->getModule()]) ? $modules['defaultsAction'][$this->getModule()] : $defaultAction
                );
            } else {
                $this->setDefaults(!empty($defaults) ? $defaults : $defaultAction);
            }

            $this->init()->check();

            $this->getModule() && $this->request->setModule($this->getModule());
            $this->request->setController($this->getClass());
            $this->request->setAction($this->getMethod());
        }

        return null;
    }

    /**
     * 检查控制器和方法是否存在
     *
     * @author lzl
     *
     */
    protected function check()
    {
        $this->classExists();

        $this->methodExists();
    }

    /**
     * 初始化控制器和方法
     *
     * @author lzl
     *
     * @return $this
     *
     */
    protected function init()
    {
        $this->setClass($this->current());

        $this->setMethod($this->next());

        return $this;
    }

    /**
     * 设置模块
     *
     * @author lzl
     *
     * @param string $module 模块名称
     *
     */
    protected function setModule($module = '')
    {
        $this->module = $module;
    }

    /**
     * 设置控制器
     *
     * @author lzl
     *
     * @param string $class 控制器名称
     *
     */
    protected function setClass($class = '')
    {
        $this->class = 'app\\' . ($this->getModule() ? $this->getModule() . '\\' : '') . 'controller\\' . ($class ? $class : $this->getDefaultClass());
    }

    /**
     * 设置方法
     *
     * @author lzl
     *
     * @param string $method 方法名称
     *
     */
    protected function setMethod($method = '')
    {
        if (strpos($method, '$') !== false) {
            $method = substr($method, strpos($method, '$') + 1);

            $this->method = $this->request->fromRoute($method);
        } else {
            $this->method = $method ? $method : $this->getDefaultMethod();
        }
    }

    /**
     * 设置默认控制器和方法
     *
     * @author lzl
     *
     * @param array $defaults 默认控制器和方法
     *
     */
    protected function setDefaults($defaults)
    {
        isset($defaults['controller']) && $this->defaultController = $defaults['controller'];
        isset($defaults['action']) && $this->defaultMethod = $defaults['action'];;
    }

    /**
     * 获取模块
     *
     * @author lzl
     *
     * @return string
     *
     */
    protected function getModule()
    {
        return $this->module;
    }

    /**
     * 获取控制器
     *
     * @author lzl
     *
     * @return string
     *
     */
    protected function getClass()
    {
        return $this->class;
    }

    /**
     * 获取方法
     *
     * @author lzl
     *
     * @return string
     *
     */
    protected function getMethod()
    {
        return $this->method;
    }

    /**
     * 获取默认控制器
     *
     * @author lzl
     *
     * @return string
     *
     */
    protected function getDefaultClass()
    {
        return $this->defaultController;
    }

    /**
     * 获取默认方法
     *
     * @author lzl
     *
     * @return string
     *
     */
    protected function getDefaultMethod()
    {
        return $this->defaultMethod;
    }

    /**
     * 检查控制器是否存在
     *
     * @author lzl
     *
     */
    protected function classExists()
    {
        if (!class_exists($this->getClass())) {

            $controller = $this->rewind() ? $this->rewind() : $this->getDefaultClass();

            throw new ClassNotFoundException('Controller "' . $controller . '" not found', $controller);
        }
    }

    /**
     * 检查方法否存在
     *
     * @author lzl
     *
     */
    protected function methodExists()
    {
        if (!is_callable(array($this->getClass(), $this->getMethod()))) {
            throw new HttpException('Method "' . $this->getMethod() . '" not found', 404);
        }
    }

    /**
     * 检查路由是否合法
     *
     * @author lzl
     *
     * @return string
     */
    protected function lookUpRoute()
    {
        $satisfyRoute = '';
        foreach ($this->routes as $route) {
            if (strpos($route, '{') !== false || strpos($route, '}') !== false) {
                $compileResult = $this->compileRoute($route);

                if (!$compileResult) continue;
            }

            $satisfyRoute = $route;
            break;
        }

        return $satisfyRoute;

        // throw new RouteNotFoundException('route not found');
    }

    /**
     * 解析正则路由
     *
     * @author lzl
     *
     * @param string $route 正则路由
     *
     * @return bool
     */
    protected function compileRoute($route)
    {
        $patterns = $this->getPatterns($route);

        $uri = $this->request->getUri();

        $patternRoute = str_replace('/', '\/', $route);

        foreach ($patterns as $name => $pattern) {
            $patternRoute = str_replace("{" . $name . "}", "{$pattern}", $patternRoute);
        }

        preg_match("/{$patternRoute}/", $uri, $match);

        if ($match) {
            $parameters = [];

            array_map(function ($routeNode, $uriNode) use (&$parameters, $patterns) {
                if (false !== $patternPos = strpos($routeNode, '{')) {
                    preg_match('/{(.*)}/', $routeNode, $matchNode);

                    if (!empty($matchNode[1])) {
                        if (false !== $pos = strpos($uriNode, '.')) {
                            $uriNode = substr($uriNode, $patternPos, $pos - $patternPos);
                        }

                        array_key_exists($matchNode[1], $patterns) && $parameters[$matchNode[1]] = $uriNode;
                    }
                }
            }, array_slice(explode('/', $route), $this->position), explode('/', $uri));

            $this->request->setRouteParam($parameters);

            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取符合请求路径的所有路由
     *
     * @author lzl
     *
     * @return array
     */
    protected function lookUpDictionary()
    {
        $this->container->getRoutes();

        return $this->container->lookUpDictionary($this->request->getMethod(), $this->request->getUri());
    }

    /**
     * 获取路由对应的执行方法
     *
     * @author lzl
     *
     * @param string $route 路由
     *
     * @return \closure|string
     */
    protected function getAction($route)
    {
        return $this->container->getAction($this->request->getMethod(), $route);
    }

    /**
     * 获取路由对应的正则表达式
     *
     * @author lzl
     *
     * @param string $route 路由
     *
     * @return array
     */
    protected function getPatterns($route)
    {
        return $this->container->getPatterns($this->request->getMethod(), $route);
    }

    /**
     * 获取路由对应的默认控制器的方法
     *
     * @author lzl
     *
     * @param string $route 路由
     *
     * @return array
     */
    protected function getDefaults($route)
    {
        return $this->container->getDefaults($this->request->getMethod(), $route);
    }

    public function current()
    {
        return $this->valid() ? $this->action[$this->key()] : '';
    }

    public function next()
    {
        $this->position++;
        return $this->current();
    }

    public function rewind()
    {
        $this->position = 0;
        return $this->current();
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->action[$this->key()]);
    }
}