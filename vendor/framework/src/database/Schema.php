<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28
 * Time: 20:46
 */

namespace Emilia\database;


class Schema
{
    public function __construct($schema)
    {
        parent::__construct();

        //SELECT DISTINCT t.table_name, n.SCHEMA_NAME FROM information_schema.TABLES t, information_schema.SCHEMATA n WHERE t.table_name = 'tableName' AND n.SCHEMA_NAME = 'databaseName';
    }
}