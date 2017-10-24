<?php
// +----------------------------------------------------------------------
// |  [活动商品管理]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author jeeluo
// | @DateTime 2017-09-19
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Product;

use app\superadmin\ActionController;
use app\lib\Model;
use app\lib\Log;
use app\model\Sys\CommonModel;

class ActivityGoodsController extends ActionController {
    /**
    * @user 构造函数
    * @param 
    * @author jeeluo
    * @date 2017年9月19日下午3:21:44
    */
    public function __construct() {
        parent::__construct();
    }
    
    /**
    * @user 活动商品列表页面
    * @param 
    * @author jeeluo
    * @date 2017年9月19日下午4:43:44
    */
    public function indexAction() {
        // 筛选数据
        $where = array();
        $where = $this->searchWhere([
            "activityStatus"=>"=",
            "productname"=>"=",
            "businessname"=>"=",
        ], $where);
        
        $where["enable"] = ["<=", 1];
        
        if(!empty($where['activityStatus'])) {
            if($where['activityStatus'] == 1) {
                $where['enable'] = 1;
                $where['starttime'] = ["<=",getFormatNow()];
                $where['endtime'] = [">=",getFormatNow()];
            } else if($where['activityStatus'] == 2) {
                $where['enable'] = 1;
                $where['starttime'] = [">=",getFormatNow()];
                $where['endtime'] = [">=", getFormatNow()];
            } else if($where['activityStatus'] == 3) {
                $where['enable'] = 1;
                $where['starttime'] = ["<=",getFormatNow()];
                $where['endtime'] = ["<=", getFormatNow()];
            }
        }
        unset($where['activityStatus']);
        
        if(!empty($where['productname'])) {
            $where['productid'] = ["in", "select id from pro_product where productname like '%".$where['productname']."%'"];
            unset($where['productname']);
        }
        
        if(!empty($where['businessname'])) {
            // 假如搜索了商家名称
            $where["businessid"] = ["in", "select id from bus_business where businessname like '%".$where['businessname']."%'"];
            unset($where['businessname']);
        }
        
        // 获取数据列表
        $list = Model::ins("ProProductBuy")->pageList($where,"*","addtime desc");
        
        if(!empty($list['list'])) {
            $num = 1;
            $ProProductModel = Model::ins("ProProduct");
            $BusBusinessModel = Model::ins("BusBusiness");
            foreach ($list['list'] as $k => $v) {
                // 获取商品信息
                $proproductInfo = $ProProductModel->getRow(["id"=>$v['productid']],"productname");
                $list['list'][$k]['productname'] = $proproductInfo['productname'];
                // 获取商家信息
                $busBusiness = $BusBusinessModel->getRow(["id"=>$v['businessid']],"businessname");
                $list['list'][$k]['businessname'] = $busBusiness['businessname'];
                
                $list['list'][$k]['saleprice'] = $v['saleprice'] > 0 ? DePrice($v['saleprice']) : '0.00';
                $list['list'][$k]['prouctprice'] = $v['prouctprice'] > 0 ? DePrice($v['prouctprice']) : '0.00';
                $list['list'][$k]['supplyprice'] = $v['supplyprice'] > 0 ? DePrice($v['supplyprice']) : '0.00';
                $list['list'][$k]['bullamount'] = $v['bullamount'] > 0 ? DePrice($v['bullamount']) : '0.00';
                
                $list['list'][$k]['num'] = $num;
                $num++;
            }
        }
        
        $viewData = array(
            "title"=>"活动商品",
            "pagelist"=>$list['list'],
            "total"=>$list['total']
        );
        return $this->view($viewData);
    }
    
    public function addActivityGoodsAction() {
        $viewData = [
            "title" => "添加活动商品",
            "actionUrl" => "/Product/ActivityGoods/addGoodsInfo"
        ];
        return $this->view($viewData);
    }
    
