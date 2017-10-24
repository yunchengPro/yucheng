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

class ArtCategoryDB extends MysqlDb {

	protected $_tableName = "art_category";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_parentid 	= null;

	protected $_categoryname 	= null;

	protected $_category_icon 	= null;

	protected $_isdelete 	= null;

	protected $_sort 	= null;

	protected $_catetype 	= null;

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
		! is_null($this->_category_icon) && $data['category_icon'] = $this->_category_icon;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_catetype) && $data['catetype'] = $this->_catetype;
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
		! is_null($this->_category_icon) && $data['category_icon'] = $this->_category_icon;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_catetype) && $data['catetype'] = $this->_catetype;
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

				$this->_category_icon 	= null;

				$this->_sort 	= null;

				$this->_isdelete 	= null;

				$this->_catetype 	= null;

			}
}
?>