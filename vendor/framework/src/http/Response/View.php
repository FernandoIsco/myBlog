<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/10/2
 * Time: 16:39
 */

namespace Emilia\http\response;


use Emilia\http\Response;
use Emilia\mvc\View as viewTemplate;

class View extends Response
{
    /**
     * 模板输出
     *
     * @author lzl
     *
     */
    public function getData()
    {
        $view = new viewTemplate();

        foreach ($this->options as $key => $option) {
            $view->assign($key, $option);
        }

        $view->display($this->data['template']);
    }
}