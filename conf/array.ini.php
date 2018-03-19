<?php
/**
 * 公众号：代码技巧
 */

function getIniArray($arr, $key = false) {
    global $$arr;
    $a = $$arr;
    if ($key === false) {
        return $a;
    } else {
        return $a[$key];
    }
}

// 活动（商品）的状态
$arr_active_status = array(
    0 => '待上线',
    1 => '已上线',
    2 => '已下线',
);

// 问答的状态
$arr_question_status = array(
    0 => '正常',
    1 => '删除',
);

// 日志的状态
$arr_log_status = array(
    0 => '正常',
    1 => '异常',
    2 => '已处理的异常',
);

// 订单状态
$arr_trade_status = array(
    0 => '初始状态',
    1 => '待支付',
    2 => '已支付',
    3 => '已过期',
    4 => '管理员已确认',
    5 => '已取消',
    6 => '已删除',
    7 => '已发货',
    8 => '已收货',
    9 => '已完成',
);

// 问答选项类型
$arr_question_types = array(
    0 => '单行文本',
    1 => '多行文本',
    2 => '下拉框单选',
    3 => '按钮单选',
    4 => '多选',
    5 => '下拉框单选+其他',
    6 => '多选+更多',
);

// 内容中的禁止标签
$arr_forbid_content_tags = array(
    'iframe', 'script', 'embed'
);

// 抽奖中的奖品类型
$arr_gift_types = array(
	0 => '虚拟币',
	1 => '虚拟券-共用',
	2 => '虚拟券-独用',
	3 => '实物-小奖',
	4 => '实物-大奖',
);

