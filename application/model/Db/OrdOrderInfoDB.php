<?php
/**
*
* 订单详细信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdOrderInfo.php 10319 2017-03-03 16:23:26Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class OrdOrderInfoDB extends MysqlDb {

	protected $_tableName = "ord_order_info";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_customerid 	= null;

	protected $_cancelsource 	= null;

	protected $_cancelreason 	= null;

	protected $_auditRemark 	= null;

	protected $_islongdate 	= null;

	protected $_autoDeliveryTime 	= null;

	protected $_actualDeliveryTime 	= null;

	protected $_evaluate 	= null;

	protected $_finishTime 	= null;

	protected $_returnStatus 	= null;

	protected $_returnAmount 	= null;

	protected $_delivertime = null;
	
	protected $_returnBull = null;
	
	protected $_returnTime = null;
	
	protected $_returnSuccessTime = null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入订单详细信息表
	 */
	public function add() {
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_cancelsource) && $data['cancelsource'] = $this->_cancelsource;
		! is_null($this->_cancelreason) && $data['cancelreason'] = $this->_cancelreason;
		! is_null($this->_auditRemark) && $data['audit_remark'] = $this->_auditRemark;
		! is_null($this->_islongdate) && $data['islongdate'] = $this->_islongdate;
		! is_null($this->_autoDeliveryTime) && $data['auto_delivery_time'] = $this->_autoDeliveryTime;
		! is_null($this->_actualDeliveryTime) && $data['actual_delivery_time'] = $this->_actualDeliveryTime;
		! is_null($this->_evaluate) && $data['evaluate'] = $this->_evaluate;
		! is_null($this->_finishTime) && $data['finish_time'] = $this->_finishTime;
		! is_null($this->_returnStatus) && $data['return_status'] = $this->_returnStatus;
		! is_null($this->_returnAmount) && $data['return_amount'] = $this->_returnAmount;
		! is_null($this->_returnBull) && $data['return_bull'] = $this->_returnBull;
		! is_null($this->_returnTime) && $data['return_time'] = $this->_returnTime;
		! is_null($this->_returnSuccessTime) && $data['return_success_time'] = $this->_returnSuccessTime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新订单详细信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_cancelsource) && $data['cancelsource'] = $this->_cancelsource;
		! is_null($this->_cancelreason) && $data['cancelreason'] = $this->_cancelreason;
		! is_null($this->_auditRemark) && $data['audit_remark'] = $this->_auditRemark;
		! is_null($this->_islongdate) && $data['islongdate'] = $this->_islongdate;
		! is_null($this->_autoDeliveryTime) && $data['auto_delivery_time'] = $this->_autoDeliveryTime;
		! is_null($this->_actualDeliveryTime) && $data['actual_delivery_time'] = $this->_actualDeliveryTime;
		! is_null($this->_evaluate) && $data['evaluate'] = $this->_evaluate;
		! is_null($this->_finishTime) && $data['finish_time'] = $this->_finishTime;
		! is_null($this->_returnStatus) && $data['return_status'] = $this->_returnStatus;
		! is_null($this->_returnAmount) && $data['return_amount'] = $this->_returnAmount;
		! is_null($this->_returnBull) && $data['return_bull'] = $this->_returnBull;
		! is_null($this->_returnTime) && $data['return_time'] = $this->_returnTime;
		! is_null($this->_returnSuccessTime) && $data['return_success_time'] = $this->_returnSuccessTime;
		return $this->update($data);
	}

	/**
	 * 删除订单详细信息表
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
	 * 获取所有订单详细信息表--分页
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
	 * 获取所有订单详细信息表
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

				$this->_orderno 	= null;

				$this->_customerid 	= null;

				$this->_cancelsource 	= null;

				$this->_cancelreason 	= null;

				$this->_auditRemark 	= null;

				$this->_islongdate 	= null;

				$this->_autoDeliveryTime 	= null;

				$this->_actualDeliveryTime 	= null;

				$this->_evaluate 	= null;

				$this->_finishTime 	= null;

				$this->_returnStatus 	= null;

				$this->_returnAmount 	= null;

				$this->_delivertime = null;
				
				$this->_returnBull = null;
				
				$this->_returnTime = null;
				
				$this->_returnSuccessTime = null;
			}
}
?>