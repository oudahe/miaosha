<?php

header('Content-Type: text/html;charset=utf-8');

include '../../conf/_local.inc.php';
include ROOT_PATH . '/function/global.inc.php';
include ROOT_PATH . '/function/function.inc.php';

// 调试探针，初始化完成，页面开始执行
\common\DebugLog::_time('_init.php, start page');

$TEMPLATE = array();

$login_userinfo = get_login_userinfo();
$TEMPLATE['login_userinfo'] = $login_userinfo;


// 验证登录授权
///////////////// Password protect ////////////////////////////////////////////////////////////////
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
		$_SERVER['PHP_AUTH_USER'] != 'miaosha' ||$_SERVER['PHP_AUTH_PW'] != 'miaosha') {
	Header("WWW-Authenticate: Basic realm=\"Login\"");
	Header("HTTP/1.0 401 Unauthorized");

	echo <<<EOB
				<html><body>
				<h1>Rejected!</h1>
				<big>Wrong Username or Password!</big>
				</body></html>
EOB;
	exit;
}

