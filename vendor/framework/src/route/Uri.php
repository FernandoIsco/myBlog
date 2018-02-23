<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/10/3
 * Time: 14:05
 */

namespace Emilia\route;


use Emilia\config\Config;
use Emilia\http\Request;

class Uri implements \Iterator
{
    /**
     * @var null|object 对象实例
     */
    private static $instance = null;

    /**
     * @var null|object|Request Request对象实例
     */
    private $request = null;

    /**
     * @var null|object|RouteContainer 路由容器对象实例
     */
    private $container = null;

    /**
     * @var string|\Closure 路由指向
     */
    private $action;

    /**
     * @var bool 是否区分模块
     */
    private $division = false;

    private $position = 0;

    /**
     * Uri constructor.
     * 架构函数
     *
     */
    public function __construct()
    {
        is_null($this->request) && $this->request = Request::instance();

        is_null($this->container) && $this->container = RouteContainer::instance();
    }

    /**
     * 对象实例
     *
     * @author lzl
     *
     * @return null|object|static
     */
    public static function instance()
    {
        is_null(self::$instance) && self::$instance = new static();

        return self::$instance;
    }

    /**
     * 整理构造url的参数
     *
     * @author lzl
     *
     * @param array $vars 构造url参数
     *
     * @return array
     */
    public function formatVars(&$vars)
    {
        $format = array(
            'module' => '',
            'controller' => '',
            'action' => '',
            'arguments' => array()
        );

        $return = $vars = array_merge($format, $vars);

        unset($return['arguments']);

        return $return;
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
    public function buildUrl($method, $vars = array())
    {
        if (!is_array($vars)) {
            throw new \InvalidArgumentException(sprintf('variable type error： %s, need Array', gettype($vars)));
        }

        $formatVars = $this->formatVars($vars);

        $modules = Config::getConfig('modules');

        if ($modules && isset($modules['division']) && $modules['division']) {
            $this->division = true;
        }

        $routes = $this->container->lookUpDictionary($method);

        $usefulRoute = array();
        foreach ($routes as $route) {
            $action = $this->container->getAction($method, $route);
            if (! ($action instanceof \Closure)) {
                $tmpAction = ltrim($action, '/');
                $actions = $tmpAction ? array_filter(explode('/', $tmpAction), array($this, 'isNotVariable')) : array(); // TODO

                if (!array_diff($actions, $formatVars)) {
                    $this->init($action);
                    $patterns = $this->container->getPatterns($method, $route);

                    // 确定模块
                    if ($this->valid() && $this->division) {
                        if (!$this->isNotVariable($this->current())) {
                            if (!$this->checkVariable($vars['module'], $patterns)) {
                                continue;
                            }
                            $this->next();
                        } elseif (!in_array($this->current(), $modules['deny']) && in_array($this->current(), $modules['modules'])) {
                            if ($this->current() != $vars['module']) {
                                continue;
                            }
                            $this->next();
                        } else {
                            $tmpModule = !empty($modules['default']) ? $modules['default'] : 'index';
                            if ($tmpModule != $vars['module']) {
                                continue;
                            }
                        }
                    }

                    // 确定控制器
                    if ($this->valid()) {
                        if (!$this->isNotVariable($this->current())) {
                            if (!$this->checkVariable($vars['controller'], $patterns)) {
                                continue;
                            }
                            $this->next();
                        } elseif ($this->current() == $vars['controller']) {
                            $this->next();
                        } elseif (!$this->checkDefault($vars, 'controller')) {
                            continue;
                        }
                    } elseif (!$this->checkDefault($vars, 'controller')) {
                        continue;
                    }

                    // 确定方法
                    if ($this->valid()) {
                        if (!$this->isNotVariable($this->current())) {
                            if (!$this->checkVariable($vars['action'], $patterns)) {
                                continue;
                            }
                            $this->next();
                        } elseif ($this->current() == $vars['action']) {
                            $this->next();
                        } elseif (!$this->checkDefault($vars, 'action')) {
                            continue;
                        }
                    } elseif (!$this->checkDefault($vars, 'action')) {
                        continue;
                    }

                    if ($this->valid() && $this->isNotVariable($this->current())) {
                        continue;
                    } else {
                        $usefulRoute[] = $route;
                    }
                }
            }
        }

        if (!$usefulRoute) {
            throw new \InvalidArgumentException(sprintf("Could not build url with arguments : [module] %s, [controller]%s, [action]%s", $vars['module'], $vars['controller'], $vars['action']));
        }

        $arguments = $vars['arguments'];
        $argumentCount = 0;
        $selectRoute = '';
        // TODO 这里选择路由的判断，要修改下
        foreach ($usefulRoute as $route) {
            if (strpos($route, '{') !== false) {
                $patterns = $this->container->getPatterns($method, $route);

                $count = is_array($patterns) ? count(array_intersect_key($arguments, $patterns)) : 0;

                if ($count > $argumentCount) {
                    $argumentCount = $count;

                    $selectRoute = preg_replace_callback('/{([0-9a-zA-Z-_\.]+)}/i', function ($value) use ($arguments, $patterns) { return $this->getParamValue($value[1], $arguments, $patterns); }, $route);
                }
            } elseif ($argumentCount == 0) {
                $selectRoute = $route;
            }
        }

        return $this->formatUrl($selectRoute);
    }

    /**
     * 路由变量参数赋值
     *
     * @author lzl
     *
     * @param string $value 变量名
     * @param array $arguments 构造路由参数
     * @param array $patterns 路由变量正则表达式
     *
     * @return int|string
     */
    protected function getParamValue($value, $arguments, $patterns = array())
    {
        if (isset($arguments[$value])) {
            return $arguments[$value];
        } else {
            if ($this->isNumberPattern($patterns[$value])) {
                return 0;
            } elseif ($this->isStringPattern($patterns[$value])) {
                return 'index';
            } else {
                return 'index';
            }
        }
    }

    /**
     * 组装url
     *
     * @author lzl
     *
     * @param string $url 构造后的url
     *
     * @return string
     */
    protected function formatUrl($url)
    {
        return $this->request->root() . $url;
    }

    /**
     * 初始化和分析路由指向
     *
     * @author lzl
     *
     * @param string $action
     *
     * @return $this
     */
    protected function init($action)
    {
        if (is_string($action)) {
            $action = explode('/', ltrim($action, '/'));
        }

        $this->action = $action;

        $this->position = 0;

        return $this;
    }

    /**
     * 检查当前url的节点是否等于默认值
     *
     * @author lzl
     *
     * @param array $vars 构造url参数
     * @param string $name 参数名：'controller|action'
     *
     * @return bool
     */
    protected function checkDefault($vars, $name)
    {
        if ($this->division && isset($modules['defaultsAction'][$vars['module']])) {
            $res = $modules['defaultsAction'][$vars['module']];
        } else {
            $defaultsAction = Config::getConfig('defaultsAction', 'app');

            $res = isset($defaultsAction[$name]) ? $defaultsAction[$name] : '';
        }

        if ($res != $vars[$name]) {
            return false;
        }

        return true;
    }

    /**
     * 检查当前url的正则表达式是否符合
     *
     * @author lzl
     *
     * @param string $variable 当前url的节点
     * @param array $patterns 正则表达式
     *
     * @return bool
     */
    protected function checkVariable($variable, $patterns)
    {
        $name = $this->getVariableName($this->current());

        preg_match("/{$patterns[$name]}/", $variable, $match);
        if ($match) {
            $this->next();
            return true;
        }

        return false;
    }

    /**
     * 正则是否匹配整数
     *
     * @author lzl
     *
     * @param string $pattern
     *
     * @return bool
     */
    protected function isNumberPattern($pattern)
    {
        preg_match("/" . $pattern . "/", 0, $match); // TODO

        return !empty($match);
    }

    /**
     * 正则是否匹配字符串
     *
     * @author lzl
     *
     * @param string $pattern
     *
     * @return bool
     */
    protected function isStringPattern($pattern)
    {
        preg_match("/" . $pattern . "/", 'index', $match); // TODO

        return !empty($match);
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

    /**
     * 检查是否不是变量。
     *
     * @author lzl
     *
     * @param string $var
     *
     * @return bool
     */
    public function isNotVariable($var)
    {
        if (!is_string($var)) {
            throw new \InvalidArgumentException(sprintf('variable type error： %s', gettype($var)));
        }

        return strpos($var, '$', 0) !== 0;
    }

    /**
     * 从字符串中获取变量名
     *
     * @author lzl
     *
     * @param string $var 当前url的节点
     *
     * @return string
     */
    public function getVariableName($var)
    {
        $name = '';

        global $scope;

        eval("{$var} = 0;");

        $scope = get_defined_vars();

        eval("\$name = \$this->searchName({$var});");

        return $name;
    }

    /**
     * 查询获取变量
     *
     * @author lzl
     *
     * @param string $var 当前url的节点
     *
     * @return mixed
     */
    public function searchName(&$var)
    {
        global $scope;

        $name = array_search($var, $scope, true);

        return $name;
    }
}