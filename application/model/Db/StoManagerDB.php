<?php
/**
*
* 实体商家铺收款用户关系表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: StoManager.php 10319 2017-03-09 16:12:48Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class StoManagerDB extends MysqlDb {

	protected $_tableName = "sto_manager";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_businessid 	= null;

	protected $_businessname 	= null;

	protected $_businessCode 	= null;

	protected $_customerid 	= null;

	protected $_customername 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入实体商家铺收款用户关系表
	 */
	public function add() {
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_businessCode) && $data['business_code'] = $this->_businessCode;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_customername) && $data['customername'] = $this->_customername;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新实体商家铺收款用户关系表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_businessCode) && $data['business_code'] = $this->_businessCode;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_customername) && $data['customername'] = $this->_customername;
		return $this->update($data);
	}

	/**
	 * 删除实体商家铺收款用户关系表
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
	 * 获取所有实体商家铺收款用户关系表--分页
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
	 * 获取所有实体商家铺收款用户关系表
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

				$this->_businessid 	= null;

				$this->_businessname 	= null;

				$this->_businessCode 	= null;

				$this->_customerid 	= null;

				$this->_customername 	= null;

			}
}
?>