    /**
    * @user 添加活动商品
    * @param 
    * @author jeeluo
    * @date 2017年9月20日下午4:35:39
    */
    public function addGoodsInfoAction() {
        $params = $this->params; 
        $nowttime = date('Y-m-d H:i:s');
        if($params['starttime']<=$nowttime){
             $this->showError("活动开始时间必须大于当前时间");
        }
        if(empty($params['starttime']) || empty($params['endtime'])) {
            $this->showError("活动时间不能为空");
        }
        
        $starttime = strtotime($params['starttime']);
        $endtime = strtotime($params['endtime']);
        
        if($starttime >= $endtime) {
            $this->showError("活动结束时间必须大于开始时间");
        }
        
        if(empty($params['productid'])) {
            $this->showError("请选择参加活动的商品");
        }
        
//         if(empty($params['limitbuy'])) {
//             $this->showError("请填写限购数量");
//         } else {
            if($params['limitbuy'] != -1) {
                if(!CommonModel::isInteger($params['limitbuy'])) {
                    $this->showError("请填写正确数字");
                }
                if(!is_numeric($params['limitbuy']) && !is_int($params['limitbuy'])) {
                    $this->showError("请填写正确数字");
                }
            }
//         }

        $posts['productid'] = $params['productid'];
        $ProProductBuyModel = Model::ins("ProProductBuy");
        // 查看该商品是否已经在参加活动中
        // 因预售问题
//         $activityRecord = $ProProductBuyModel->getRow(["productid"=>$posts['productid'],"enable"=>1,"endtime"=>[">=",getFormatNow()]],"id");
//         if(!empty($activityRecord['id'])) {
//             $this->showError("该商品已经在活动中了");
//         }

        // 查看该商品是否和该商品其它活动交叉
        $where = array("productid"=>$posts['productid'],"enable"=>1,"endtime"=>array(">",$params['starttime']),"starttime"=>array("<",$params['endtime']));

        $activityRecord = $ProProductBuyModel->getRow($where,"id");
        if(!empty($activityRecord['id'])) {
            $this->showError("商品在此活动时间已经有进行活动了");
        }
        
        // 检验库存
        $totalStorage = Model::ins("ProProductSpec")->getRow(["productid"=>$posts["productid"]],"SUM(productstorage) as storage");
        
        $posts['productstorage'] = $posts['activeproductnum'] = !empty($params['productstorage']) ? $params['productstorage'] : 0;
        
        if($posts['productstorage'] > $totalStorage['storage']) {
            $this->showError("商品现有库存为:".$totalStorage['storage']."件，活动库存不能大于现有库存");
        }
        
        // 校验限购
        $posts['limitbuy'] = !empty($params['limitbuy']) ? $params['limitbuy'] : 0;
        if($posts['limitbuy'] > $posts['productstorage']) {
            $this->showError("限购数量不能大于库存");
        }
        
        $ProProductModel = Model::ins("ProProduct");
        $proproduct = $ProProductModel->getRow(["id"=>$params['productid']],"businessid,categoryid,saleprice,prouctprice,supplyprice,bullamount");

        $posts['starttime'] = $params['starttime'];
        $posts['endtime'] = $params['endtime'];
        $posts['businessid'] = !empty($proproduct['businessid']) ? $proproduct['businessid'] : 0;
        $posts['categoryid'] = !empty($proproduct['categoryid']) ? $proproduct['categoryid'] : 0;
        $posts['addtime'] = getFormatNow();
        $posts['enable'] = !empty($params['enable']) ? $params['enable'] : -1;
        $posts['saleprice'] = !empty($params['saleprice']) ? EnPrice($params['saleprice']) : $proproduct['saleprice'];
        $posts['prouctprice'] = !empty($params['prouctprice']) ? EnPrice($params['prouctprice']) : $proproduct['prouctprice'];
        $posts['supplyprice'] = !empty($params['supplyprice']) ? EnPrice($params['supplyprice']) : $proproduct['supplyprice'];
        $posts['bullamount'] = !empty($params['bullamount']) ? EnPrice($params['bullamount']) : $proproduct['bullamount'];
        $posts['productstorage_buy'] = $posts['productstorage']; // 剩余购买数

        // 开启事务
        $ProProductBuyModel->startTrans();
        try{
            // 写入记录
            $status = $ProProductBuyModel->insert($posts);
            if($status) {
                $ProProductBuyModel->commit();
                
//                 $ascEndActivity = Model::ins("ProProductBuy")->getRow(["endtime"=>[">=",getFormatNow()],"productid"=>$params['productid']],"id","endtime asc");
//                 if($ascEndActivity['id'] == $status) {
                    // 同步redis，更新最新的活动状态
                    Model::new("Product.ProductBuy")->updateProductBuyRedis([
                        "productid"=>$posts['productid'],
                        "id"=>$status,
                    ]);
//                 }
                
                $this->showSuccess("添加成功!");
            }
            $this->showError("数据写入异常");
        } catch (\Exception $e) {
            $ProProductBuyModel->rollback();
            
            Log::add($e,__METHOD__);
            $this->showError("操作错误，请联系管理员");
        }
    }
    
    /**
    * @user 编辑商品
    * @param 
    * @author jeeluo
    * @date 2017年9月20日下午4:44:34
    */
    public function editInfoAction() {
        if(!$this->getParam("id")) {
            $this->showError("数据有异，请正确操作");
        }
        
        // 查看商品是否存在
        $productBuy = Model::ins("ProProductBuy")->getRow(["id"=>$this->getParam("id")],"*");
        if(empty($productBuy['id'])) {
            $this->showError("数据不存在");
        }
        
        //  获取商品的部分内容
        $productInfo = Model::ins("ProProduct")->getRow(["id"=>$productBuy['productid']],"productname");
        $productBuy['productname'] = !empty($productInfo['productname']) ? $productInfo['productname'] : '';
        
        $viewData = [
            'title' => "编辑商品信息",
            'productBuy' => $productBuy,
            'actionUrl' => "/Product/ActivityGoods/updateActivityGoods?id=".$productBuy['id'],
        ];
        
        return $this->view($viewData);
    }
    
