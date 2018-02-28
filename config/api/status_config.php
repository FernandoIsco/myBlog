<?php
/**
 * Created by PhpStorm.
 * User: isco
 * Date: 2017/4/30
 * Time: 15:52
 */

/**
 * 公共 1
 */
/** 操作成功 @var 0 */
define('STATUS_SUCCESS', '0');
define('DESCRIPTION_0', '操作成功');
/** 未知错误 @var 1000 */
define('STATUS_UNKNOWN', '1000');
define('DESCRIPTION_1000', '未知错误');
define('REAL_DESCRIPTION_1000', '哎呀，您的网络打瞌睡啦！');
/** 协议版本过低，服务器已经不支持 @var 1001 */
define('STATUS_VERSION_LOW', '1001');
define('DESCRIPTION_1001', '协议版本过低，服务器已经不支持');
define('REAL_DESCRIPTION_1001', '您的版本过低啦，请尽快更新客户端！');
/** session id为空或不存在 @var 1002 */
define('STATUS_SESSION_EMPTY', '1002');
define('DESCRIPTION_1002', 'session id为空或不存在');
define('REAL_DESCRIPTION_1002', '哎呀，您的网络打瞌睡啦，请重新打开APP！' . '(1002)');
/** 验证码错误 @var 1003 */
define('STATUS_CAPTCHA_ERROR', '1003');
define('DESCRIPTION_1003', '验证码错误');
define('REAL_DESCRIPTION_1003', '验证码错误');
/** 请求参数不完整 @var 1004 */
define('STATUS_PARAMETERS_INCOMPLETE', '1004');
define('DESCRIPTION_1004', '请求参数不完整，缺少：%s');
define('REAL_DESCRIPTION_1004', '哎呀，您的网络打瞌睡啦，请重新打开APP！');
/** 没有获取设备号 @var 1005 */
define('STATUS_NO_DEVICETOKEN', '1005');
define('DESCRIPTION_1005', '没有获取设备号');
define('REAL_DESCRIPTION_1005', '哎呀，您的网络打瞌睡啦，请重新打开APP！');
/** 设备号已经被绑定 @var 1006 */
define('STATUS_DEVICE_BOUND', '1006');
define('DESCRIPTION_1006', '该设备已被绑定');
define('REAL_DESCRIPTION_1006', '哎呀，您的网络打瞌睡啦，请重新打开APP！');
/** 请求超时 @var 1010 */
define('STATUS_TIMEOUT', '1010');
define('DESCRIPTION_1010', '请求超时');
define('REAL_DESCRIPTION_1010', '哎呀，您的网络打瞌睡啦，请重新打开APP！');
/** 数据已删除 / 数据不存在 @var 1011 */
define('STATUS_NODATA', '1011');
define('DESCRIPTION_1011', '数据已删除 / 数据不存在');
define('REAL_DESCRIPTION_1011', '您访问的内容无法地球连接！');
/** session id 会话过期 @var 1012 */
define('STATUS_SESSION_TIMEOUT', '1012');
define('DESCRIPTION_1012', 'session id 会话过期');
define('REAL_DESCRIPTION_1012', '抱歉！请您重新登录账户！');
/** 未更新任何数据 @var 1013 */
define('STATUS_NOT_UPDATE', '1013');
define('DESCRIPTION_1013', '未更新任何数据');
define('REAL_DESCRIPTION_1013', '未更新任何数据');
/** 已经提交，不需要重复提交 @var 1014 */
define('STATUS_CAN_NOT_RESEND', '1014');
define('DESCRIPTION_1014', '已经提交，不需要重复提交');
define('REAL_DESCRIPTION_1014', '已经提交，不需要重复提交');
/** 短信发送失败 @var 1015 */
define('STATUS_SEND_SMSCODE_FAIL', '1015');
define('DESCRIPTION_1015', '短信发送失败');
define('REAL_DESCRIPTION_1015', '短信发送失败');
/** 数据包含敏感词汇 @var 1016 */
define('STATUS_SENSITIVE_WORD', '1016');
define('DESCRIPTION_1016', '数据包含敏感词汇');
define('REAL_DESCRIPTION_1016', '内容含有敏感词');
/** 安全验证不通过 @var 1017 */
define('STATUS_MD5', '1017');
define('DESCRIPTION_1017', '安全验证不通过');
define('REAL_DESCRIPTION_1017', '安全验证不通过');
/** 缓存数据可用 @var 1020 */
define('STATUS_CACHE_AVAILABLE', '1020');
define('DESCRIPTION_1020', '缓存数据可用');
define('REAL_DESCRIPTION_1020', '缓存数据可用');
/** 操作太快，请稍后再试 @var 1021 */
define('STATUS_TOO_FAST', '1021');
define('DESCRIPTION_1021', '操作太快，请稍后再试');
define('REAL_DESCRIPTION_1021', '操作太快，请稍后再试');


