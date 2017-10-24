<?php
namespace app\model\Sys;
use think\Cache;
use app\lib\Sms;
use app\lib\Snoopy;
use app\model\SysAreaModel;
use app\model\CusRoleLogModel;
use app\model\AmoFlowCusCashModel;
use think\Config;
use app\model\User\FlowModel;
use app\model\AmoFlowFutCusCashModel;
use app\lib\Model;
use app\model\AmoFlowCusComCashModel;

class CommonModel
{
    // 初始化值
    const initNumber = 0;
    // 一天秒数
    const daySecond = 86400;
    
    const hourSecond = 3600;
    
    const minSecond = 60;
    
    // 100份能转换的现金值
    const cashBull = 14;
    
    const defaultRole = 1;
    
    const minRand = 100000;
    
    const maxRand = 999999;
    
    const minute = 5;
    
    const minuteToSecond = 60;
    
    const directProfit = 1;
    const directeExpend = 2;
    /**
    * @user 获取cache缓存
    * @param $key 键名
    * @author jeeluo
    * @date 2017年3月3日下午2:37:03
    */
    public static function getCacheNumber($key) {
        return Cache::get($key) ?: self::initNumber;
    }
    
    /**
    * @user 设置cache缓存
    * @param $key 键名
    * @param $value 键值
    * @param $timeOut 过期时间
    * @author jeeluo
    * @date 2017年3月3日下午2:37:55
    */
    public static function setCacheNumber($key, $value, $timeOut) {
        return Cache::set($key, $value, $timeOut);
    }
    
    /**
    * @user 销毁cache缓存
    * @param $key 键名
    * @author jeeluo
    * @date 2017年3月3日下午2:38:55
    */
    public static function destoryNumber($key) {
        return Cache::set($key, null);
    }
    
    /**
    * @user 计算两个时间之间的差距
    * @param $begin_time 时间1 $end_time 时间2 dateTime类型
    * @author jeeluo
    * @date 2017年3月8日下午5:36:05
    */
    public static function timediff($begin_time, $end_time) {
        $begin_time = strtotime($begin_time);
        $end_time = strtotime($end_time);
        if($begin_time < $end_time) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        
        $timediff = $endtime - $starttime;
        $result['days'] = intval($timediff / self::daySecond);
        $timediff = $timediff % self::daySecond;
        $result['hours'] = intval($timediff / self::hourSecond);
        $result['hours'] = $result['hours'] < 10 ? '0'.$result['hours'] : $result['hours'];
        $timediff = $timediff % self::hourSecond;
        $result['mins'] = intval($timediff / self::minSecond);
        $result['mins'] = $result['mins'] < 10 ? '0'.$result['mins'] : $result['mins'];
        $timediff = $timediff % self::minSecond;
        $result['secs'] = $timediff % self::minSecond;
        $result['secs'] = $result['secs'] < 10 ? '0'.$result['secs'] : $result['secs'];
        
        return $result;
    }
    
    /**
    * @user 退货单号
    * @param $orderno 订单编号
    * @param $num 个数
    * @author jeeluo
    * @date 2017年3月10日上午10:49:58
    */
    public static function getReturnNo($orderno, $num) {
         return $orderno.'-T-'.$num;
     }
    
    /**
     *@function: CURL模拟POST 請求
     *@Author:   xurui[xuruiss@126.com]
     *@DateTime: 2017-03-08 am
     *@Param:    url string  接口地址
     */
    public static function post_curl ($url) {
        //优先使用curl模式发送数据
        if (function_exists('curl_init') == 1){
            $curl = curl_init();
            curl_setopt ($curl, CURLOPT_URL, $url);
            curl_setopt ($curl, CURLOPT_HEADER,0);
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
            curl_setopt ($curl, CURLOPT_TIMEOUT,5);
            $get_content = curl_exec($curl);
            curl_close ($curl);
        } else {
            $snoopy = new Snoopy();
            $snoopy->referer = 'http://www.baidu.com/';//伪装来源
            $snoopy->fetch($url);
            $get_content = $snoopy->results;
        }
        return $get_content;
    }
    
    /**
    * @user curl模拟get 请求
    * @param $url 请求地址
    * @author jeeluo
    * @date 2017年4月6日上午9:49:35
    */
    public static function get_curl($url) {
        $curl = curl_init();
        //设置选项，包括URL
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($curl);
        //释放curl句柄
        curl_close($curl);
        
        return $output;
    }
    
