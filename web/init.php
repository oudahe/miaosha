<?php
/**
 * @name init.php
 * @desc 文件初始化设置,包含此目录包需要的文件及变量声明
 * @author wangyi
 */

header('Content-Type: text/html;charset=utf-8');

include '../conf/_local.inc.php';
include ROOT_PATH . '/function/global.inc.php';
include ROOT_PATH . '/function/function.inc.php';

// 调试探针，初始化完成，页面开始执行
\common\DebugLog::_time('_init.php, start page');

$TEMPLATE = array();

$login_userinfo = get_login_userinfo();

$TEMPLATE['login_userinfo'] = $login_userinfo;
$now = time();
