<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/6/29
 * Time: 21:46
 */

namespace Emilia;


use Emilia\config\Config;
use Emilia\exception\ApplicationException;
use Emilia\exception\Exception;
use Emilia\http\Request;
use Emilia\http\Response;
use Emilia\route\Router;

class Application
{
    /**
     * 程序运行
     *
     * @author lzl
     *
     * @param Request $request
     *
     * @return Response
     */
    public function run(Request $request = null)
    {
        // 配置加载
        Config::loadConfig();

        Hook::runtime();

        // 注册异常处理
        Exception::bootstrap(self::environment());

        $request = $request ?: Request::instance();

        // 跨域检测
        $cors = Config::getConfig('CORS');
        if ($request->getMethod() == 'options') return Response::instance('', array(), 200, $cors); // TODO 了解下laravel和thinkphp是怎么做的

        // 路由解析
        $response = (new Router($request))->resolveRoute();

        // 如果指向闭包，直接返回
        if ($response != null) return ($response instanceof Response) ? $response : Response::instance($response);

        // 执行控制器方法，返回执行结果
        Config::loadConfig($request->getModule());
        $controller = $request->getController();
        $action = $request->getAction();

        $response = (new $controller)->$action();
        return ($response instanceof Response) ? $response : Response::instance($response);
    }

    /**
     * 获取运行环境
     *
     * @author lzl
     *
     * @return string 'develop|testing|product'
     */
    public static function environment()
    {
        $environments = Config::getConfig('environments');

        $env = 'product';
        foreach ($environments as $environment => $flag) {
            $flag && $env = $environment;
        }

        return $env;
    }
}