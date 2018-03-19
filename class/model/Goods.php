<?php
/**
 *
 * Class model\Goods
 * @author Sandy
 */

namespace model;

class Goods Extends \Mysql\Crud {
    protected $table = 'ms_goods';
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
     * 特定活动的有效商品列表
     * @param int $active_id 活动ID
     * @param int $status 状态， -1 不限制状态
     * @return mixed
     */
    public function getListByActive($active_id = 0, $status = -1) {
        $params = array('active_id' => $active_id);
        if ($status < 0) {
            $sql = "SELECT * FROM `" . $this->table
                . "` WHERE active_id=:active_id ORDER BY `"
                . $this->pk . "` DESC";
        } else {
            $sql = "SELECT * FROM `" . $this->table
                . "` WHERE active_id=:active_id AND sys_status=:sys_status ORDER BY `"
                . $this->pk . "` DESC";
            $params['sys_status'] = $status;
        }
        return $this->getDb()->query($sql, $params);
    }

    /**
     * 更新商品的剩余数量
     * @param $id 商品ID
     * @param $num 商品变化数量，大于0则增加，小于0则减少
     * @return mixed
     */
    public function changeLeftNum($id, $num) {
        $params['id'] = array('id' => $id);
        $sql = "UPDATE " . $this->table . " SET num_left=num_left" . ($num > 0 ? '+' : '') . "$num WHERE id=:id";
        return $this->getDb()->query($sql, $params);
    }

    /**
     * 从缓存中更新商品的剩余数量
     * @param $id
     * @param $num
     */
    public function changeLeftNumCached($id, $num) {
        $key = 'info_g_' . $id;
        $redis_obj = \common\Datasource::getRedis('instance1');
        $left = $redis_obj->hincrby($key, 'num_left', $num);
        return $left;
    }

    /**
    * @param $id
    * @param $status
     * @return mixed
     */
    public function changeStatusCached($id, $status) {
        $redis_obj = \common\Datasource::getRedis('instance1');
        $key = 'st_g_' . $id;
        return $redis_obj->set($key, $status);
    }

}