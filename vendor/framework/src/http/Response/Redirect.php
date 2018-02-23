<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/10/3
 * Time: 09:57
 */

namespace Emilia\http\response;


use Emilia\http\Response;
use Emilia\route\Uri;

class Redirect extends Response
{

    /**
     * Redirect constructor.
     * 架构函数
     *
     * @param string $url 路径
     * @param array $options 额外参数
     * @param int   $code 状态码
     * @param array $header 头部信息
     *
     */
    public function __construct($url, array $options, $code, array $header)
    {
        parent::__construct($url, $options, 302, $header);
        $this->cacheControl('no-cache,must-revalidate');
    }

    /**
     * 获取跳转路径
     *
     * @author lzl
     *
     */
    public function getData()
    {
        if (is_string($this->data)){
            $url = $this->data;
        } elseif (is_array($this->data) && !empty($this->data['url'])) {
            $url = $this->data['url'];
        } else {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL. Has no key named "url" in array or wrong data format given');
        }

        if ($this->code != 302) {
            throw new \InvalidArgumentException(sprintf('The HTTP status code is not a redirect ("%s" given).', $this->code));
        }

        if (!empty($this->options)) {
            $url = $url . '?' . http_build_query(array_map('urlencode', $this->options));
        }

        $this->header['Location'] = $url;
        return;
    }
}