<?php
/**
 * 系统中的一些公共方法，比如：
 *
 * @author wangyi
 */

/**
 * 根据现有的参数和密钥计算出sign值
 * @param Array $params 参数集合
 * @return String
 */
function create_sign($params = null)
{
	if ($params && is_array($params))
	{
		ksort($params);
		$str = '2017_miaosha';
		foreach ($params as $key => $value)
		{
			if ($key != 'sign')
			{
				$str .= $key.$value;
			}
		}
		return strtoupper(md5($str));
	}
	return '';
}


// 保存登录的cookie信息
function save_auth_cookie($auth_cookie)
{
	$now = time();
	header('P3P: CP="ALL ADM DEV PSAi COM OUR OTRo STP IND ONL"');
	$datalist = array();

	foreach ($auth_cookie as $key => $value)
	{
		$datalist[] = $key.'='.urlencode($value);
	}
	$sign = create_sign($auth_cookie);
	$datalist[] = 'sign='.$sign;
	$cookie = implode('&', $datalist);
	$expire = $auth_cookie['third_expires'];
	if ($expire <= $now)
	{	// 默认保持一个月的时间
		$expire = $now + 86400;
	}
	setcookie(AUTH_COOKIE_NAME, $cookie, $expire, '/', '');
}
// 退出登录，清空cookie信息
function clear_auth_cookie()
{
	setcookie(AUTH_COOKIE_NAME, '', 1, '/', '');
}

// 获取登录信息
function get_login_userinfo()
{
	$datalist = array();
	$cookie = null;
    if (isset($_COOKIE[AUTH_COOKIE_NAME])) {
        $cookie = $_COOKIE[AUTH_COOKIE_NAME];
    }
	if ($cookie)
	{
		$arr_cookie = explode('&', $cookie);
		foreach ($arr_cookie as $str_cookie)
		{
			$index  =strpos($str_cookie, '=');
			if ($index !== false)
			{
				$datalist[substr($str_cookie, 0, $index)] = urldecode(substr($str_cookie, $index + 1));
			}
			else
			{
				$datalist[$str_cookie] = '';
			}
		}
		// 验证码是否被篡改
		$sign = $datalist['sign'];
		$new_sign = create_sign($datalist);
		if ($sign != $new_sign)
		{	// cookie校验失败
			return null;
		}
		else
		{
			return $datalist;
		}
	}
}

// 根据明文生成密文
function create_password($pwd)
{
	return md5('84_miaosha'.$pwd);
}

/**
 * 过滤出来一个正常的正则表达式
 * @param String $str 含有特色字符的字符串
 * @return string
 */
function filteRegEx($str)
{
	$str = str_replace(array('/'), array('\\/'), $str);
	$str = '/'.$str.'/i';
	return $str;
}

/**
 * @name getClientIp
 * @desc 获得客户端ip
 * @return  string client ip
 */
function getClientIp()
{
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    return $onlineip;
}

/**
 * @name redirect
 * @desc 跳转函数
 * @param string $url 跳转的url
 * @return void
 **/
function redirect($url)
{
    if(!empty($url))
    {
        header("Location: ".$url."");
    }
    exit;
}

/**
 * @name cutstr
 * @desc 按照指定的规则切分字符串,针对UTF8. $length 为你要显示的汉字 * 3
 * @param string $string	原始字符串
 * @param int $length	切割的长度
 * @param string $suffix	后缀名
 * @return string
 */
function cutstr($string, $length, $suffix = '')
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
    $length_tmp	=	($string_len - $let_len * 2) + $let_len * 2;
    if($length_tmp > $length)
    {
        for ($k=0;$k<=($length-3);$k++)
        {
            $j++;
            if($j	>	($length-3))
            {
                break;
            }
            if (ord(substr($string,$k,1)) >= 129)
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

/**
 * @name cutstr
 * @desc 按照指定的规则切分字符串,针对UTF8. $length 为你要显示的汉字 * 3
 * @param string $string	原始字符串
 * @param int $length	切割的长度
 * @param string $suffix	后缀名
 * @return string
 * @author Sandy
 */
function ccutstr($string, $length, $suffix = '')
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

/**
 * @name yaddslashes
 * @desc 转义定符串函数
 * @param string $string
 * @return mixed
 */
function yaddslashes($string)
{
    if(!get_magic_quotes_gpc())
    {
        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = yaddslashes($val);
            }
        } else {
            $string = addslashes($string);
        }
    }
    return $string;
}

