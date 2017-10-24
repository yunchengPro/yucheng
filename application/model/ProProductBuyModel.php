<?php
/**
 * 商家店铺
 */
namespace app\model;

use app\lib\Db;

use app\lib\Img;
use app\lib\model\RedisModel;
use app\model\Sys\CommonModel;
use app\lib\Model;

class ProProductBuyModel{    // extends RedisModel


    protected $_id   = null;

    protected $_customerid  = null;

    protected $_businessid  = null;

    protected $_productid   = null;

    protected $_productnum  = null;

    protected $_skuid   = null;

    protected $_skuCode     = null;

    protected $_addtime     = null;

    protected $_modelObj;

    protected $_totalPage = null;
    
    protected $_dataInfo = null;

    protected $_modelname = "ProProductBuy";

    public function __construct() {
        $this->_modelObj = Db::Table($this->_modelname);
        $this->_modelObj->_fields = ['spu','businessid','productname','categoryid','businesscategoryid','categoryname','addtime','enable','enabletime','thumb','productunit','weight','weight_gross','volume','stateremark','prouctprice','saleprice','supplyprice','bullamount','productstorage','saletype'];
    }


    /**
     * 生成SPU
     * @Author   zhuangqm
     * @DateTime 2017-03-03T16:30:47+0800
     * @return   [type]                   [description]
     */
    public function getSPuNo(){
        return "NNHITM".date("YmdHis").rand(100000,999999);
    }


    /*
        负责把表单提交来的数组
        清除掉不用的单元
        留下与表的字段对应的单元
    */
    public function _facade($array = []){
        return $this->_modelObj->_facade($array);
    }

    /**
     *
     * 添加购物车
     */
    public function add() {
        $this->_modelObj->_customerid       = $this->_customerid;
        $this->_modelObj->_businessid       = $this->_businessid;
        $this->_modelObj->_productid        = $this->_productid;
        $this->_modelObj->_productnum       = $this->_productnum;
        $this->_modelObj->_skuid        = $this->_skuid;
        $this->_modelObj->_skuCode          = $this->_skuCode;
        $this->_modelObj->_addtime          = $this->_addtime;
        return $this->_modelObj->add();
    }
    
    //开启事务
    public function startTrans(){
        return $this->_modelObj->startTrans();
    }
    
    //提交事务
    public function commit(){
        return $this->_modelObj->commit();
    }
    
    //事务回滚
    public function rollback(){
        return $this->_modelObj->rollback();
    }

    /**
     *
     * 更新购物车
     */
//     public function modify($id) {
//         $this->_modelObj->_customerid  = $this->_customerid;
//         $this->_modelObj->_businessid  = $this->_businessid;
//         $this->_modelObj->_productid  = $this->_productid;
//         $this->_modelObj->_productnum  = $this->_productnum;
//         $this->_modelObj->_skuid  = $this->_skuid;
//         $this->_modelObj->_skuCode  = $this->_skuCode;
//         $this->_modelObj->_addtime  = $this->_addtime;
//         return $this->_modelObj->modify($id);
//     }

    public function modify($data, $where) {
        return $this->_modelObj->update($data, $where);
    }
    
    public function update($data, $where) {
        return $this->_modelObj->update($data, $where);
    }

    /**
     *
     * 详细
     */
    public function getById($id = null) {
        $this->_id = is_null($id)? $this->_id : $id;
        $this->_dataInfo = $this->_modelObj->getById($this->_id);
        return $this->_dataInfo;
    }

