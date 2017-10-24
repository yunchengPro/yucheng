<?php
/**
 * 商家店铺
 */
namespace app\model;

use app\lib\Db;

use app\lib\Img;

class ProEvaluateModel
{   


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

    public function __construct() {
        $this->_modelObj = Db::Table('ProEvaluate');
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

    /**
     *
     * 详细
     */
    public function getById($id = null) {
        $this->_id = is_null($id)? $this->_id : $id;
        $this->_dataInfo = $this->_modelObj->getById($this->_id);
        return $this->_dataInfo;
    }

    /**
     * [getRow 获取单条记录]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-03T14:31:36+0800
     * @param    [type]                   $where    [description]
     * @param    string                   $field    [description]
     * @param    string                   $order    [description]
     * @param    string                   $otherstr [description]
     * @return   [type]                             [description]
     */
    public function  getRow($where,$field='*',$order='',$otherstr=''){
        return $this->_modelObj->getRow($where,$field,$order,$otherstr); 
    }
    
    /**
     * [insert 插入数据]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-04T18:13:37+0800
     * @param    [type]                   $insertData [description]
     * @return   [type]                               [description]
     */
    public function insert($insertData){
        return $this->_modelObj->insert($insertData);
    }
    

    /*
    * 更新数据
    * $updateData = array()
    */
    public function update($updateData,$where,$limit=''){
        return $this->_modelObj->update($updateData,$where,$limit='');
    }
 
    /**
     *
     * 商品车列表
     */
    public function getList($where,$field='*',$order='',$otherstr='') {
        return $this->_modelObj->getList($where,$field,$order,$otherstr);
    }
    
    /**
    * @user 根据productid、skuid 获取评论信息
    * @param $data['productid'] $data['skuid'] $data['orderid']
    * @author jeeluo
    * @date 2017年3月17日下午6:01:30
    */
    public function getOrderProList($data) {
       
        // 获取该商品评论
        $evaluate = $this->_modelObj->getRow(array("productid" => $data['productid'], "orderno" => $data['orderno']), "id, content, scores, addtime");
     
        // 根据评论id
        if(!empty($evaluate)) {
            $evaluateOBJ = new ProEvaluateImageModel();
            $evaluate_images = $evaluateOBJ->getList(array("evaluate_id" => $evaluate['id']), 'thumb');
            
            if(!empty($evaluate_images)) {
                foreach ($evaluate_images as $key => $image) {
                    $evaluate['images'][$key]['thumb'] = Img::url($image['thumb']);
                }
            }
            
            return $evaluate;
        }
        return false;
    }

 
    public function pageList($where,$field='',$order='',$flag=1){
        return $this->_modelObj->pageList($where,$field,$order,$flag);
    }

    /**
     * 获取所有购物车
     */
    public function getAll() {
        return $this->_modelObj->getAll();
    }



    /**
     *
     * 删除购物车
     */
    public function del($id) {
        return $this->_modelObj->del($id);
    }
    
    /**
    * @user 删除操作
    * @param 
    * @author jeeluo
    * @date 2017年4月5日下午2:53:01
    */
    public function delete($where) {
        return $this->_modelObj->delete($where);
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
        return new ShoppingCartDB();
    }

    public function getTotalPage() {
        return intval($this->_modelObj->_totalPage);
    }
}
