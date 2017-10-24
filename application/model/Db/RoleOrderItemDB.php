<?php
/**
*
* 订单明细表（对应商品）类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdOrderItem.php 10319 2017-03-03 16:27:39Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class RoleOrderItemDB extends MysqlDb {

	protected $_tableName = "role_order_item";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_orderid 	= null;

	protected $_orderCode 	= null;

	protected $_businessid 	= null;

	protected $_businessname 	= null;

	protected $_productid 	= null;

	protected $_productname 	= null;

	protected $_productcode 	= null;

	protected $_externalcode 	= null;

	protected $_productnum 	= null;

	protected $_categoryid 	= null;

	protected $_categoryname 	= null;

	protected $_skuid 	= null;

	protected $_skucode 	= null;

	protected $_externalskucode 	= null;

	protected $_thumb 	= null;

	protected $_prouctprice 	= null;

	protected $_bullamount 	= null;

	protected $_addtime 	= null;

	protected $_returnType 	= null;

	protected $_returnnum 	= null;

	protected $_intendedReturn 	= null;

	protected $_returnMoney 	= null;

	protected $_intendedMoney 	= null;
	
	protected $_returnBull = null;
	
	protected $_intendedBull = null;

	protected $_evaluate 	= null;

	protected $_remark 	= null;
	
	protected $_enable = null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入订单明细表（对应商品）
	 */
	public function add() {
		! is_null($this->_orderid) && $data['orderid'] = $this->_orderid;
		! is_null($this->_orderCode) && $data['order_code'] = $this->_orderCode;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_productname) && $data['productname'] = $this->_productname;
		! is_null($this->_productcode) && $data['productcode'] = $this->_productcode;
		! is_null($this->_externalcode) && $data['externalcode'] = $this->_externalcode;
		! is_null($this->_productnum) && $data['productnum'] = $this->_productnum;
		! is_null($this->_categoryid) && $data['categoryid'] = $this->_categoryid;
		! is_null($this->_categoryname) && $data['categoryname'] = $this->_categoryname;
		! is_null($this->_skuid) && $data['skuid'] = $this->_skuid;
		! is_null($this->_skucode) && $data['skucode'] = $this->_skucode;
		! is_null($this->_externalskucode) && $data['externalskucode'] = $this->_externalskucode;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_prouctprice) && $data['prouctprice'] = $this->_prouctprice;
		! is_null($this->_bullamount) && $data['bullamount'] = $this->_bullamount;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_returnType) && $data['return_type'] = $this->_returnType;
		! is_null($this->_returnnum) && $data['returnnum'] = $this->_returnnum;
		! is_null($this->_intendedReturn) && $data['intended_return'] = $this->_intendedReturn;
		! is_null($this->_returnMoney) && $data['return_money'] = $this->_returnMoney;
		! is_null($this->_intendedMoney) && $data['intended_money'] = $this->_intendedMoney;
		! is_null($this->_returnBull) && $data['return_bull'] = $this->_returnBull;
		! is_null($this->_intendedBull) && $data['intended_bull'] = $this->_intendedBull;
		! is_null($this->_evaluate) && $data['evaluate'] = $this->_evaluate;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新订单明细表（对应商品）
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_orderid) && $data['orderid'] = $this->_orderid;
		! is_null($this->_orderCode) && $data['order_code'] = $this->_orderCode;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_productname) && $data['productname'] = $this->_productname;
		! is_null($this->_productcode) && $data['productcode'] = $this->_productcode;
		! is_null($this->_externalcode) && $data['externalcode'] = $this->_externalcode;
		! is_null($this->_productnum) && $data['productnum'] = $this->_productnum;
		! is_null($this->_categoryid) && $data['categoryid'] = $this->_categoryid;
		! is_null($this->_categoryname) && $data['categoryname'] = $this->_categoryname;
		! is_null($this->_skuid) && $data['skuid'] = $this->_skuid;
		! is_null($this->_skucode) && $data['skucode'] = $this->_skucode;
		! is_null($this->_externalskucode) && $data['externalskucode'] = $this->_externalskucode;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_prouctprice) && $data['prouctprice'] = $this->_prouctprice;
		! is_null($this->_bullamount) && $data['bullamount'] = $this->_bullamount;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_returnType) && $data['return_type'] = $this->_returnType;
		! is_null($this->_returnnum) && $data['returnnum'] = $this->_returnnum;
		! is_null($this->_intendedReturn) && $data['intended_return'] = $this->_intendedReturn;
		! is_null($this->_returnMoney) && $data['return_money'] = $this->_returnMoney;
		! is_null($this->_intendedMoney) && $data['intended_money'] = $this->_intendedMoney;
		! is_null($this->_returnBull) && $data['return_bull'] = $this->_returnBull;
		! is_null($this->_intendedBull) && $data['intended_bull'] = $this->_intendedBull;
		! is_null($this->_evaluate) && $data['evaluate'] = $this->_evaluate;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		return $this->update($data);
	}

	/**
	 * 删除订单明细表（对应商品）
	 */
	public function del($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		return $this->delete($data);
	}

	/**
	 *
	 * 根据ID获取一行
	 * @param interger $id
	 */
	public function getById($id) {
		$this->_id = is_null($id)? $this->_id : $id;
		return $this->getRow(array($this->_primary => $this->_id));
	}

	/**
	 *
	 * 获取所有订单明细表（对应商品）--分页
	 * @param interger $status
	 */
	public function getAllForPage($page = 0, $pagesize = 20) {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		$this->_totalPage = $this->count();
		return $this->page($page, $pagesize)->order("{$this->_primary} desc")->select();
	}

	/**
	 * 获取所有订单明细表（对应商品）
	 * @return Ambigous 
	 */
	public function getAll() {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		return $this->select();
	}
	
	public function cleanAll() {
				$this->_id 	= null;

				$this->_orderid 	= null;

				$this->_orderCode 	= null;

				$this->_businessid 	= null;

				$this->_businessname 	= null;

				$this->_productid 	= null;

				$this->_productname 	= null;

				$this->_productcode 	= null;

				$this->_externalcode 	= null;

				$this->_productnum 	= null;

				$this->_categoryid 	= null;

				$this->_categoryname 	= null;

				$this->_skuid 	= null;

				$this->_skucode 	= null;

				$this->_externalskucode 	= null;

				$this->_thumb 	= null;

				$this->_prouctprice 	= null;

				$this->_bullamount 	= null;

				$this->_addtime 	= null;

				$this->_returnType 	= null;

				$this->_returnnum 	= null;

				$this->_intendedReturn 	= null;

				$this->_returnMoney 	= null;

				$this->_intendedMoney 	= null;
				
				$this->_returnBull = null;
				
				$this->_intendedBull = null;

				$this->_evaluate 	= null;

				$this->_remark 	= null;
				
				$this->_enable = null;

			}
}
?>