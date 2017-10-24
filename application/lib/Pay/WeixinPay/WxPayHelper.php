<?php

namespace App\Lib\Pay\WeixinPay;

use Config;

use DOMDocument;

class WxPayHelper{



    /*
    配置参数
    */
    var $config = array();

    //获取预支付订单
    public function paytouser($param){
       
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";

        $this->config = Config::get("weixin");
        //生成回调地址
        $notify_url = $this->config['notify_url'];

        //32位随机数
        $onoce_str = $this->getRandChar(32);

        $data["mch_appid"] = $this->config["appid"];
        $data["mchid"] = $this->config['mchid'];
        $data["nonce_str"] = $onoce_str;
        $data['partner_trade_no'] = $param['pay_order_id'];
        $data['openid'] = $param['openid'];
        $data['check_name'] = "NO_CHECK";
        $data['amount'] = $param['pay_price'];
        $data["desc"] = "用户提现";
        $data['spbill_create_ip'] = $_SERVER["REMOTE_ADDR"];

        $s = $this->getSign($data, false);
        $data["sign"] = $s;
        //print_r($data);
        $xml = $this->arrayToXml($data);

        //$response = $this->postXmlCurl($xml, $url);
        $response = $this->curl_post_ssl($url,$xml);

        //$logName = "response_".date("Y_m_d_H_i_s").".log";
        //ModelLog::saveLog($logName,$response);
        //将微信返回的结果xml转成数组
        return $this->xmlstr_to_array($response);
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $key == "sign_type" || $val == "")continue;
            else    $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    //执行第二次签名，才能返回给客户端使用
    public function getOrder($prepayId){
        $data["appid"] = $this->config["appid"];
        $data["noncestr"] = $this->getRandChar(32);;
        $data["package"] = "Sign=WXPay";
        $data["partnerid"] = $this->config['mch_id'];
        $data["prepayid"] = $prepayId;
        $data["timestamp"] = time();
       
        $s = $this->getSign($data, false);
        $data["sign"] = $s;
        
        return $data;
    }

    /*
        生成签名
    */
    public function getSign($Obj)
    {        
        $Obj = $this->paraFilter($Obj);

        foreach ($Obj as $k => $v)
        {
            $Parameters[strtolower($k)] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);

        $String = $this->formatBizQueryParaMap($Parameters, false);
        //echo "【string】 =".$String."</br>";
        //签名步骤二：在string后加入KEY
        $String = $String."&key=".$this->config['api_key'];
        //echo "【string】 =".$String."</br>";
        //签名步骤三：MD5加密
        $result_ = strtoupper(md5($String));
        return $result_;
    }

    //获取指定长度的随机字符串
    public function getRandChar($length){
       $str = null;
       $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
       $max = strlen($strPol)-1;

       for($i=0;$i<$length;$i++){
        $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
       }

       return $str;
    }

    /**
     * [verifyNotify description]
     * @Author   zhuangqm
     * @Datetime 2016-11-08T14:01:26+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function verifyNotify($param){
        if(empty($param)) {//判断POST来的数组是否为空
            return false;
        }

        $this->config = Config::get("pay_weixin");

        $sign = $param['sign'];
        unset($param['sign']);

        $param_filter = $this->paraFilter($param);

     
        $newSign = $this->getSign($param_filter);

        if($newSign == $sign){
            return true;
        }else{
            return false;
        }



    }


    //数组转xml
    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            $xml.="<".$key.">".$val."</".$key.">";

            /*
             if (is_numeric($val))
             {
                $xml.="<".$key.">".$val."</".$key.">"; 

             }
             else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
                */
        }
        $xml.="</xml>";
        return $xml; 
    }

    /**
     * 基于证书的请求方式
     * @Author   zhuangqm
     * @Datetime 2016-11-23T17:30:17+0800
     * @param    [type]                   $url     [description]
     * @param    [type]                   $vars    [description]
     * @param    integer                  $second  [description]
     * @param    array                    $aHeader [description]
     * @return   [type]                            [description]
     */
    function curl_post_ssl($url, $vars, $second=30,$aHeader=array()){

        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        
        //以下两种方式需选择一种
        
        //第一种方法，cert 与 key 分别属于两个.pem文件
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLCERT,$this->config['apiclient_cert']);
        //默认格式为PEM，可以注释
        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
        curl_setopt($ch,CURLOPT_SSLKEY,$this->config['apiclient_key']);
        
        //第二种方式，两个文件合成一个.pem文件
        //curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
     
        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
     
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
        $data = curl_exec($ch);
        if($data){
            curl_close($ch);
            return $data;
        }
        else { 
            $error = curl_errno($ch);
            echo "call faild, errorCode:$error\n"; 
            curl_close($ch);
            return false;
        }
    }

    //post https请求，CURLOPT_POSTFIELDS xml格式
    public function postXmlCurl($xml,$url,$second=30)
    {       
        //初始化curl        
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);

        //返回结果
        if($data)
        {
            curl_close($ch);
            return $data;
        }
        else 
        { 
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }

    /*
        获取当前服务器的IP
    */
    public function get_client_ip()
    {
        if ($_SERVER['REMOTE_ADDR']) {
        $cip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
        $cip = getenv("REMOTE_ADDR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
        $cip = getenv("HTTP_CLIENT_IP");
        } else {
        $cip = "unknown";
        }
        return $cip;
    }

    //将数组转成uri字符串
    public function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
               $v = urlencode($v);
            }
            $buff .= strtolower($k) . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) 
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    /**
    xml转成数组
    */
    public function xmlstr_to_array($xmlstr) {
		$doc = new DOMDocument();
		$doc->loadXML($xmlstr);
		return $this->domnode_to_array($doc->documentElement);
    }
	
    public function domnode_to_array($node) {
		$output = array();
		switch ($node->nodeType) {
			case XML_CDATA_SECTION_NODE:
			case XML_TEXT_NODE:
				$output = trim($node->textContent);
				break;
			case XML_ELEMENT_NODE:
				for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
					$child = $node->childNodes->item($i);
					$v = $this->domnode_to_array($child);
					if(isset($child->tagName)) {
						$t = $child->tagName;
						if(!isset($output[$t])) {
							$output[$t] = array();
						}
						$output[$t][] = $v;
					}elseif($v) {
						$output = (string) $v;
					}
				}
				
				if(is_array($output)) {
					if($node->attributes->length) {
						$a = array();
						foreach($node->attributes as $attrName => $attrNode) {
							$a[$attrName] = (string) $attrNode->value;
						}
						$output['@attributes'] = $a;
					}
					foreach ($output as $t => $v) {
						if(is_array($v) && count($v)==1 && $t!='@attributes') {
							$output[$t] = $v[0];
						}
					}
				}
				break;
		}
		return $output;
    }
}






