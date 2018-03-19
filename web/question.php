<?php
/**
 * 获取秒杀的问答信息
 */

include 'init.php';

$TEMPLATE['type'] = 'question';
$TEMPLATE['pageTitle'] = '秒杀问答';

if (!$login_userinfo || !$login_userinfo['uid']) {
    $result = array('error_no' => '101', 'error_msg' => '登录之后才可以参与');
    return_result($result);
}
$uid = $login_userinfo['uid'];
$ip = getClientIp();

$aid = getReqInt('aid');

$question_model = new \model\Question();

$ask_list = $answer_list = array();
$question_info = $question_model->getActiveQuestion($aid);
if (!$question_info) {
    $result = array('error_no' => '201', 'error_msg' => '没有设置问答信息');
    return_result($result);
}
// 提取出来有效的问答数据
for ($i = 1; $i <= 10; $i++) {
    if (isset($question_info['ask' . $i]) && isset($question_info['answer' . $i])
        && $question_info['ask' . $i] && $question_info['answer' . $i]) {
        $ask_list[] = $question_info['ask' . $i];
        $answer_list[] = $question_info['answer' . $i];
    }
}
// 随机抽取最多4个问答选项
$count = count($answer_list);
if ($count > 4) {
    $count = 4;
}
$question_data = array();
while (count($question_data) < $count) {
    $i = rand(0, count($answer_list) - 1);
    $question_data[$i] = $i;
}
$datalist_ask = $datalist_answer = array();
foreach ($question_data as $d) {
    $datalist_ask[] = $ask_list[$d];
    $datalist_answer[] = $answer_list[$d];
}
// 从选项中随机抽取一个作为问题和正确答案
$i = rand(0, $count - 1);
$ask = $datalist_ask[$i];
$answer = $datalist_answer[$i];

$question_info = array('aid' => $aid, 'id' => $question_info['id'],
    'ask' => $ask, 'answer' => $answer,
    'datalist' => $datalist_answer, 'title' => $question_info['title'],
    'uid' => $uid, 'ip' => $ip, 'now' => time()
);

$sign = signQuestion($question_info);

$result = array('sign' => $sign,
    'ask' => $ask, 'datalist' => $datalist_answer, 'title' => $question_info['title'],
);
// TODO: 每个人获取到的问题数量是要限制的，否则很容易就被全部获取和分析，失去问题的保密性
//print_r($result);
return_result($result);
