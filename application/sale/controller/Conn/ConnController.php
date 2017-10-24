<?php
namespace app\sale\controller\Conn;

use think\Config;
use app\sale\ActionController;

use app\lib\Model;
use app\lib\QRcode;
use app\lib\Img;
use app\model\User\UserInfoModel;

class ConnController extends ActionController {
    
    /**
    * @user 构造函数
    * @param 
    * @author jeeluo
    * @date 2017年9月2日下午2:57:16
    */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 购买记录
     * @Author   zhuangqm
     * @DateTime 2017-10-10T15:19:29+0800
     * @return   [type]                   [description]
     */
    public function buyconlistAction(){

        $viewData = [
            'title' => '购买记录',
           
        ];
        return $this->view($viewData);
    }

    /**
     * 购买记录
     * @Author   zhuangqm
     * @DateTime 2017-10-10T15:19:29+0800
     * @return   [type]                   [description]
     */
    public function getbuyconlistAction(){
        
        $myuserinfo = UserInfoModel::userBaseInfo(['customerid'=>$this->userid]);
       
        $where['customerid'] = $this->userid;
       
        $list = Model::ins("ConOrder")->pageList($where,"*","addtime desc");
        foreach ($list['list'] as $key => $value) {
            $list['list'][$key]['count'] = DePrice($value['count']);
            $list['list'][$key]['totalamount'] = DePrice($value['totalamount']);
        }
        $maxPage = ceil($list['total']/20);
        $list['maxPage'] = $maxPage;
        return $this->json("200",$list);
    }
    
    /**
     * 购买金牛
     * @Author   zhuangqm
     * @DateTime 2017-10-10T15:13:22+0800
     * @return   [type]                   [description]
     */
    public function buyconAction() {
        $conn_coupon = Config::get("conn_coupon");
      
        $con_config = Config::get("conn");
        //$myuserinfo = UserInfoModel::userBaseInfo(['customerid'=>$this->userid]);
        
        // $userinfo['data']['headerpic'] = Img::url($userinfo['data']['headerpic']);

        $topuser = Model::ins('CusRelation')->getRow(['customerid'=>$this->userid,'role'=>1,'parentrole'=>2],'parentid');
       
        $userinfo = UserInfoModel::userBaseInfo(['customerid'=>$topuser['parentid']]);
        $userinfo['data']['headerpic'] = Img::url($userinfo['data']['headerpic']);
       
        $role_arr = [
            '1'=>'消费者',
            '2'=>'商家',
            '3'=>'经理',
            '4'=>'总监'
        ];
        $role = $role_arr[$userinfo['data']['role']];
        $con_config = Config::get("conn");
        $viewData = [
            'userinfo' => $userinfo['data'],
            'title'    => '购买金牛',
            'role'     => $role,
            //'mobile'   => $myuserinfo['data']['mobile'],
            'businessid'=>$topuser['parentid'],
            'con_price'=>$con_config['con_price'],
            'con_more_price'=>$con_config['con_more_price'],
            'con_maxamout'=>$con_config['con_maxamout'],
            'con_minamout'=>$con_config['con_minamout'],
            'customerid'=>$this->userid,
            'conn_coupon'=>$conn_coupon,
            'con_config'=>$con_config

        ];

        return $this->view($viewData);
    }
    
    /**
     * 购买金牛--提交 商家购买 | 转发给消费者购买 同一个入口
     * @Author   zhuangqm
     * @DateTime 2017-10-10T15:22:17+0800
     * @return   [type]                   [description]
     */
    public function buyconSubAction() {
        $con_config = Config::get("conn");
        $amount = $this->params['amount'];
        $userid = $this->userid;
        $pay_voucher = $this->params['pay_voucher']; // 转账购买
        //$mobile = $this->params['mobile']; // 这个参数暂时没有用
        $businessid = $this->params['businessid'];

        // 金额判断
        if(!is_numeric($amount))
            return $this->json("30019",[],"购买金额有误");

        // if($amount< $con_config['con_minamout'] )
        //     return $this->json("404",[],"最低购买".$con_config['con_minamout']);

        $saleamount = Model::new("Amount.Amount")->getUserAmount($userid,'saleamount');

        

        if($con_config['con_maxamout'] - DePrice($saleamount) < $amount)
            return $this->json("30020",[],"每人最高只能购买".$con_config['con_maxamout']);

        // if($mobile!=''){

        //     if(phone_filter($mobile)){
        //         // 手机验证不通过https://ylom.com/finance/mywt/
        //         return $this->json("404",[],"手机号格式不正确");
        //     }

        //     // 判断用户是否已注册
        //     $customer = Model::ins("CusCustomer")->getRow(["mobile"=>$mobile],"id");

        //     // 创建用户
        //     if(empty($customer)){
        //         return $this->json("404",[],"用户信息不存在请重新登录");
        //     }else{
        //         $mobile_userid = $customer['id'];
        //     }

        // }
        if($pay_voucher != 1 && $pay_voucher != 2)
            return $this->json("30022",[],"购买方式错误");

        $result = Model::new("Conn.Order")->addOrder([
            "customerid"=>$userid,
            "amount"=>$amount,
            //"mobile"=>$mobile,
            //"mobile_userid"=>$mobile_userid,
            "pay_voucher"=>$pay_voucher,
            "businessid"=>$businessid
        ]);

        return $this->json($result['code'],$result['orderno'],$result['msg']);
    }

