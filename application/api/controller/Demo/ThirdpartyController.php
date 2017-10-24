<?php
namespace app\api\controller\Demo;
use app\api\ActionController;
use app\lib\Model;
use app\lib\Thirdparty\Api as ThirdpartyApi;

class ThirdpartyController extends ActionController
{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    
    /**
     * 添加门店
     * @Author   zhuangqm
     * @DateTime 2017-09-19T10:16:51+0800
     * @return   [type]                   [description]
     */
    public function chainstoreAction(){
        $ThirdpartyApi = new ThirdpartyApi();

        $result = $ThirdpartyApi->chain_store([
            "name"=>"小牛造饭",
            "contactPhone"=>"15013883804",
            "address"=>"广东省深圳市南山区软件园一期3m",
            "longitude"=>"116.416357",
            "latitude"=>"39.928353",
        ]);

        print_r($result);
    }

    public function addorderAction(){

        // 113.951327,22.548573

        $dataArray = array(
              'transport_info' => array(
                'transport_name' => '小牛造饭',
                'transport_address' => '广东省深圳市南山区软件园一期3m',
                'transport_longitude' => 121.5156496362,
                'transport_latitude' => 31.2331643501,
                'position_source' => 1,
                'transport_tel' => '15013883804',
                'transport_remark' => '备注'
              ),
              'receiver_info' => array(
                'receiver_name' => 'jiabuchong',
                'receiver_primary_phone' => '13900000000',
                'receiver_second_phone' => '13911111111',
                'receiver_address' => '太阳',
                'receiver_longitude' => 121.5156496362,
                'position_source' => 3,
                'receiver_latitude' => 31.2331643501
              ),
              'items_json' => array(
                array(
                  'item_name' => '苹果',
                  'item_quantity'=> 5,
                  'item_price' => 9.50,
                  'item_actual_price' => 10.00,
                  'is_need_package' => 1,
                  'is_agent_purchase' => 0
                ),
                array(
                  'item_name' => '香蕉',
                  'item_quantity'=> 20,
                  'item_price' => 100.00,
                  'item_actual_price' => 300.59,
                  'is_need_package' => 1,
                  'is_agent_purchase' => 0
                )
              ),
              'partner_remark' => '天下萨拉',
              'partner_order_code' => '1234567890xx124',     // 第三方订单号, 需唯一
              //'notify_url' => 'http://vpcb-anubis-web-base-2.vm.elenet.me:5000',     //第三方回调 url地址
              'order_type' => 1,
              'order_total_amount' => 50.00,
              'order_actual_amount' => 48.00,
              'order_weight'=> 1.0,
              'is_invoiced' => 1,
              'invoice' => '饿了么',
              'order_payment_status' => 1,
              'order_payment_method' => 1,
              'require_payment_pay' => 50.00,
              'goods_count' => 4,
              'is_agent_payment' => 1,
              'require_receive_time' => strtotime('+1 day') * 1000  //注意这是毫秒数
        );

        $ThirdpartyApi = new ThirdpartyApi();

        $result = $ThirdpartyApi->addOrder($dataArray);

        print_r($result);
    }
    
    /**
     * 取消订单
     * @Author   zhuangqm
     * @DateTime 2017-09-19T15:58:10+0800
     * @return   [type]                   [description]
     */
    public function cancelorderAction(){

      $ThirdpartyApi = new ThirdpartyApi();

      $result = $ThirdpartyApi->cancelOrder([
          "partner_order_code"=>"1234567890xx124",
          "order_cancel_reason_code"=>"0",
          "order_cancel_code"=>"0",
          "order_cancel_description"=>"订单取消原因描述",
          "order_cancel_time"=>microtime(true)*10000,
      ]);

        print_r($result);
    }

    /**
     * 查询订单
     * @Author   zhuangqm
     * @DateTime 2017-09-19T15:58:01+0800
     * @return   [type]                   [description]
     */
    public function queryorderAction(){

      $ThirdpartyApi = new ThirdpartyApi();

      $result = $ThirdpartyApi->QueryOrder([
          "partner_order_code"=>"1234567890xx124",
      ]);

      print_r($result);
    }

    /**
     * 查询骑手位置
     * @Author   zhuangqm
     * @DateTime 2017-09-19T16:04:06+0800
     * @return   [type]                   [description]
     */
    public function carrierAction(){

      $ThirdpartyApi = new ThirdpartyApi();

      $result = $ThirdpartyApi->carrier([
          "partner_order_code"=>"1234567890xx124",
      ]);

      print_r($result);
    }

    /**
     * 订单投诉
     * @Author   zhuangqm
     * @DateTime 2017-09-19T16:05:24+0800
     * @return   [type]                   [description]
     */
    public function complaintorderAction(){

      $ThirdpartyApi = new ThirdpartyApi();

      $result = $ThirdpartyApi->complaintOrder([
          "partner_order_code"=>"1234567890xx124",
          "order_complaint_code"=> 230,
          "order_complaint_desc"=>"订单投诉测试",
          "order_complaint_time"=> microtime(true)*10000
      ]);

      print_r($result);
    }
}
