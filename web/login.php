<?php
/**
 * 支付接口
 */

include 'init.php';

$action = $_GET['action'];

if ('logout' == $action) {
    clear_auth_cookie();
} else {    // 默认就是来登录
    $id = rand(1, 100000000);
    $auth_cookie = array(
        'uid' => $id,
        'username' => '测试用户' . $id,
    );
    save_auth_cookie($auth_cookie);
}
redirect('/');
