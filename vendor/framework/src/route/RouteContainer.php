<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/6/29
 * Time: 21:47
 */

namespace Emilia\route;


use Emilia\cache\Cache;
use Emilia\config\Config;
use Emilia\FileSystem;
use Emilia\route\Route;

class RouteContainer
{
    /**
     * @var object 对象实例
     */
    private static $instance;

    /**
     * @var object 文件管理
     */
    private $file;

    /**
     * @var string 当前路由
     */
    private $route;

    /**
     * @var array 保存路由指向方法
     */
    private $routes;

    /**
     * @var array 所有路由
     */
    private $allRoute;

    /**
     * @var array 路由字典
     */
    private $dictionary;

    /**
     * @var array 保存路由参数正则
     */
    private $patterns;

    /**
     * @var array 保存路由默认控制器和方法
     */
    private $defaults;

    /**
     * @var object 路由单元实例
     */
    private $routeUnit;

    /**
     * @var string 缓存文件
     */
    private $storageName;

    /**
     * @var string 路由收集路径
     */
    private $collectPath = ROUTES_PATH;

    /**
     * Container constructor.
     * 构造方法，读取路由缓存，无则收集目录下的所有路由
     *
     * @param string $storagePath
     * @param string $collectPath
     */
    private function __construct($storagePath = '', $collectPath = '')
    {
        $this->file = new FileSystem();

        $this->setStorageName($storagePath);
        $this->setCollectPath($collectPath);
    }

    /**
     * container 单例实例
     *
     * @author lzl
     *
     * @param string $storagePath 缓存文件
     * @param string $collectPath 配置路由的路径
     *
     * @return RouteContainer|object
     */
    public static function instance($storagePath = 'routesCache', $collectPath = '')
    {
        if (empty(self::$instance)) self::$instance = new self($storagePath, $collectPath);

        return self::$instance;
    }

    /**
     * 获取路由
     *
     * @author lzl
     *
     * @param \Closure|null $extendsRouteUnits 额外设定的路由
     */
    public function getRoutes(\Closure $extendsRouteUnits = null)
    {
        if (false !== $cache = $this->checkStorage()) {
            $this->readCache($cache, $extendsRouteUnits);
        } else {
            $this->collect($extendsRouteUnits);
        }
    }

    /**
     * 路由收集
     *
     * @author lzl
     *
     * @param \Closure|null $extendsRouteUnits 额外设定的路由
     * @throws \Exception
     */
    public function collect($extendsRouteUnits = null)
    {
        if (!is_dir($this->collectPath)) {
            throw new \Exception('routes config file is not found');
        }

        $this->initRouteUnit();

        $defaultsAction = Config::getConfig('defaultsAction');
        if (array_key_exists('controller', $defaultsAction) && array_key_exists('action', $defaultsAction)) {
            Route::get('/', $defaultsAction['controller'] . '/' . $defaultsAction['action']);
        } else {
            Route::get('/', 'site/index');
        }

        foreach (glob($this->collectPath . "*.php") as $file) {
            if ($this->file->exists($file) && !$this->file->isDot($file)) {
                $this->file->requireOnce($file);
            }
        }

        if (!is_null($extendsRouteUnits) && $extendsRouteUnits instanceof \Closure) {
            call_user_func($extendsRouteUnits);
        }

        $this->unsetRouteUnit();

        $this->storage();
    }

    /**
     * 将路由缓存内容，初始化为适合container对象使用
     *
     * @author lzl
     *
     * @param array $data 缓存内容
     * @param \Closure|null $extendsRouteUnits 额外设定的路由
     */
    private function readCache($data, $extendsRouteUnits = null)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        $serializeClosure = new SerializeClosure();

        $tempRoutes = [];
        foreach ($this->routes as $method => $routes) {
            $this->allRoute[$method] = array_keys($routes);

            foreach ($routes as $route => $action) {
                if (is_array($action) && !$serializeClosure->checkFormat($action)) {
                    $action = $serializeClosure->transClosure($action);
                }

                $tempRoutes[$method][$route] = $action;
            }
        }

        $this->routes = $tempRoutes;

