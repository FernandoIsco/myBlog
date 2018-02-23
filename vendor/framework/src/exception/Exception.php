<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/7/9
 * Time: 17:34
 */

namespace Emilia\exception;

use ErrorException;
use Emilia\log\Logger;

class Exception
{
    /**
     * 注册自定义错误处理程序
     *
     * @param string $environment 环境
     *
     */
    public static function bootstrap($environment)
    {
        error_reporting(-1);

        set_error_handler([__CLASS__, 'errorHandler']);

        set_exception_handler([__CLASS__, 'exceptionHandler']);

        register_shutdown_function([__CLASS__, 'shutdownException']);

        if ($environment != 'testing') {
            ini_set('display_errors', 'Off');
        }
    }

    /**
     * 设置自定义错误处理程序
     *
     * @param int $errorNo 错误编号
     * @param string $errorMsg 错误信息
     * @param string $errorFile 错误文件
     * @param int|number $errorLine 错误行数
     *
     * @throws ErrorException
     *
     */
    public static function errorHandler($errorNo, $errorMsg, $errorFile = '', $errorLine = 0)
    {
        if (error_reporting() & $errorNo) {
            $exception = new ErrorException($errorMsg, 0, $errorNo, $errorFile, $errorLine);

            throw $exception;
        }
    }

    /**
     * 设置异常处理程序
     *
     * @param \Exception $e
     *
     */
    public static function exceptionHandler($e)
    {
        if (!$e instanceof \Exception) {
            $e = new ThrowableException($e);
        }

        if ($e instanceof \Exception) {
            echo "<b>Custom error occurred：</b>";
            echo "Exception Type: " . get_class($e) . "<br>";
            echo "Error Message: [" . $e->getCode() . "] {$e->getMessage()}<br>";
            echo "Error on line {$e->getLine()} in {$e->getFile()}<br>";
        }

        Logger::record($e);
    }

    /**
     * 设置PHP终止处理程序
     *
     */
    public static function shutdownException()
    {
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            $exception = new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);

            self::exceptionHandler($exception);
        }
    }

    /**
     * 是否致命错误
     *
     * @param int $type 错误类型
     *
     * @return bool
     */
    protected static function isFatal($type)
    {
        return in_array($type, array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE));
    }
}