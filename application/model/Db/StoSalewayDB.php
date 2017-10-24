<?php
/**
*
* 店铺快捷方式表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: StoSaleway.php 10319 2017-03-09 16:22:16Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class StoSalewayDB extends MysqlDb {

	protected $_tableName = "sto_saleway";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_cityId 	= null;

	protected $_city 	= null;

	protected $_name 	= null;

	protected $_thumb 	= null;

	protected $_urltype 	= null;

	protected $_url 	= null;

	protected $_addtime 	= null;

	protected $_sort 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入店铺快捷方式表
	 */
	public function add() {
		! is_null($this->_cityId) && $data['city_id'] = $this->_cityId;
		! is_null($this->_city) && $data['city'] = $this->_city;
		! is_null($this->_name) && $data['name'] = $this->_name;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_urltype) && $data['urltype'] = $this->_urltype;
		! is_null($this->_url) && $data['url'] = $this->_url;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新店铺快捷方式表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_cityId) && $data['city_id'] = $this->_cityId;
		! is_null($this->_city) && $data['city'] = $this->_city;
		! is_null($this->_name) && $data['name'] = $this->_name;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_urltype) && $data['urltype'] = $this->_urltype;
		! is_null($this->_url) && $data['url'] = $this->_url;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		return $this->update($data);
	}

	/**
	 * 删除店铺快捷方式表
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
	 * 获取所有店铺快捷方式表--分页
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
	 * 获取所有店铺快捷方式表
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

				$this->_cityId 	= null;

				$this->_city 	= null;

				$this->_name 	= null;

				$this->_thumb 	= null;

				$this->_urltype 	= null;

				$this->_url 	= null;

				$this->_addtime 	= null;

				$this->_sort 	= null;

			}
}
?>