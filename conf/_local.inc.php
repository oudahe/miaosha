<?php
/**
 * @name _local.inc.php
 * @desc 配置文件
 * @author wangyi
 * @caution 路径和URL请不要加反斜线
 **/

/*---------------------------项目级别常量开始---------------------------------*/
//此项目的根目录URL
define('ROOT_DOMAIN','http://'.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '127.0.0.1'));
//此项目绝对地址
define('ROOT_PATH',substr(dirname(__FILE__),0, -4));
define('APP_PATH', ROOT_PATH . '/web');
/*--------下面的常量定义都可以被更小项目中前缀为SUB_的同名常量所覆盖-------*/
//此项目日记文件地址
define('LOG_PATH',ROOT_PATH . '/log');
//配置文件目录
define('AUTOLOAD_CONF_PATH',ROOT_PATH . '/conf');
//自定义类自动加载路径
define('CUSTOM_CLASS_PATH', ROOT_PATH . '/class');
//模版目录
define('TEMPLATE_PATH', ROOT_PATH . '/views');
/*--------常量覆盖结束-------*/
/*---------------------------项目级别常量开始---------------------------------*/
define('AUTH_COOKIE_NAME', 'miaosha_auth');

