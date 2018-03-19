<?php
/**
 * 我的订单
 */

include 'init.php';

$TEMPLATE['type'] = 'trade';
$TEMPLATE['pageTitle'] = '我的订单';

$uid = $login_userinfo['uid'];

$trade_model = new \model\Trade();

$list_trade = $trade_model->getUserTrade($uid);

$TEMPLATE['list_trade'] = $list_trade;

include TEMPLATE_PATH . '/trade.php';
