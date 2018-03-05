<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/3
 * Time: 11:52
 */

namespace app\api\controller;


use app\api\controller\base\BaseRequest;
use app\api\controller\base\BaseResponse;
use app\api\controller\base\Structure;
use app\api\controller\traits\EncryptVerify as EncryptVerify;
use Emilia\Application;


class Api extends Base
{
    use EncryptVerify;

    /**
     * @var object 数据结构体
     */
    private $structure = null;

    /**
     * @var object 请求数据结构体，用于控制器使用接收参数
     */
    protected $myRequest = null;

    /**
     * @var object 请求条件结构体，用于控制器使用接收参数
     */
    protected $myWhereRequest = null;

    /**
     * @var string 请求方法
     */
    protected $requestAction = '';

    /**
     * @var object 响应结构体
     */
    protected $myResponse = null;

    public function __construct()
    {
        parent::__construct();
        $this->initStructure();
    }

    /**
     * 初始化请求结构体
     */
    private function initStructure()
    {
        $this->structure = new Structure();
        if (!$this->myRequest) {
            $this->myRequest = new BaseRequest();
        }

        if ($this->myWhereRequest) {
            $this->myRequest->where = $this->myWhereRequest;
        }
        $this->structure->query = $this->myRequest;
    }

    /**
     * 按请求结构体格式化请求的key，并设置请求参数
     * @param object $request 外部参数
     * @param string $structureName structure节点
     * @return object
     */
    private function parseRequest($request, $structureName = '')
    {
        if (!is_object($request)) {
            $this->setApiResponse(STATUS_INCORRECT_FORMAT);
        }

        if ($structureName && isset($this->structure->$structureName)) {
            $tmpStructure = $this->structure->$structureName;
        } elseif ($structureName && isset($this->structure->query->$structureName)) {
            $tmpStructure = $this->structure->query->$structureName;
        } else {
            $tmpStructure = $this->structure;
        }

        $option = $tmpStructure->getOption();

        if (strtolower($structureName) == 'query' && isset($option['methods'])) {
            $this->requestAction = $option['methods'][$this->getMethod()];
        }

        // $tmpRequest = new \stdClass();
        foreach ($tmpStructure as $key => $item) {
            if (!empty($option['key'][$key])) {
                $requestKey = $option['key'][$key];
            } else {
                $requestKey = $key;
            }

            if (is_object($item)) {
                $subRequest = isset($request->$requestKey) ? $request->$requestKey : new \stdClass();
                $tmpStructure->$key = $this->parseRequest($subRequest, $key);
            } else {
                $requestValue = !empty($request->$requestKey) ? $request->$requestKey : '';
                // 获取公共过滤方法
                $commonRules = !empty($option['filter'][$key]['rule']) ? (array)$option['filter'][$key]['rule'] : array();
                $commonErrMsg = !empty($option['filter'][$key]['msg']) ? (array)$option['filter'][$key]['msg'] : array();

                // 获取方法指定的过滤方法
                $methodValidate = isset($option['filter'][$this->getMethod()]) ? $option['filter'][$this->getMethod()] : array();
                $methodRules = !empty($methodValidate[$key]['rule']) ? (array)$methodValidate[$key]['rule'] : array();
                $methodErrMsg = !empty($methodValidate[$key]['msg']) ? (array)$methodValidate[$key]['msg'] : array();

                $rules = array_merge($commonRules, $methodRules);
                $errMsg = array_merge($commonErrMsg, $methodErrMsg);

                // 过滤校验
                $functionValidate = function ($func) use ($errMsg, $requestValue, $key) {
                    if ($func && TRUE !== $validateRes = $this->check($requestValue, $func, $errMsg)) {
                        !$validateRes && $validateRes = STATUS_PARAMETERS_INCOMPLETE;
                        $this->setApiResponse($validateRes, array($key));
                    }
                };
                array_map($functionValidate, $rules);
                //$this->validatedObj->checkValue($request_value, $rules, $err_msg);

                // 处理函数
                $functions = !empty($option['function'][$key]) ? (array)$option['function'][$key] : array();
                $requestValue = $this->dealFunction($requestValue, $functions);

                // 默认值
                $tmpStructure->$key = $requestValue ? $requestValue : $this->getDefault($item); //先验证，后判断默认值
            }
        }

        return $tmpStructure;
    }

    /**
     * 设置我的请求
     * @return $this
     * @param $query
     */
    public function setApiRequest($query)
    {
        $this->structure = $this->parseRequest($this->parseObject($query));

        return $this;
    }

