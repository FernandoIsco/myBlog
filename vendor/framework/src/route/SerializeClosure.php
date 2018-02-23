<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/6/29
 * Time: 21:48
 */

namespace Emilia\route;


class SerializeClosure
{
    /**
     * @var \Closure 闭包
     */
    private $closure;

    /**
     * @var object 闭包反射
     */
    private $reflection;

    /**
     * @var array 闭包序列化前格式
     */
    private $closureFormat = ['code' => '', 'param' => ''];

    /**
     * SerializeClosure constructor.
     * 架构函数
     *
     * @param \Closure|null $closure
     */
    public function __construct(\Closure $closure = null)
    {
        $this->closure = $closure;
    }

    /**
     * 设置要处理的闭包
     *
     * @author lzl
     *
     * @param \Closure $closure 闭包
     *
     * @return $this
     */
    public function setClosure(\Closure $closure)
    {
        $this->closure = $closure;

        return $this;
    }

    /**
     * 检查闭包类型
     *
     * @author lzl
     *
     * @return bool
     */
    public function checkType()
    {
        return ($this->closure instanceof \Closure);
    }

    /**
     * 检查闭包反序列化后格式是否合法
     *
     * @author lzl
     *
     * @param array $serialized 闭包反序列化后
     *
     * @return array
     */
    public function checkFormat($serialized)
    {
        return array_diff_key($this->closureFormat, $serialized);
    }

    /**
     * 初始化对象
     *
     * @author lzl
     */
    public function init()
    {
        $this->reflection = null;
    }

    /**
     * 获取闭包的反射类
     *
     * @author lzl
     */
    private function reflectionFunc()
    {
        empty($this->reflection) && $this->reflection = new \ReflectionFunction($this->closure);
    }

    /**
     * 获取闭包字符串，并组装合法数据
     *
     * @author lzl
     *
     * @return array
     * @throws \Exception
     */
    public function getSerializeFormat()
    {
        if (!$this->checkType()) {
            throw new \Exception('it is not closure');
        }

        $this->init();
        $this->reflectionFunc();

        $this->closureFormat['code'] = $this->pickTokenToCode($this->shearingToToken());
        $this->closureFormat['param'] = $this->getStaticParam();

        return $this->closureFormat;
    }

    /**
     * 转化为闭包
     *
     * @author lzl
     *
     * @param array $serialized 闭包反序列化后的数组
     *
     * @return string
     */
    public function transClosure($serialized)
    {
        $closure = '';

        eval("{$serialized['param']};\$closure = {$serialized['code']};");

        return $closure;
    }

    /**
     * 序列化闭包
     *
     * @author lzl
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->getSerializeFormat());
    }

    /**
     * 反序列化闭包
     *
     * @author lzl
     *
     * @param string $serialized 闭包序列化字符串
     *
     * @return string
     * @throws \Exception
     */
    public function unSerialize($serialized)
    {
        $serialized = @unserialize($serialized);

        if ($serialized == false) {
            throw new \Exception('The closure could not be unSerialized.');
        }

        if ($this->checkFormat($serialized)) {
            throw new \Exception('unSerialize value format is not legal');
        }

        return $this->transClosure($serialized);
    }

    /**
     * 获取闭包字符串，并做词法解析
     *
     * @author lzl
     *
     * @return array
     */
    private function shearingToToken()
    {
        $file = new \SplFileObject($this->reflection->getFileName());

        $file->seek($this->reflection->getStartLine() - 1);

        $code = '';
        while ($file->key() < $this->reflection->getEndLine()) {
            $code .= $file->current();
            $file->next();
        }

        $code = trim($code);
        if (strpos($code, '<?php') !== 0) {
            $code = "<?php\n" . $code;
        }

        return token_get_all($code);
    }

    /**
     * 获取闭包字符串
     *
     * @author lzl
     *
     * @param array $tokens
     *
     * @return string
     */
    private function pickTokenToCode($tokens)
    {
        $funcStart = $bodyStart = $start = 0;

        $code = '';
        $tokenObj = new Token();
        foreach ($tokens as $token) {

            $tokenObj->setToken($token);

            if ($tokenObj->isFunc()) {
                $funcStart++;
                $code .= $tokenObj->getCode();
                continue;
            }

            if ($funcStart > 0) {

                $code .= $tokenObj->getCode();

                if ($tokenObj->isBodyStart()) {
                    $bodyStart++;
                    $start++;
                }

                if ($tokenObj->isBodyEnd()) {

                    $bodyStart--;
                }

                if ($start && $bodyStart == 0) {
                    break;
                }
            }

        }

        return $code;
    }

    /**
     * 获取闭包参数
     *
     * @author lzl
     *
     * @return string
     */
    private function getStaticParam()
    {
        $params = $this->reflection->getStaticVariables();

        $string = '';
        foreach ($params as $key => $param) {
            $string .= "\${$key}='{$param}';";
        }

        return $string;
    }
}