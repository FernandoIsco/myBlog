<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/6/29
 * Time: 21:46
 */

namespace Emilia;




class Autoload
{
    /**
     * @var string 文件后缀名称
     */
    protected static $extension = 'php';

    /**
     * @var array 存放命名空间数组
     */
    protected static $namespace = array();

    /**
     * @var bool 是否已添加系统命名空间
     */
    protected $hasAdded = false;

    /**
     * 注册自动加载方法
     *
     * @param string $function 自动加载方法
     */
    public function register($function = '')
    {
        $function = $function ?: array($this, 'loader');

        spl_autoload_register($function);

        if (!$this->hasAdded) {
            $this->hasAdded = true;

            $this->addSysDirectory();
            $this->addAppDirectory();
        }
    }

    /**
     * 卸载自动加载方法
     *
     * @param string $function 自动加载方法
     */
    public function unregister($function = '')
    {
        $function = $function ?: array($this, 'loader');

        spl_autoload_unregister($function);
    }

    /**
     * 命名空间和路径的映射关系
     *
     * @param string $namespace 命名空间
     * @param string $directory 路径
     * @param bool   $prepend   如果为真，将会在路径数组头部添加
     */
    public function addDirectory($namespace, $directory = '', $prepend = false)
    {
        $namespace = $this->normalizeNamespace($namespace);
        $directory = $this->normalizeDirectory($directory);

        if (!isset(self::$namespace[$namespace])) {
            self::$namespace[$namespace] = array();
        }

        if ($prepend) {
            array_unshift(self::$namespace[$namespace], $directory);
        } else {
            array_push(self::$namespace[$namespace], $directory);
        }
    }

    /**
     * 自动加载文件
     *
     * @param string $class 类名
     *
     * @return bool|string
     */
    public function loader($class)
    {
        $prefix = $class;

        while (false !== $pos = strrpos($prefix, '\\')) {

            $prefix = substr($class, 0, $pos + 1);

            $className = substr($class, $pos + 1);

            if (isset(self::$namespace[$prefix])) {
                $directories = self::$namespace[$prefix];

                foreach ($directories as $directory) {
                    $file = $directory . $className . '.' . self::$extension;
                    $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);

                    if($this->requireFile($file)) {
                        return $file;
                    }
                }
            }

            $prefix = rtrim($prefix, '\\');
        }

        return false;
    }

    /**
     * 添加系统命名空间
     *
     */
    public function addSysDirectory()
    {
        $this->addDirectory('Emilia\\', FRAMEWORK_PATH . 'foundation' . DS);
        $this->addDirectory('Emilia\\', FRAMEWORK_PATH);
        $this->addDirectory('app\\',  APP_PATH);
    }

    /**
     * 添加用户自定义命名空间
     *
     */
    public function addAppDirectory()
    {
        $classMap = include_once ROOT_PATH . 'config/autoload.php';

        if ($classMap && is_array($classMap)) {
            array_map(function ($k, $v) {
                $this->addDirectory($k, $v, false);
            }, array_keys($classMap), array_values($classMap));
        }
    }

    /**
     * 规范化命名空间格式
     *
     * @param string $namespace 命名空间
     *
     * @return string
     */
    public function normalizeNamespace($namespace)
    {
        return trim($namespace, '\\') . '\\';
    }

    /**
     * 规范化路径格式
     *
     * @param string $directory 路径
     *
     * @return string
     */
    public function normalizeDirectory($directory)
    {
        return rtrim(rtrim($directory, '\\'), '/') . DIRECTORY_SEPARATOR;
    }

    /**
     * 试图引入文件
     *
     * @param string $file
     *
     * @return bool
     */
    public function requireFile($file)
    {
        if (is_file($file)) {
            require_once $file;
            return true;
        }

        return false;
    }

    /**
     * 获取已添加的命名空间
     *
     * @return array
     */
    public function getNamespace()
    {
        return self::$namespace;
    }

    /**
     * 设置自动加载文件后缀
     *
     * @param string $extension
     */
    public function setExtension($extension)
    {
        self::$extension = $extension;
    }

    /**
     * 获取自动加载文件后缀
     *
     * @return string
     */
    public function getExtension()
    {
        return self::$extension;
    }
}