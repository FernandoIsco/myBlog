<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/19
 * Time: 17:26
 */


use Emilia\database\DbManager;
use Emilia\testing\TestCase;

require_once '../src/testing/TestCase.php';

class DbTest extends TestCase
{
    public $db;

    public $recordId;

    public $config = array(
        'driver' => 'mysql',

        'host' => '127.0.0.1',

        'database' => 'blog',

        'port' => '3306',

        'username' => 'root',

        'password' => '123456',

        'charset' => 'utf8',

        'collation' => 'utf8_unicode_ci',

        'prefix' => '',

        'engine' => null,
    );

    protected function setUp()
    {
        $this->initEnv();

        empty($this->db) && $this->db = (new DbManager())->establishQuery($this->config);
    }

    public function testInDatabase()
    {
        $this->db->setTable('blogs')->insert(array(
            'user_id'   => '1',
            'title'     => 'testInDatabase',
            'summary'   => 'testInDatabase',
            'content'   => 'testInDatabase',
            'created_at'=> date('Y-m-d H:i:s'),
        ));
        $recordId = $this->db->getLastId();

        $actual = $this->db->setTable('blogs')->count(array('id' => $recordId));
        $this->assertGreaterThan(0, $actual);
    }

    public function testNotInDatabase()
    {
        $actual = $this->db->setTable('blogs')->count(array('id' => 0));
        $this->assertEquals(0, $actual);
    }

    public function testInsert()
    {
        $actual = $this->db->setTable('blogs')->insert(array(
            'user_id'   => '1',
            'title'     => 'testInsert',
            'summary'   => 'testInsert',
            'content'   => 'testInsert',
            'created_at'=> date('Y-m-d H:i:s'),
        ));
        $this->assertEquals(1, $actual);
    }

    public function testUpdate()
    {
        $this->db->setTable('blogs')->insert(array(
            'user_id'   => '1',
            'title'     => 'testUpdate',
            'summary'   => 'testUpdate',
            'content'   => 'testUpdate',
            'created_at'=> date('Y-m-d H:i:s'),
        ));
        $recordId = $this->db->getLastId();

        $title = 'world';
        $actual = $this->db->setTable('blogs')->update(array(
            'title' => $title,
        ), array(
            'id' => $recordId
        ));
        $this->assertEquals(1, $actual);
    }

    public function testDelete()
    {
        $this->db->setTable('blogs')->insert(array(
            'user_id'   => '1',
            'title'     => 'testDelete',
            'summary'   => 'testDelete',
            'content'   => 'testDelete',
            'created_at'=> date('Y-m-d H:i:s'),
        ));
        $recordId = $this->db->getLastId();

        $actual = $this->db->setTable('blogs')->delete(array(
            'id' => $recordId
        ));
        $this->assertEquals(1, $actual);
    }
}