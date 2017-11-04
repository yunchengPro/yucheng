<?php

namespace app\form;
use app\lib\form\BaseForm;

class FormToken{
	
	/**
	提交时候验证
	if($this->check_formtoken($_tokename,$_tokenvalue)){
		
	}
	
	添加表单时生成
	$formtoken = $this->Btoken("activity-subject-addsubject");
		
	
	
	**/
	
	public static $expiry=86400;//有效时间
	
	/**
     * 生成表单token
     *
     * @var param  约定的参数 参数方式如：memner-index-add memner-index-add-编号 或者其他
     */
	public static function formtoken($param){
		session_start();
		//生成表达token名
		$_tokenname =  md5(($param!=''?$param:"_from_tokenname"));
		//生成表单token
		$_tokenvalue = md5(rand(100,999).time().rand(100,999));

   	 	$_form_token = @$_SESSION['_formtoken'];//session 存json数组
   	 	$_form_token_arr = $_form_token!=''?json_decode($_form_token,true):array();
   	 	$_form_token_arr[$_tokenname] = array(
   	 											"_tokenvalue"=>$_tokenvalue,
   	 											"_token_expirytime"=>(time()+self::$expiry)
   	 										);
		$_SESSION['_formtoken'] = json_encode($_form_token_arr);
		return "<input type='hidden' name='_tokenname' value='".$_tokenname."'>\n<input type='hidden' name='_tokenvalue' value='".$_tokenvalue."'>\n";
	}

	/**
	 * 检测token是否正常
	 */
	public static function check_formtoken($_tokename,$_tokenvalue){
		session_start();
		if(empty($_tokename) || empty($_tokenvalue)) return false;
		
		$_form_token = $_SESSION['_formtoken'];//session 存json数组
   	 	
   	 	$_form_token_arr = $_form_token!=''?json_decode($_form_token,true):array();
   	 	
		if(!empty($_form_token_arr) && $_form_token_arr[$_tokename]['_tokenvalue']!='' && $_form_token_arr[$_tokename]['_tokenvalue']==$_tokenvalue && $_form_token_arr[$_tokename]['_token_expirytime']>=time()){
			
			//删除token
			unset($_form_token_arr[$_tokename]);
			
			$_SESSION['_formtoken'] = json_encode($_form_token_arr);
			return true;
		}else{
			
			return false;
		}
	}
}
