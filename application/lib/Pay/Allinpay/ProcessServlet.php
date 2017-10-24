<?php
/**
 * 通联通 代付
 */
namespace app\lib\Pay\Allinpay;

use think\Config;
use app\lib\Pay\Allinpay\Common;

use app\lib\Pay\Allinpay\allinpayInter\libs\PhpTools;

class ProcessServlet{

	protected $config = [];

	public function __construct() {
		
		//加载配置
		$this->config = Config::get("allinpay");

	}

    /*
    通联推荐使用下面的机制进行查询：
    超时实时交易结果的查询：
       对于某笔超时的实时交易需要查询结果，超时后3分钟内，相邻查询时间间隔不应短于20秒
       在超时后3-10分钟内，相邻查询时间间隔不应短于1分钟
       在超时后10分钟以上的，相邻查询时间间隔不应短于5分钟
       对于30分钟内通联一直返回1002的，应确认该笔交易失败，通联没有成功接收，应立刻停止继续查询。
    批量交易结果的查询
       建议至少间隔5分钟查询一次
       对于50分钟内通联一直返回1002的，应确认该笔交易失败，通联没有成功接收，应立刻停止继续查询。
    对于查询过于频繁的客户，通联会向对方提出改进的建议，坚持不改的，通联将会把该客户列入黑名单，列入黑名单的客户通过本接口进行查询交易将受到严厉的限制。
    */
    /**
     * 查询交易结果
     * @Author   zhuangqm
     * @DateTime 2017-07-31T14:21:02+0800
     * @param    [type]                   $param [
     *                                           orderno
     *                                           query_sn 交易流水号
     * ]
     * @return   [type]                          [description]
     */
    public function getOrderResult($param){

        if(Common::checkProcessServletResultLog($param)){
            return ["code"=>"201","msg"=>"该请求已提交，请勿重复提交"];
        }

        $return = [];

        $TRX_CODE = "200004";
        
        $data['INFO']['TRX_CODE']  = $TRX_CODE;
        $data['INFO']['VERSION']  = "03";
        $data['INFO']['DATA_TYPE']  = "2";
        $data['INFO']['LEVEL']  = "6";
        $data['INFO']['USER_NAME']  = $this->config['ProcessServlet_username'];
        $data['INFO']['USER_PASS']  = $this->config['ProcessServlet_userpwd'];


        //$data['QTRANSREQ']['TRANS_SUM']['BUSINESS_CODE'] = $this->config['ProcessServlet_businesscode'];
        $data['QTRANSREQ']['MERCHANT_ID'] = $this->config['cusid_servlet']; // 通联通 商户号
        $data['QTRANSREQ']['QUERY_SN']    = $param['query_sn']; // 要查询的交易流水
        /*
        交易状态条件, 0成功,1失败, 2全部,3退票,4代付失败退款,5代付退票退款,6委托扣款7提现
         */
        $data['QTRANSREQ']['STATUS']      = '2';
        $data['QTRANSREQ']['START_DAY']   = '';
        $data['QTRANSREQ']['END_DAY']     = '';
        
        //添加记录
        Common::addProcessServletResultLog([
                "orderno"=>$param['orderno'],
                "data"=>$data,
                "query_sn"=>$param['query_sn'],
            ]);

        /*
        $this->certFile         = $config['certFile'];
        $this->privateKeyFile   = $config['privateKeyFile'];
        $this->password         = $config['password'];
        $this->apiUrl           = $config['apiUrl'];
         */
        $tools = new PhpTools([
                "certFile"=>$this->config['ProcessServlet_certFile'],
                "privateKeyFile"=>$this->config['ProcessServlet_privateKeyFile'],
                "password"=>$this->config['ProcessServlet_password'],
                "apiUrl"=>$this->config['ProcessServlet_apiurl'],
            ]);
        $response = $tools->send($data);

        //print_r($response);

        if($response!=FALSE){
            //echo  '验签通过，请对返回信息进行处理';
            //下面商户自定义处理逻辑，此处返回一个数组
            
        }else{
            //print_r("验签结果：验签失败，请检查通联公钥证书是否正确");
            //exit;
            return [
                "code"=>'400',
                "msg"=>"验签结果：验签失败，请检查通联公钥证书是否正确",
            ];
        }

        Common::returnProcessServletResultLog([
                "query_sn"=>$param['query_sn'],
                "retcode"=>$response['AIPG']['QTRANSRSP']['QTDETAIL']['RET_CODE'],
                "retmsg"=>$response['AIPG']['QTRANSRSP']['QTDETAIL']['ERR_MSG'],
                "data"=>$response,
            ]);
        

        if($response['AIPG']['QTRANSRSP']['QTDETAIL']['RET_CODE']=='0000'){
            //单笔的处理结果
            
            $return = [
                "code"=>"200",
                "msg"=>$response['AIPG']['QTRANSRSP']['QTDETAIL']['ERR_MSG'],
                "response"=>$response,
            ];
        }else{
            //echo $response['AIPG']['INFO']['ERR_MSG'];
            $return = [
                "code"=>'202',
                "msg"=>$response['AIPG']['QTRANSRSP']['QTDETAIL']['ERR_MSG'],
                "response"=>[],
            ];
        }

        return $return;
    }

