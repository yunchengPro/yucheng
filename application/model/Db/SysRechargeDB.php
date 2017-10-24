<?php
/**
*
* 地铁信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: SysRecharge.php 10319 2017-05-03 19:22:35Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class SysRechargeDB extends MysqlDb {

	protected $_tableName = "sys_recharge";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_rechargeType 	= null;

	protected $_customerid 	= null;

	protected $_mobile 	= null;

	protected $_amount 	= null;

	protected $_payStatus 	= null;

	protected $_payMoney = null;

	protected $_payTime = null;

	protected $_addtime = null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入地铁信息表
	 */
	public function add() {
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_rechargeType) && $data['recharge_type'] = $this->_rechargeType;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_payStatus) && $data['pay_status'] = $this->_payStatus;
		! is_null($this->_payMoney) && $data['pay_money'] = $this->_payMoney;
		! is_null($this->_payTime) && $data['pay_time'] = $this->_payTime;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新地铁信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_rechargeType) && $data['recharge_type'] = $this->_rechargeType;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_payStatus) && $data['pay_status'] = $this->_payStatus;
		! is_null($this->_payMoney) && $data['pay_money'] = $this->_payMoney;
		! is_null($this->_payTime) && $data['pay_time'] = $this->_payTime;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除地铁信息表
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
	 * 获取所有地铁信息表--分页
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
	 * 获取所有地铁信息表
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

				$this->_rechargeType 	= null;

				$this->_customerid 	= null;

				$this->_mobile 	= null;

				$this->_amount 	= null;

				$this->_payStatus 	= null;

				$this->_payMoney = null;

				$this->_payTime = null;

				$this->_addtime = null;

			}
}
?>