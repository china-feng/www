<?php
/**
 * AES 加密
 * 
 */
namespace logic\Encryption;

class AES {
	
	//key长度应该为16
	public static $key = "Rpmy6kDD8I4nVp79";
	
	//iv长度应该为16
	public static $iv = "kPPrXtK2OYrG9JWe";
	
	//配合Java端，使用128位，256位java端默认是不支持的
	public static $method = 'AES-128-CBC';
	
    private static $sign_key = 'YIjtYA332ANVaBLqhC6HHnIgINJmPqdU';

	/**
	 * AES加密
	 * 
	 * 可传入自定义密码
	 * 
	 * $key
	 */
	public static function encrypt($data = array(), $key = ''){
		$cleartext = self::getLastStrData($data);
		$key = empty($key) ? self::$key : $key;		
		$encrypted = openssl_encrypt($cleartext, self::$method, $key, OPENSSL_RAW_DATA, self::$iv);
		return base64_encode($encrypted);		
	}
	
	/**
	 * AES解密
	 * 
	 * 可传入自定义密码
	 * 
	 * $key
	 */
	public static function decrypt($encrypted,$key = ''){		
		$key = empty($key) ? self::$key : $key; 
		$encrypted = base64_decode($encrypted); 
		$decrypted = openssl_decrypt($encrypted, self::$method, $key, OPENSSL_RAW_DATA, self::$iv); 
		$decrypt = urldecode($decrypted);
		parse_str($decrypt, $arr);
		if (empty($arr)) {
			return false;
		}
		if (empty($arr['sign'])) {
			return false;
		}
		$request_sign = $arr['sign'];
		unset($arr['sign']);
		if (self::makeSign($arr) != $request_sign) {
			return false;
		}
		$arr['sign'] = $request_sign;
		return $arr;
	}

	/**
	 * 将数组 拼接成字符串 
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	private static function array2str($data = array()){
		if (empty($data)) {
			return false;
		}
		$str = '';
		foreach ($data as $k => $v) {
			$str .= '&' . $k . '=' .$v;
		}
		return trim($str, '&');
	}

	/**
	 * 制作签名
	 * @param  array  $data 需要签名的数组
	 * @return [type]       [description]
	 */
	public static function makeSign($data = array()){
		//先自然排序
		ksort($data);

		//拼接字符串
		$str = self::array2str($data);
		if ($str === false) {
			return false;
		}

		//连接密钥MD5加密
		return strtoupper(md5($str . self::$sign_key));
	}

	/**
	 * 得到最后要加密的字符串
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public static function getLastStrData($data = array()){
		$sign = self::makeSign($data);
		if ($sign == false) {
			return false;
		}
		// var_dump($sign);
		ksort($data);
		$data['sign'] = $sign;

		return http_build_query($data);
	}


}