        if (!is_null($extendsRouteUnits) && $extendsRouteUnits instanceof \Closure) {
            $this->initRouteUnit();
            call_user_func($extendsRouteUnits);
            $this->unsetRouteUnit();
        }
    }

    /**
     * 初始路由单元
     *
     * @author lzl
     *
     */
    public function initRouteUnit()
    {
        $this->routeUnit = Route::instance($this);
    }

    /**
     * 销毁路由单元
     *
     * @author lzl
     *
     */
    public function unsetRouteUnit()
    {
        unset($this->routeUnit);
    }

    /**
     * container字典。根据method，存放路由。
     *
     * @author lzl
     *
     * @param string $method 请求方法
     * @param string $route 路由
     */
    public function addToDictionary($method, $route)
    {
        $routeTags = explode('/', trim($route, '/'));

        foreach ($routeTags as $key => $tag) {
            if (!isset($this->dictionary[$method][$key][$tag])) {
                $this->dictionary[$method][$key][$tag] = array();
            }

            if (!in_array($route, $this->dictionary[$method][$key][$tag])) {
                $this->dictionary[$method][$key][$tag][] = $route;
            }
        }
    }

    /**
     * 将路由和路由指向关联起来
     *
     * @author lzl
     *
     * @param string $method 请求方法
     * @param string $route 路由
     * @param string|\closure $action 路由指向，可以是字符串，也可以是闭包
     */
    public function addRoute($method, $route, $action)
    {
        $this->route = $route;

        $this->allRoute[$method][] = $route;

        $this->routes[$method][$route] = $action;
    }

    /**
     * 为当前目录添加参数正则表达式
     *
     * @author lzl
     *
     * @param string $method 请求方法
     * @param string $name 参数名称
     * @param string $expression 正则表达式
     */
    public function addPattern($method, $name, $expression)
    {
        if (!is_array($name)) {
            $name = [$name => $expression];
        }

        foreach ($name as $key => $pattern) {
            $this->patterns[$method][$this->route][$key] = $pattern;
        }
    }

    /**
     * 为当前目录添加默认控制器和默认方法
     *
     * @author lzl
     *
     * @param string $method 请求方法
     * @param array $defaults 默认控制器和默认方法
     */
    public function addDefaults($method, $defaults)
    {
        $defaults = (array)$defaults;

        foreach ($defaults as $key => $default) {
            $this->defaults[$method][$this->route][$key] = $default;
        }
    }

    /**
     * 通过请求方式，和请求路径，查询字典中是否存在该路由
     *
     * @author lzl
     * @param string $method 请求方法 
     * @param string $route 请求路径
     *
     * @return array 所有符合条件的路由
     */
    public function lookUpDictionary($method, $route = '')
    {
        if (empty($this->allRoute[$method]) || empty($this->dictionary[$method])) {
            return array();
        }

        $findResult = $this->allRoute[$method];

        $methodDirectories = $this->dictionary[$method];

        if ($route) {
            $routes = explode('/', trim($route, '/'));

            foreach ($routes as $position => $node) {
                if (!isset($methodDirectories[$position])) {
                    return [];
                }

                $temp = [];
                foreach ($methodDirectories[$position] as $key => $item) {
                    if (strpos($key, '{') !== false || strpos($key, '}') !== false || ($key == $node && $route == $item)) {
                        $temp = array_merge($temp, $item);
                    }
                }

                $findResult = array_intersect($findResult, $temp);
            }
        }

        return $findResult;
    }

    /**
     * 获取路由对应的操作方法
     *
     * @author lzl
     *
     * @param string $method 请求方法
     * @param string $route 路由
     *
     * @return string|\closure 返回访问路径，或者是闭包
     */
    public function getAction($method, $route)
    {
        if (isset($this->routes[$method][$route])) {
            return $this->routes[$method][$route];
        }

        return '';
    }

    /**
     * 获取路由对应的参数正则匹配表达式
     *
     * @author lzl
     *
     * @param string $method 请求方法
     * @param string $route 路由
     *
     * @return array 参数正则匹配表达式
     */
    public function getPatterns($method, $route)
    {
        if (isset($this->patterns[$method][$route])) {
            return $this->patterns[$method][$route];
        }

        return [];
    }

    /**
     * 获取路由对应的默认控制器和默认方法
     *
     * @author lzl
     *
     * @param string $method 请求方法
     * @param string $route 路由
     *
     * @return array 默认控制器和默认方法
     */
    public function getDefaults($method, $route)
    {
        if (isset($this->defaults[$method][$route])) {
            return $this->defaults[$method][$route];
        }

        return [];
    }

    /**
     * 设置路由存储路径
     *
     * @author lzl
     *
     * @param string $name 储存路径
     *
     */
    public function setStorageName($name)
    {
        !empty($name) && $this->storageName = $name;
    }

    /**
     * 设置路由获取路径
     *
     * @author lzl
     *
     * @param string $path 获取路径
     *
     */
    public function setCollectPath($path)
    {
        !empty($path) && $this->collectPath = rtrim($path, DS) . DS;
    }

    /**
     * 路由缓存
     *
     * @author lzl
     *
     */
    private function storage()
    {
        $data = [
            'makeTime' => filemtime(ROUTES_PATH),
            'routes' => $this->serializeAction(),
            'dictionary' => $this->dictionary,
            'patterns' => $this->patterns,
            'defaults' => $this->defaults
        ];

        Cache::createFileStore()->put($this->storageName, $data, 8760);
    }

    /**
     * 序列化路由
     *
     * @author lzl
     *
     * @return array
     */
    private function serializeAction()
    {
        $storage = [];

        $serializeClosure = new SerializeClosure();

        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $route => $action) {
                if ($action instanceof \Closure) {
                    $action = $serializeClosure->setClosure($action)->getSerializeFormat();
                }

                $storage[$method][$route] = $action;
            }
        }

        return $storage;
    }

    /**
     * 获取路由缓存
     *
     * @author lzl
     *
     * @return array
     */
    private function getStorage()
    {
        return Cache::createFileStore()->get($this->storageName);
    }

    /**
     * 检查路径文件是否有变动
     *
     * @author lzl
     *
     * @return array|bool 缓存未过期，返回缓存内容；过期则返回false
     */
    private function checkStorage()
    {
        $cache = $this->getStorage();

        if (isset($cache['makeTime']) && $cache['makeTime'] >= $this->file->lastModified(ROUTES_PATH)) {
            return $cache;
        }

        return false;
    }
}