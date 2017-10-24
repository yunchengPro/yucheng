<?php
/**
*
* 产品类型规格属性关系表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ProModuleRelation.php 10319 2017-03-14 21:36:59Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ProModuleRelationDB extends MysqlDb {

	protected $_tableName = "pro_module_relation";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_moduleId 	= null;

	protected $_type 	= null;

	protected $_objId 	= null;

	protected $_sort 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入产品类型规格属性关系表
	 */
	public function add() {
		! is_null($this->_moduleId) && $data['module_id'] = $this->_moduleId;
		! is_null($this->_type) && $data['type'] = $this->_type;
		! is_null($this->_objId) && $data['obj_id'] = $this->_objId;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新产品类型规格属性关系表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_moduleId) && $data['module_id'] = $this->_moduleId;
		! is_null($this->_type) && $data['type'] = $this->_type;
		! is_null($this->_objId) && $data['obj_id'] = $this->_objId;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		return $this->update($data);
	}

	/**
	 * 删除产品类型规格属性关系表
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
	 * 获取所有产品类型规格属性关系表--分页
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
	 * 获取所有产品类型规格属性关系表
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

				$this->_moduleId 	= null;

				$this->_type 	= null;

				$this->_objId 	= null;

				$this->_sort 	= null;

			}
}
?>