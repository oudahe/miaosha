<?php
/**
 * 问答信息管理页
 * @author wangyi
 */

include 'init.php';

$refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/admin/';
$TEMPLATE['refer'] = $refer;
$TEMPLATE['type'] = 'question';

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

$question_model = new \model\Question();
if ('list' == $action) {	// 列表页
	$page = getReqInt('page', 'get', 1);
	$size = 50;
	$offset = ($page - 1) * $size;

    $datalist = $question_model->getList($offset, $size);
    $total_num = $question_model->count();

	$TEMPLATE['datalist'] = $datalist;
	$TEMPLATE['pageTitle'] = '问答管理';
	include TEMPLATE_PATH . '/admin/question_list.php';
} else if ('edit' == $action) {	// 编辑页
    $id = getReqInt('id', 'get', 0);
    if ($id) {
        $data = $question_model->get($id);
    } else {
        $data = array('id' => 0, 'active_id' => 0, 'title' => '');
        for ($i = 1; $i <= 10; ++$i) {
            $data['ask' . $i] = '';
            $data['answer' . $i] = '';
        }
    }

    $TEMPLATE['data'] = $data;
    $TEMPLATE['pageTitle'] = '编辑问答信息-问答管理';
    include TEMPLATE_PATH . '/admin/question_edit.php';
} else if ('save' == $action) {	// 保存
	$info = $_POST['info'];
//    print_r($info);
	$info['title'] = addslashes($info['title']);
// 	print_r($info);
// 	exit();
    foreach ($info as $k => $v) {
        $question_model->$k = $v;
    }
    if ($info['id']) {
        $question_model->sys_lastmodify = time();
        $ok = $question_model->save();
    } else {
        $question_model->sys_dateline = $question_model->sys_lastmodify = time();
        $question_model->sys_ip = getClientIp();
        $ok = $question_model->create();
    }

	if ($ok) {
		redirect('question.php');
	} else {
		echo '<script>alert("数据保存失败");history.go(-1);</script>';
	}
} else if ('delete' == $action) {	// 删除
	$id = getReqInt('id', 'get', 0);
	if ($id) {
        $question_model->id = $id;
        $question_model->sys_status = 1;
        $ok = $question_model->save($data);
	}
	if ($ok) {
		redirect($refer);
	} else {
		echo '<script>alert("下线的时候出现错误");location.href="'.$refer.'";</script>';
	}
} else if ('reset' == $action) {	// 恢复
	$id = getReqInt('id', 'get', 0);
	if ($id) {
        $question_model->id = $id;
        $question_model->sys_status = 0;
		$ok = $question_model->save($data);
	}
	if ($ok) {
		redirect($refer);
	} else {
		echo '<script>alert("上线的时候出现错误");location.href="'.$refer.'";</script>';
	}
} else {
    echo 'error question action';
}
