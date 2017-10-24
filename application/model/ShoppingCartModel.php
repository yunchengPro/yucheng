<?php
/**
* 购物车类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-02 14:43:24Z robert zhao $
*/

namespace app\model;
use app\lib\Db;
use app\model\ProductModel;

class ShoppingCartModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_businessid 	= null;

	protected $_productid 	= null;

	protected $_productnum 	= null;

	protected $_skuid 	= null;

	protected $_skuCode 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	

	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('ShoppingCart');
	}

	/**
	 *
	 * 添加购物车
	 */
	public function add() {
		$this->_modelObj->_customerid  		= $this->_customerid;
		$this->_modelObj->_businessid  		= $this->_businessid;
		$this->_modelObj->_productid  		= $this->_productid;
		$this->_modelObj->_productnum  		= $this->_productnum;
		$this->_modelObj->_skuid  		= $this->_skuid;
		$this->_modelObj->_skuCode  		= $this->_skuCode;
		$this->_modelObj->_addtime  		= $this->_addtime;
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
	public function getById($id = null,$field="*") {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getById($this->_id);
		return $this->_dataInfo;
	}



	/**
	 * [insert 添加数据]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-03T20:06:03+0800
	 * @param    [type]                   $insertData [description]
	 * @return   [type]                               [description]
	 */
	public function insert($insertData){
		return $this->_modelObj->insert($insertData);
	}

	/**
	 * [update description]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-03T20:10:08+0800
	 * @param    [type]                   $updateData [description]
	 * @param    [type]                   $where      [description]
	 * @param    string                   $limit      [description]
	 * @return   [type]                               [description]
	 */
	public function update($updateData,$where,$limit=''){
		return $this->_modelObj->update($updateData,$where,$limit);
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


    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr='') {
        return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
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

	/**
	 * [delete 删除表操作]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-03T20:52:35+0800
	 * @param    [type]                   $where [description]
	 * @param    string                   $limit [description]
	 * @return   [type]                          [description]
	 */
	public function delete($where,$limit=''){
 		return $this->_modelObj->delete($where,$limit);
	}
	

	
}

