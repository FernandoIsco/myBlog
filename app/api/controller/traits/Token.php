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

        if (empty($params['sign'])) {
            return false;
        }

        if (empty($params['identity'])) {
            return false;
        }

        $tmpParams = array_intersect_key($params, array_flip($this->common));

        if (empty($tmpParams)) {
            return false;
        }

        $arr = array();
        foreach ($tmpParams as $key => $value) {
            $k = array_search($key, $this->common);
            $arr[$k] = $value;
        }

        $this->identity = $params['identity'];
        return $params['sign'] == $this->getSign($arr);
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