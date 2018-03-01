<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/6/29
 * Time: 21:48
 */

namespace Emilia\http;


use Emilia\config\Config;
use Emilia\session\Session;

class Request
{
    public $session;

    /**
     * @var object 对象实例
     */
    private static $instance;

    /**
     * @var string 模块
     */
    private $module;

    /**
     * @var string 控制器
     */
    private $controller;

    /**
     * @var string 方法
     */
    private $action;

    /**
     * @var string 请求路径
     */
    private $uri;

    /**
     * @var bool|string 访问请求原始数据
     */
    private $input;

    /**
     * @var array 路由参数
     */
    private $routeParam;

    /**
     * @var array 参数数组
     */
    private $parameters;

    /**
     * Request constructor.
     * 构造方法，解析请求路径，获取请求数据
     * @param array $query
     * @param array $request
     * @param array $cookie
     * @param null $files
     * @param array $server
     * @param null $input
     */
    private function __construct($query = array(), $request = array(), $cookie = array(), $files = null, $server = array(), $input = null)
    {
        $_GET  = array_merge($_GET, $query);
        $_POST = array_merge($_POST, $request);
        $_COOKIE = array_merge($_COOKIE, $cookie);
        $_FILES = $files ?: $_FILES;
        $_SERVER = array_merge($_SERVER, $server);
        $this->input = $input ?: file_get_contents('php://input');

        $this->parseUrl();
    }

    /**
     * Request 单例实例
     *
     * @author lzl
     * @return Request|object
     * @param array $options
     */
    public static function instance($options = array())
    {
        is_null(self::$instance) && self::$instance = new static($options);

        return self::$instance;
    }

    /**
     * 借鉴和略改laravel框架sympony/http-foundation/request的create方法
     *
     * @return Request
     * @param $uri
     * @param string $method
     * @param array $parameters
     * @param array $cookie
     * @param array $files
     * @param array $server
     * @param null $content
     */
    public static function create($uri, $method = 'GET', $parameters = array(), $cookie = array(), $files = array(), $server = array(), $content = null)
    {
        $originServerInfo = array(
            'REQUEST_METHOD' => strtoupper($method),
            'REMOTE_ADDR' => '127.0.0.1',
            'SERVER_PORT' => 80,
            'SERVER_NAME' => 'localhost',
            'PATH_INFO' => '',
            'HTTP_HOST' => 'localhost'
        );

        $server = array_merge($originServerInfo, $server);

        $components = parse_url($uri);

        if (isset($components['host'])) {
            $server['SERVER_NAME'] = $components['host'];
            $server['HTTP_HOST'] = $components['host'];
        }

        if (isset($components['scheme'])) {
            if ('https' === $components['scheme']) {
                $server['HTTPS'] = 'on';
                $server['SERVER_PORT'] = 443;
            } else {
                unset($server['HTTPS']);
                $server['SERVER_PORT'] = 80;
            }
        }

        if (isset($components['port'])) {
            $server['SERVER_PORT'] = $components['port'];
            $server['HTTP_HOST'] = $server['HTTP_HOST'] . ':' . $components['port'];
        }

        if (isset($components['user'])) {
            $server['PHP_AUTH_USER'] = $components['user'];
        }

        if (isset($components['pass'])) {
            $server['PHP_AUTH_PW'] = $components['pass'];
        }

        if (!isset($components['path'])) {
            $components['path'] = '/';
        }

        switch ($server['REQUEST_METHOD']) {
            case 'POST':
            case 'PUT':
            case 'DELETE':
                if (!isset($server['content_type'])) {
                    $server['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
                }
            case 'PATCH':
                $request = $parameters;
                $query = array();
                break;
            default:
                $request = array();
                $query = $parameters;
                break;
        }

        $queryString = '';
        if (isset($components['query'])) {
            parse_str(html_entity_decode($components['query']), $uriQuery);

            if ($query) {
                $query = array_merge($query, $uriQuery);
                $queryString = http_build_query($query, '', '&');
            } else {
                $query = $uriQuery;
                $queryString = $components['query'];
            }
        } elseif (!empty($query)) {
            $queryString = $components['query'];
        }

        $server['PATH_INFO'] = $components['path'];
        $server['REQUEST_URI'] = $components['path'] . ('' !== $queryString ? '?' . $queryString : '');
        $server['QUERY_STRING'] = $queryString;

        return new static($query, $request, $cookie, $files, $server, $content);
    }

    /**
     * 解析请求路径
     *
     * @author lzl
     *
     */
    public function parseUrl()
    {
        if (!isset($_SERVER['PATH_INFO'])) {
            foreach (array('ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL') as $type) {
                if (!empty($_SERVER[$type])) {
                    $_SERVER['PATH_INFO'] = (false !== strpos($_SERVER[$type], $_SERVER['SCRIPT_NAME'])) ? substr($_SERVER[$type], strlen($_SERVER['SCRIPT_NAME'])) : $_SERVER[$type];
                    break;
                }
            }
        }

        $pathInfo = empty($_SERVER['PATH_INFO']) ? '/' : '/' . ltrim($_SERVER['PATH_INFO'], '/');

        $this->setUri($pathInfo);
    }

    /**
     * 当前完整URL
     *
     * @return string
     */
    public function url()
    {
        if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
            return $_SERVER['HTTP_X_REWRITE_URL'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            return $_SERVER['REQUEST_URI'];
        } elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
            return $_SERVER['ORIG_PATH_INFO'] . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
        } else {
            return '';
        }
    }