    /**
     * 当前命名空间
     * @return string
     */
    public function getNamespace()
    {
        return __NAMESPACE__ . '\\';
    }

    /**
     * 获取请求方法
     * @return string
     */
    public function getMethod()
    {
        return strtolower($this->request->getMethod());
    }

    /**
     * 获取控制器方法
     * @return mixed
     */
    public function getRequestAction()
    {
        return $this->requestAction;
    }

    /**
     * 经过函数处理
     * @param string $value
     * @param array $functions
     * @return mixed
     */
    public function dealFunction($value, $functions)
    {
        $functions = (array) $functions;

        $res = $value;
        foreach ($functions as $function) {
            if (method_exists($this, $function)) {
                $res = $this->$function($value);
            }

            if (function_exists($function)) {
                $res = $function($value);
            }
        }

        return $res;
    }

    /**
     * 获取默认值
     * @param string $default
     * @return mixed
     */
    public function getDefault($default)
    {
        // $default = !empty($option['default'][$key]) ? $option['default'][$key] : '';

        if (method_exists($this, $default)) {
            $default = $this->$default();
        } elseif (function_exists($default)) {
            $default = $default();
        }

        return $default;
    }

    /**
     * 获取完整结构体
     * @return string
     */
    public function getApiStructure()
    {
        return $this->structure;
    }

    /**
     * 获取请求结构体
     * @return mixed
     */
    public function getApiRequest()
    {
        return $this->structure->query;
    }

    /**
     * 获取table结构体
     * @return mixed
     */
    public function getApiTableRequest()
    {
        return isset($this->structure->query->table) ? $this->structure->query->table : null;
    }

    /**
     * 获取where结构体
     * @return mixed
     */
    public function getApiWhereRequest()
    {
        return isset($this->structure->query->where) ? $this->structure->query->where : null;
    }

    /**
     * 获取session
     *
     * @return mixed
     */
    public function getSession()
    {
        return $this->structure->session;
    }

    /**
     * 获取来源
     *
     * @return mixed
     */
    public function getSource()
    {
        return $this->structure->source;
    }

    /**
     * 获取请求者身份
     *
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->structure->identity;
    }

    /**
     * 设置响应体
     * @param null $response
     * @param array $param
     */
    public function setApiResponse($response = null, $param = array())
    {
        $myResponse = $this->getApiResponse();
        if ($response instanceof BaseResponse || get_parent_class($response) == 'BaseResponse') {
            $myResponse = $response;
        } elseif (is_array($response) || is_object($response)) {
            foreach ($response as $key => $value) {
                $myResponse->$key = $value;
            }
        } elseif (is_string($response) || is_int($response)) {
            $myResponse->status = $response;
        }

        $res = array();
        $option = $myResponse->getOption('key');
        foreach ($myResponse as $key => $value) {
            if (isset($option[$key])) {
                $tmp_key = $option[$key];
            } else {
                $tmp_key = $key;
            }
            switch ($key) {
                case 'status':
                    $res["$tmp_key"] = intval($value);
                    break;
                case 'description':
                    $res["$tmp_key"] = $value ? $value : $this->getDescription($this->myResponse->status, '', $param);
                    break;
                case 'timestamp':
                    $res["$tmp_key"] = intval(microtime(true) * 1000);
                    break;
                case 'total' :
                case 'id':
                    if ($value !== '') $res['result']["$tmp_key"] = $value;
                    break;
                default :
                    $res['result']["$tmp_key"] = $value;
            }
        }

        $this->json($res)->output();
        die;
    }

    /**
     * 获取返回结构体
     * @return BaseResponse|string
     */
    public function getApiResponse()
    {
        if (!$this->myResponse) {
            $this->myResponse = new BaseResponse();
        }
        return $this->myResponse;
    }

    /**
     * 获取描述
     * @param int $status
     * @param string $default
     * @param array $param
     * @return mixed|string
     */
    public function getDescription($status, $default = '', $param = array())
    {
        if (Application::environment() == 'develop') {
            $prefix = 'DESCRIPTION_';
        } else {
            $prefix = 'REAL_DESCRIPTION_';
        }

        $description = defined($prefix . $status) ? constant($prefix . $status) : ($default ? $default : constant($prefix . STATUS_NOSTATUS));

        if (strpos($description, '%') && $param) {
            $description = vsprintf($description, $param);
        }

        return $description;
    }
}