/**
 * @name getFormHash
 * @desc 生成防止跨站攻击(XSS)的字串
 * @param string $addstring 字串的附加码.建议为用户ID
 */
function getFormHash($addstring = '')
{
    static $hash ;
    if(empty($hash))
    {
        $domain = defined('ROOT_DOMAIN') ? ROOT_DOMAIN : '' ;
        $clientip = getClientIp();
        $hash = substr(md5($domain . '_' . $clientip . '_' . $addstring), 0, 12);
    }
    return $hash ;
}

/**
 * @name getReqInt
 * @desc 接收用户输入值-整型
 * @param string $name	变量的名称
 * @param string $method  接收方式：GET & POST & REQUEST
 * @param int $default	默认值
 * @param int $min	最小值
 * @param int $max	最大值
 */
function getReqInt($name, $method = 'REQUEST', $default = 0, $min = false, $max = false)
{
    $method = strtoupper($method);
    switch ($method)
    {
        case 'POST':
            $variable = $_POST;
            break;
        case 'GET':
            $variable = $_GET;
            break;
        default:
            $variable = $_REQUEST;
            break;
    }
    if(!isset($variable[$name]) || $variable[$name] == '')
    {
        return $default ;
    }
    $value = intval($variable[$name]) ;
    if($min !== false)
    {
        $value = max($value, $min);
    }
    if($max !== false)
    {
        $value = min($value, $max);
    }
    return $value;
}

/**
 * 获取XML文档对象的数据
 * @param simplexml $obj XML文档对象
 * @author wangyi
 * @return Array 节点的数据
 */
function get_object_vars_final($obj){
    if(is_object($obj)){
        $obj=get_object_vars($obj);
    }

    if(is_array($obj)){
        foreach ($obj as $key=>$value){
            $obj[$key] = get_object_vars_final($value);
        }
    }
    return $obj;
}

/**
 * 以UTF-8格式输出标准的网页，适合于输出简单提示之类的页面，只是 echo 出一个标准HTML页面
 * @param String $content 网页的主体内容
 * @param String $title 网页标题，默认是：秒杀。注意：分段标题使用“_”分割
 * @author wangyi
 */
function printHtml($content, $title='秒杀')
{
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'.$title.'</title>
</head>
<body>'.$content.'</body></html>';
    echo $html;
}

/**
 * 返回数据json格式的内容
 * @param $result
 */
function return_result($result) {
    echo json_encode($result);
    exit();
}

/**
 * 显示结果信息
 * @param $result
 */
function show_result($result, $url = '/') {
    if (isset($result['error_no']) && isset($result['error_msg'])) {
        echo '<script>
        alert("异常代码： ' . $result['error_no'] . '\n异常信息： ' . $result['error_msg'] . '");
        </script>';
    } else {
        echo '<script>
        alert("' . $result . '");
        </script>';
    }
    echo '<script>location.href="' . $url . '";</script>';
    exit();
}

/**
 * 加解密的密钥
 * @return string
 */
function signKey() {
    return pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
}

/**
 * 对问答信息进行签名
 * @param $info
 */
function signQuestion($info) {
    $key = signKey();
//    $key_size =  strlen($key);
//    echo "Key size: " . $key_size . "\n";

    $plaintext = json_encode($info);

    # 为 CBC 模式创建随机的初始向量
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);


    # 创建和 AES 兼容的密文（Rijndael 分组大小 = 128）
    # 仅适用于编码后的输入不是以 00h 结尾的
    # （因为默认是使用 0 来补齐数据）
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
        $plaintext, MCRYPT_MODE_CBC, $iv);

    # 将初始向量附加在密文之后，以供解密时使用
    $ciphertext = $iv . $ciphertext;
    # 对密文进行 base64 编码
    $ciphertext_base64 = base64_encode($ciphertext);
    return $ciphertext_base64;
}

/**
 * 将字符串解开，得到问答信息
 * @param $ciphertext
 */
function unsignQuestion($ciphertext_base64) {
    $key = signKey();


    # === 警告 ===
    # 密文并未进行完整性和可信度保护，
    # 所以可能遭受 Padding Oracle 攻击。
    # --- 解密 ---

    $ciphertext_dec = base64_decode($ciphertext_base64);

    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    # 初始向量大小，可以通过 mcrypt_get_iv_size() 来获得
    $iv_dec = substr($ciphertext_dec, 0, $iv_size);

    # 获取除初始向量外的密文
    $ciphertext_dec = substr($ciphertext_dec, $iv_size);

    # 可能需要从明文末尾移除 0
    $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
        $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

    return $plaintext_dec;
}
