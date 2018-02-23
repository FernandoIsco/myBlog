<?php
/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/6/24
 * Time: 08:45
 */

use Emilia\route\Route;

Route::get('/index/index', function () {
    return 'welcome to index page';
});