<?php

include 'init.php';

$TEMPLATE['type'] = 'list';
$TEMPLATE['pageTitle'] = '秒杀商品列表';

$active_model = new \model\Active();
$goods_model = new \model\Goods();

$list_active = $active_model->getListInuse();
$list_active_goods = array();
foreach ($list_active as $data) {
    $aid = $data['id'];
    $list_goods = $goods_model->getListByActive($aid, -1);
    $list_active_goods[$aid] = $list_goods;
}
$TEMPLATE['list_active'] = $list_active;
$TEMPLATE['list_active_goods'] = $list_active_goods;

include TEMPLATE_PATH . '/list.php';
