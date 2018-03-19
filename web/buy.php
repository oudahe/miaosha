<?php
/**
 * 抢购的处理逻辑
 */

include 'init.php';

$TEMPLATE['type'] = 'buy';
$TEMPLATE['pageTitle'] = '抢购';

$active_model = new \model\Active();
$goods_model = new \model\Goods();

// 参数的处理
$active_id = getReqInt('active_id');
$goods_id = getReqInt('goods_id');
$goods_num = getReqInt('goods_num');
$sign_data = $_POST['sign_data'];
$question_sign = $_POST['question_sign'];
$ask = $_POST['ask'];
$answer = $_POST['answer'];
$action = isset($_POST['action']) ? $_POST['action'] : false;

if ('buy_cart' == $action) {
    $goods_num = $_POST['num'][0];
}
$client_ip = getClientIp();

// 1 验证用户是否登录
if (!$login_userinfo || !$login_userinfo['uid']) {
    $result = array('error_no' => '101', 'error_msg' => '用户登录之后才可以参与');
    show_result($result);
}
$uid = $login_userinfo['uid'];
$username = $login_userinfo['username'];
// 2 验证参数是否正确、合法
if (!$active_id || !$goods_id
    || !$goods_num || !$question_sign) {
    $result = array('error_no' => '102', 'error_msg' => '参数提交异常');
    show_result($result);
}
// 3.1 验证活动状态信息
$status_check = false;
$str_sign_data = unsignQuestion($sign_data);
$sign_data_info = json_decode($str_sign_data, true);
// 时间不能超过当前时间5分钟，IP和用户保持不变
if ($sign_data_info
    && $sign_data_info['now'] < $now
    && $sign_data_info['now'] > $now - 300
    && $sign_data_info['ip'] == $client_ip
    && $sign_data_info['uid'] == $uid
) {
    $status_check = true;
}
if (!$status_check) {
    $result = array('error_no' => '103', 'error_msg' => '用户校验值验证没有通过');
    show_result($result);
}
// 3.2 验证问答信息是否正确
$question_check = false;
$str_question = unsignQuestion($question_sign);
$question_info = json_decode(trim($str_question), true);
if ($str_question && $question_info) {
    if ($question_info['ask'] == $ask
        && $question_info['answer'] == $answer
        && $question_info['aid'] == $active_id
        && $question_info['uid'] == $uid
        && $question_info['ip'] == $client_ip
        && $question_info['now'] > $now -300
    ) {
        $question_check = true;
    }
}
if (!$question_check) {
    $result = array('error_no' => '103', 'error_msg' => '问答验证没有通过');
    show_result($result);
}

// 统一格式化单商品、组合商品的数据结构
$nums = $goods = array();
if ('buy_cart' != $action) {
    $nums = array($goods_num);
    $goods = array($goods_id);
} else {
    $num = $_POST['num'];
    $goods = $_POST['goods'];
}

$redis_obj = \common\Datasource::getRedis('instance1');
$d_list = array(
    'u_trade_' . $uid . '_' . $active_id,
    'st_a_' . $active_id
);
/**
 * id, sys_status,
 * num_user, num_left,
 * price_normal, price_discount
 */
foreach ($goods as $i => $goods_id) {
    $d_list[] = 'info_g_' . $goods_id;  // 商品详情
}
$data_list = $redis_obj->mget($d_list);
// 4 验证用户是否已经购买
if ($data_list[0]) {
    $result = array('error_no' => '104', 'error_msg' => '请不要重复提交订单');
    show_result($result);
}
// 5 验证活动信息，商品信息是否正常
if ($data_list[1]) {
    $result = array('error_no' => '105', 'error_msg' => '活动信息异常');
    show_result($result);
}
unset($data_list[0]);
unset($data_list[1]);
/*
// 4 验证用户是否已经购买
$trade_model = new \model\Trade();
$trade_info = $trade_model->getUserTrade($uid, $active_id);
if ($trade_info) {
    $result = array('error_no' => '104', 'error_msg' => '请不要重复提交订单');
    show_result($result);
}
// 5 验证活动信息，商品信息是否正常
$active_info = $active_model->get($active_id);
if (!$active_info || $active_info['sys_status'] !== '1'
    || $active_info['time_begin'] > $now
    || $active_info['time_end'] < $now
) {
    $result = array('error_no' => '105', 'error_msg' => '活动信息异常');
    show_result($result);
}
if ('buy_cart' != $action) {
    $nums = array($goods_num);
    $goods = array($goods_id);
} else {
    $nums = $_POST['num'];
    $goods = $_POST['goods'];
}
*/
$num_total = $price_total = $price_discount = 0;
$trade_goods = array();
foreach ($data_list as $i => $goods_info) {
    $goods_num = $nums[$i - 2];
//    $goods_info = $goods_model->get($goods_id);
    if (!$goods_info || $goods_info['sys_status'] !== '1') {
        $result = array('error_no' => '106', 'error_msg' => '商品信息异常');
        show_result($result);
    }
// 6 验证用户购买的商品数量是否在限制的范围内
    if ($goods_num > $goods_info['num_user']) {
        $result = array('error_no' => '107', 'error_msg' => '超出商品数量的限制');
        show_result($result);
    }
// 7 验证商品是否还有剩余数量
    if ($goods_info['num_left'] < $goods_num) {
        $result = array('error_no' => '108', 'error_msg' => '商品剩余数量不足');
        show_result($result);
    }
// 8 扣除商品剩余数量
    $left = $goods_model->changeLeftNumCached($goods_id, 0-$goods_num);
    $ok = false;
    if ($left >= 0) {
        $ok = $goods_model->changeLeftNum($goods_id, 0-$goods_num);
    } else {
        // 扣除商品库存失败
        $goods_model->changeStatusCached($goods_id, 0);
        $result = array('error_no' => '108', 'error_msg' => '商品剩余数量不足');
        show_result($result);
    }


// 9.1 创建订单信息，订单的商品信息
    $trade_goods[] = array(
        'goods_info' => $goods_info,
        'goods_num' => $goods_num
    );
    $num_total += $goods_num;
    $price_total += $goods_info['price_normal'] * $goods_num;
    $price_discount += $goods_info['price_discount'] * $goods_num;
}
// 9.2 保存订单信息
$trade_model = new \model\Trade();
$trade_info = array(
    'active_id' => $active_id,
    'goods_id' => $goods_id,
    'num_total' => $num_total,
    'num_goods' => count($goods),
    'price_total' => $price_total,
    'price_discount' => $price_discount,
    'goods_info' => json_encode($trade_goods),
    'uid' => $uid,
    'username' => $username,
    'sys_ip' => $client_ip,
    'sys_dateline' => $now,
    'time_confirm' => $now,
    'sys_status' => 1,
);
foreach ($trade_info as $k => $v) {
    $trade_model->$k = $v;
}
$trade_id = $trade_model->create();
if ($trade_id) {
    $redis_obj->set('u_trade_' . $uid . '_' . $active_id, 1, 86400);
}

// 10 返回提示信息
$result = '秒杀成功，请尽快去支付';
show_result($result, '/trade.php');
