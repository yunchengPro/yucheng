<?php
/**
*
* 投诉类型表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CusProPosalType.php 10319 2017-03-03 16:12:46Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CusProPosalTypeDB extends MysqlDb {

	protected $_tableName = "cus_proposal_type";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_name 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入投诉类型表
	 */
	public function add() {
		! is_null($this->_name) && $data['name'] = $this->_name;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新投诉类型表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_name) && $data['name'] = $this->_name;
		return $this->update($data);
	}

	/**
	 * 删除投诉类型表
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
	 * 获取所有投诉类型表--分页
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
	 * 获取所有投诉类型表
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

				$this->_name 	= null;

			}
}
?>