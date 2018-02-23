<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/16
 * Time: 15:10
 */

namespace tests\autoload;

use Emilia\testing\TestCase;

require_once '../src/testing/TestCase.php';
require_once './autoload/MockAutoload.php';

class AutoloadTest extends TestCase
{
    public $loader;

    public $basePath;

    public $autoloadTestPath;

    protected function setUp()
    {
        $this->basePath = dirname(__DIR__);

        $this->autoloadTestPath = $this->basePath . '/autoload';

        $this->loader = new MockAutoload();

        $files = array(
            $this->autoloadTestPath . '/src/ClassName.php',
            $this->autoloadTestPath . '/tests/ClassNameTest.php',
        );

        $this->loader->setFiles($files);

        $this->loader->addDirectory('tests', $this->basePath);
        $this->loader->addDirectory('autoloadTest', $this->autoloadTestPath . '/src/');
        $this->loader->addDirectory('autoloadTest', $this->autoloadTestPath . '/tests/');
    }

    public function testExistingFile()
    {
        $actual = $this->loader->loader('autoloadTest\ClassName');
        $expect = $this->autoloadTestPath . '/src/ClassName.php';
        $this->assertSame($expect, $actual);

        $actual = $this->loader->loader('autoloadTest\ClassNameTest');
        $expect = $this->autoloadTestPath . '/tests/ClassNameTest.php';
        $this->assertSame($expect, $actual);

        $actual = $this->loader->loader('tests\autoload\src\ClassName');
        $expect = $this->autoloadTestPath . '/src/ClassName.php';
        $this->assertSame($expect, $actual);

        $actual = $this->loader->loader('tests\autoload\tests\ClassNameTest');
        $expect = $this->autoloadTestPath . '/tests/ClassNameTest.php';
        $this->assertSame($expect, $actual);
    }

    public function testMissingFile()
    {
        $actual = $this->loader->loader('tests\autoload\src\ClassNameTest');
        $this->assertFalse($actual);
    }
}