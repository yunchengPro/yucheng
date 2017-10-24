<?php
/**
*
* 地铁信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: SysMetro.php 10319 2017-03-14 11:57:07Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class SysMetroDB extends MysqlDb {

	protected $_tableName = "sys_metro";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_areaname 	= null;

	protected $_areaid 	= null;

	protected $_linename 	= null;

	protected $_pinyin 	= null;

	protected $_metroname 	= null;

	protected $_sort 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入地铁信息表
	 */
	public function add() {
		! is_null($this->_areaname) && $data['areaname'] = $this->_areaname;
		! is_null($this->_areaid) && $data['areaid'] = $this->_areaid;
		! is_null($this->_linename) && $data['linename'] = $this->_linename;
		! is_null($this->_pinyin) && $data['pinyin'] = $this->_pinyin;
		! is_null($this->_metroname) && $data['metroname'] = $this->_metroname;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新地铁信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_areaname) && $data['areaname'] = $this->_areaname;
		! is_null($this->_areaid) && $data['areaid'] = $this->_areaid;
		! is_null($this->_linename) && $data['linename'] = $this->_linename;
		! is_null($this->_pinyin) && $data['pinyin'] = $this->_pinyin;
		! is_null($this->_metroname) && $data['metroname'] = $this->_metroname;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		return $this->update($data);
	}

	/**
	 * 删除地铁信息表
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
	 * 获取所有地铁信息表--分页
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
	 * 获取所有地铁信息表
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

				$this->_areaid 	= null;

				$this->_linename 	= null;

				$this->_pinyin 	= null;

				$this->_metroname 	= null;

				$this->_sort 	= null;

			}
}
?>