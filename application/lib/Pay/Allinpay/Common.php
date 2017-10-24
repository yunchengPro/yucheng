<?php

namespace app\lib\Pay\Allinpay;

use app\lib\Model;

class Common{

	public static function curl_old($param){
		//print_r($param);
		$url 	= $param['url'];
		$data 	= $param['data'];
		$method = $param['method']!=''?$param['method']:"POST";
        //$url = "http://www.baidu.com";
        echo $url."\n";
        print_r($data);
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); //设置请求的URL
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出 
       
        switch($method){
            case 'GET':
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($curl, CURLOPT_POST,true);   
                curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");   
                curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");   
                curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
                break;
        }

        $result = curl_exec($curl);//执行预定义的CURL
        //echo "result:".$result;
        curl_close($curl);

        //echo self::curl_https("https://www.job1001.com");

        return json_decode($result,true);
	}

    /** curl 获取 https 请求 
    * @param String $url 请求的url 
    * @param Array $data 要發送的數據 
    * @param Array $header 请求时发送的header 
    * @param int $timeout 超时时间，默认30s 
    */ 
    public static function curl($param){ 

        $url     = $param['url'];
        $data    = $param['data'];
        $timeout = !empty($param['timeout'])?$param['timeout']:30;

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); // 从证书中检查SSL加密算法是否存在 
        curl_setopt($ch, CURLOPT_URL, $url); 
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 

        $response = curl_exec($ch); 

        if($error=curl_error($ch)){ 
            die($error); 
        } 

        curl_close($ch); 

        return $response; 

    } 

    // 支付日志
    public static function addPayLog($param){

        Model::ins("AllinpayPayLog")->insert([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
                "addtime"=>date("Y-m-d H:i:s"),
                "amount"=>$param['amount'],
                "pay_type"=>$param['pay_type'],
                "request_content"=>json_encode($param['data'],JSON_UNESCAPED_UNICODE),
            ]);

    }

    // 支付回调日志
    public static function returnPayLog($param){

        Model::ins("AllinpayPayLog")->update([
                "retcode"=>$param['retcode'],
                "retmsg"=>$param['retmsg'],
                "return_content"=>json_encode($param['data'],JSON_UNESCAPED_UNICODE),
            ],[
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
            ]);
    }

    // 判断日志是否存在
    public static function checkProcessServletLog($param){

        $row = Model::ins("AllinpayPsLog")->getRow([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
            ],"count(*) as count");

        if($row['count']>0)
            return true;
        else
            return false;
    }

    // 代付日志
    public static function addProcessServletLog($param){

        Model::ins("AllinpayPsLog")->insert([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
                "addtime"=>date("Y-m-d H:i:s"),
                "amount"=>$param['amount'],
                "account_number"=>$param['account_number'],
                "account_name"=>$param['account_name'],
                "request_content"=>json_encode($param['data'],JSON_UNESCAPED_UNICODE),
                "trx_type"=>$param['trx_type'],
                "query_sn"=>$param['query_sn'],
            ]);
    }

    // 代付回调日志
    public static function returnProcessServletLog($param){

        Model::ins("AllinpayPsLog")->update([
                "retcode"=>$param['retcode'],
                "retmsg"=>$param['retmsg'],
                "return_content"=>json_encode($param['data'],JSON_UNESCAPED_UNICODE),
            ],[
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
            ]);
    }


    // 判断日志是否存在
    public static function checkProcessServletResultLog($param){

        $row = Model::ins("AllinpayPsresultLog")->getRow([
                "query_sn"=>$param['query_sn'],
            ],"count(*) as count");

        if($row['count']>0)
            return true;
        else
            return false;
    }

    // 代付日志
    public static function addProcessServletResultLog($param){

        Model::ins("AllinpayPsresultLog")->insert([
                "orderno"=>$param['orderno'],
                "querytime"=>date("Y-m-d H:i:s"),
                "querycount"=>1,
                "request_content"=>json_encode($param['data'],JSON_UNESCAPED_UNICODE),
                "query_sn"=>$param['query_sn'],
            ]);
    }

    // 代付回调日志
    public static function returnProcessServletResultLog($param){

        Model::ins("AllinpayPsresultLog")->update([
                "retcode"=>$param['retcode'],
                "retmsg"=>$param['retmsg'],
                "return_content"=>json_encode($param['data'],JSON_UNESCAPED_UNICODE),
            ],["query_sn"=>$param['query_sn']]);
    }

    // 支付回调日志
    public static function addNotifyLog($param){

        Model::ins("AllinpayNotifyLog")->insert([
                "orderno"=>$param['orderno'],
                "addtime"=>date("Y-m-d H:i:s"),
                "amount"=>$param['amount'],
                "status"=>$param['status'],
                "return_content"=>json_encode($param['data'],JSON_UNESCAPED_UNICODE),
            ]);

    }

	//获取指定长度的随机字符串
    public static function getRandChar($length){
       $str = null;
       $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
       $max = strlen($strPol)-1;

       for($i=0;$i<$length;$i++){
        $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
       }

       return $str;
    }

	/*
        生成签名
    */
    public static function getSign($Obj,$key='',$sign_type=1)
    {        
        if($sign_type==1){
            if(!empty($key))
                $Obj['key'] = $key;
        }

        $Obj = self::paraFilter($Obj);

        foreach ($Obj as $k => $v)
        {
            $Parameters[strtolower($k)] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = self::formatBizQueryParaMap($Parameters, false);
        //echo "【string】".$String."</br>";
        //签名步骤二：在string后加入KEY
        if($sign_type==2)
            $String = $String."&key=".$key;
        //echo "【string】 ".$String."</br>";
        //签名步骤三：MD5加密
        $result_ = strtoupper(md5($String));
        return $result_;
    }

    // $checkempty 判断是否空
    public static function getSignQuick($Obj,$key='',$getuserid=true,$checkempty=false){

        $Obj = self::paraFilter($Obj);

        foreach ($Obj as $k => $v)
        {
            if($checkempty){
                if($v!='')
                    $Parameters[$k] = $v;
            }else{
                $Parameters[$k] = $v;
            }
        }
        //签名步骤一：按字典序排序参数
        $String = self::formatBizQueryParaMap_quick($Parameters, false,false);
        //echo "【string】".$String."</br>";
        //签名步骤二：在string后加入KEY
        if($getuserid)
            $String = "&".$String."&key=".$key."&";
        else
            $String = $String."&key=".$key;
        //echo "【string】 ".$String."</br>";
        //签名步骤三：MD5加密
        $result_ = strtoupper(md5($String));
        return $result_;
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    public static function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $key == "sign_type" )continue;
            else    $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    //将数组转成uri字符串
    public static function formatBizQueryParaMap($paraMap, $urlencode,$ksortflag = true)
    {
        $buff = "";
        if($ksortflag)
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

    public static function formatBizQueryParaMap_quick($paraMap, $urlencode,$ksortflag = true)
    {
        $buff = "";
        if($ksortflag)
            ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
               $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) 
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @return 提交表单HTML文本
     */
    public static function buildRequestForm($param) {

        $gatewayUrl = $param['gatewayUrl'];
        $para_temp  = $param['para_temp'];
        
//         $sHtml = "<form id='allinpaysubmit' name='allinpaysubmit' action='".$gatewayUrl."?charset=utf8' method='POST'>";
        $sHtml = "<form id='allinpaysubmit' name='allinpaysubmit' action='".$gatewayUrl."?charset=utf8' method='POST'>";
        while (list ($key, $val) = each ($para_temp)) {
            //if (false === self::checkEmpty($val)) {
                //$val = $this->characet($val, $this->postCharset);
                $val = str_replace("'","&apos;",$val);
                //$val = str_replace("\"","&quot;",$val);
                $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>\n";
            //}
        }
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml."<input type='submit' value='ok' style='display:none;''></form>";
        
        $sHtml = $sHtml."<script>document.forms['allinpaysubmit'].submit();</script>";

        return $sHtml;
    }
    
    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *  if is null , return true;
     **/
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
}