	/**
     * [批量 - 生成代付订单]
     * @Author   zhuangqm
     * @DateTime 2017-07-10T15:02:34+0800
     * @param    [type]                   $param [
     *                                           orderno
     *                                           userid
     *                                           pay_price
     *                                           account_no
     *                                           account_name
     *                                           summary
     *                                           batch_list = [ ] 该数组不为空时表示批量
     *                                 ]
     *                                    
     * @return   [type]                          [description]
     */
	public function getPayOrder($param){

        $return = [];

        if(Common::checkProcessServletLog($param)){
            return ["code"=>"400","msg"=>"该请求已提交，请勿重复提交"];
        }

        $TRX_CODE = "100002";
        
        $data['INFO']['TRX_CODE']  = $TRX_CODE;
        $data['INFO']['VERSION']  = "03";
        $data['INFO']['DATA_TYPE']  = "2";
        $data['INFO']['LEVEL']  = "6";
        $data['INFO']['USER_NAME']  = $this->config['ProcessServlet_username'];
        $data['INFO']['USER_PASS']  = $this->config['ProcessServlet_userpwd'];

        /*
        必须全局唯一，商户提交的批次号必须以商户号开头以保证与其他商户不冲突，一旦冲突交易将无法提交；建议格式：商户号+时间+固定位数顺序流水号。该字段值用于后续的查询交易、对账文件等的唯一标识，对应通联系统中的交易文件名，可以在通联系统交易查询页面查询到该值
         */
        $query_sn = self::getREQSN($this->config['cusid_servlet']);
        $data['INFO']['REQ_SN']  = $query_sn;
        //$data['INFO']['SIGNED_MSG'] = "";
        

        $data['BODY']['TRANS_SUM']['BUSINESS_CODE'] = $this->config['ProcessServlet_businesscode'];
        $data['BODY']['TRANS_SUM']['MERCHANT_ID'] = $this->config['cusid_servlet']; // 通联通 商户号
        $data['BODY']['TRANS_SUM']['SUBMIT_TIME'] = date("YmdHis");
        
        if(isset($param['batch_list']) && !empty($param['batch_list'])){

            $TOTAL_ITEM = 0;
            $TOTAL_SUM = 0;
            foreach($param['batch_list'] as $k=>$v){
                if($v['account_no']!='' && $v['account_name']!='' && $v['pay_price']>0){
                    $TOTAL_ITEM+=1;
                    $TOTAL_SUM = $TOTAL_SUM+$v['pay_price'];
                }
            }

            $data['BODY']['TRANS_SUM']['TOTAL_ITEM'] = $TOTAL_ITEM;
            $data['BODY']['TRANS_SUM']['TOTAL_SUM']  = $TOTAL_SUM;

            $sn = 1;
            foreach($param['batch_list'] as $k=>$v){
                if($v['account_no']!='' && $v['account_name']!='' && $v['pay_price']>0){
                    $TRANS_DETAIL = [];
                    $TRANS_DETAIL['SN']        = $this->initSN($sn);
                    //$data['BODY']['TRANS_DETAILS']['TRANS_DETAIL']['E_USER_CODE']        = "0001";
                    $TRANS_DETAIL['BANK_CODE'] = ""; // 存折必须填写
                    $TRANS_DETAIL['ACCOUNT_NO'] = $v['account_no']; // 账号
                    $TRANS_DETAIL['ACCOUNT_NAME'] = $v['account_name']; // 账号名
                    $TRANS_DETAIL['PROVINCE'] = ""; // 账号
                    $TRANS_DETAIL['CITY'] = ""; // 账号
                    $TRANS_DETAIL['ACCOUNT_PROP'] = "0"; // 账号属性 0私人，1公司。不填时，默认为私人0。
                    $TRANS_DETAIL['AMOUNT'] = $v['pay_price'];
                    if(!empty($param['summary']))
                        $TRANS_DETAIL['SUMMARY'] = $v['summary'];

                    $data['BODY']['TRANS_DETAILS']['TRANS_DETAIL'][] = $TRANS_DETAIL;

                    $sn++;
                }
            }

        }else{
            $data['BODY']['TRANS_SUM']['TOTAL_ITEM'] = "1";
            $data['BODY']['TRANS_SUM']['TOTAL_SUM'] = $param['pay_price'];

            $data['BODY']['TRANS_DETAILS']['TRANS_DETAIL']['SN']        = "0001";
            //$data['BODY']['TRANS_DETAILS']['TRANS_DETAIL']['E_USER_CODE']        = "0001";
            $data['BODY']['TRANS_DETAILS']['TRANS_DETAIL']['BANK_CODE'] = ""; // 存折必须填写
            $data['BODY']['TRANS_DETAILS']['TRANS_DETAIL']['ACCOUNT_NO'] = $param['account_no']; // 账号
            $data['BODY']['TRANS_DETAILS']['TRANS_DETAIL']['ACCOUNT_NAME'] = $param['account_name']; // 账号名
            $data['BODY']['TRANS_DETAILS']['TRANS_DETAIL']['PROVINCE'] = ""; // 账号
            $data['BODY']['TRANS_DETAILS']['TRANS_DETAIL']['CITY'] = ""; // 账号
            $data['BODY']['TRANS_DETAILS']['TRANS_DETAIL']['ACCOUNT_PROP'] = "0"; // 账号属性 0私人，1公司。不填时，默认为私人0。
            $data['BODY']['TRANS_DETAILS']['TRANS_DETAIL']['AMOUNT'] = $param['pay_price'];
            if(!empty($param['summary']))
                $data['BODY']['TRANS_DETAILS']['TRANS_DETAIL']['SUMMARY'] = $param['summary'];
        }
        

        //print_r($data);
        Common::addProcessServletLog([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
                "amount"=>$param['pay_price'],
                "trx_type"=>$TRX_CODE,
                "data"=>$data,
                "query_sn"=>$query_sn,
            ]);

        /*
        $this->certFile         = $config['certFile'];
        $this->privateKeyFile   = $config['privateKeyFile'];
        $this->password         = $config['password'];
        $this->apiUrl           = $config['apiUrl'];
         */
        $tools = new PhpTools([
                "certFile"=>$this->config['ProcessServlet_certFile'],
                "privateKeyFile"=>$this->config['ProcessServlet_privateKeyFile'],
                "password"=>$this->config['ProcessServlet_password'],
                "apiUrl"=>$this->config['ProcessServlet_apiurl'],
            ]);
        $response = $tools->send($data);


        //print_r($response);
        /*
        echo "=========response======\n";
        print_r($response);
        echo "@@@@@@@@@@@@@@@@@@@@@@@@\n";*/
        if($response!=FALSE){
            //echo  '验签通过，请对返回信息进行处理';
            //下面商户自定义处理逻辑，此处返回一个数组
            
        }else{
            //print_r("验签结果：验签失败，请检查通联公钥证书是否正确");
            //exit;
            return [
                "code"=>'400',
                "msg"=>"验签结果：验签失败，请检查通联公钥证书是否正确",
            ];
        }

        Common::returnProcessServletLog([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
                "retcode"=>$response['AIPG']['INFO']['RET_CODE'],
                "retmsg"=>$response['AIPG']['INFO']['ERR_MSG'],
                "data"=>$response,
            ]);
        
        
        /*echo "===========结果===========\n";
        print_r($response);*/

        if($response['AIPG']['INFO']['RET_CODE']=='0000'){
            $return = [
                "code"=>"200",
                "msg"=>$response['AIPG']['INFO']['ERR_MSG'],
            ];
        }else{
            //echo $response['AIPG']['INFO']['ERR_MSG'];
            $return = [
                "code"=>$response['AIPG']['INFO']['RET_CODE'],
                "msg"=>$response['AIPG']['INFO']['ERR_MSG'],
            ];
        }

        return $return;
	}


