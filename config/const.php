<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/7/9
 * Time: 11:25
 */

// 目录分隔符
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

// 根目录
defined('ROOT_PATH') || define('ROOT_PATH', dirname(dirname($_SERVER['SCRIPT_FILENAME'])) . DS);

// 开发应用路径
defined('APP_PATH') || define('APP_PATH', ROOT_PATH . 'app' . DS);

// 系统路径
defined('VENDOR_PATH') || define('VENDOR_PATH', ROOT_PATH . 'vendor' . DS);

// 框架系统路径
defined('FRAMEWORK_PATH') || define('FRAMEWORK_PATH', VENDOR_PATH . 'framework' . DS . 'src' . DS);

// 框架配置文件路径
defined('CONFIG_PATH') || define('CONFIG_PATH', ROOT_PATH . 'config' . DS);

// 框架路由配置路径
defined('ROUTES_PATH') || define('ROUTES_PATH', ROOT_PATH . 'routes' . DS);

// 框架静态资源存放路径
defined('RESOURCES_PATH') || define('RESOURCES_PATH', ROOT_PATH . 'resources' . DS);

// 框架视图存放路径
defined('VIEW_PATH') || define('VIEW_PATH', RESOURCES_PATH . 'views' . DS);

// 框架缓存存放路径
defined('STORAGE_PATH') || define('STORAGE_PATH', ROOT_PATH . 'storage' . DS);

// 系统级别设置的缓存路径
defined('APP_STORAGE_PATH') || define('APP_STORAGE_PATH', STORAGE_PATH . 'app' . DS);

// 路由缓存路径
defined('ROUTES_STORAGE_PATH') || define('ROUTES_STORAGE_PATH', APP_STORAGE_PATH . 'routes' . DS);

// 日志路径
defined('LOG_PATH') || define('LOG_PATH', STORAGE_PATH . 'log' . DS);