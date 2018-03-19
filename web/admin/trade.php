<?php
/**
 * 订单信息管理页
 * @author wangyi
 */

include 'init.php';

$refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/admin/';
$TEMPLATE['refer'] = $refer;
$TEMPLATE['type'] = 'trade';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

$trade_model = new \model\Trade();
if ('list' == $action) {	// 列表页
	$page = getReqInt('page', 'get', 1);
	$size = 50;
	$offset = ($page - 1) * $size;

    $datalist = $trade_model->getList($offset, $size);
    $total_num = $trade_model->count();

	$TEMPLATE['datalist'] = $datalist;
	$TEMPLATE['pageTitle'] = '订单管理';
	include TEMPLATE_PATH . '/admin/trade_list.php';
} elseif ('edit' == $action) {	// 编辑页
    $id = getReqInt('id', 'get', 0);
    if ($id) {
        $data = $trade_model->get($id);
    } else {
        $data = array('id' => 0, 'active_id' => 0, 'title' => '', 'description' => '', 'img' => '',
            'price_normal' => '0', 'price_discount' => '0',
            'num_total' => 0, 'num_user' => 0,
            'time_begin' => '', 'time_end' => '');
    }

    $TEMPLATE['data'] = $data;
    $TEMPLATE['pageTitle'] = '编辑订单信息-订单管理';
    include TEMPLATE_PATH . '/admin/trade_edit.php';
} elseif ('save' == $action) {	// 保存
	$info = $_POST['info'];
//    print_r($info);
	$info['title'] = addslashes($info['title']);
    $info['description'] = addslashes($info['description']);
    $info['img'] = addslashes($info['img']);
    $info['price_normal'] = intval($info['price_normal']);
    $info['price_discount'] = intval($info['price_discount']);
    $info['num_total'] = $info['num_left'] = intval($info['num_total']);
    $info['num_user'] = intval($info['num_user']);
// 	print_r($info);
// 	exit();
    foreach ($info as $k => $v) {
        $trade_model->$k = $v;
    }
    if ($info['id']) {
        $trade_model->sys_lastmodify = time();
        $ok = $trade_model->save();
    } else {
        $trade_model->sys_dateline = $trade_model->sys_lastmodify = time();
        $trade_model->sys_ip = getClientIp();
        $ok = $trade_model->create();
    }

	if ($ok) {
		redirect('trade.php');
	} else {
		echo '<script>alert("数据保存失败");history.go(-1);</script>';
	}
} elseif ('delete' == $action) {	// 删除
	$id = getReqInt('id', 'get', 0);
	if ($id) {
        $trade_model->id = $id;
        $trade_model->sys_status = 2;
        $ok = $trade_model->save($data);
	}
	if ($ok) {
		redirect($refer);
	} else {
		echo '<script>alert("下线的时候出现错误");location.href="'.$refer.'";</script>';
	}
} elseif ('reset' == $action) {	// 恢复
	$id = getReqInt('id', 'get', 0);
	if ($id) {
        $trade_model->id = $id;
        $trade_model->sys_status = 1;
		$ok = $trade_model->save();
	}
	if ($ok) {
		redirect($refer);
	} else {
		echo '<script>alert("上线的时候出现错误");location.href="'.$refer.'";</script>';
	}
} elseif ('confirm' == $action) {
    $id = getReqInt('id', 'get', 0);
    if ($id) {
        $trade_model->id = $id;
        $trade_model->sys_status = 4;
        $ok = $trade_model->save();
    }
    if ($ok) {
        redirect($refer);
    } else {
        echo '<script>alert("确认订单的时候出现错误");location.href="'.$refer.'";</script>';
    }
} else {
    echo 'error trade action';
}