    /**
    * @user 身份证号码格式化
    * @param
    * @author jeeluo
    * @date 2017年3月11日下午5:17:20
    */
    public static function identity_format($number) {
        return str_replace(substr($number, 6, 8), "********", $number);
    }

    public static function mobile_format($number) {
        return substr_replace($number,'****',3,4);
    }
    
    /**
    * @user 银行卡号码格式化
    * @param 
    * @author jeeluo
    * @date 2017年3月22日上午11:17:08
    */
    public static function bank_format($number) {
        return str_replace(substr($number, 0, -4), "**** **** **** ", $number);
    }
    
    /**
    * @user
    * @param 获取银行卡后4位数字
    * @author jeeluo
    * @date 2017年7月13日上午11:38:13
    */
    public static function last4_bank($number) {
        return substr($number, strlen($number)-4, 4);
    }
    
    /**
    * @user 根据地区 获取编号
    * @param $area 地区
    * @author jeeluo
    * @date 2017年3月14日下午3:09:08
    */
    public static function getAreaCode($area, $offset="3") {
        $area_arr = array_filter(explode(",", $area));
        $lastArea = $area_arr[-1+$offset];
        
        $sysAreaOBJ = new SysAreaModel();
        $areaInfo = $sysAreaOBJ->getRow(array("areaname" => $lastArea), "id");
        
        return $areaInfo;
    }
    
    /**
    * @user 验证身份证号码
    * @param $idnumber 身份证号码
    * @author jeeluo
    * @date 2017年3月14日下午9:52:38
    */
    public static function validation_filter_idcard($idnumber) {
        
        // 暂时
        // return true;
        
        if(strlen($idnumber) == 18) {
            return self::idcard_checksum18($idnumber);
        } else if(strlen($idnumber) == 15) {
            return self::idcard_15to18($idnumber);
        } else {
            return false;
        }
    }
    
    /**
    * @user 检验18号数身份号
    * @param $idnumber 身份证号码
    * @author jeeluo
    * @date 2017年3月14日下午9:50:52
    */
    private function idcard_checksum18($idnumber) {
        if(strlen($idnumber) != 18) {
            return false;
        }
        $idcard_base = substr($idnumber, 0, 17);
        if(self::idcard_verify_number($idcard_base) != strtoupper(substr($idnumber, 17, 1))) {
            return false;
        } else {
            return true;
        }
    }
    
    /**
    * @user 校验身份证号码
    * @param  
    * @author jeeluo
    * @date 2017年3月14日下午9:52:02
    */
    private function idcard_verify_number($idcard_base) {
        if(strlen($idcard_base) != 17) {
            return false;
        }
        // 加权因子
        $factor = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
        // 校验码对应值
        $verify_number_list=array('1','0','X','9','8','7','6','5','4','3','2');
        $checksum = 0;
        for($i = 0; $i < strlen($idcard_base); $i++) {
            $checksum += substr($idcard_base,$i,1) * $factor[$i];
        }
        $mod=$checksum % 11;
        $verify_number=$verify_number_list[$mod];
        return $verify_number;
    }
    
    /**
    * @user 升级15位身份证号码到18位
    * @param $idnumber 身份证号码
    * @author jeeluo
    * @date 2017年3月14日下午9:51:24
    */
    private function idcard_15to18($idnumber) {
        if(strlen($idnumber) != 15) {
            return false;
        } else {
            if(array_search(substr($idnumber,12,3),array('996','997','998','999')) !== false){
                $idcard=substr($idnumber,0,6).'18'.substr($idnumber,6,9);
            }else{
                $idcard=substr($idnumber,0,6).'19'.substr($idnumber,6,9);
            }
        }
        $idnumber = $idnumber.self::idcard_verify_number($idnumber);
        return $idnumber;
    }
    
