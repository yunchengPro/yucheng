<?php
/**
*
* 地区信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: SysArea.php 10319 2017-03-03 15:34:45Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class SysAreaDB extends MysqlDb {

	protected $_tableName = "sys_area";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_areaname 	= null;

	protected $_parentid 	= null;

	protected $_shortname 	= null;

	protected $_areacode 	= null;

	protected $_zipcode 	= null;

	protected $_pinyin 	= null;

	protected $_lng 	= null;

	protected $_lat 	= null;

	protected $_level 	= null;

	protected $_position 	= null;

	protected $_sort 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入地区信息表
	 */
	public function add() {
		! is_null($this->_areaname) && $data['areaname'] = $this->_areaname;
		! is_null($this->_parentid) && $data['parentid'] = $this->_parentid;
		! is_null($this->_shortname) && $data['shortname'] = $this->_shortname;
		! is_null($this->_areacode) && $data['areacode'] = $this->_areacode;
		! is_null($this->_zipcode) && $data['zipcode'] = $this->_zipcode;
		! is_null($this->_pinyin) && $data['pinyin'] = $this->_pinyin;
		! is_null($this->_lng) && $data['lng'] = $this->_lng;
		! is_null($this->_lat) && $data['lat'] = $this->_lat;
		! is_null($this->_level) && $data['level'] = $this->_level;
		! is_null($this->_position) && $data['position'] = $this->_position;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新地区信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_areaname) && $data['areaname'] = $this->_areaname;
		! is_null($this->_parentid) && $data['parentid'] = $this->_parentid;
		! is_null($this->_shortname) && $data['shortname'] = $this->_shortname;
		! is_null($this->_areacode) && $data['areacode'] = $this->_areacode;
		! is_null($this->_zipcode) && $data['zipcode'] = $this->_zipcode;
		! is_null($this->_pinyin) && $data['pinyin'] = $this->_pinyin;
		! is_null($this->_lng) && $data['lng'] = $this->_lng;
		! is_null($this->_lat) && $data['lat'] = $this->_lat;
		! is_null($this->_level) && $data['level'] = $this->_level;
		! is_null($this->_position) && $data['position'] = $this->_position;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		return $this->update($data);
	}

	/**
	 * 删除地区信息表
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
	 * 获取所有地区信息表--分页
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
	 * 获取所有地区信息表
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

				$this->_areaname 	= null;

				$this->_parentid 	= null;

				$this->_shortname 	= null;

				$this->_areacode 	= null;

				$this->_zipcode 	= null;

				$this->_pinyin 	= null;

				$this->_lng 	= null;

				$this->_lat 	= null;

				$this->_level 	= null;

				$this->_position 	= null;

				$this->_sort 	= null;

			}
}
?>