/**
 * 验证 8
 */
/** http请求方式不符合 @var 8000 */
define('STATUS_ERROR_REQUEST_METHOD', '8000');
define('DESCRIPTION_8000', 'http请求方式不符合');
define('REAL_DESCRIPTION_8000', '哎呀，您的网络打瞌睡啦，请重新打开APP！');
/** 邮箱格式不正确 @var 8001 */
define('STATUS_EMAIL_FORMAT_INCORRECT', '8001');
define('DESCRIPTION_8001', '邮箱格式不正确');
define('REAL_DESCRIPTION_8001', '请输入正确的邮箱！');
/** 请求参数不合法 @var 8002 */
define('STATUS_PARAMETERS_INCORRECT', '8002');
define('DESCRIPTION_8002', '请求参数不合法');
define('REAL_DESCRIPTION_8002', '部分输入格式不正确，请检查后再提交！');
/** 用户名已存在 @var 8003 */
define('STATUS_USER_EXISTS', '8003');
define('DESCRIPTION_8003', '用户名已存在');
define('REAL_DESCRIPTION_8003', '用户名已存在');
/** 用户名不存在 @var 8004 */
define('STATUS_USER_NOT_EXISTS', '8004');
define('DESCRIPTION_8004', '用户不存在');
define('REAL_DESCRIPTION_8004', '账号或密码错误');
/** 用户名未登录 @var 8005 */
define('STATUS_USER_NOT_LOGIN', '8005');
define('DESCRIPTION_8005', '用户未登录');
define('REAL_DESCRIPTION_8005', '用户未登录');


/*
 * 其它 9
 */
/** 没有返回状态码 @var 9000 */
define('STATUS_NOSTATUS', '9000');
define('DESCRIPTION_9000', '没有返回状态码');
define('REAL_DESCRIPTION_9000', '有重大更新，请更新客户端。');
/** 协议格式不正确 @var 9001 */
define('STATUS_INCORRECT_FORMAT', '9001');
define('DESCRIPTION_9001', '请求参数格式不正确');
define('REAL_DESCRIPTION_9001', '有重大更新，请更新客户端。');
/** 协议不存在 @var 9002 */
define('STATUS_NO_PROTOCOL', '9002');
define('DESCRIPTION_9002', '协议不存在');
define('REAL_DESCRIPTION_9002', '有重大更新，请更新客户端。');
/** 请求方法不存在 @var 9003 */
define('STATUS_NO_REQUEST_ACTION', '9003');
define('DESCRIPTION_9003', '请求方法不存在');
define('REAL_DESCRIPTION_9003', '有重大更新，请更新客户端。');
/** 请求方法不存在 @var 9004 */
define('STATUS_SERVICE_ERROR', '9004');
define('DESCRIPTION_9004', '服务器错误');
define('REAL_DESCRIPTION_9004', '有重大更新，请更新客户端。');