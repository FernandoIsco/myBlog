<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/6/29
 * Time: 21:48
 */

namespace Emilia\route;




class Route
{
    /**
     * @var RouteContainer 路由容器
     */
    private $container;

    /**
     * @var string 记录请求方式
     */
    private $requireMethods;

    /**
     * @var array 所有请求方式
     */
    private $methods = ['get', 'post', 'put', 'patch', 'delete'];

    /**
     * @var object 对象实例
     */
    private static $instance;

    /**
     * Route constructor.
     * 架构函数
     *
     * @param RouteContainer $container 路由容器对象
     *
     */
    private function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * 对象实例
     *
     * @author lzl
     *
     * @param RouteContainer $container 路由容器对象
     *
     * @return object|Route
     *
     */
    public static function instance(RouteContainer $container = null)
    {
        if (empty(self::$instance)) self::$instance = new self($container);

        return self::$instance;
    }

    /**
     * 路由注册
     *
     * @author lzl
     *
     * @param string $methods 请求方式
     * @param string $route 路由规则
     * @param string $action 路由指向
     *
     * @return object
     *
     */
    public function routeRegister($methods, $route, $action)
    {
        self::$instance->requireMethods = $methods = (array)$methods;

        foreach ($methods as $method) {
            self::getContainer()->addRoute($method, $route, $action);

            self::getContainer()->addToDictionary($method, $route);
        }

        return self::$instance;
    }

    /**
     * get方式路由注册
     *
     * @author lzl
     *
     * @param string $route 路由规则
     * @param string $action 路由指向
     *
     * @return object
     */
    public static function get($route, $action)
    {
        return self::instance()->routeRegister(['get', 'head'], $route, $action);
    }

    /**
     * post方式路由注册
     *
     * @author lzl
     *
     * @param string $route 路由规则
     * @param string $action 路由指向
     *
     * @return object
     */
    public static function post($route, $action)
    {
        return self::instance()->routeRegister('post', $route, $action);
    }

    /**
     * put方式路由注册
     *
     * @author lzl
     *
     * @param string $route 路由规则
     * @param string $action 路由指向
     *
     * @return object
     */
    public static function put($route, $action)
    {
        return self::instance()->routeRegister('put', $route, $action);
    }

    /**
     * patch方式路由注册
     *
     * @author lzl
     *
     * @param string $route 路由规则
     * @param string $action 路由指向
     *
     * @return object
     */
    public static function patch($route, $action)
    {
        return self::instance()->routeRegister('patch', $route, $action);
    }

    /**
     * delete方式路由注册
     *
     * @author lzl
     *
     * @param string $route 路由规则
     * @param string $action 路由指向
     *
     * @return object
     */
    public static function delete($route, $action)
    {
        return self::instance()->routeRegister('delete', $route, $action);
    }

    /**
     * 多种方式路由注册
     *
     * @author lzl
     *
     * @param array $methods 请求方式数组
     * @param string $route 路由规则
     * @param string $action 路由指向
     *
     * @return object
     */
    public static function match($methods, $route, $action)
    {
        $methods = array_intersect(self::$instance->methods, $methods);

        return self::instance()->routeRegister($methods, $route, $action);
    }

    /**
     * 全部方式路由注册
     *
     * @author lzl
     *
     * @param string $route 路由规则
     * @param string $action 路由指向
     *
     * @return object
     */
    public static function any($route, $action)
    {
        return self::instance()->routeRegister(self::$instance->methods, $route, $action);
    }

    /**
     * 除了个别方式的路由注册
     *
     * @author lzl
     *
     * @param array $methods 被排除的请求方式
     * @param string $route 路由规则
     * @param string $action 路由指向
     *
     * @return object
     */
    public static function except($methods, $route, $action)
    {
        $methods = array_diff(self::$instance->methods, $methods);

        return self::instance()->routeRegister($methods, $route, $action);
    }

    /**
     * 设置正则表达式
     *
     * @author lzl
     *
     * @param string $name 路由参数名
     * @param string $expression 正则表达式
     *
     * @return object
     */
    public function pattern($name, $expression = '\w+')
    {
        $methods = self::$instance->requireMethods;

        foreach ($methods as $method) {
            self::getContainer()->addPattern($method, $name, $expression);
        }

        return self::$instance;
    }

    /**
     * 设置默认控制器和方法
     *
     * @author lzl
     *
     * @param array $defaults 默认控制器和方法
     *
     * @return object
     */
    public function defaults($defaults)
    {
        $methods = self::$instance->requireMethods;

        foreach ($methods as $method) {
            self::getContainer()->addDefaults($method, $defaults);
        }

        return self::$instance;
    }

    /**
     * 获取路由容器
     *
     * @author lzl
     *
     * @return mixed
     */
    private static function getContainer()
    {
        return self::$instance->container;
    }
}