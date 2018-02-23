<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/10/3
 * Time: 09:42
 */

namespace Emilia\http\response;


use Emilia\http\Response;

class Json extends Response
{
    /**
     * @var string 响应JSON格式
     */
    protected $contentType = 'application/json';

    /**
     * @var array 输出参数
     */
    protected $options = array(
        'json_encode_param' => JSON_UNESCAPED_UNICODE,
    );

    /**
     * Json constructor.
     * 架构函数
     *
     * @param array $data 输出数据
     * @param array $options 额外参数
     * @param int   $code 状态码
     * @param array $header 头部信息
     *
     */
    public function __construct(array $data, array $options, $code, array $header)
    {
        $this->contentType($this->contentType, $this->charset);
        parent::__construct($data, $options, $code, $header);
    }

    /**
     * 获取json数据
     *
     * @author lzl
     *
     * @return string
     * @throws \Exception
     */
    public function getData()
    {
        $data = json_encode($this->data, $this->options['json_encode_param']);

        if ($data === false) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        return $data;
    }
}