    /**
    * @user 修改活动商品信息
    * @param 
    * @author jeeluo
    * @date 2017年9月23日上午11:41:40
    */
    public function updateActivityGoodsAction() {
        if(!$this->getParam("id")) {
            $this->showError("数据有异，请正确操作");
        }
        
        $ProProductBuyModel = Model::ins("ProProductBuy");
        // 查看商品是否存在
        $productBuy = $ProProductBuyModel->getRow(["id"=>$this->getParam("id")],"id,productid,activeproductnum");
        if(empty($productBuy['id'])) {
            $this->showError("数据不存在");
        }
        
        $params = $this->params;
        if(empty($params['starttime']) || empty($params['endtime'])) {
            $this->showError("活动时间不能为空");
        }
        
        $starttime = strtotime($params['starttime']);
        $endtime = strtotime($params['endtime']);
        
        if($starttime >= $endtime) {
            $this->showError("活动结束时间必须大于开始时间");
        }
        
        if(empty($params['productid'])) {
            $this->showError("请选择参加活动的商品");
        }
        $posts['productid'] = $params['productid'];
        
        // 查看该商品是否已经在参加活动中
//         $activityRecord = $ProProductBuyModel->getRow(["productid"=>$posts['productid'],"enable"=>1,"endtime"=>[">=",getFormatNow()]],"id");
        $activityRecord = $ProProductBuyModel->getRow(["id"=>$productBuy['id'],"enable"=>1,"endtime"=>[">=",getFormatNow()]],"id");
        if(!empty($activityRecord['id'])) {
            $this->showError("该商品已经在活动中了");
        } else {
            // 校验时间
            $timeWhere = array("productid"=>$posts['productid'],"enable"=>1,"endtime"=>array(">",$params['starttime']),"starttime"=>array("<",$params['endtime']));
            $updateRecord = $ProProductBuyModel->getRow($timeWhere,"id");
            if(!empty($updateRecord['id'])) {
                $this->showError("商品在此活动时间已经有进行活动了");
            }
        }
        $ProProductModel = Model::ins("ProProduct");
        $proproduct = $ProProductModel->getRow(["id"=>$params['productid']],"businessid,categoryid,saleprice,prouctprice,supplyprice");
        
        $posts['starttime'] = $params['starttime'];
        $posts['endtime'] = $params['endtime'];
        $posts['businessid'] = !empty($proproduct['businessid']) ? $proproduct['businessid'] : 0;
        $posts['categoryid'] = !empty($proproduct['categoryid']) ? $proproduct['categoryid'] : 0;
//         $posts['enable'] = !empty($params['enable']) ? $params['enable'] : -1;
        $posts['saleprice'] = !empty($params['saleprice']) ? EnPrice($params['saleprice']) : $proproduct['saleprice'];
        $posts['prouctprice'] = !empty($params['prouctprice']) ? EnPrice($params['prouctprice']) : $proproduct['prouctprice'];
        $posts['supplyprice'] = !empty($params['supplyprice']) ? EnPrice($params['supplyprice']) : $proproduct['supplyprice'];
        $posts['bullamount'] = !empty($params['bullamount']) ? EnPrice($params['bullamount']) : 0;
        $posts['productstorage'] = !empty($params['productstorage']) ? $params['productstorage'] : 0;
        $posts['activeproductnum'] = !empty($params['activeproductnum']) ? $params['activeproductnum'] : $productBuy['activeproductnum'];
        $posts['limitbuy'] = !empty($params['limitbuy']) ? $params['limitbuy'] : 0;
        // 开启事务
        $ProProductBuyModel->startTrans();
        try{
            // 修改记录
            $ProProductBuyModel->update($posts,["id"=>$productBuy['id']]);
            
            $ProProductBuyModel->commit();
        
//             $ascEndActivity = Model::ins("ProProductBuy")->getRow(["endtime"=>[">=",getFormatNow()],"productid"=>$productBuy['productid']],"id","endtime asc");
//             if($ascEndActivity['id'] == $productBuy['id']) {
                // 同步redis，更新最新的活动状态
                Model::new("Product.ProductBuy")->updateProductBuyRedis([
                    "productid"=>$posts['productid'],
                    "id"=>$productBuy['id'],
                ]);
//             }
            // 同步redis，更新最新的活动状态
//             Model::new("Product.ProductBuy")->updateProductBuyRedis([
//                 "productid"=>$posts['productid'],
//                 "id"=>$params['productid'],
//             ]);
            $this->showSuccess("修改完成", "/Product/ActivityGoods/index");
        } catch (\Exception $e) {
            $ProProductBuyModel->rollback();
        
            Log::add($e,__METHOD__);
            $this->showError("操作错误，请联系管理员");
        }
//         // 修改记录
//         $ProProductBuyModel->update($posts,["id"=>$productBuy['id']]);

//         // 同步redis，更新最新的活动状态
//         Model::new("Product.ProductBuy")->updateProductBuyRedis([
//             "productid"=>$productBuy['productid'],
//             "id"=>$this->getParam("id"),
//         ]);

//         $this->showSuccessPage("修改完成", "/Product/ActivityGoods/index");
    }
    
