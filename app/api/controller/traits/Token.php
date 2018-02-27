<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/24
 * Time: 16:46
 */

namespace app\api\controller\traits;



use Emilia\config\Config;

trait Token
{
    private $identity;

    private $session;

    private $common = array(
        'n' => 'namespace',
        's' => 'session',
        'i' => 'identity',
        'sr' => 'source',
        'si' => 'sign',
        't' => 'lastTimestamp',
        'q' => 'query'
    );

    /**
     * 签名验证
     *
     * @param mixed $request
     * @return bool
     */
    public function validateToken($request)
    {
        if (is_object($request)) {
            $params = json_decode(json_encode($request), true);
        } else {
            $params = (array) $request;
        }

        $tmpParams = array();
        foreach ($params as $key => $value) {
            isset($this->common[$key]) && $tmpParams[$this->common[$key]] = $value;
        }

        if (empty($tmpParams['sign'])) {
            return false;
        }

        if (empty($tmpParams['identity'])) {
            return false;
        }

        $this->identity = $tmpParams['identity'];
        unset($params[array_search('sign', $this->common)]);
        return $tmpParams['sign'] == $this->getSign($params);
    }

    /**
     * 获取签名
     *
     * @param array $params
     * @return string
     */
    public function getSign($params)
    {
        $keys = Config::getConfig('keys');
        file_put_contents('./token.txt', $this->assemble($params). "\r\n", FILE_APPEND);
        return strtoupper(md5($this->assemble($params) . $keys[$this->identity]));
    }

    /**
     * 返回签名数据
     *
     * @param array $params
     * @return string
     */
    public function assemble($params)
    {
        ksort($params, SORT_STRING);

        $sign = '';
        foreach ($params as $key => $param) {
            $sign .= $key . (is_array($param) ? $this->assemble($param) : $param);
        }

        return $sign;
    }

    /**
     * 获取身份
     *
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }
}