    /**
     * [单笔实时代付请求]
     * @Author   zhuangqm
     * @DateTime 2017-07-10T15:02:34+0800
     * @param    [type]                   $param [
     *                                           orderno
     *                                           userid
     *                                           pay_price
     *                                           account_no
     *                                           account_name
     *                                           summary
     *                                           
     *                                 ]
     * @return   [type]                          [description]
     */
    public function getTimePayOrder($param){

        $return = [];

        if(Common::checkProcessServletLog($param)){
            return ["code"=>"400","msg"=>"该请求已提交，请勿重复提交"];
        }

        $TRX_CODE = "100014";
        
        $data['INFO']['TRX_CODE']  = $TRX_CODE; //单笔实时
        $data['INFO']['VERSION']  = "03";
        $data['INFO']['DATA_TYPE']  = "2";
        $data['INFO']['LEVEL']  = "6";
        $data['INFO']['USER_NAME']  = $this->config['ProcessServlet_username'];
        $data['INFO']['USER_PASS']  = $this->config['ProcessServlet_userpwd'];

        /*
        必须全局唯一，商户提交的批次号必须以商户号开头以保证与其他商户不冲突，一旦冲突交易将无法提交；建议格式：商户号+时间+固定位数顺序流水号。该字段值用于后续的查询交易、对账文件等的唯一标识，对应通联系统中的交易文件名，可以在通联系统交易查询页面查询到该值
         */
        $query_sn = self::getREQSN($this->config['cusid_servlet']);
        $data['INFO']['REQ_SN']  = $query_sn;
        //$data['INFO']['SIGNED_MSG'] = "";
        

        $data['TRANS']['BUSINESS_CODE'] = $this->config['ProcessServlet_businesscode'];
        $data['TRANS']['MERCHANT_ID'] = $this->config['cusid_servlet']; // 通联通 商户号
        $data['TRANS']['SUBMIT_TIME'] = date("YmdHis");
        
        $data['TRANS']['ACCOUNT_NO'] = $param['account_no']; // 账号
        $data['TRANS']['ACCOUNT_NAME'] = $param['account_name']; // 账号名

        $data['TRANS']['ACCOUNT_PROP'] = "0"; // 账号属性 0私人，1公司。不填时，默认为私人0。
        $data['TRANS']['AMOUNT'] = $param['pay_price'];

        if(!empty($param['summary']))
            $data['TRANS']['SUMMARY'] = $param['summary'];

        //print_r($data);
        Common::addProcessServletLog([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
                "amount"=>$param['pay_price'],
                "trx_type"=>$TRX_CODE,
                "data"=>$data,
                "query_sn"=>$query_sn,
            ]);

        /*
        $this->certFile         = $config['certFile'];
        $this->privateKeyFile   = $config['privateKeyFile'];
        $this->password         = $config['password'];
        $this->apiUrl           = $config['apiUrl'];
         */
        $tools = new PhpTools([
                "certFile"=>$this->config['ProcessServlet_certFile'],
                "privateKeyFile"=>$this->config['ProcessServlet_privateKeyFile'],
                "password"=>$this->config['ProcessServlet_password'],
                "apiUrl"=>$this->config['ProcessServlet_apiurl'],
            ]);
        $response = $tools->send($data);


        /*echo "=========response======\n";
        print_r($response);
        echo "@@@@@@@@@@@@@@@@@@@@@@@@\n";*/
        if($response!=FALSE){
            //echo  '验签通过，请对返回信息进行处理';
            //下面商户自定义处理逻辑，此处返回一个数组
            
        }else{
            /*print_r("验签结果：验签失败，请检查通联公钥证书是否正确");
            exit;*/
            return [
                "code"=>'400',
                "msg"=>"验签结果：验签失败，请检查通联公钥证书是否正确",
            ];
        }
        

        Common::returnProcessServletLog([
                "orderno"=>$param['orderno'],
                "userid"=>$param['userid'],
                "retcode"=>$response['AIPG']['INFO']['RET_CODE'],
                "retmsg"=>$response['AIPG']['INFO']['ERR_MSG'],
                "data"=>$response,
            ]);

        /*echo "===========结果===========\n";
        print_r($response);*/

        if($response['AIPG']['INFO']['RET_CODE']=='0000'){
            $return = [
                "code"=>"200",
                "msg"=>$response['AIPG']['INFO']['ERR_MSG'],
            ];
        }else{
            //echo $response['AIPG']['INFO']['ERR_MSG'];
            $return = [
                "code"=>$response['AIPG']['INFO']['RET_CODE'],
                "msg"=>$response['AIPG']['INFO']['ERR_MSG'],
            ];
        }

        return $return;

    }