    /**
    * @user 搜索商品
    * @param 
    * @author jeeluo
    * @date 2017年9月20日下午2:52:11
    */
    public function searchGoodsAction() {
        $where = array();
        $where = $this->searchWhere([
            "productname"=>"like",
        ], $where);
        
        $list = Model::ins("ProProduct")->pagelist($where,"id,productname,businessid","sort desc, id desc");
        
        if(!empty($list['list'])) {
            $busBusinessModel = Model::ins("BusBusiness");
            $num = 1;
            foreach ($list['list'] as $k => $v) {
                $busBusiness = $busBusinessModel->getRow(["id"=>$v['businessid']],"businessname");
                $list['list'][$k]['businessname'] = $busBusiness['businessname'];
                $list['list'][$k]['num'] = $num;
                $num++;
            }
        } else {
            $list['llist'] = array();
        }
        
        $viewData = [
            "title" => "搜索商品",
            "pagelist" => $list['list'],
            "total" => $list['total']
        ];
        return $this->view($viewData);
    }

    /**
    * @user ajax 获取商品信息
    * @param 
    * @author jeeluo
    * @date 2017年9月20日下午3:04:53
    */
    public function ajaxGoodsInfoAction() {
        // 获取商品id值
        $params['id'] = $this->params['goodsid'];
        
        $productInfo = array();
        
        if(!empty($params['id'])) {
            // 获取商品部分信息
            $productInfo = Model::ins("ProProduct")->getRow(["id"=>$params['id']],"productname,saleprice,prouctprice,supplyprice");
            
            $productInfo['saleprice'] = $productInfo['saleprice'] > 0 ? DePrice($productInfo['saleprice']) : '0.00';
            $productInfo['prouctprice'] = $productInfo['prouctprice'] > 0 ? DePrice($productInfo['prouctprice']) : '0.00';
            $productInfo['supplyprice'] = $productInfo['supplyprice'] > 0 ? DePrice($productInfo['supplyprice']) : '0.00';
            $productInfo['bullamount'] = $productInfo['bullamount'] > 0 ? DePrice($productInfo['bullamount']) : '0.00';
        }
        return json_encode($productInfo);
    }
    
    /**
    * @user 商品上下架功能
    * @param 
    * @author jeeluo
    * @date 2017年9月19日下午4:58:47
    */
    public function enableAction() {
        if(!$this->getParam("id") || !$this->getParam("enable")) {
            $this->showError("数据有异，请正确操作");
        }
        // 查看商品是否存在
        $productBuy = Model::ins("ProProductBuy")->getRow(["id"=>$this->getParam("id")],"id,productid,enable,starttime,endtime");
        if(empty($productBuy['id'])) {
            $this->showError("数据不存在");
        }
        
        $enable = $this->getParam("enable");
        // 校验活动时间不能下架
        if($productBuy['enable'] == 1 && $productBuy['starttime'] <= getFormatNow() && $productBuy['endtime'] >= getFormatNow() && $enable == -1) {
            $this->showError("商品活动已经开始，无法下架");
        }
        if($productBuy['enable'] == 1) {
            // 上架中 只能执行下架操作
            if($enable != -1) {
                $this->showError("请选择正确操作");
            }
        } else if($productBuy['enable'] == -1) {
            // 下架中 只能执行上架操作和删除操作
            if($enable < 1) {
                $this->showError("请选择正确操作");
            }
        } else {
            $this->showError("请选择正确操作");
        }
        
        // 执行修改操作
        $status = Model::ins("ProProductBuy")->update(["enable"=>$enable],["id"=>$productBuy['id']]);

        if($status) {
            
            // 同步redis，更新最新的活动状态
            Model::new("Product.ProductBuy")->updateProductBuyRedis([
                "productid"=>$productBuy['productid'],
                "id"=>$productBuy['id'],
                "updateData"=>["enable"=>$enable],
            ]);
        
            // 修改成功
            $this->showSuccess("操作成功");
        }
        $this->showError("操作失败");
    }
}