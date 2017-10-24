<?php
/**
*
* 实体商家分类表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: StoCategory.php 10319 2017-03-09 16:03:26Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class StoPayFlowProductDB extends MysqlDb {

	protected $_tableName = "sto_pay_flow_product";

	protected $_primary = "id";

	protected $_id 	= null;
	
	protected $_productid 	= null;

	protected $_productname 	= null;

	protected $_prouctprice 	= null;

	protected $_productnum 	= null;

	protected $_productamount 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入实体商家分类表
	 */
	public function add() {
		! is_null($this->_parentid) && $data['parentid'] = $this->_parentid;
		! is_null($this->_categoryname) && $data['categoryname'] = $this->_categoryname;
		! is_null($this->_businesscount) && $data['businesscount'] = $this->_businesscount;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新实体商家分类表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_parentid) && $data['parentid'] = $this->_parentid;
		! is_null($this->_categoryname) && $data['categoryname'] = $this->_categoryname;
		! is_null($this->_businesscount) && $data['businesscount'] = $this->_businesscount;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		return $this->update($data);
	}

	/**
	 * 删除实体商家分类表
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
	 * 获取所有实体商家分类表--分页
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
	 * 获取所有实体商家分类表
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

				$this->_parentid 	= null;

				$this->_categoryname 	= null;

				$this->_businesscount 	= null;

				$this->_sort 	= null;

			}
}
?>