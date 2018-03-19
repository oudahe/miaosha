<?php
/**
 * @author Sandy
 */

namespace common;

class Datasource {
    /*{{{*/
    public static $redises = array();
    public static $caches = array();
    public function __construct() {
    }
    public static function getRedis($config_name = NULL, $server_region = 'default') {
        if ($config_name === NULL) {
            return;
        }
        if (isset(self::$redises[$config_name]) && self::$redises[$config_name]) {
            return self::$redises[$config_name];
        }
        global $config;
        $redis_config = $config['redis'][$config_name];
        try {
            self::$redises[$config_name] = RedisHelper::instance($config_name, $redis_config, $server_region);
        } catch (Exception $e) {
            self::$redises[$config_name] = null;
        }
        return self::$redises[$config_name];
    }

    public static function getCache($config_name = NULL, $server_region = 'default') {
        if (isset(self::$caches[$config_name]) && self::$caches[$config_name]) {
            return self::$caches[$config_name];
        }
        if ($config_name === NULL) {
            return;
        }
        global $config;
        $memcache_config = $config['cache'][$config_name];
        try {
            self::$caches[$config_name] = CacheHelper::instance($config_name, $memcache_config, $server_region);
        } catch (Exception $e) {
            self::$caches[$config_name] = null;
        }
        return self::$caches[$config_name];
    }
    
}

class RedisHelper {
    /*{{{*/
    private $_config_name = "";
    private $_redis_config = null;
    private $_server_region = null;
    public $timeout = 1;
    private $_redis = null;
    private static $instances = array();
    private static $connect_error = 0;
    private $call_error = 0;

    private function __construct($config_name, $redis_config, $server_region) {
        if ($config_name && $redis_config && $server_region) {
            $this->_config_name = $config_name;
            $this->_redis_config = $redis_config;
            $this->_server_region = $server_region;
            $this->timeout = isset($this->_redis_config[$server_region]['timeout']) ? $this->_redis_config[$server_region]['timeout'] : $this->timeout;
            try {
                $this->_redis = new \redis();
                $this->_redis->connect($this->_redis_config[$server_region]['host'], $this->_redis_config[$server_region]['port'], $this->timeout);
                if($this->_redis_config[$server_region]['password'] && !$this->_redis->auth($this->_redis_config[$server_region]['password'])) {
                    $this->_redis = null;
                }
            } catch (Exception $e) {
                $this->_redis = null;
            }
        } else {
            $this->_redis = null;
        }
    }

    public static function instance($config_name, $redis_config, $server_region) {
        if (!$config_name || !$redis_config) {
            return false;
        }
        $starttime = microtime();
        $only_key = $config_name . ':' . $server_region;
        if (!isset(self::$instances[$only_key])) {
            try {
                self::$instances[$only_key] = new RedisHelper($config_name, $redis_config, $server_region);
                self::$connect_error = 0;
            } catch (Exception $e) {
                if (self::$connect_error < 2) {
                    self::$connect_error += 1;
                    return RedisHelper::instance($config_name, $redis_config, $server_region);
                } else {
                    self::$connect_error = 0;
                    self::$instances[$only_key] = new RedisHelper(false, false, false);
                }
            }
        }
        $redis_config_info = array();
        if ($redis_config && isset($redis_config[$server_region]) && isset($redis_config[$server_region]['password'])) {
            $redis_config_info = $redis_config[$server_region];
            unset($redis_config_info['password']);
        }
        \common\DebugLog::_redis('redis_instance', $config_name, $redis_config_info, $starttime, microtime(), null);
        self::$connect_error = 0;
        return self::$instances[$only_key];
    }

