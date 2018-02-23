<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/19
 * Time: 16:02
 */

use Emilia\testing\TestCase;

require_once '../src/testing/TestCase.php';
require_once '../src/http/Request.php';

class RequestTest extends TestCase
{
    public $request;

    public $uri = 'site/index?a=2&b=2';

    public $method = 'post';

    public $post = array(
        'c' => 3,
        'd' => 4
    );

    public $cookie = array(
        'cookieName' => 'cookieName'
    );

    public function setUp()
    {
        $this->request = \Emilia\http\Request::create($this->host . '/' . $this->uri, $this->method, $this->post, $this->cookie);
    }

    public function testGetVars()
    {
        $actual = $this->request->fromGet('a');
        $expect = 2;
        $this->assertEquals($expect, $actual);
    }

    public function testPostVars()
    {
        $actual = $this->request->fromPost(key($this->post));
        $expect = reset($this->post);
        $this->assertEquals($expect, $actual);
    }

    public function testCookiesVars()
    {
        $actual = $this->request->cookie(key($this->cookie));
        $expect = reset($this->cookie);
        $this->assertEquals($expect, $actual);
    }

    public function testRequestMethod()
    {
        $actual = $this->request->getMethod();
        $expect = $this->method;
        $this->assertEquals($expect, $actual);
    }

    public function testUri()
    {
        $actual = $this->request->getUri();
        $url = parse_url($this->uri);
        $expect = '/' . trim($url['path'], '/');
        $this->assertEquals($expect, $actual);
    }
}