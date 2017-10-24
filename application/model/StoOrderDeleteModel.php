<?php
/**
* 实体店订单表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:19:56Z robert zhao $
*/

namespace app\model;
use app\lib\Db;
use app\lib\Img;
use app\lib\Model;

class StoOrderDeleteModel {


	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_customerid 	= null;

	protected $_customer_name 	= null;

	protected $_actualfreight 	= null;

	protected $_productcount 	= null;

	protected $_productamount 	= null;

	protected $_totalamount 	= null;

	protected $_addtime 	= null;

	protected $_orderstatus 	= null;

	protected $_return_status	= null;

	protected $_businessid = null;

	protected $_businessname = null;

	protected $_evaluate = null;

	protected $_ordertime = null;

	protected $_delivertime = null;

	protected $_confirm_time = null;

	protected $_finish_time = null;

	protected $_return_time = null;

	protected $_return_success_time = null;

	protected $_cancelsource = null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoOrderDelete');
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
	 * 生成订单编号
	 * @Author   zhuangqm
	 * @DateTime 2017-03-03T16:30:47+0800
	 * @return   [type]                   [description]
	 */
	public function getOrderNo(){
		return "NNHOTO".date("YmdHis").rand(100000,999999);
	}

	/**
	 * [getInfoByOrderNo 获取订单详情]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-06-29T17:01:16+0800
	 * @param    [type]                   $orderno [description]
	 * @param    string                   $field   [description]
	 * @return   [type]                            [description]
	 */
	public function getInfoByOrderNo($orderno,$field="*"){
		$item = $this->_modelObj->getRow(['orderno'=>$orderno],$field);
		return $item;
	}

	/**
	 *
	 * 添加订单表
	 */
	public function add($data) {
	
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新订单表
	 */
	public function modify($updateData,$where) {
		/*
		$this->_modelObj->_orderno  = $this->_orderno;
		$this->_modelObj->_customerid  = $this->_customerid;
		$this->_modelObj->_custName  = $this->_custName;
		$this->_modelObj->_actualfreight  = $this->_actualfreight;
		$this->_modelObj->_productcount  = $this->_productcount;
		$this->_modelObj->_productamount  = $this->_productamount;
		$this->_modelObj->_bullamount  = $this->_bullamount;
		$this->_modelObj->_totalamount  = $this->_totalamount;
		$this->_modelObj->_businessid  = $this->_businessid;
		$this->_modelObj->_businessname  = $this->_businessname;
		return $this->_modelObj->modify($id);
		*/
		
		return $this->_modelObj->update($updateData,$where);
	}


	/**
	 *
	 * 详细
	 */
	public function getById($id = null,$field="*") {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getRow(["id"=>$id],$field);
		return $this->_dataInfo;
	}

	public function getByNo($orderno,$field="*") {
		return $this->_modelObj->getRow(["orderno"=>$orderno],$field);
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
        return $this->_modelObj->pageList($where,$field,$order,$flag);
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
	 * 获取所有订单表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}

	

	/**
	 *
	 * 删除订单表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}

	

	/**
	 * 设置主键id
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置订单号
	 *
	 */
	public function setOrderno($orderno) {
		$this->_orderno = $orderno;
		return $this;
	}

	/**
	 * 设置下单用户ID
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
		return $this;
	}

	/**
	 * 设置用户名
	 *
	 */
	public function setCustName($custName) {
		$this->_custName = $custName;
		return $this;
	}

	/**
	 * 设置实际运费
	 *
	 */
	public function setActualfreight($actualfreight) {
		$this->_actualfreight = $actualfreight;
		return $this;
	}

	/**
	 * 设置商品总数量
	 *
	 */
	public function setProductcount($productcount) {
		$this->_productcount = $productcount;
		return $this;
	}

	/**
	 * 设置商品总金额
	 *
	 */
	public function setProductamount($productamount) {
		$this->_productamount = $productamount;
		return $this;
	}

	/**
	 * 设置商品总牛币数
	 *
	 */
	public function setBullamount($bullamount) {
		$this->_bullamount = $bullamount;
		return $this;
	}

	/**
	 * 设置订单总金额=实际运费+商品总金额
	 *
	 */
	public function setTotalamount($totalamount) {
		$this->_totalamount = $totalamount;
		return $this;
	}

	/**
	 * 设置订单添加时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	/**
	 * 设置订单状态0待付款1已付款待发货2已发货3部分发货4订单完结5确认收货6取消
	 *
	 */
	public function setOrderstatus($orderstatus) {
		$this->_orderstatus = $orderstatus;
		return $this;
	}

	public static function getModelObj() {
		return new OrdOrderDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}

	
}
?>