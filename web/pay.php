<?php
/**
 * 支付接口
 */

include 'init.php';

$uid = $login_userinfo['uid'];
$id = getReqInt('id');

if (!$uid || !$id) {
    $result = array('error_no' => '101', 'error_msg' => '参数异常');
    show_result($result);
}

$trade_model = new \model\Trade();
$info = $trade_model->get($id);
if ($info['uid'] != $uid) {
    $result = array('error_no' => '102', 'error_msg' => '没有权限更新订单信息');
    show_result($result);
}
$trade_model = new \model\Trade();
$trade_model->id = $id;
if (isset($_GET['action']) && $_GET['action'] == 'cancel') {
    $trade_model->sys_status = 5;
    $trade_model->time_cancel = $now;
    $result = '订单取消';
} else {
    $trade_model->sys_status = 2;
    $trade_model->time_pay = $now;
    $result = '订单支付';
}

$ok = $trade_model->save($id);
if ($ok) {
    $result .= '成功';
} else {
    $result .= '失败，请稍后再试';
}

show_result($result, '/trade.php');
