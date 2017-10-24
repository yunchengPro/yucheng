<?php
/**
*
* 商家分类表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: BusBusinessCategory.php 10319 2017-03-03 15:13:49Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class BusBusinessCategoryDB extends MysqlDb {

	protected $_tableName = "bus_business_category";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_businessid 	= null;

	protected $_categoryName 	= null;

	protected $_parentId 	= null;

	protected $_sort 	= null;

	protected $_isDelete 	= null;

	protected $_categoryIcon 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";
	
	public function __construct() {
		$this->_fields = ['businessid','category_name','parent_id','sort','is_delete','category_icon'];
	}

	/**
	 *
	 * 插入商家分类表
	 */
	public function add() {
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_categoryName) && $data['category_name'] = $this->_categoryName;
		! is_null($this->_parentId) && $data['parent_id'] = $this->_parentId;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_isDelete) && $data['is_delete'] = $this->_isDelete;
		! is_null($this->_categoryIcon) && $data['category_icon'] = $this->_categoryIcon;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商家分类表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_categoryName) && $data['category_name'] = $this->_categoryName;
		! is_null($this->_parentId) && $data['parent_id'] = $this->_parentId;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_isDelete) && $data['is_delete'] = $this->_isDelete;
		! is_null($this->_categoryIcon) && $data['category_icon'] = $this->_categoryIcon;
		return $this->update($data);
	}

	/**
	 * 删除商家分类表
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
	 * 获取所有商家分类表--分页
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
	 * 获取所有商家分类表
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

				$this->_categoryName 	= null;

				$this->_parentId 	= null;

				$this->_sort 	= null;

				$this->_isDelete 	= null;

				$this->_categoryIcon 	= null;

			}
}
?>