    public function __call($name, $arguments) {
        if (!$this->_redis) {
            return false;
        }
        $starttime = microtime();
        try {
            if ('scan' == $name) {
                $data = $this->_redis->scan($arguments[0]);
            } else {
                $data =  call_user_func_array(array($this->_redis, $name), $arguments);
            }
        } catch (Exception $e) {
            if ($this->call_error < 2) {
                $this->call_error++;
                return call_user_func_array(array($this->_redis, $name), $arguments);
            } else {
                $this->call_error = 0;
            }
            $data = false;
        }
        $this->call_error = 0;
        $redis_config = $this->_redis_config[$this->_server_region];
        if ($redis_config && isset($redis_config['password'])) {
            unset($redis_config['password']);
        }
        \common\DebugLog::_redis($name, $arguments, $redis_config, $starttime, microtime(), (is_string($data) || is_array($data)) ? $data : null);
        return $data;
    }
    public function __destruct() {
        if ($this->_redis != NULL) {
            $this->_redis -> close();
        }
    }
    /*}}}*/
}

class CacheHelper {
    /*{{{*/
    public $config_name;
    public $memcache_config;
    public $server_region;
    public $timeout = 1;
    private $_memcache;
    private static $instances = array();

    private function __construct($config_name = NULL, $memcache_config = NULL, $server_region = SERVER_REGION)
    {
        if ($config_name === NULL) {
            return;
        }
        $mtime1 = microtime();
        $this->config_name = $config_name;
        $this->memcache_config = $memcache_config;
        $this->server_region = $server_region;
        $this->_memcache = new Memcache();
        foreach ($this->memcache_config[$server_region] as $server) {
            $timeout = $server['timeout'] ? $server['timeout'] : $this->timeout;
            $this->_memcache->addServer($server['host'], $server['port'], true, $timeout);
        }
        $mtime2 = microtime();
        \common\DebugLog::_cache('connect', null, $memcache_config, $mtime1, $mtime2, null);
    }
    public static function instance($config_name = NULL, $memcache_config = NULL, $server_region = SERVER_REGION) {
        if (!$config_name || !$memcache_config) {
            return false;
        }
        $only_key = $config_name . ':' . $server_region;
        if (!isset(self::$instances[$only_key])) {
            self::$instances[$only_key] = new CacheHelper($config_name, $memcache_config, $server_region);
        }
        return self::$instances[$only_key];
    }
    public function __call($name, $arguments) {
        if (!$this->_memcache) {
            return false;
        }
        $mtime1 = microtime();
        if ('set' == $name) {
            if (count($arguments) >= 3) {
                $ttl = $arguments[2];
                $arguments[2] = MEMCACHE_COMPRESSED;
                $arguments[3] = $ttl;
            } else {
                $arguments[2] = MEMCACHE_COMPRESSED;
                $arguments[3] = 0;
            }
        }
        $data = call_user_func_array(array($this->_memcache, $name), $arguments);
        $mtime2 = microtime();
        \common\DebugLog::_cache('call:' . $name, $arguments, $this->memcache_config, $mtime1, $mtime2, $data);
        return $data;
    }
    function __destruct()
    {
        if ($this->_memcache != NULL) {
            $this->_memcache->close();
        }
    }
    /*}}}*/
}
/*{{{ curl */
if ( ! function_exists('curl'))
{
    function curl($url, $timeout = NULL) {
        if(!isset($url)){
            return NULL;
        }
        $curl = new Curl();

        $curl->setOpt(CURLOPT_NOSIGNAL,TRUE);

        if($timeout!==NULL){
            $curl->setOpt(CURLOPT_TIMEOUT_MS,$timeout);
        }
        $curl->get($url);
        return $curl->response;
    }   
}

if ( ! function_exists('curl_with_header'))
{
    function curl_with_header($url, $header, $timeout = 1000) {
        if(!isset($url)){
            return NULL;
        }
        $curl = new Curl();

        $curl->setOpt(CURLOPT_NOSIGNAL,TRUE);

        if($timeout!==NULL){
            $curl->setOpt(CURLOPT_TIMEOUT_MS,$timeout);
        }
        foreach($header as $k=>$v){
            $curl->setHeader($k, $v);
        }
        $curl->get($url);
        return $curl->response;
    }   
}
/*}}}*/

