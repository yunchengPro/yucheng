<?php
/**
*
* 商家基本信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: BusBusinessInfo.php 10319 2017-03-03 15:16:36Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class BusBusinessInfoDB extends MysqlDb {

	protected $_tableName = "bus_business_info";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_businesstype 	= null;

	protected $_businessname 	= null;

	protected $_businesslogo 	= null;

	protected $_idnumber 	= null;

	protected $_mobile 	= null;

	protected $_area 	= null;

	protected $_areaCode 	= null;

	protected $_address 	= null;

	protected $_servicetel 	= null;

	protected $_description 	= null;

	protected $_businessintro 	= null;

	protected $_scores 	= scores;

	protected $_lngx 	= null;

	protected $_laty 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商家基本信息表
	 */
	public function add() {
		! is_null($this->_businesstype) && $data['businesstype'] = $this->_businesstype;
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_businesslogo) && $data['businesslogo'] = $this->_businesslogo;
		! is_null($this->_idnumber) && $data['idnumber'] = $this->_idnumber;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_servicetel) && $data['servicetel'] = $this->_servicetel;
		! is_null($this->_description) && $data['description'] = $this->_description;
		! is_null($this->_businessintro) && $data['businessintro'] = $this->_businessintro;
		! is_null($this->_lngx) && $data['lngx'] = $this->_lngx;
		! is_null($this->_laty) && $data['laty'] = $this->_laty;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商家基本信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_businesstype) && $data['businesstype'] = $this->_businesstype;
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_businesslogo) && $data['businesslogo'] = $this->_businesslogo;
		! is_null($this->_idnumber) && $data['idnumber'] = $this->_idnumber;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_servicetel) && $data['servicetel'] = $this->_servicetel;
		! is_null($this->_description) && $data['description'] = $this->_description;
		! is_null($this->_businessintro) && $data['businessintro'] = $this->_businessintro;
		! is_null($this->_lngx) && $data['lngx'] = $this->_lngx;
		! is_null($this->_laty) && $data['laty'] = $this->_laty;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除商家基本信息表
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
	 * 获取所有商家基本信息表--分页
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
	 * 获取所有商家基本信息表
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

				$this->_businesstype 	= null;

				$this->_businessname 	= null;

				$this->_businesslogo 	= null;

				$this->_idnumber 	= null;

				$this->_mobile 	= null;

				$this->_area 	= null;

				$this->_areaCode 	= null;

				$this->_address 	= null;

				$this->_servicetel 	= null;

				$this->_description 	= null;

				$this->_businessintro 	= null;

				$this->_lngx 	= null;

				$this->_laty 	= null;

				$this->_addtime 	= null;

			}
}
?>