    protected function initSN($sn){
        $len = strlen($sn);
        if($len<4){
            for($i=1; $i<=(4-$len); $i++) { 
                $sn="0".$sn; 
            } 
        }
        return $sn;
    }

    protected function getREQSN($key){
        return $key.time().Common::getRandChar(10);
    }

    /**
     * 数组转xml标签
     * @Author   zhuangqm
     * @DateTime 2017-07-07T17:07:42+0800
     * @param    [type]                   $arr [description]
     * @return   [type]                        [description]
     */
    protected function arrarToXml($arr){
        $xml = '<?xml version="1.0" encoding="GBK"?>';
        $xml.="<AIPG>";
        /*foreach ($arr as $key=>$val)
        {
            if(is_array($val)){
                $xml.="<".$key.">";
                foreach($val as $k1=>$v1){
                    if(is_array($v1)){
                        $xml.="<".$k1.">";
                        foreach($v1 as $k2=>$v2){
                            if(is_array($v2)){
                                $xml.="<".$k2.">";
                                foreach($v2 as $k3=>$v3){
                                    $xml.="<".$k3.">".$v3."</".$k3.">";
                                }
                                $xml.="</".$k2.">";
                            }else{
                                $xml.="<".$k2.">".$v2."</".$k2.">";
                            }

                        }
                        $xml.="</".$k1.">";
                    }else{
                        $xml.="<".$k1.">".$v1."</".$k1.">";
                    }
                }
                $xml.="</".$key.">";
            }else{
                $xml.="<".$key.">".$val."</".$key.">";
            }
        }*/
        $xml.=$this->createXML($xml,$arr);
        $xml.="</AIPG>";
        return $xml; 
    }

    protected function createXML($xml,$arr){

        foreach($arr as $key=>$value){
            if(is_array($value)){
                $xml.="<".$key.">";
                $xml.=$this->createXML($xml,$value);
                $xml.="</".$key.">";
            }else{
                $xml.="<".$key.">".$val."</".$key.">";
            }
        }

        return $xml;
    }


    /**
    xml转成数组
    */
    protected function xmlstr_to_array($xmlstr) {
        $doc = new DOMDocument();
        $doc->loadXML($xmlstr);
        return $this->domnode_to_array($doc->documentElement);
    }
    
    protected function domnode_to_array($node) {
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