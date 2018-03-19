<?php
/**
 * 日志信息管理页
 * @author wangyi
 */

include 'init.php';

$refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/admin/';
$TEMPLATE['refer'] = $refer;
$TEMPLATE['type'] = 'log';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

$log_model = new \model\log();
if ('list' == $action) {	// 列表页
	$page = getReqInt('page', 'get', 1);
	$size = 50;
	$offset = ($page - 1) * $size;

    $datalist = $log_model->getList($offset, $size);
    $total_num = $log_model->count();

	$TEMPLATE['datalist'] = $datalist;
	$TEMPLATE['pageTitle'] = '日志管理';
	include TEMPLATE_PATH . '/admin/log_list.php';
} else if ('edit' == $action) {	// 编辑页
    $id = getReqInt('id', 'get', 0);
    if ($id) {
        $data = $log_model->get($id);
        $data['time_begin'] = date('Y-m-d H:i:s', $data['time_begin']);
        $data['time_end'] = date('Y-m-d H:i:s', $data['time_end']);
    } else {
        $data = array('id' => 0, 'title' => '', 'time_begin' => '', 'time_end' => '');
    }

    $TEMPLATE['data'] = $data;
    $TEMPLATE['pageTitle'] = '编辑日志信息-日志管理';
    include TEMPLATE_PATH . '/admin/log_edit.php';
} else if ('save' == $action) {	// 保存
	$info = $_POST['info'];
// 	print_r($info);
	$info['title'] = addslashes($info['title']);
	$info['time_begin'] = strtotime($info['time_begin']);
	$info['time_end'] = strtotime($info['time_end']);
// 	print_r($info);
// 	exit();
    foreach ($info as $k => $v) {
        $log_model->$k = $v;
    }
    if ($info['id']) {
        $log_model->sys_lastmodify = time();
        $ok = $log_model->save();
    } else {
        $log_model->sys_dateline = $log_model->sys_lastmodify = time();
        $log_model->sys_ip = getClientIp();
        $ok = $log_model->create();
    }

	if ($ok) {
		redirect('log.php');
	} else {
		echo '<script>alert("数据保存失败");history.go(-1);</script>';
	}
} else if ('delete' == $action) {	// 删除
	$id = getReqInt('id', 'get', 0);
	if ($id) {
        $log_model->id = $id;
        $log_model->sys_status = 2;
        $ok = $log_model->save($data);
	}
	if ($ok) {
		redirect($refer);
	} else {
		echo '<script>alert("下线的时候出现错误");location.href="'.$refer.'";</script>';
	}
} else if ('reset' == $action) {	// 恢复
	$id = getReqInt('id', 'get', 0);
	if ($id) {
        $log_model->id = $id;
        $log_model->sys_status = 2;
		$ok = $log_model->save($data);
	}
	if ($ok) {
		redirect($refer);
	} else {
		echo '<script>alert("上线的时候出现错误");location.href="'.$refer.'";</script>';
	}
} else {
    echo 'error log action';
}