    /**
     * 生成二维码
     * @Author   zhuangqm
     * @DateTime 2017-10-10T17:09:20+0800
     * @return   [type]                   [description]
     */
    public function qrcodeAction(){
        $orderno = $this->params['orderno'];

        if(empty($orderno))
            exit("参数有误");

        $domain = Config::get("domain");

        echo QRcode::png($domain['domain']."/Conn/Conn/conorder?orderno=".$orderno);
        exit;
    }

    /**
     * 订单详情
     * @Author   zhuangqm
     * @DateTime 2017-10-10T16:17:20+0800
     * @return   [type]                   [description]
     */
    public function conorderAction(){
        $orderno = $this->params['orderno'];

        if(empty($orderno))
            exit("参数有误");

        $conorder = Model::ins("ConOrder")->getRow(["orderno"=>$orderno],"*");

        if(empty($conorder))
            exit("记录不存在");

        $viewData = [
            
        ];
        return $this->view($viewData);
    }   

    /**
     * 转让记录
     * @Author   zhuangqm
     * @DateTime 2017-10-10T15:20:54+0800
     * @return   [type]                   [description]
     */
    public function transferconlistAction(){

        $viewData = [
            
        ];
        return $this->view($viewData);
    }

    /**
     * [转让记录 description]
     * @Author   zhuangqm
     * @DateTime 2017-10-10T20:01:56+0800
     * @return   [type]                   [description]
     */
    public function gettransferconlistAction(){

        $list = Model::ins("ConTransfer")->pageList(["fromuserid"=>$this->userid],"*","addtime desc");

        return $this->json("200",$list);
    }

    /**
     * 转让金牛
     * @Author   zhuangqm
     * @DateTime 2017-10-10T15:20:30+0800
     * @return   [type]                   [description]
     */
    public function transferconAction(){
        $viewData = [
            
        ];
        return $this->view($viewData);
    }

    /**
     * 转让金牛--提交
     * @Author   zhuangqm
     * @DateTime 2017-10-10T15:22:17+0800
     * @return   [type]                   [description]
     */
    public function transferconSubAction() {
        
        $mobile = $this->params['mobile'];
        $count  = $this->params['count'];

        if(empty($mobile) || empty($count))
            return $this->json("404");

        // 1 用户为商家才能转让 2 余额足够
        $userrole = Model::new("User.User")->getUserRoleID(["customerid"=>$this->userid]);

        if($userrole!=2)
            return $this->json("406");

        // 判断用户是否已注册
        $customer = Model::ins("CusCustomer")->getRow(["mobile"=>$mobile],"id");
        $mobile_userid = 0;
        // 创建用户
        if(empty($customer)){
            $result = Model::new("User.User")->addUser([
                "mobile"=>$mobile,
                "role"=>1,
                "businessid"=>$userid,
            ]);
            $mobile_userid = $result['customerid'];
        }else{
            $mobile_userid = $customer['id'];
        }

        if($mobile_userid>0){
            // 转让钻石
            Model::new("Conn.Con")->transferCon([
                "fromuserid"=>$this->userid,
                "touserid"=>$mobile_userid,
                "amount"=>$count,
            ]);
        }
        return $this->json("200",[
            
        ]);
    }

    /**
     * [showbuyconAction 核对订单]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-12T16:35:11+0800
     * @return   [type]                   [description]
     */
    public function showbuyconAction(){
        $orderno = $this->params['orderno'];
        if(empty($orderno))
            return $this->json(404,'','参数错误');
        $con_config = Config::get("conn");
        $viewData = [
            'orderno' => $orderno,
            'con_config'=>$con_config
        ];
        return $this->view($viewData);
    }

    /**
     * [getbuyconorderAction 获取订单信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-12T16:46:06+0800
     * @return   [type]                   [description]
     */
    public function getbuyconorderAction(){

        $orderno = $this->params['orderno'];

        if(empty($orderno))
            return $this->json("404",'','参数错误');

        $order = Model::ins('ConOrder')->getRow(['orderno'=>$orderno]);

        $order['count'] = DePrice($order['count']);
        $order['totalamount'] = DePrice($order['totalamount']);

        if(empty($order))
            return $this->json("404",'','参数错误');
        return $this->json("200",$order);
    }

    /**
     * [ajaxcanbuyconAction 异步判断还能买多少]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-18T15:17:23+0800
     * @return   [type]                   [description]
     */
    public function ajaxcanbuyconAction(){
        $customerid = $this->params['customerid'];
        $amount = $this->params['amount'];

        if(empty($customerid))
            return $this->json("404",[],"参数错误");

        if(empty($amount))
            return $this->json("404",[],"参数错误");

        $con_config = Config::get("conn");

        // if($amount < $con_config['con_minamout'] )
        //     return $this->json("404",[],"最低购买".$con_config['con_minamout']);

        $saleamount = Model::new("Amount.Amount")->getUserAmount($customerid,'saleamount');
       

        if($con_config['con_maxamout'] - DePrice($saleamount) < $amount)
            return $this->json("30020",[],"每人最高只能购买".$con_config['con_maxamout']);

        return $this->json("200");
    }
}