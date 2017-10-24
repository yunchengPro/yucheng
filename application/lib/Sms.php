<?php
/**
 * 发送短信类
 */
namespace app\lib;

use \think\Config;

use app\lib\Sms\Yuntongxun\CCPRestSmsSDK;

class Sms{

	/**
	 * 发送短信
	 * @Author   zhuangqm
	 * @DateTime 2017-02-24T09:55:21+0800
	 * @param    [type]                   $phone [手机号码 string  多手机号用","分隔]
	 * @param    [type]                   $param [数组，相关参数]
	 * @return   [type]                   true|false  [发送成功或者失败]
	 */
	public static function send($phone,$param,$tempId=''){
		return self::yuntongxun(
				$phone,
				$param,
				$tempId
			);
	}

	protected static function yuntongxun($to,$datas,$tempId=''){

		$sms_config = Config::get("sms.yuntongxun");

		$tempId = $tempId!=''?$tempId:$sms_config['tempId'];
		
		$accountSid 	= $sms_config['accountSid'];
		$accountToken 	= $sms_config['accountToken'];
		$appId 			= $sms_config['appId'];
		$serverIP 		= $sms_config['serverIP'];
		$serverPort 	= $sms_config['serverPort'];
		$softVersion 	= $sms_config['softVersion'];


		$rest = new CCPRestSmsSDK($serverIP,$serverPort,$softVersion);
		$rest->setAccount($accountSid,$accountToken);
		$rest->setAppId($appId);

		// 发送模板短信
		//echo "Sending TemplateSMS to $to <br/>";
		$result = $rest->sendTemplateSMS($to,$datas,$tempId);
		if($result == NULL ) {
			echo "result error!";
			return false;
		}
		if($result->statusCode!=0) {
			//echo "error code :" . $result->statusCode . "<br>";
			//echo "error msg :" . $result->statusMsg . "<br>";
			//TODO 添加错误处理逻辑
			return false;
		}else{
			//echo "Sendind TemplateSMS success!<br/>";
			// 获取返回信息
			$smsmessage = $result->TemplateSMS;
			//var_dump($smsmessage);
			//echo "dateCreated:".$smsmessage->dateCreated."<br/>";
			//echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
			//TODO 添加成功处理逻辑
			if($smsmessage->statusCode == '000000'){
				return true;
			}else{
				return false;
			}
		}
	}
}