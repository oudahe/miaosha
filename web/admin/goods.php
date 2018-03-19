<?php
/**
 * 商品信息管理页
 * @author wangyi
 */

include 'init.php';

$refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/admin/';
$TEMPLATE['refer'] = $refer;
$TEMPLATE['type'] = 'goods';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

$goods_model = new \model\goods();
if ('list' == $action) {	// 列表页
	$page = getReqInt('page', 'get', 1);
	$size = 50;
	$offset = ($page - 1) * $size;

    $datalist = $goods_model->getList($offset, $size);
    $total_num = $goods_model->count();

	$TEMPLATE['datalist'] = $datalist;
	$TEMPLATE['pageTitle'] = '商品管理';
	include TEMPLATE_PATH . '/admin/goods_list.php';
} else if ('edit' == $action) {	// 编辑页
    $id = getReqInt('id', 'get', 0);
    if ($id) {
        $data = $goods_model->get($id);
    } else {
        $data = array('id' => 0, 'active_id' => 0, 'title' => '', 'description' => '', 'img' => '',
            'price_normal' => '0', 'price_discount' => '0',
            'num_total' => 0, 'num_user' => 0,
            'time_begin' => '', 'time_end' => '');
    }

    $TEMPLATE['data'] = $data;
    $TEMPLATE['pageTitle'] = '编辑商品信息-商品管理';
    include TEMPLATE_PATH . '/admin/goods_edit.php';
} else if ('save' == $action) {	// 保存
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
        $goods_model->$k = $v;
    }
    if ($info['id']) {
        $goods_model->sys_lastmodify = time();
        $ok = $goods_model->save();
    } else {
        $goods_model->sys_dateline = $goods_model->sys_lastmodify = time();
        $goods_model->sys_ip = getClientIp();
        $ok = $goods_model->create();
    }

	if ($ok) {
		redirect('goods.php');
	} else {
		echo '<script>alert("数据保存失败");history.go(-1);</script>';
	}
} else if ('delete' == $action) {	// 删除
	$id = getReqInt('id', 'get', 0);
	if ($id) {
        $goods_model->id = $id;
        $goods_model->sys_status = 2;
        $ok = $goods_model->save($data);
	}
	if ($ok) {
		redirect($refer);
	} else {
		echo '<script>alert("下线的时候出现错误");location.href="'.$refer.'";</script>';
	}
} else if ('reset' == $action) {	// 恢复
	$id = getReqInt('id', 'get', 0);
	if ($id) {
        $goods_model->id = $id;
        $goods_model->sys_status = 1;
		$ok = $goods_model->save($data);
	}
	if ($ok) {
		redirect($refer);
	} else {
		echo '<script>alert("上线的时候出现错误");location.href="'.$refer.'";</script>';
	}
} else {
    echo 'error goods action';
}
