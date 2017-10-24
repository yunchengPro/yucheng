<?php
/**
*
* 分润记录表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: AmoProfit.php 10319 2017-03-20 12:06:49Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class AmoProfitSettingDB extends MysqlDb {

	protected $_tableName = "amo_profit_setting";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_orderAmount 	= null;

	protected $_totalAmount 	= null;

	protected $_profitAmount 	= null;

	protected $_formula 	= null;

	protected $_profitobj 	= null;

	protected $_profitobjid 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入分润记录表
	 */
	public function add() {
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_orderAmount) && $data['order_amount'] = $this->_orderAmount;
		! is_null($this->_totalAmount) && $data['total_amount'] = $this->_totalAmount;
		! is_null($this->_profitAmount) && $data['profit_amount'] = $this->_profitAmount;
		! is_null($this->_formula) && $data['formula'] = $this->_formula;
		! is_null($this->_profitobj) && $data['profitobj'] = $this->_profitobj;
		! is_null($this->_profitobjid) && $data['profitobjid'] = $this->_profitobjid;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新分润记录表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_orderAmount) && $data['order_amount'] = $this->_orderAmount;
		! is_null($this->_totalAmount) && $data['total_amount'] = $this->_totalAmount;
		! is_null($this->_profitAmount) && $data['profit_amount'] = $this->_profitAmount;
		! is_null($this->_formula) && $data['formula'] = $this->_formula;
		! is_null($this->_profitobj) && $data['profitobj'] = $this->_profitobj;
		! is_null($this->_profitobjid) && $data['profitobjid'] = $this->_profitobjid;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除分润记录表
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
	 * 获取所有分润记录表--分页
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
	 * 获取所有分润记录表
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

				$this->_orderAmount 	= null;

				$this->_totalAmount 	= null;

				$this->_profitAmount 	= null;

				$this->_formula 	= null;

				$this->_profitobj 	= null;

				$this->_profitobjid 	= null;

				$this->_addtime 	= null;

			}
}
?>