       /*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
        return $this->_modelObj->getRow($where,$field,$order,$otherstr='');
    }
    /*
    * 获取多行记录
    */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
        return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
    }

    /*
    * 分页列表
    * $flag = 0 表示不返回总条数
    */
    public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
        return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
    }
    
    /*
    * 写入数据
    * $insertData = array() 如果ID是自增 返回生成主键ID
    */
    public function insert($insertData) {
        return $this->_modelObj->insert($insertData);
    }
    
    /**
    * @user 抢购商品排序问题
    * @param 
    * @author jeeluo
    * @date 2017年9月19日上午11:38:59
    */
    public function sort($params=array()) {
        $where = array();
        $type = !empty($params['type']) ? $params['type'] : 0;
        $productid = !empty($params['productid']) ? $params['productid'] : 0;
        $customerid = !empty($params['customerid']) ? $params['customerid'] : 0;
        
        if($type == 0) {
            // 正在进行的
            $where = ["enable"=>1,"starttime"=>["<=",getFormatNow()],"endtime"=>[">=",getFormatNow()]];
        } else {
            // 还未进行的
            $activeStart = date('Y-m-d H:i:s',strtotime(getFormatNow())+86400*3);
            $where = ["enable"=>1,"starttime"=>[[">=",getFormatNow()],["<",$activeStart]]];
        }

        if($params['limit']) {
            $result = $this->getList($where,"id,productid as goodsid,saleprice,prouctprice,productstorage,activeproductnum,productstorage_buy,starttime,endtime,bullamount","endtime asc, starttime desc", $params['list']);
        } else {
            $result = $this->pageList($where,"id,productid as goodsid,saleprice,prouctprice,productstorage,activeproductnum,productstorage_buy,starttime,endtime,bullamount","endtime asc, starttime desc");
        }
        
        if($productid != 0) {
            $where['productid'] = $productid;
            // 点击了某个商品
            $productInfo = $this->getRow($where,"id,productid as goodsid,saleprice,prouctprice,productstorage,activeproductnum,starttime,endtime");
            if(!empty($productInfo['id'])) {
                // 追加到数组列表中
                if(!empty($result['list'])) {
                    array_unshift($result['list'],$productInfo);
                } else {
                    $result['list'][0] = $productInfo;
                }
            }
        }
        
        $ProProductBuyRemindModel = Model::ins("ProProductBuyRemind");
        foreach ($result['list'] as $k => $v) {
            // $result['list'][$k]['remindStatus'] = 2;
            $result['list'][$k]['remindStatus'] = -1;
            // 处理商品的倒计时时间问题
            if($type == 0) {
                $result['list'][$k]['time'] = CommonModel::timediff(getFormatNow(), $v['endtime']);
            } else {
                // 预售列表
                $result['list'][$k]['time'] = CommonModel::timediff(getFormatNow(), $v['starttime']);
                if($customerid > 0) {
                    // 根据商品id 活动id 用户id 查询remind status
                    $remindStatus = $ProProductBuyRemindModel->getProductRemind(["product_buy_id"=>$v['id'],"productid"=>$v['goodsid'],"customerid"=>$customerid]);
                    if(!empty($remindStatus['id'])) {
                        $result['list'][$k]['remindStatus'] = !empty($remindStatus['status']) ? $remindStatus['status'] : 0;
                    }
                }
            }
        }
        
        return $result;
    }


    /*
    * 更新数据
    * $updateData = array()
    */
    // public function update($updateData,$where,$limit=''){
    //     return $this->_modelObj->update($updateData,$where,$limit='');
    // }
    /*
    * 删除数据
    */
    public function delete($where,$limit=''){
        return $this->_modelObj->delete($where,$limit);
    }



    /**
     *
     * 删除购物车
     */
    public function del($id) {
        return $this->_modelObj->del($id);
    }


    /**
     * 设置主键
     *
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    /**
     * 设置客户id
     *
     */
    public function setCustomerid($customerid) {
        $this->_customerid = $customerid;
        return $this;
    }

    /**
     * 设置商家id
     *
     */
    public function setBusinessid($businessid) {
        $this->_businessid = $businessid;
        return $this;
    }

    /**
     * 设置商品id
     *
     */
    public function setProductid($productid) {
        $this->_productid = $productid;
        return $this;
    }

    /**
     * 设置商品数量
     *
     */
    public function setProductnum($productnum) {
        $this->_productnum = $productnum;
        return $this;
    }

    /**
     * 设置商品sku的id
     *
     */
    public function setSkuid($skuid) {
        $this->_skuid = $skuid;
        return $this;
    }

    /**
     * 设置商品sku编码
     *
     */
    public function setSkuCode($skuCode) {
        $this->_skuCode = $skuCode;
        return $this;
    }

    /**
     * 设置添加时间
     *
     */
    public function setAddtime($addtime) {
        $this->_addtime = $addtime;
        return $this;
    }

    public static function getModelObj() {
        return new ProProductBuyDB();
    }

    public function getTotalPage() {
        return intval($this->_modelObj->_totalPage);
    }
}
