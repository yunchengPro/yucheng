<?php
/**
 * 商家店铺
 */
namespace app\model;

use app\lib\Db;

use app\lib\Img;

class ProProductSpecTmpModel
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
        $this->_modelObj = Db::Table('ProProductspecTmp');
        $this->_modelObj->_fields = ['productid','productname','prouctprice','supplyprice','bullamount','productstorage','weight','weight_gross','volume','productspec','spec','productimage','sku','businessid'];
    }

    /**
     * 生成SPU
     * @Author   zhuangqm
     * @DateTime 2017-03-03T16:30:47+0800
     * @return   [type]                   [description]
     */
    public function getSKuNo(){
        return "NNHSKUITM".date("YmdHis").rand(100000,999999);
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

    /**
     *
     * 更新购物车
     */
    public function modify($id) {
        $this->_modelObj->_customerid  = $this->_customerid;
        $this->_modelObj->_businessid  = $this->_businessid;
        $this->_modelObj->_productid  = $this->_productid;
        $this->_modelObj->_productnum  = $this->_productnum;
        $this->_modelObj->_skuid  = $this->_skuid;
        $this->_modelObj->_skuCode  = $this->_skuCode;
        $this->_modelObj->_addtime  = $this->_addtime;
        return $this->_modelObj->modify($id);
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
    
    /**
    * @user 根据商品skuid productid 获取商品相关信息
    * @param 
    * @author jeeluo
    * @date 2017年3月17日下午6:18:12
    */
    public function getSkuProduct($params) {
        $goods = array();
        if(!empty($params['skuid'])) {
            $goods = $this->_modelObj->setId($params['skuid'])->getById($params['skuid'], "productname, prouctprice, bullamount, spec, productimage");
            
            $decodespec = json_decode($goods['spec']);
            $goods['skudetail'] = '';
            foreach ($decodespec as $v) {
                $goods['skudetail'] .= $v->name.":".$v->value;
            }
            $goods['thumb'] = $goods['productimage'];
            unset($goods['productimage']);
        } else {
            $productOBJ = new ProProductModel();
            $goods = $productOBJ->setId($params['productid'])->getById($params['productid'], "productname, prouctprice, bullamount, thumb");
            $goods['skudetail'] = '';
        }
        $goods['skudetail'] = substr($goods['skudetail'], 0, -1);
        return $goods;
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

    /*
    * 更新数据
    * $updateData = array()
    */
    public function update($updateData,$where,$limit=''){
        return $this->_modelObj->update($updateData,$where,$limit='');
    }
    /*
    * 删除数据
    */
    public function delete($where,$limit=''){
        return $this->_modelObj->delete($where,$limit);
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
