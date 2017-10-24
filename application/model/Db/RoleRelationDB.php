<?php
/**
*
* 角色关系值表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: RoleRelation.php 10319 2017-03-03 15:30:52Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class RoleRelationDB extends MysqlDb {

	protected $_tableName = "role_relation";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_parentid 	= null;

	protected $_grandpaid 	= null;

	protected $_bindbusinessid 	= null;

	protected $_citycode 	= null;

	protected $_countycode 	= null;

	protected $_parentagentid 	= null;

	protected $_introducerid 	= null;

	protected $_introducername 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入角色关系值表
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_parentid) && $data['parentid'] = $this->_parentid;
		! is_null($this->_grandpaid) && $data['grandpaid'] = $this->_grandpaid;
		! is_null($this->_bindbusinessid) && $data['bindbusinessid'] = $this->_bindbusinessid;
		! is_null($this->_citycode) && $data['citycode'] = $this->_citycode;
		! is_null($this->_countycode) && $data['countycode'] = $this->_countycode;
		! is_null($this->_parentagentid) && $data['parentagentid'] = $this->_parentagentid;
		! is_null($this->_introducerid) && $data['introducerid'] = $this->_introducerid;
		! is_null($this->_introducername) && $data['introducername'] = $this->_introducername;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新角色关系值表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_parentid) && $data['parentid'] = $this->_parentid;
		! is_null($this->_grandpaid) && $data['grandpaid'] = $this->_grandpaid;
		! is_null($this->_bindbusinessid) && $data['bindbusinessid'] = $this->_bindbusinessid;
		! is_null($this->_citycode) && $data['citycode'] = $this->_citycode;
		! is_null($this->_countycode) && $data['countycode'] = $this->_countycode;
		! is_null($this->_parentagentid) && $data['parentagentid'] = $this->_parentagentid;
		! is_null($this->_introducerid) && $data['introducerid'] = $this->_introducerid;
		! is_null($this->_introducername) && $data['introducername'] = $this->_introducername;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除角色关系值表
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
	 * 获取所有角色关系值表--分页
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
	 * 获取所有角色关系值表
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

				$this->_customerid 	= null;

				$this->_parentid 	= null;

				$this->_grandpaid 	= null;

				$this->_bindbusinessid 	= null;

				$this->_citycode 	= null;

				$this->_countycode 	= null;

				$this->_parentagentid 	= null;

				$this->_introducerid 	= null;

				$this->_introducername 	= null;

				$this->_addtime 	= null;

			}
}
?>