<?php
/**
*
* 产品类型表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ProModule.php 10319 2017-03-14 21:32:52Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ProProductAlterlogDB extends MysqlDb {

	protected $_tableName = "pro_product_alterlog";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_username 	= null;

	protected $_productid   = null;

	protected $_content 	= null;

	protected $_addtime  	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入产品类型表
	 */
	public function add() {
		! is_null($this->_username) && $data['username'] = $this->_username;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_content) && $data['content'] = $this->_content;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新产品类型表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_username) && $data['username'] = $this->_username;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_content) && $data['content'] = $this->_content;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除产品类型表
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
	 * 获取所有产品类型表--分页
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
	 * 获取所有产品类型表
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

				$this->_modulename 	= null;

				$this->_sort 	= null;

				$this->_isdelete 	= null;

			}
}
?>