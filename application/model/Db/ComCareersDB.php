<?php
/**
*
* 商品分类类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ProCategoty.php 10319 2017-03-03 18:07:41Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ComCareersDB extends MysqlDb {

	protected $_tableName = "com_careers";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_position 	= null;

	protected $_position_en 	= null;

	protected $_responsibility 	= null;

	protected $_requirement 	= null;

	protected $_isdelete 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_com";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商品分类
	 */
	public function add() {
		! is_null($this->_position) && $data['position'] = $this->_position;
		! is_null($this->_position_en) && $data['position_en'] = $this->_position_en;
		! is_null($this->_responsibility) && $data['responsibility'] = $this->_responsibility;
		! is_null($this->_requirement) && $data['requirement'] = $this->_requirement;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商品分类
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_parentId) && $data['parentid'] = $this->_parentId;
		! is_null($this->_categoryname) && $data['categoryname'] = $this->_categoryname;
		! is_null($this->_category_icon) && $data['category_icon'] = $this->_category_icon;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
		return $this->update($data);
	}

	/**
	 * 删除商品分类
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
	 * 获取所有商品分类--分页
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
	 * 获取所有商品分类
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

				$this->_parentId 	= null;

				$this->_name 	= null;

				$this->_status 	= null;

			}
}
?>