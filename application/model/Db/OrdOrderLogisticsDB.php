<?php
/**
*
* 订单物流信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdOrderLogistics.php 10319 2017-03-03 16:30:09Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class OrdOrderLogisticsDB extends MysqlDb {

	protected $_tableName = "ord_order_logistics";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_mobile 	= null;

	protected $_realname 	= null;

	protected $_cityId 	= null;

	protected $_city 	= null;

	protected $_address 	= null;

	protected $_leavemessage 	= null;

	protected $_expressType 	= null;

	protected $_expressid 	= null;

	protected $_expressName 	= null;

	protected $_expressNo 	= null;

	protected $_deliveryTime 	= null;

	protected $_orderremark 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入订单物流信息表
	 */
	public function add() {
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_realname) && $data['realname'] = $this->_realname;
		! is_null($this->_cityId) && $data['city_id'] = $this->_cityId;
		! is_null($this->_city) && $data['city'] = $this->_city;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_leavemessage) && $data['leavemessage'] = $this->_leavemessage;
		! is_null($this->_expressType) && $data['express_type'] = $this->_expressType;
		! is_null($this->_expressid) && $data['expressid'] = $this->_expressid;
		! is_null($this->_expressName) && $data['express_name'] = $this->_expressName;
		! is_null($this->_expressNo) && $data['express_no'] = $this->_expressNo;
		! is_null($this->_deliveryTime) && $data['delivery_time'] = $this->_deliveryTime;
		! is_null($this->_orderremark) && $data['orderremark'] = $this->_orderremark;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新订单物流信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_realname) && $data['realname'] = $this->_realname;
		! is_null($this->_cityId) && $data['city_id'] = $this->_cityId;
		! is_null($this->_city) && $data['city'] = $this->_city;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_leavemessage) && $data['leavemessage'] = $this->_leavemessage;
		! is_null($this->_expressType) && $data['express_type'] = $this->_expressType;
		! is_null($this->_expressid) && $data['expressid'] = $this->_expressid;
		! is_null($this->_expressName) && $data['express_name'] = $this->_expressName;
		! is_null($this->_expressNo) && $data['express_no'] = $this->_expressNo;
		! is_null($this->_deliveryTime) && $data['delivery_time'] = $this->_deliveryTime;
		! is_null($this->_orderremark) && $data['orderremark'] = $this->_orderremark;
		return $this->update($data);
	}

	/**
	 * 删除订单物流信息表
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
	 * 获取所有订单物流信息表--分页
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
	 * 获取所有订单物流信息表
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

				$this->_mobile 	= null;

				$this->_realname 	= null;

				$this->_cityId 	= null;

				$this->_city 	= null;

				$this->_address 	= null;

				$this->_leavemessage 	= null;

				$this->_expressType 	= null;

				$this->_expressid 	= null;

				$this->_expressName 	= null;

				$this->_expressNo 	= null;

				$this->_deliveryTime 	= null;

				$this->_orderremark 	= null;

			}
}
?>