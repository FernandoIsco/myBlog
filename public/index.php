<?php
require __DIR__ . '/../config/const.php';

require __DIR__ . '/../vendor/framework/src/foundation/Autoload.php';

require __DIR__ . '/../vendor/framework/src/foundation/Application.php';

// 注册自动加载方法
(new \Emilia\Autoload())->register();

$app = new \Emilia\Application();
$app->run()->output();