<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/22
 * Time: 11:14
 */

namespace Emilia\session;

use Emilia\config\Config;

class Session
{
    /**
     * 会话初始配置
     *
     * @author lzl
     *
     * @param string $sessionId 会话id
     * @param array $options 会话配置
     *
     */
    public static function init($sessionId, $options = array())
    {
        $config = Config::getConfig('session');

        $config = array_merge($config, $options);

        // see: http://php.net/manual/zh/session.configuration.php
        if (!empty($config['name'])) {
            session_name($config['name']);
        }

        if (!empty($sessionId)) {
            session_id($sessionId);
        } elseif (!empty($config['name'])) {
            isset($_COOKIE[$config['name']]) && session_id($_COOKIE[$config['name']]);
        }

        if (isset($config['path'])) {
            session_save_path($config['path']);
        }

        if (isset($config['expire'])) {
            ini_set('session.gc_maxlifetime', $config['expire']);
            ini_set('session.cookie_lifetime', $config['expire']);
        }

        if (isset($config['type'])) {

            $handlerName = __NAMESPACE__ . '\\handlers\\' . ucfirst($config['type']) . 'SessionHandler';

            class_exists($handlerName) && session_set_save_handler(new $handlerName());
        }

        // see: http://php.net/manual/zh/function.session-set-save-handler.php
        if (PHP_VERSION_ID >= 50400) {
            session_register_shutdown();
        } else {
            register_shutdown_function('session_write_close');
        }
    }

    /**
     * 会话开启
     *
     * @author lzl
     *
     * @param string $sessionId 会话id
     * @param array $options 会话配置
     *
     */
    public static function start($sessionId = '', $options = array())
    {
        self::init($sessionId, $options);

        if (PHP_SESSION_ACTIVE != session_status()) {
            session_start();
        }
    }

    /**
     * 获取session
     *
     * @author lzl
     *
     * @param string $name
     *
     * @return string
     *
     */
    public static function get($name)
    {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : '';
    }

    /**
     * 设置session
     *
     * @author lzl
     *
     * @param string $name
     * @param int|string $value
     *
     */
    public static function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * 获取session_id
     *
     * @author lzl
     *
     * @return string
     */
    public static function sessionId()
    {
        return session_id();
    }


    /**
     * 清除session
     *
     * @author lzl
     *
     * @param string $name
     */
    public static function clear($name = null)
    {
        if ($name) {
            unset($_SESSION[$name]);
        } else {
            $_SESSION = [];
        }
    }
}