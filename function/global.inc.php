<?php
/**
 * @name global.inc.php
 * @desc 通用文件包
 * @author wangyi
 */

if(PHP_VERSION < '5.0.0')
{
	echo 'PHP VERSION MUST > 5';
	exit;
}

//默认将显示错误关闭
ini_set('display_errors', true);
//默认将读外部文件的自动转义关闭
ini_set("magic_quotes_runtime", 0);

//设置默认时区
date_default_timezone_set('PRC');
// 调试参数 __debug 的值
define('_DEBUG_PASS', 'miaosha_debug'); // TODO: 为了避免调试信息泄漏，请定义自己的密钥
// 是否开启调试状态
define('_IS_DEBUG', false);
// 异常信息等级
define('_ERROR_LEVEL', E_ALL);


/**
 * Class SYSCore
 * 类文件的自动加载器
 */
class SYSCore {
    public static function registerAutoload($class = 'SYSCore') {
        spl_autoload_register(array($class, 'autoload'));
    }

    public static function unregisterAutoload($class) {
    	spl_autoload_unregister(array($class, 'autoload'));
    }

	public static function my_callback($match){
		return DIRECTORY_SEPARATOR. $match[0];
	}
				
    public static function autoload($class_name) {
        if (strpos($class_name, 'common') === 0
            || strpos($class_name, 'model') === 0
            || strpos($class_name, 'Mysql') === 0
            || strpos($class_name, 'Curl') === 0
            || strpos($class_name, 'controller') === 0
        ) {
            // 系统内部自定义的类域名空间
        } else {
            return true;
        }
		$class_name = str_replace('\\', '/', $class_name);

        $class_path = CUSTOM_CLASS_PATH . DIRECTORY_SEPARATOR . $class_name.'.php';
        $class_path = str_replace('//', '/', $class_path);
        if(file_exists($class_path)) {
            return include_once($class_path);
        } else {
            echo "file not exists class_path=$class_path\n<br/>";
        }

        return false;
    }
}

SYSCore::registerAutoload();

/*---Debug Begin---*/
if((defined('_IS_DEBUG') && _IS_DEBUG) || (isset($_REQUEST['__debug']) && strpos($_REQUEST['__debug'], _DEBUG_PASS) !== false))
{
//    $_REQUEST['__debug'] = _DEBUG_PASS + 1 (2 数字表示级别 )
    $debug_level = intval(substr($_REQUEST['__debug'], -1));
    if ($debug_level > 0) {
        define('DEBUG_LEVEL', $debug_level );
    } else {
        define('DEBUG_LEVEL', 1);
    }
	//Debug模式将错误打开
	ini_set('display_errors', true);
	//设置错误级别
	error_reporting(_ERROR_LEVEL);
	//开启ob函数
	ob_start();
	//Debug开关打开
	common\DebugLog::_init();
	//注册shutdown函数用来Debug显示
	register_shutdown_function(array('common\DebugLog', '_show'));
} else {
    define('DEBUG_LEVEL', 0);
}
/*---Debug End---*/

// 自动加载的配置文件信息
if(defined('AUTOLOAD_CONF_PATH'))
{
	$handle = opendir(AUTOLOAD_CONF_PATH);
	while ($file = readdir($handle)) {
		if(substr($file, -8) == '.ini.php' && is_file(AUTOLOAD_CONF_PATH . DIRECTORY_SEPARATOR . $file))
		{
			include AUTOLOAD_CONF_PATH . DIRECTORY_SEPARATOR . $file;
		}
	}
	unset($handle, $file);
}
