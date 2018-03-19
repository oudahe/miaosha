<?php
/**
 * Created by PhpStorm.
 * User: wangyi
 * Date: 17/7/22
 * Time: 12:41
 */

$str = '{"active_id":2,"id":"2","ask":"1","answer":"2","datalist":[],"title":"3","uid":1,"ip":"127.0.0.1","now":1500698406}';
print_r(json_decode($str, 1));
