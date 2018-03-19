<?php
/**
 *
 * Class model\Goods
 * @author Sandy
 */

namespace model;

class Question Extends \Mysql\Crud {
    protected $table = 'ms_question';
    protected $pk	 = 'id';

    /**
     * 分页查询，不需要条件
     * @param int $start 分页开始偏移位置
     * @param int $end 分页结束便宜位置
     * @return mixed 列表
     */
    public function getList($start = 0, $end = 50) {
        $start = max(0, $start);
        $end = min(50, $end);
        $sql = "SELECT * FROM `" . $this->table . "` ORDER BY `$this->pk` DESC LIMIT $start, $end";
        return $this->getDb()->query($sql);
    }

    /**
     * 获取某个活动的问答信息
     * @param $aid
     * @return mixed
     */
    public function getActiveQuestion($aid) {
        $sql = "SELECT * FROM `" . $this->table . "` WHERE active_id='" . $aid . "' AND sys_status=0 LIMIT 1";
        return $this->getDb()->row($sql);
    }
}