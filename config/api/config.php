<?php
/**
 * 临时配置
 */

return array(
    // 是否允许多端登录
    'multi_login' => false,

    // 跨域配置
    'cors' => array(
        'origins' => array(
            '*'
        ),
        'methods' => array(
            'HEAD', 'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'DELETE', 'OPTIONS'
        ),
        'headers' => array(
            'Content-Type', 'Content-Length', 'Authorization', 'Accept', 'X-Requested-With', 'Token', 'X_REQUESTED_WITH'
        )
    ),

    // 签名验证，身份配置
    'sign' => array(
        'validate' => true,

        'keys' => array(
            'fernando' => 'fernando'
        )
    ),
);