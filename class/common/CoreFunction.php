<?php
/**
 * 常用函数
 * @author Sandy
 */

namespace common;

class CoreFunction {

	public static $cookie_pre = 'yTGF__';
	public static $authkey = 'jjd887656JHHGGFkdjax';

	/**
	 * 获取客户端IP
	 */
    public static function getClientIp() {
        $realip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($ips as $ip) {
                $matches = array();
                preg_match("/^(10|172\.16|192\.168)\./", $ip, $matches);
                if (!$matches[1]) {
                    $realip = $ip;
                    break;
                }
            }
        }
		if ($realip) {
			// 通过 HTTP_CLIENT_IP , HTTP_X_FORWARDED_FOR 得到的IP，还需要验证 REMOTE_ADDR 是本地IP
			$remote_ip = $_SERVER['REMOTE_ADDR'];
			preg_match("/^(10|127|172\.16|192\.168|101\.198)\./", $remote_ip, $matches);	// 内网IP
			if (!$matches[1]) {
				$realip = '';
			}
		}
        return $realip ? $realip : $_SERVER['REMOTE_ADDR'];
    }

	/**
	 * 获取当前登录用户
	 */
    public static function getLoginUser() {
		global $_config;
		$c_auth = false;
		$c_saltkey = false;
		$c_userinfo = false;
		foreach ($_COOKIE as $k => $v) {
			if (strpos($k, CoreFunction::$cookie_pre) === 0) {
				if (strpos($k, '_auth')) {
					$c_auth = $v;
				} elseif (strpos($k, '_saltkey')) {
					$c_saltkey = $v;
				} elseif (strpos($k, '_userinfo')) {
					$c_userinfo = $v;
				}
			}
		}
		if ($c_auth && $c_saltkey) {
			$c_auth = CoreFunction::daddslashes(explode("\t", CoreFunction::authcode($c_auth, 'DECODE', $c_saltkey)));
			list($discuz_pw, $discuz_uid) = empty($c_auth) || count($c_auth) < 2 ? array('', '') : $c_auth;
		}
		if ($discuz_pw && $discuz_uid) {
			// cookie 能解开，就认为是成功的登录状态
			$uid = $discuz_uid;
			$try_member = new Data_Try_Member();
			$user = $try_member->getInfo(array("uid"=>$uid), "");
			if ($user) {
				return $user[0];
			}
		}
		if ($c_userinfo) {
			$userinfo = json_decode($c_userinfo, true);
			if ($userinfo && $discuz_uid && $discuz_uid == $userinfo['uid']) {
				$userinfo['avatar'] = $_config['sso']['sych_user_info_avatar'] . "?uid={$discuz_uid}&size=middle";
				$info = array(
					'uid' => $userinfo['uid'],
                    "cyid" => '',
                    "username" => $userinfo['username'],
                    "avatar" => $userinfo['avatar'],
                    "sys_dateline" => time(),
                    "sys_status" => 0,
                    "sys_ip" => CoreFunction::getClientIp(),
                );
                $new_member = new Data_Try_Member($info);
                $uid = $new_member->create();
				return $info;
			}
		}
		/*
		print_r(array($discuz_pw, $discuz_uid));exit();
        $uid = intval($_COOKIE['uid']);
        $auth = $_COOKIE['auth'];
        if($uid && $auth){
            $try_member = new Data_Try_Member();
            $user = $try_member->getInfo(array("uid"=>$uid), "");
            if($user && $auth == md5("{$uid};{$user[0]['cyid']}")){
                return $user[0];
            }
        }
		*/
        return false;
    }

    public static function getLoginAdmin() {
        return $_SERVER['PHP_AUTH_USER'];
    }

    /**
     * 检查禁词
     * 返回 false 说明一切正常，否则返回禁词
     * @param $content
     * @return bool
     */
    public static function checkForbidWords($content) {
        if (!$content || strlen($content) < 3) {
            return false;
        }
        if (isset($GLOBALS['array_forbid_words'])) {
            $forbid_words = $GLOBALS['array_forbid_words'];
            $blank_words = $GLOBALS['array_blank_words'];
        } else {
            include APP_PATH . '/conf/forbidwords.ini.php';
            $GLOBALS['array_forbid_words'] = $forbid_words;
            $GLOBALS['array_blank_words'] = $blank_words;
        }
        $content = str_replace($blank_words, '', $content);
        foreach ($forbid_words as $wd) {
            if (strpos($content, $wd) !== FALSE) {
                return $wd;
            }
        }
        return false;
    }

    /**
     * 获取用户最近一次发帖时间，防灌水
     * @param type $uid
     */
    public static function getLastComment($uid){
        $try_comment = new Data_Try_Comment();
        $lastComment = $try_comment->getLastComment($uid);
        return $lastComment[0]['sys_dateline'];
    }
    
    /**
     * 检测apply是否存在
     */
    public static function checkApplyExistById($try_id){
        #当数据量很大的时候将$exist_apply_ids加入缓存
        $try_info = new Data_Try_Info();
        $exist_applies = $try_info->getInfo("");
        foreach($exist_applies as $apply){
            $exist_apply_ids[] = $apply['id'];
        }
        if(in_array($try_id, $exist_apply_ids)){
            return true;
        }
        return false;
    }
    
    /**
     * 判断是否填过申请
     */
    public static function checkApplyByUser($try_id, $uid){
        $try_apply = new Data_Try_Apply();
        $apply_info = $try_apply->checkUserTryApply($try_id, $uid);
        if($apply_info){
            #如果已填
            return true;
        }
        return false;
    }
    
    /**
     * 检测apply数据是否格式正确
     */
    public static function checkApplyDataFormat($try_id, $answer=""){
        $answer_array = json_decode($answer, 1);
        if(is_null($answer_array)){
            return false;
        }
        $try_info = new Data_Try_Info();
        $try_data = $try_info->getInfo(array("id" => $try_id));
        $survey_id = $try_data[0]['survey_id'];
        $survey_question = new Data_Survey_Question();
        $question_data = $survey_question->getDatalistBySurvey($survey_id);
        if(count($question_data) != count($answer_array)){
            return false;
        }
        return true;
    }

	/**
	 * @name cutstr
	 * @desc 按照指定的规则切分字符串,针对UTF8. $length 为你要显示的汉字 * 3
	 * @param string $string	原始字符串
	 * @param int $length	切割的长度
	 * @param string $suffix	后缀名
	 * @return string 
	 * @author Sandy
	 */
	public static function ccutstr($string, $length, $suffix = '')
	{
		$p	=	0;
		$j	=	0;
		if($string == "")
		{
			return "";
		}
		preg_match_all('/([x41-x5a,x61-x7a,x30-x39])/', $string, $letter); //字母
		$string_len = strlen($string);
		$let_len = count($letter[0]);
		if($string_len == $let_len)
		{
			//没有汉字
			$len = floor($length / 2);
			if($string_len > $len)
				return substr($string, 0, $len) . $suffix;
			else 
				return substr($string, 0, $len);
		}
		$length_tmp	= $string_len;
		if($length_tmp > $length)
		{
			for ($k=0;$k<=($length-3);$k++)
			{
				$j++;
				if($j > ($length-3))
				{
					break;
				}
				$c = ord(substr($string,$k,1));
				if ($c > 252)
				{
					$k+=6;
					$j+=6;
				}
				else if ($c > 248)
				{
					$k+=5;
					$j+=5;
				}
				else if ($c > 240)
				{
					$k+=4;
					$j+=4;
				}
				else if ($c > 224)
				{
					$k+=3;
					$j+=3;
				}
				else if ($c > 192)
				{
					$k+=2;
					$j+=2;
				}
				else
				{
					$p++;
				}
				if($p	==	2)
				{
					$j++;
					$p	=	0;
				}
			}
			$string = substr($string, 0, $k);
		}
		$string	=	str_replace("<BR…","<BR>…",$string);
		$string	=	str_replace("<B…","<BR>…",$string);
		$string	=	str_replace("<…","<BR>…",$string);
		
		if($string_len > strlen($string))
			return $string . $suffix;
		else 
			return $string;
	}

	public static function daddslashes($string, $force = 1) {
		if(is_array($string)) {
			$keys = array_keys($string);
			foreach($keys as $key) {
				$val = $string[$key];
				unset($string[$key]);
				$string[addslashes($key)] = CoreFunction::daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
		return $string;
	}


	public static function authcode($string, $operation = 'DECODE', $saltkey = '', $expiry = 0) {
		$ckey_length = 4;
		$authkey = md5(CoreFunction::$authkey . $saltkey);
		$key = md5($authkey);
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}

	}

}