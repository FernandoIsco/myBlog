<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/10/17
 * Time: 20:43
 */

namespace Emilia\config;


class Config
{
    /**
     * @var array 配置
     */
    private static $configures = array();

    /**
     * 获取配置
     *
     * @author lzl
     *
     * @param string $name 配置参数名
     * @param mixed|string $default 默认值
     *
     * @return mixed|string 配置值
     */
    public static function getConfig($name, $default = '')
    {
        $name = explode('.', $name);

        $configure = self::$configures;
        foreach ($name as $value) {
            if (isset($configure[$value])) {
                $configure = $configure[$value];
            } else {
                return $default;
            }
        }

        return $configure;
    }

    /**
     * 加载配置文件
     *
     * @author lzl
     *
     * @param string $module 模块名称
     *
     * @return bool
     */
    public static function loadConfig($module = '')
    {
        if (isset(self::$configures['modules']['division'])) {

            if (!self::$configures['modules']['division'] || !$module) {
                return false;
            }

            $dirName = CONFIG_PATH . $module . DS;
        } else {
            $dirName = CONFIG_PATH;
        }

        if (is_dir($dirName)) {
            if ($dir = opendir($dirName)) {
                while (($file = readdir($dir)) != false) {
                    !in_array($file, ['.', '..']) && self::readConfigFile($dirName . $file);
                }

                closedir($dir);
            }
        }

        return true;
    }

    /**
     * 读取配置文件
     *
     * @author lzl
     *
     * @param string $filename 配置文件
     *
     * @return bool
     */
    private static function readConfigFile($filename)
    {
        $configs = array();

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        switch ($extension) {
            case 'php':
                $configs = include $filename;
                break;
            case 'xml':
                $configs = simplexml_load_file($filename);
                break;
        }

        $configs = json_decode(json_encode($configs, JSON_UNESCAPED_UNICODE), true);

        if (!is_array($configs) || empty($configs)) {
            return false;
        }

        foreach ($configs as $name => $config) {
            self::$configures[$name] = $config;
        }

        return true;
    }
}