    /**
    * @user 银行卡校验
    * @param 
    * @author jeeluo
    * @date 2017年3月22日下午2:03:00
    */
    public static function account_bank_validate($number) {
//         return true;
        if(!is_numeric($number)) {
            return false;
        }
        
        if(strlen($number) <= 15 || strlen($number) > 19) {
            // 紧急处理小银行帐号、老帐号和对公帐号
            if(preg_match("/^[\d]{8,27}$/", $number)) {
                return true;
            }
            return false;
        }
        
        $arr_no = str_split($number);
        $last_n = $arr_no[count($arr_no)-1];
        krsort($arr_no);
        $i = 1;
        $total = 0;
        foreach ($arr_no as $n){
            if($i%2==0){
                $ix = $n*2;
                if($ix>=10){
                    $nx = 1 + ($ix % 10);
                    $total += $nx;
                }else{
                    $total += $ix;
                }
            }else{
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $total *= 9;
        
        return $total % 10 == $last_n;
    }
    
    /**
    * @user 检查银行卡 对应的银行(个人账户)
    * @param 
    * @author jeeluo
    * @date 2017年7月11日下午4:03:06
    */
    public static function account_bank_check($number) {
        if(!is_numeric($number)) {
//             return ["code" => "400", "data" => "银行卡格式不正确"];
            return ["code"=>"20016"];
        }
        $banklist = Config::get("banklist");
        
        $str = '';
        $card_6 = substr($number, 0, 6);
        if(isset($banklist[$card_6])) {
            $str = $banklist[$card_6];
        }
        
        // 可能有其它位数(8 5 4 暂时不写);
        if($str == '') {
            $card_8 = substr($number, 0, 8);
            if(isset($banklist[$card_8])) {
                $str = $banklist[$card_8];
            }
        }
        
        if($str == '') {
            $card_5 = substr($number, 0, 5);
            if(isset($banklist[$card_5])) {
                $str = $banklist[$card_5];
            }
        }
        
        if($str == '') {
            $card_4 = substr($number, 0, 4);
            if(isset($banklist[$card_4])) {
                $str = $banklist[$card_4];
            }
        }
        
        if($str == '') {
//             return ["code" => "400", "data" => "无对应银行信息"];
            return ["code"=>"20017"];
        }
        // 截取字符串数据
        $str_arr = array_filter(explode("-",$str));
        
        return ["code" => "200", "data" => $str_arr[0]];
    }
    
    /**
    * @user 实名校验
    * @param realname 真实姓名 idnumber 身份证号码 customerid 用户id
    * @author jeeluo
    * @date 2017年5月22日上午11:03:47
    */
    public static function nameauth_validate($params) {
        
        // 校验
        
        return true;
    }
    
    public static function userNameAuth($params) {
        // 假如传递用户值 说明是检验已知用户的数据
        
        if(!empty($params['customerid'])) {
            $cusInfo = Model::ins("CusCustomerInfo")->getRow(array("id"=>$params['customerid']),"isnameauth");
        
            $updateData['realname'] = $params['realname'];
            $updateData['idnumber'] = $params['idnumber'];
            $updateData['isnameauth'] = 1;
            if(!empty($cusInfo)) {
                if($cusInfo['isnameauth'] != 1) {
                    // 执行修改操作
                    Model::ins("CusCustomerInfo")->modify($updateData, array("id"=>$params['customerid']));
                }
            } else {
                $updateData['id'] = $params['customerid'];
        
                Model::ins('CusCustomerInfo')->insert($updateData);
            }
        }
        return true;
    }
    
    /**
    * @user 获取公司联系方式
    * @param 
    * @author jeeluo
    * @date 2017年3月21日上午9:42:24
    */
    public static function getCompanyPhone() {
        $company_phone = array("0" => "400-830-5066");
        return $company_phone;
    }

    /**
    * @user 获取设备一天能发起多少次短信
    * @param 
    * @author jeeluo
    * @date 2017-04-21 16:54:09
    */
    public static function getMaxDevice() {
        return 20;
    }
    
    public static function getMaxStoDevice() {
        return 15;
    }
    
    public static function getDeviceProfix() {
        return "devicenumber_";
    }
    
    public static function getStoProfix($param) {
        return "mobile_".$param."_sto";
    }
    
    /**
    * @user 昨日时间(0点0分0秒)
    * @param 
    * @author jeeluo
    * @date 2017年3月21日上午9:48:03
    */
    public static function getYesterdayTime() {
        return date('Y-m-d 00:00:00', strtotime("-1 day"));
    }
    
    /**
    * @user 今日时间(0点0分0秒)
    * @param 
    * @author jeeluo
    * @date 2017年3月21日上午9:48:29
    */
    public static function getTodayTime() {
        return date("Y-m-d 00:00:00", time());
    }
    
    /**
    * @user 本月时间(01日0点0分0秒)
    * @param 
    * @author jeeluo
    * @date 2017年3月21日上午10:05:07
    */
    public static function getMonthTime() {
        return date('Y-m-01 00:00:00', time());
    }
    
    /**
    * @user 定义初始开始时间
    * @param 
    * @author jeeluo
    * @date 2017年3月21日上午11:38:29
    */
    public static function getInitStartTime() {
        return "1970-01-01 00:00:00";
    }
    
    public static function updateEndTime($endTime) {
        return date("Y-m-d H:i:s", strtotime("+1 month", strtotime($endTime)));
    }
    
    /**
    * @user 判断是否为默认时间
    * @param $time 时间datetime
    * @author jeeluo
    * @date 2017年4月14日下午4:01:57
    */
    public static function judgeDefaultTime($time) {
        return $time == '0000-00-00 00:00:00' ? true : false;
    }
    
    /**
    * @user 时间格式化(Y-m-d)
    * @param 
    * @author jeeluo
    * @date 2017年4月13日下午3:35:40
    */
    public static function getFormatTime($time) {
        return date('Y-m-d', strtotime($time));
    }
    
    public static function getMsecTime() {
        list($msec, $sec) = explode(' ', microtime());
        $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
        return $msectime;
    }
    
    /**
    * @user 牛币转换现金
    * @param 
    * @author jeeluo
    * @date 2017年3月22日下午5:54:49
    */
    public static function bullChangeCash($bullNumber) {
        return DePrice($bullNumber / 100 * self::cashBull);
    }
    
    public static function isInteger($input){
        return(ctype_digit(strval($input)));
    }
    
    /**
    * @user 获取现金类型
    * @param 
    * @author jeeluo
    * @date 2017年3月22日下午5:56:30
    */
    public static function getCashType() {
        $typeArr = array("1", "2", "3");    // 1为现金 2为收益现金 3牛豆
        return $typeArr;
    }
    
    /**
    * @user 获取当前用户的角色值
    * @param 
    * @author jeeluo
    * @date 2017年3月24日上午10:44:01
    */
    public static function getNowCusRole($params) {
        $cusRoleLogOBJ = new CusRoleLogModel();
        $logInfo = $cusRoleLogOBJ->getRow(array("customerid" => $params['customerid']),"role","id desc");
        $result['role'] = self::defaultRole;
        if(!empty($logInfo)) {
            $result['role'] = $logInfo['role'];
        }
        return $result['role'];
    }
    
    /**
    * @user 修改市区编号
    * @param 
    * @author jeeluo
    * @date 2017年3月24日下午9:05:35
    */
    public static function updateCityCode($area_code) {
//         return substr($area_code, 0, 4).'00';
        $sysInfo = Model::ins("SysArea")->getRow(array("id" => $area_code), "parentid");
        
        // 因为app端使用的是高德地图地区编号。可能和系统的编号有些误差，所以当查询不到数据时，采取截取段(2017-06-17 14:22:18)
        if(empty($sysInfo)) {
            return substr($area_code,0,4)."00";
        }
        return $sysInfo["parentid"];
    }
    
    /**
    * @user 获取现金充值类型
    * @param 
    * @author jeeluo
    * @date 2017年3月25日下午4:08:31
    */
    public static function getCashChargeType() {
        $str = '18, 19';
        return $str;
    }
    
    public static function getCashWithdrawType() {
        $str = "45";
        return $str;
    }
    
    public static function getReturnType() {
        $str = "48, 49";
        return $str;
    }
    
    /**
    * @user 获取现金分润类型
    * @param 
    * @author jeeluo
    * @date 2017年3月28日上午11:36:02
    */
    public static function getCashProfitType() {
        $str = "1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 79";
        return $str;
    }
    
    public static function getComProfitType() {
        $str = "16, 17, 78, 80, 81";
        return $str;
    }
    
    public static function getComPayType() {
        return "20, 21";
    }
    
    public static function getStoFlowProfitType($selfRoleType) {
        if($selfRoleType == 2 || $selfRoleType == 3 || $selfRoleType == 8) {
            return "79";
        } else if($selfRoleType == 5) {
            return "78,82";
        } else if($selfRoleType == 6) {
            return "80";
        } else if($selfRoleType == 7) {
            return "81";
        }
        return "0";
    }
    
    public static function getStoShareProfitType($selfRoleType) {
        if($selfRoleType == 2 || $selfRoleType == 3 || $selfRoleType == 8) {
            return "12";
        }
        if($selfRoleType == 6) {
            return "7";
        } else if($selfRoleType == 7) {
            return "9";
        }
        return "1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15";
    }
    
    public static function getStoRecoShareProfitType($selfRoleType, $recoRoleType) {
        return "3,4";
    }
    
    public static function getStoFlowComProfitType() {
        return "17";
    }
    
    public static function getFansFlowTypeRole($recoRole) {
        if($recoRole == 1) {
            $str = "1,2";
        } else if($recoRole == 6) {
            $str = "8";
        } else if($recoRole == 7) {
            $str = "10";
        }
        return $str;
    }
    
    public static function getNrFlowTypeRole($recoRole,$type='') {
        if($recoRole == 2) {
//             $str = "1,2,3,4";
            $str = "1,2,3,4,6";
        } else if($recoRole == 4) {
            $str = "5";
        } else if($recoRole == 5) {
            $str = "12,79";
        } else if($recoRole == 6) {
            $str = "8";
        } else if($recoRole == 7) {
            $str = "10";
        }
        return $str;
    }
    
    public static function getNdFlowTypeRole($recoRole) {
        if($recoRole == 8) {
            //             $str = "1,2,3,4";
            $str = "1,2,3,4,6";
        } else if($recoRole == 4) {
            $str = "5";
        } else if($recoRole == 5) {
            $str = "12,79";
        } else if($recoRole == 6) {
            $str = "8";
        } else if($recoRole == 7) {
            $str = "10";
        }
        return $str;
    }
    
    public static function getNcFlowTypeRole($recoRole) {
        if($recoRole == 3) {
            $str = "1,2,3,4,6";
        } else if($recoRole == 4) {
            $str = "5";
        } else if($recoRole == 5) {
            $str = "12,79";
        } else if($recoRole == 6) {
            $str = "8";
        } else if($recoRole == 7) {
            $str = "10";
        }
        return $str;
    }
    
    public static function getBusFlowTypeRole($recoRole) {
        if($recoRole == 6) {
            $str = "8";
        } else if($recoRole == 7) {
            $str = "10";
        }
        return $str;
    }
    
    public static function getStoFlowTypeRole($recoRole) {
        if($recoRole == 1) {
            $str = "1,2,11";
        } else if($recoRole == 6) {
            $str = "8";
        } else if($recoRole == 7) {
            $str = "10";
        }
        return $str;
    }
    
    public static function getCountyFlowTypeRole($recoRole) {
        if($recoRole == 6) {
            $str = "8";
        } else if($recoRole == 7) {
            $str = "10";
        } else {
            $str = "7";
        }
        return $str;
    }
    
    public static function getCityFlowTypeRole($recoRole) {
        if($recoRole == 6) {
            $str = "8";
        } else if($recoRole == 7) {
            $str = "10";
        } else {
            $str = "9";
        }
        return $str;
    }
    
    /**
    * @user
    * @param unknowtype
    * @author jeeluo
    * @date 2017年4月20日下午2:36:09
    */
    public static function getComCashProfitRole() {
        $str = "0, 2, 8, 10, 21, 23, 24, 25";
        return $str;
    }
    
    /**
    * @user 粉丝推荐收益角色值
    * @param 
    * @author jeeluo
    * @date 2017年4月14日下午10:49:37
    */
    public static function getFansRecoProfitRole() {
        return "2, 6, 7";
    }
    
    /**
     * @user 牛人推荐收益角色值
     * @param
     * @author jeeluo
     * @date 2017年4月14日下午10:49:37
     */
    public static function getNrRecoProfitRole() {
        return "4, 5, 16, 22";
    }
    
    public static function getNdRecoProfitRole() {
        return "4, 5, 16, 22";
    }
    
    /**
     * @user 牛创客推荐收益角色值
     * @param
     * @author jeeluo
     * @date 2017年4月14日下午10:49:37
     */
    public static function getNcRecoProfitRole() {
        return "4, 5, 16, 22";
    }
    
    /**
     * @user 牛商推荐收益角色值
     * @param
     * @author jeeluo
     * @date 2017年4月14日下午10:49:37
     */
    public static function getBusRecoProfitRole() {
        return "15, 22";
    }
    
    /**
     * @user 牛掌柜推荐收益角色值
     * @param
     * @author jeeluo
     * @date 2017年4月14日下午10:49:37
     */
    public static function getStoRecoProfitRole() {
        return "3, 21, 22";
    }
    
    /**
     * @user 区县代理推荐收益角色值
     * @param
     * @author jeeluo
     * @date 2017年4月14日下午10:49:37
     */
    public static function getCountyRecoProfitRole() {
        return "11, 23, 24";
    }
    
    /**
     * @user 地市代理推荐收益角色值
     * @param
     * @author jeeluo
     * @date 2017年4月14日下午10:49:37
     */
    public static function getCityRecoProfitRole() {
        return "9, 23, 24";
    }
    
    /**
    * @user 获取用户分润现金总额
    * @param 
    * @author jeeluo
    * @date 2017年3月28日上午11:42:52
    */
    public static function getFlowTotalAmount($params) {
        $flowOBJ = new FlowModel();
//         $where['flowtype'] = array("in", self::getCashProfitType());
        
        $where['flowtype'] = array("in", $flowOBJ->recoFlowTypeRole($params['selfRole'], $params['recoRole']));
        $where['direction'] = self::directProfit;
        $where['userid'] = $params['customerid'];
        $where['parent_userid'] = $params['parent_userid'];

//         $where['role'] = $params['role'];
        $where['profit_role'] = array("in", $flowOBJ->recoProfitRole($params['recoRole']));
        
        if($params['recoRole'] != 6 && $params['recoRole'] != 7) {
            if($params['selfRole'] == 2 || $params['selfRole'] == 3 || $params['selfRole'] == 8) {
                $where['role'] = $params['selfRole'];
            }
        }
        
        // 查询流水现金表
        $flowCashOBJ = new AmoFlowCusCashModel();
        
        if(($params['selfRole'] == 5 && $params['recoRole'] == 1)) {
            $flowCashOBJ = new AmoFlowCusComCashModel();
        } else {
            $flowCashOBJ = new AmoFlowCusCashModel();
        }
        
//         $flowCashOBJ = new AmoFlowFutCusCashModel();
        $flowInfo = $flowCashOBJ->getRow($where, "SUM(amount) as amount ");
        
        return $flowInfo['amount'] ? DePrice($flowInfo['amount']) : '0.00';
    }
    
    /**
    * @user 发送校验码
    * @param 
    * @author jeeluo
    * @date 2017年3月27日下午4:08:34
    */
    public static function sendValidate($params) {
        if(empty($params['mobile']) || empty($params['privatekey']) || empty($params['sendType'])) {
            return ["code" => 404];
        }
        $sendTypeArr = array("login_register_");
        if(!in_array($params['sendType'], $sendTypeArr)) {
            return ["code" => 1001];
        }
        if(phone_filter($params['mobile'])) {
            return ["code" => 404];
        }
        $privatekey = strtoupper($params['privatekey']);
        $autokey = strtoupper(md5($params['mobile'].getConfigKey()));
        
        if($privatekey != $autokey) {
            return ["code" => 400];
        }

        $MessageRedis = Model::Redis("MessageValicode");
        
        // 因为添加子店需要特殊处理，所以做特殊判断
        $countNumber = self::initNumber;
        if($params['sendType'] == "sto_store_") {
            if($MessageRedis->exists(CommonModel::getStoProfix($params['mobile']))) {
                $countNumber = $MessageRedis->get(CommonModel::getStoProfix($params['mobile']));
            }

            if($countNumber >= CommonModel::getMaxStoDevice()) {
                return ["code"=>"405"];
            }
        }
        
        $randNumber = getRandNumber(self::minRand, self::maxRand);
        
        $mobile = $params['mobile'];
        
        if(Sms::send("$mobile", ["$randNumber", self::minute])) {
            return ["code" => 2001];
        } else {
            // 验证码发送成功
            $MessageRedis->set($params['sendType'].$mobile, $randNumber, self::minute * self::minuteToSecond);

            if($params['sendType'] == "sto_store_") {
                $MessageRedis->set(CommonModel::getStoProfix($params['mobile']), ++$countNumber, strtotime(date('Y-m-d', time()+86400))-time());
            }
//             CommonModel::setCacheNumber($params['sendType'].$mobile, $randNumber, self::minute * self::minuteToSecond);
        }
        return ["code" => 200];
    }
    
    /**
    * @user 根据地区编号  获取系统地区(完整areaname)
    * @param $area_code 最下级地区编号
    * @author jeeluo
    * @date 2017年3月30日下午6:02:31
    */
    public static function getSysArea($area_code) {
        $areaOBJ = new SysAreaModel();
        $areaInfo = $areaOBJ->getRow(array("id" => $area_code), "position, areaname");
        
        if(empty($areaInfo['position'])) {
            return ["code" => "10003"];
        }
        
        $position_arr = explode(" ", $areaInfo['position']);
        
        $areaStr = '';
        foreach ($position_arr as $v) {
            $tr_arr = explode("_", $v);
            $parentInfo = $areaOBJ->getRow(array("id" => $tr_arr[1]), "areaname");
            $areaStr .= $parentInfo['areaname'];
        }
        $areaStr .= $areaInfo['areaname'];
        return ["code" => "200", "data" => $areaStr];
    }
    
    /**
    * @user 获取角色订单号
    * @param 
    * @author jeeluo
    * @date 2017年4月5日下午8:11:57
    */
    public static function getRoleOrderNo($prefix) {
        return $prefix.date("YmdHis").rand(100000, 999999);
    }
    
    /**
    * @user 根据地址获取经纬度
    * @param $address 地址位置
    * @author jeeluo
    * @date 2017年4月6日上午9:57:06
    */
    public static function getAddressLngLat($address) {
        if(empty($address)) {
            return ["code" => "404", "data" => "参数有误"];
        }
        $gaomap = Config::get('map');
        
        $url = $gaomap['domain']."?address=".$address."&output=JSON&key=".$gaomap['key'];
        
        $map_json = self::get_curl($url);
        
        $map_arr = json_decode($map_json, true);
        if($map_arr['status'] == 0) {
            return ["code" => "400", "data" => "接口有误"];
        }
        
        // 获取第1个下标的数据
        $location = $map_arr['geocodes'][0]['location'];
        
        $location_arr = explode(",", $location);
        
        $result['lngx'] = $location_arr[0];
        $result['laty'] = $location_arr[1];
        return ["code" => 200, "data" => $result];
    }

    /**
     * [getLngLatAddress 根据经纬度获取地址信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-08-31T17:24:04+0800
     * @param    [type]                   $location [description]
     * @return   [type]                             [description]
     */
    public function getLngLatAddress($location){
        if(empty($location)) {
            return ["code" => "404", "data" => "参数有误"];
        }
        $gaomap = Config::get('map');

        $url = "http://restapi.amap.com/v3/geocode/regeo?key=".$gaomap['key']."&location=".$location ."&output=JSON";//
        
        $map_json = self::get_curl($url);
       
        $map_arr = json_decode($map_json, true);
       
        if($map_arr['status'] == 0) {
            return ["code" => "400", "data" => "接口有误"];
        }
        $result['city'] = $map_arr['regeocode']['addressComponent']['city'];
        $result['citycode'] =  $map_arr['regeocode']['addressComponent']['adcode'];

        return ["code" => 200, "data" => $result];
    }
    
    /**
    * @user 充值跳转H5页面
    * @param 
    * @author jeeluo
    * @date 2017年4月24日下午4:44:44
    */
    public static function getRechargeUrl() {
        return "http://www.baidu.com";
    }

    /**
     * 计算两点地理坐标之间的距离
     * @param Decimal $longitude1 起点经度
     * @param Decimal $latitude1 起点纬度
     * @param Decimal $longitude2 终点经度 
     * @param Decimal $latitude2 终点纬度
     * @param Int   $unit    单位 1:米 2:公里
     * @param Int   $decimal  精度 保留小数位数
     * @return Decimal
     */
    public static function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=1, $decimal=0){
     
        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI = 3.1415926;
         
        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;
         
        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI /180.0;
         
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
         
        $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $distance = $distance * $EARTH_RADIUS * 1000;
         
        if($unit==2){
            $distance = $distance / 1000;
        }
         
        return round($distance, $decimal);
         //     // 起点坐标
            // $longitude1 = 113.330405;
            // $latitude1 = 23.147255;
             
            // // 终点坐标
            // $longitude2 = 113.314271;
            // $latitude2 = 23.1323;
             
            // $distance = getDistance($longitude1, $latitude1, $longitude2, $latitude2, 1);
            // echo $distance.'m'; // 2342.38m
             
            // $distance = getDistance($longitude1, $latitude1, $longitude2, $latitude2, 2);
            // echo $distance.'km'; // 2.34km
    }
    
    public static function getVipBusiness($business_id) {
        $bus = Model::ins("BusBusiness")->getRow(array("id" => $business_id),"isvip");
        if($bus['isvip'] == 1) {
            return true;
        }
        return false;
    }
    
    /**
    * @user 获取地区子位置列表数据
    * @param 
    * @author jeeluo
    * @date 2017年5月22日上午10:18:03
    */
    public static function getChildArea($area_code) {
        $sysAreaList = array();
        $sysAreaList = Model::ins("SysArea")->getList(array("parentid"=>$area_code),"id");
        return $sysAreaList;
    }
    
    /**
    * @user 获取用户实名认证信息
    * @param 
    * @author jeeluo
    * @date 2017年5月22日上午10:28:31
    */
    public static function getUserNameAuth($customerid) {
        $result = array();
        // 查询用户表数据
        $cusInfo = Model::ins("CusCustomerInfo")->getRow(array("id"=>$customerid),"realname,idnumber,isnameauth");
        if(!empty($cusInfo)) {
            if($cusInfo['isnameauth'] == 1) {
                $result = $cusInfo;
            }
        }
        return $result;
    }
    
    /**
    * @user 获取用户是否有企业账户信息
    * @param 
    * @author jeeluo
    * @date 2017-05-22 20:06:39
    */
    public static function isUserCom($customerid) {
        $result['status'] = -1;
        $comAmountRole = array(4,5,6,7);
        // 查询用户角色表  是否有企业帐号
        $cusRoleList = Model::ins("CusRole")->getList(array("customerid"=>$customerid),"role");
        if(!empty($cusRoleList)) {
            foreach ($cusRoleList as $v) {
                if(in_array($v['role'], $comAmountRole)) {
                    
                    $result['status'] = 1;
                    break;
                }
            }
        }
        return $result;
    }
    
    /**
    * @user 查看数据库该区县是否已经存在代理
    * @param 
    * @author jeeluo
    * @date 2017年5月22日下午2:08:44
    */
    public static function isFindCode($join_code) {
        $agentInfo = Model::ins("CusCustomerAgent")->isFindCode($join_code);
        if(!empty($agentInfo)) {
            return ["code"=>"20008", "data"=>"代理已经存在"];
        }
        return ["code"=>"200", "data"=>""];
    }
    
    /**
    * @user 根据手机号码获取实体点是否存在
    * @param 
    * @author jeeluo
    * @date 2017年6月23日下午5:24:48
    */
    public static function getMobileExistsSto($params) {
        $cus = Model::ins("CusCustomer")->getRow(["mobile"=>$params['mobile']],"id");
        if(!empty($cus)) {
            // 查看该用户是否已经存在实体店
            $sto = Model::ins("StoBusiness")->getRow(["customerid"=>$cus['id']],"id");
            if(!empty($sto)) {
                return false;
            }
        }
        return true;
    }
    
    /**
    * @user 获取实体店分类信息
    * @param 
    * @author jeeluo
    * @date 2017年6月28日下午5:21:12
    */
    public static function getStoTypeName($params) {
        $type = Model::ins("StoCategory")->getRow(["id"=>$params['id']],"parentid,categoryname");
        if(!empty($type['parentid'])) {
            $parentType = Model::ins("StoCategory")->getRow(["id"=>$type['parentid']],"categoryname");
            
            return $parentType['categoryname'].'-'.$type['categoryname'];
        }
        return '';
    }

    /**
    * @user 获取地铁信息
    * @param 
    * @author jeeluo
    * @date 2017年6月28日下午5:05:14
    */
    public static function getMetroName($params) {
        $metro = Model::ins("SysMetro")->getRow(["id"=>$params['id']],"linename,metroname");
        if(!empty($metro)) {
            return $metro['linename'].'-'.$metro['metroname'];
        }
        return '';
    }

    /**
    * @user 获取商圈信息
    * @param 
    * @author jeeluo
    * @date 2017年6月28日下午5:05:40
    */
    public static function getDistrictName($params) {
        $district = Model::ins("SysDistrict")->getRow(["id"=>$params['id']],"district_name");
        if(!empty($district)) {
            return $district['district_name'];
        }
        return '';
    }
    
    /**
    * @user mtoken redis 有效期(半年)
    * @param 
    * @author jeeluo
    * @date 2017年8月16日下午4:03:25
    */
    public static function mtokenRedisOutTime() {
        return 86400*30*6;
    }
    
    public static function nopassApplyButtonStr($params) {
        if($params['mobile'] == "15566666661" || $params['mobile'] == "15566666662") {
            return true;
        }
        return false;
    }
    
    public static function passValicode() {
        return "170220";
    }
    
    public static function getWeixinWebType() {
        return "weixin_web";
    }
}