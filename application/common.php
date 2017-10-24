<?php
// 公共函数库
error_reporting(E_ERROR);

use \think\Config;
//error_reporting(E_ERROR);
/**
 * [getStringLenth 判断字符串长度]
 * @Author   ISir<673638498@qq.com>
 * @DateTime 2017-03-06T11:14:59+0800
 * @param    [type]                   $string [description]
 * @return   [type]                           [description]
 */
function getStringLength($string){
	return strlen($string);
}

function custom_number_format($number) {
     $number = sprintf("%.4f", $number);
     return $number;
}

function ForMatPrice($price){
	if($price>0){
		//$price = number_format($price,1);
        $price = sprintf("%.2f", $price);
		return $price;
	}else{
		return 0;
	} 
}

function scoresFormat($scores){
    if($scores>0){
        $scores = number_format($scores,1);
        return $scores;
    }else{
        return 0;
    } 
}

/*
 * 折扣小数点 discount int
 */
function discountFormat($discount) {
    if($discount>0) {
        $discount = number_format($discount,1);
        return $discount;
    } else {
        return 0;
    }
}

/*
 * 手机号码规范
 */
function phone_format($number) {
//     return str_replace(substr($number, 3, 5), "*****", $number);
    return substr_replace($number, "*****", 3, 5);
}

/*
 * 手机号码过滤
 */
function phone_filter($phone) {
//     if(preg_match("/^1[34578]\d{9}$/", $phone)) {
//         return false;
//     }
    if(preg_match("/^((13[0-9])|(14[0|1|5|6|7|8])|(15[0-9])|(16[6])|(17[0-9])|(18[0-9])|(19[8|9]))\d{8}$/", $phone)) {
        return false;
    }
    return true;
}

/*
 * 生成随机数
 */
function getRandNumber($min, $max) {
    return rand($min, $max);
}

function getConfigKey() {
    return Config::get('key.app_key');
}

/**
 * @user 营业格式int化
 * @param
 * @author jeeluo
 * @date 2017年3月27日下午5:28:32
 */
function business_int_datetime($time) {
    $time_arr = explode(":", $time);
    if(empty($time_arr)) {
        return 0;
    } else {
        $hour = intval($time_arr[0]);

        return $hour*100+$time_arr[1];
    }
}

/**
 * 转为小数价格（元为单位）
 * @Author   zhuangqm
 * @DateTime 2017-03-08T14:20:47+0800
 */
function DePrice($price){
	if($price>0){
		$price = $price/100;
		$price = sprintf("%.2f", $price);
		if($price>0)
			return $price;
		else
			return 0;
	}else{
		return 0;
	}
}

/**
 * 转为整数价格（分为单位）
 * @DateTime 2017-03-08T15:11:28+0800
 * @Author   zhuangqm
 * @param    [type]                   $price [description]
 */
function EnPrice($price){
	return floor($price*100);
}

function SubstrPrice($price) {
    return substr($price, 0, -3);
}

/**
 * 转为整数价格（分为单位）
 * @DateTime 2017-03-08T15:11:28+0800
 * @Author   zhuangqm
 * @param    [type]                   $price [description]
 */
function EnAdminPrice($price){
    return $price*100;
}

/*
 * 获取格式化后的当前时间
 */
function getFormatNow() {
    return date('Y-m-d H:i:s', time());
}


/**
 * @Function :格式化输出
 * @Author: xurui[xuruiss@126.com]
 * @DateTime: 2017-03-08 am
 * @param   target       string  打印标识
 * @param   obj          object  打印对象(任意php数据类型)
 * @param   is_format    int     0: 不格式化输出;1:格式化输出;
 * @param   is_var_dump  int     0: print_r;1:var_dump;
 * @param   is_die       int     0:不终止后续代码执行1:打印完后终止运行后续代码;
 * @return  void
 */
function dump ($target = 0,$obj = '',$is_format = 1,$is_var_dump = 0,$is_die = 1) {
    if (!empty($target)) echo $target;
    if (!empty($is_format)) echo '<pre>';
    if (!empty($is_var_dump)) var_dump($obj);else  print_r($obj);
    if (!empty($is_die)) die();
}

//判断是否微信平台
function is_weixin(){  
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {  
            return true;  
    }    
    return false;  
}  

// 判断是否支付宝平台
function is_alipay() {
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Alipay') !== false ) {
        return true;
    }
    return false;
}

/*
* 对内容的img表情图片地址做转换
*/
function textimg($text){
	$str = (string) '\"';

    $text = str_replace($str, '"', $text);
    return $text;
}

/**
 * [imgLazyload 商品详情替换成延时加载的图片]
 * @Author   ISir<673638498@qq.com>
 * @DateTime 2017-04-25T14:15:50+0800
 * @param    [type]                   $text [description]
 * @return   [type]                         [description]
 */
function imgLazyload($text){

    $pattern='/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/i'; 
    preg_match_all($pattern,$text,$match); 
    
    if(!empty($match[0])){
        foreach($match[0] as $url){
            $tmp_url = str_replace('src=','class="lazy" data-original=',$url); 
            $text = str_replace($url,$tmp_url,$text);
        }
    }
    return $text;
}


/**
 * [business_hour_format 营业时间格式化]
 * @Author   ISir<673638498@qq.com>
 * @DateTime 2017-03-11T10:47:59+0800
 * @return   [type]                   [description]
 */
function business_hour_format($time){
    
    if($time == 0){
        return "00:00";
    }else{

        $time = (string) $time;
        
        $min  = substr($time,-2,2); 
        
        $hour = str_replace($min,'',$time);
       
        if($hour < 10 || $hour == '0')
            $hour = '0'.$hour;
        $string = $hour .':' . $min;
       
        return $string;
    }
}

/**
 * 只保留字符串首尾字符，隐藏中间用*代替（两个字符时只显示第一个）
 * @param string $user_name 姓名
 * @return string 格式化后的姓名
 */
function substr_cut($user_name){
    $strlen     = mb_strlen($user_name, 'utf-8');
    $firstStr     = mb_substr($user_name, 0, 1, 'utf-8');
    $lastStr     = mb_substr($user_name, -1, 1, 'utf-8');
    return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
}

/**
 * [imgLazyload  去掉图片样式]
 * @Author   ISir<673638498@qq.com>
 * @DateTime 2017-04-25T14:15:50+0800
 * @param    [type]                   $text [description]
 * @return   [type]                         [description]
 */
function imgStyleReplace($text){

    $pattern='/<[img|IMG].*?style=[\'|\"](.*?)[\'|\"].*?[\/]?>/i'; 
    preg_match_all($pattern,$text,$match); 

   // print_r($match);

    if(!empty($match[0])){
        foreach($match[0] as $key => $url){
           
            $tmp_url = str_replace($match[1][$key],'',$url); 
            $text = str_replace($url,$tmp_url,$text);
        }
    }

    $pattern_h='/<[img|IMG].*?height=[\'|\"](.*?)[\'|\"].*?[\/]?>/i'; 

    preg_match_all($pattern_h,$text,$match_h); 
    
    if(!empty($match_h[0])){
        foreach($match_h[0] as $key => $url){
            
            $tmp_url = str_replace($match_h[1][$key],'',$url); 

            $text = str_replace($url,$tmp_url,$text);

        }
    }

    return $text;
}