    /**
     * 当前执行的文件 SCRIPT_NAME
     *
     * @return string
     */
    public function baseFile()
    {
        $script_name = basename($_SERVER['SCRIPT_FILENAME']);
        if (basename($_SERVER['SCRIPT_NAME']) === $script_name) {
            return $_SERVER['SCRIPT_NAME'];
        } elseif (basename($_SERVER['PHP_SELF']) === $script_name) {
            return $_SERVER['PHP_SELF'];
        } elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $script_name) {
            return $_SERVER['ORIG_SCRIPT_NAME'];
        } elseif (($pos = strpos($_SERVER['PHP_SELF'], '/' . $script_name)) !== false) {
            return substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $script_name;
        } elseif (isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT']) === 0) {
            return str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
        } else {
            return '';
        }
    }

    /**
     * URL访问根地址
     *
     * @return string
     */
    public function root()
    {
        $file = $this->baseFile();

        if ($file && 0 !== strpos($this->url(), $file)) {
            $file = str_replace('\\', '/', dirname($file));
        }
        return rtrim($file, '/');
    }

    /**
     * 获取请求信息的格式
     *
     * @author lzl
     *
     * @return mixed
     *
     */
    public function getContentType()
    {
        return $_SERVER['CONTENT_TYPE'];
    }

    /**
     * 获取请求方法
     *
     * @author lzl
     *
     * @return string
     *
     */
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * 设置请求路径
     *
     * @author lzl
     *
     * @param string $uri 请求路径
     *
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * 获取请求路径
     *
     * @author lzl
     *
     * @return string
     *
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * 设置请求模块
     *
     * @author lzl
     *
     * @param string $module 模块名称
     *
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * 获取请求模块名称
     *
     * @author lzl
     *
     * @return string
     *
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * 设置请求控制器
     *
     * @author lzl
     *
     * @param string $controller 控制器名称
     *
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * 获取请求控制器名称
     *
     * @author lzl
     *
     * @return string
     *
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * 设置请求方法
     *
     * @author lzl
     *
     * @param string $action 方法名称
     *
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * 获取请求方法
     *
     * @author lzl
     *
     * @return string
     *
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * 设置路由参数
     *
     * @author lzl
     *
     * @param array $param
     *
     */
    public function setRouteParam($param)
    {
        $this->routeParam = $param;
    }

    /**
     * 初始参数数组
     *
     * @author lzl
     *
     * @param array $parameters 参数
     *
     */
    public function initParameters($parameters = array())
    {
        $this->parameters = $parameters;
    }

    /**
     * 获取主机地址
     *
     * @author lzl
     *
     * @return mixed
     *
     */
    public function getHost()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * 获取请求协议
     *
     * @author lzl
     *
     * @return string
     *
     */
    public function getScheme()
    {
        $ssl = false;
        if (isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) {
            $ssl = true;
        } elseif (isset($_SERVER['REQUEST_SCHEME']) && 'https' == $_SERVER['REQUEST_SCHEME']) {
            $ssl = true;
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            $ssl = true;
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO']) {
            $ssl = true;
        }

        return $ssl ? 'https' : 'http';
    }

    /**
     * 获取端口
     *
     * @author lzl
     *
     * @return mixed
     *
     */
    public function getPort()
    {
        return $_SERVER['SERVER_PORT'];
    }

    /**
     * 获取IP地址
     *
     * @return mixed
     *
     */
    public function getIp() {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            $ip = getenv("REMOTE_ADDR");
        } elseif (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = "unknown";
        }

        return $ip;
    }

    /**
     * 获取路由参数
     *
     * @author lzl
     *
     * @param string $name 路由参数名
     * @param string $default 默认值
     * @param array $filters 过滤方法
     *
     * @return array|mixed|string
     *
     */
    public function fromRoute($name = '', $default = '', $filters = [])
    {
        $this->initParameters($this->routeParam);

        return $this->param($name, $default, $filters);
    }

    /**
     * 获取GET参数
     *
     * @author lzl
     *
     * @param string $name 路由参数名
     * @param string $default 默认值
     * @param array $filters 过滤方法
     *
     * @return array|mixed|string
     *
     */
    public function fromGet($name = '', $default = '', $filters = [])
    {
        $this->initParameters($_GET);

        return $this->param('get.' . $name, $default, $filters);
    }

    /**
     * 获取POST参数
     *
     * @author lzl
     *
     * @param string $name 路由参数名
     * @param string $default 默认值
     * @param array $filters 过滤方法
     *
     * @return array|mixed|string
     *
     */
    public function fromPost($name = '', $default = '', $filters = [])
    {
        $this->initParameters($_POST);

        return $this->param('post.' . $name, $default, $filters);
    }

    /**
     * 获取异步请求数据
     *
     * @author lzl
     *
     * @param string $name 路由参数名
     * @param string $default 默认值
     * @param array $filters 过滤方法
     *
     * @return array|mixed|string
     *
     */
    public function fromAjax($name = '', $default = '', $filters = [])
    {
        $parameters = array();

        if ($this->isAjax()) {
            $parameters = json_decode($this->input, true);
        }

        $this->initParameters($parameters);

        return $this->param('ajax.' . $name, $default, $filters);
    }

    /**
     * 获取文件上传内容
     *
     * @author lzl
     *
     * @param string $name 文件上传表单name名称
     *
     * @return array
     */
    public function fromFile($name = '')
    {
        if (empty($name)) {
            return $_FILES;
        }

        return isset($_FILES[$name]) ? $_FILES[$name] : [];
    }

    /**
     * 从REQUEST获取数据
     *
     * @author lzl
     *
     * @param string $name 路由参数名
     * @param string $default 默认值
     * @param array $filters 过滤方法
     *
     * @return array|mixed|string
     *
     */
    public function fromRequest($name = '', $default = '', $filters = [])
    {
        $this->initParameters($_REQUEST);

        return $this->param('request.' . $name, $default, $filters);
    }

    /**
     * 获取PUT数据
     *
     * @author lzl
     *
     * @param string $name 路由参数名
     * @param string $default 默认值
     * @param array $filters 过滤方法
     *
     * @return array|mixed|string
     *
     */
    public function fromPut($name = '', $default = '', $filters = [])
    {
        $parameters = array();
        if ($this->isAjax()) {
            $parameters = json_decode($this->input, true);
        } else {
            parse_str($this->input, $parameters); // TODO
        }

        $this->initParameters($parameters);

        return $this->param('put.' . $name, $default, $filters);
    }

    /**
     * 获取delete数据
     *
     * @author lzl
     *
     * @param string $name 路由参数名
     * @param string $default 默认值
     * @param array $filters 过滤方法
     *
     * @return array|mixed|string
     *
     */
    public function fromDelete($name = '', $default = '', $filters = [])
    {
        $parameters = array();
        if ($this->isAjax()) {
            $parameters = json_decode($this->input, true);
        } else {
            parse_str($this->input, $parameters); // TODO
        }

        $this->initParameters($parameters);

        return $this->param('delete.' . $name, $default, $filters);
    }

    /**
     * 获取参数
     *
     * @author lzl
     *
     * @param string $name    参数名，格式：get.id或id
     * @param string $default 默认值
     * @param array $filters  过滤方法
     *
     * @return array|mixed|string
     */
    public function param($name = '', $default = '', $filters = [])
    {
        $method = '';
        if (false !== strpos($name, '.')) {
            list($method, $name) = explode('.', $name, 2);
        }

        if (empty($this->parameters)) {
            $method = $method ?: $this->getMethod();
            switch ($method) {
                case 'get':
                    $this->parameters = $_GET;
                    break;
                case 'post':
                    if ($this->getContentType() == 'application/json') {
                        $this->parameters = json_decode($this->input, true);
                    } else {
                        $this->parameters = $_POST;
                    }
                    break;
                case 'put':
                case 'delete':
                    switch ($this->getContentType()) {
                        case 'application/x-www-form-urlencoded':
                            parse_str($this->input, $this->parameters);
                            break;
                        case 'application/json':
                            $this->parameters = json_decode($this->input, true);
                            break;
                    }
                    break;
                case 'ajax':
                    if ($this->getContentType() == 'application/json') {
                        $this->parameters = json_decode($this->input, true);
                    }
                    break;
                case 'session':
                    $this->sessionStart();
                    $this->parameters = $_SESSION;
                    break;
                case 'cookie':
                    $this->parameters = $_COOKIE;
                    break;
                case 'server':
                    $this->parameters = $_SERVER;
                    break;
                default :
                    $this->parameters = $_REQUEST;
                    break;
            }
        }

        if (empty($this->parameters)) return $default;

        $val = $name ? ($this->parameters[$name] ?: $default) : $this->parameters;

        if ($val === '' || $val === array()) return $default;

        is_string($filters) && $filters = explode(',', $filters);

        $filters = array_merge($filters, Config::getConfig('filter'));

        if (!$filters) return $val;

        foreach ($filters as $filter) {
            $this->filterValue($val, $filter);
        }

        return $val;
    }

    /**
     * 获取session参数
     *
     * @author lzl
     *
     * @param string $name
     * @param string $default
     * @return array|mixed|string
     */
    public function session($name = '', $default = '')
    {
        $this->sessionStart();

        $this->initParameters($_SESSION);

        return $this->param($name, $default);
    }

    /**
     * 获取cookie信息
     *
     * @author lzl
     *
     * @param string $name    cookie参数名
     * @param string $default 默认值
     *
     * @return string
     */
    public function cookie($name = '', $default = '')
    {
        $this->initParameters($_COOKIE);

        return $this->param($name, $default);
    }

    /**
     * 参数过滤
     *
     * @author lzl
     *
     * @param array|string $value
     * @param string $filter
     *
     * @return bool
     */
    public function filterValue(&$value, $filter)
    {
        if (!function_exists($filter)) return false;

        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $this->filterValue($val, $filter); // TODO 这里似乎有问题
            }
        } else {
            $value = $filter($value); // TODO 这里似乎有问题
        }

        return true;
    }

    /**
     * session会话开启
     *
     * @author lzl
     * @param string $sessionId
     * @param array $options
     */
    public function sessionStart($sessionId = '', $options = array())
    {
        if (!$this->session) {
            $this->session = new Session();
        }

        $this->session->start($sessionId, $options);
    }

    /**
     * 判断是否是异步请求
     *
     * @return bool
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ? true : false;
    }
}