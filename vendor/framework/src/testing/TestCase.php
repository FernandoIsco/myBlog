<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/22
 * Time: 14:23
 */

namespace Emilia\testing;

use PHPUnit_Framework_TestCase;
use Emilia\Autoload;
use Emilia\config\Config;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    public $host = 'http://localhost';

    public $basePath;

    private $init = false;

    public function initEnv()
    {
        if (!$this->init) {
            $this->defineRootPath();

            require_once ROOT_PATH . 'config/' . 'const.php';
            require_once FRAMEWORK_PATH . 'foundation/Autoload.php';

            $loader = new Autoload();
            spl_autoload_register(array($loader, 'loader'));
            $loader->addDirectory('tests\\', ROOT_PATH . 'vendor/framework/tests' . DS);
            $loader->addDirectory('app\\', APP_PATH);
            $loader->addDirectory('Emilia\\', ROOT_PATH . 'vendor/framework/src/foundation' . DS);
            $loader->addDirectory('Emilia\\', ROOT_PATH . 'vendor/framework/src' . DS);

            Config::loadConfig();

            $this->init = true;
        }
    }

    public function defineRootPath()
    {
        if (PHP_VERSION_ID >= 70000) {
            $this->basePath = dirname(__DIR__, 4);
        } else {
            $basePath = array_reverse(explode('/', __DIR__));

            $this->basePath = implode("/", array_reverse(array_slice($basePath, 4)));
        }

        defined('ROOT_PATH') || define('ROOT_PATH', $this->basePath . '/');
    }
}