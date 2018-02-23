<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/23
 * Time: 9:39
 */

use Emilia\testing\TestCase;
use Emilia\Application;
use Emilia\http\Request;
use Emilia\route\RouteContainer;
use Emilia\route\Route;

require_once '../src/testing/TestCase.php';

class ResponseTest extends TestCase
{
    public $app;

    public $closureResponse = 'testRoute';

    protected function setUp()
    {
        $this->initEnv();

        $closureResponse = $this->closureResponse;
        $container = RouteContainer::instance('routeTestCache', __DIR__ . '/routes');
        $container->getRoutes(function () use ($closureResponse) {
            Route::get('/', function () use ($closureResponse) { return $closureResponse; });
            Route::get('/site/index', 'site/index');
        });

        empty($this->app) && $this->app = new Application();
    }

    public function testClosureResponse()
    {
        try {
            $actual = $this->app->run(Request::create($this->host . '/', 'GET'))->getData();
        } catch (Exception $exception) {
            $actual = $exception->getMessage();
        }

        $this->assertEquals($this->closureResponse, $actual);
    }

    public function testViewResponse()
    {
        try {
            $actual = $this->app->run(Request::create($this->host . '/site/index'))->getData();
        } catch (Exception $exception) {
            $actual = $exception->getMessage();
        }

        $this->assertRegExp('/FRAMEWORK/', $actual);
    }
}