<?php
namespace app\lib\Thirdparty\Tool;

class AnubisApiHelper {
	public static function generateSign($appId, $salt, $secretKey) {
		$seed = 'app_id=' . $appId . '&salt=' . $salt . '&secret_key=' . $secretKey;
		return md5(urlencode($seed));
	}

	public static function generateBusinessSign($appId, $token, $urlencodeData, $salt) {
		$seed = 'app_id=' . $appId . '&access_token=' . $token
	      		. '&data=' . $urlencodeData . '&salt=' . $salt;
		return md5($seed);
	}

	public static function getSalt(){
		return mt_rand(1000, 9999);
	}
}
