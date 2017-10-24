<?php
/**
*
* 实体店订单表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdOrder.php 10319 2017-03-03 16:19:56Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class SysBountyListDB extends MysqlDb {

	protected $_tableName = "sys_bounty_list";

	protected $_primary = "id";
	
	protected $_customerid 	= null;

	protected $_cashamount 	= null;

	protected $_profitamount 	= null;

	protected $_bullamount 	= null;

	protected $_getbountydate	= null;

	protected $_addtime	= null;

	protected $_ischeck = null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入订单表
	 */
	public function add() {
		
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新订单表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		
		return $this->update($data);
	}

	/**
	 * 删除订单表
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
	 * 获取所有订单表--分页
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
	 * 获取所有订单表
	 * @return Ambigous 
	 */
	public function getAll() {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		return $this->select();
	}

	
	
}
?>