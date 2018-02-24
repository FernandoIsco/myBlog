<?php
/**
 * Created by PhpStorm.
 * User: 李作霖 QQ: 604625124
 * Date: 2017/7/9
 * Time: 15:23
 */

return [
    /*
    |--------------------------------------------------------------------------
    | environments 环境设置
    |--------------------------------------------------------------------------
    |
    | product, testing, develop 为基础环境设置选项。
    | 变更对应布尔值切换不同环境。当多个为true，以最后一个为准，建议product放最后一个。
    | 可以根据需要，自定义扩展设置。例如不同环境引入不同配置文件
    |
    */
    'environments' => array(
        'develop' => true,

        'testing' => false,

        'product' => false,
    ),

    /*
    |--------------------------------------------------------------------------
    | filter 自定义参数过滤方法
    |--------------------------------------------------------------------------
    |
    | 自定义参数过滤。
    |
    */
    'filter' => array('trim', 'addslashes'),

    /*
    |--------------------------------------------------------------------------
    | 默认控制器和方法
    |--------------------------------------------------------------------------
    |
    | 统一默认控制器和方法。
    | 在区分模块时，某模块没有重新定义defaultsAction，将使用该配置
    |
    */
    'defaultsAction' => array(
        'controller' => 'site',

        'action' => 'index',
    ),

    /*
    |--------------------------------------------------------------------------
    | module 模块
    |--------------------------------------------------------------------------
    |
    | division  bool, 是否区分多模块,false下面设置无效
    | modules   array, 设置有哪些模块，小驼峰命名
    | default   string, 默认模块
    | deny      array, 拒绝直接访问的模块
    | defaultsAction  array, 区分模块时有效。重新定义默认控制器和方法
    |
    */
    'modules' => array(
        'division' => false,

        'modules' => array(
            'index', 'admin', 'api', 'wap'
        ),

        'default' => 'index',

        'deny' => array(),

        'defaultsAction' => array(
            'index' => array(
                'controller' => 'site',
                'action' => 'index',
            ),
        ),
    ),

    /*
    |--------------------------------------------------------------------------
    | view 自定义视图布局
    |--------------------------------------------------------------------------
    |
    | header    string, 头部共用模板
    | leftBar   string, 左部共用模板
    | rightBar  string, 右部共用模板
    | footer    string, 底部共用模板
    |
    | header、leftBar、rightBar、footer键值名称可以替换，其对应的值为模板路径
    | 在主模版上使用$this->header()即可。
    | 也可以自行添加共用模板部分，使用方法同上。
    |
    */
    'views' => array(
        'header' => 'index/common/header.html',

        'leftBar' => '',

        'rightBar' => '',

        'footer' => '',
    ),

    /*
    |--------------------------------------------------------------------------
    | session 会话管理
    |--------------------------------------------------------------------------
    |
    | 公共参数：
    | type   string  定义存储和获取与会话关联的数据的处理器的名字，不填默认session文件处理器。
    |                具体查看：http://php.net/manual/zh/session.configuration.php#ini.session.save-handler
    | name   string  指定会话名以用做 cookie 的名字。只能由字母数字组成，默认为 PHPSESSID。
    | expire int     会话过期时间。默认1440秒
    |
    | 各处理器参数
    | file:
    |       path   string  创建文件的路径。当选择了session文件处理器，默认为/tmp；自定义file文件处理器，默认为根目录/../storage/temp
    |
    | PDO:
    |       table  string  数据表名称
    |       schema array   表结构字段，只允许设置session_id，session_data，last_modify对应的表结构字段。
    |                      其余字段值请使用PDOSessionHandler::setData
    */
    'session' => array(
        // common
        'type' => 'PDO',

        'expire' => 1440,

        'name' => '',

        // file
        'path' => APP_STORAGE_PATH . 'temp/',

        // PDO
        'table' => 'session',

        'schema' => array(
            'session_id' => 'session_id',
            'session_data' => 'session_data',
            'last_modify' => 'last_modify'
        ),
    )
];