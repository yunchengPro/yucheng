<?php
/**
*
* 品牌表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ProBrand.php 10319 2017-03-15 10:02:27Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ProBrandDB extends MysqlDb {

	protected $_tableName = "pro_brand";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_brandname 	= null;

	protected $_brandlogo 	= null;

	protected $_remark 	= null;

	protected $_country 	= null;

	protected $_company 	= null;

	protected $_sort 	= null;

	protected $_isdelete 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";

	public function __construct() {
		$this->_fields = ['brandname','brandlogo','remark','country','company','sort','businessid','isdelete'];
	}

	/**
	 *
	 * 插入品牌表
	 */
	public function add() {
		! is_null($this->_brandname) && $data['brandname'] = $this->_brandname;
		! is_null($this->_brandlogo) && $data['brandlogo'] = $this->_brandlogo;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_country) && $data['country'] = $this->_country;
		! is_null($this->_company) && $data['company'] = $this->_company;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新品牌表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_brandname) && $data['brandname'] = $this->_brandname;
		! is_null($this->_brandlogo) && $data['brandlogo'] = $this->_brandlogo;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_country) && $data['country'] = $this->_country;
		! is_null($this->_company) && $data['company'] = $this->_company;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
		return $this->update($data);
	}

	/**
	 * 删除品牌表
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
	 * 获取所有品牌表--分页
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
	 * 获取所有品牌表
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

				$this->_brandname 	= null;

				$this->_brandlogo 	= null;

				$this->_remark 	= null;

				$this->_country 	= null;

				$this->_company 	= null;

				$this->_sort 	= null;

				$this->_isdelete 	= null;

			}
}
?>