<?php
/**
*
* 商品规格值表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ProSpecValue.php 10319 2017-03-03 15:09:33Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ProSpecValueDB extends MysqlDb {

	protected $_tableName = "pro_spec_value";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_specValueName 	= null;

	protected $_specId 	= null;

	protected $_categoryId 	= null;

	protected $_storeId 	= null;

	protected $_specValueColor 	= null;

	protected $_specValueSort 	= null;

	protected $_isdelete 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商品规格值表
	 */
	public function add() {
		! is_null($this->_specValueName) && $data['spec_value_name'] = $this->_specValueName;
		! is_null($this->_specId) && $data['spec_id'] = $this->_specId;
		! is_null($this->_categoryId) && $data['category_id'] = $this->_categoryId;
		! is_null($this->_storeId) && $data['store_id'] = $this->_storeId;
		! is_null($this->_specValueColor) && $data['spec_value_color'] = $this->_specValueColor;
		! is_null($this->_specValueSort) && $data['spec_value_sort'] = $this->_specValueSort;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商品规格值表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_specValueName) && $data['spec_value_name'] = $this->_specValueName;
		! is_null($this->_specId) && $data['spec_id'] = $this->_specId;
		! is_null($this->_categoryId) && $data['category_id'] = $this->_categoryId;
		! is_null($this->_storeId) && $data['store_id'] = $this->_storeId;
		! is_null($this->_specValueColor) && $data['spec_value_color'] = $this->_specValueColor;
		! is_null($this->_specValueSort) && $data['spec_value_sort'] = $this->_specValueSort;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
		return $this->update($data);
	}

	/**
	 * 删除商品规格值表
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
	 * 获取所有商品规格值表--分页
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
	 * 获取所有商品规格值表
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

				$this->_specValueName 	= null;

				$this->_specId 	= null;

				$this->_categoryId 	= null;

				$this->_storeId 	= null;

				$this->_specValueColor 	= null;

				$this->_specValueSort 	= null;

				$this->_isdelete 	= null;

			}
}
?>