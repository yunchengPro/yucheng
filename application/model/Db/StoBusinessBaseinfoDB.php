<?php
/**
*
* 实体商家表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: StoBusinessBaseinfo.php 10319 2017-03-09 15:59:04Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class StoBusinessBaseinfoDB extends MysqlDb {

	protected $_tableName = "sto_business_baseinfo";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_businessCode 	= null;

	protected $_businessname 	= null;

	protected $_idnumber 	= null;

	protected $_mobile 	= null;

	protected $_address 	= null;

	protected $_servicetel 	= null;

	protected $_description 	= null;

	protected $_businessintro 	= null;

	protected $_typeid 	= null;

	protected $_typename 	= null;

	protected $_discount 	= null;

	protected $_scorescount 	= null;

	protected $_delivery 	= null;

	protected $_productcount 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入实体商家表
	 */
	public function add() {
		! is_null($this->_businessCode) && $data['business_code'] = $this->_businessCode;
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_idnumber) && $data['idnumber'] = $this->_idnumber;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_servicetel) && $data['servicetel'] = $this->_servicetel;
		! is_null($this->_description) && $data['description'] = $this->_description;
		! is_null($this->_businessintro) && $data['businessintro'] = $this->_businessintro;
		! is_null($this->_typeid) && $data['typeid'] = $this->_typeid;
		! is_null($this->_typename) && $data['typename'] = $this->_typename;
		! is_null($this->_discount) && $data['discount'] = $this->_discount;
		! is_null($this->_scorescount) && $data['scorescount'] = $this->_scorescount;
		! is_null($this->_delivery) && $data['delivery'] = $this->_delivery;
		! is_null($this->_productcount) && $data['productcount'] = $this->_productcount;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新实体商家表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_businessCode) && $data['business_code'] = $this->_businessCode;
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_idnumber) && $data['idnumber'] = $this->_idnumber;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_servicetel) && $data['servicetel'] = $this->_servicetel;
		! is_null($this->_description) && $data['description'] = $this->_description;
		! is_null($this->_businessintro) && $data['businessintro'] = $this->_businessintro;
		! is_null($this->_typeid) && $data['typeid'] = $this->_typeid;
		! is_null($this->_typename) && $data['typename'] = $this->_typename;
		! is_null($this->_discount) && $data['discount'] = $this->_discount;
		! is_null($this->_scorescount) && $data['scorescount'] = $this->_scorescount;
		! is_null($this->_delivery) && $data['delivery'] = $this->_delivery;
		! is_null($this->_productcount) && $data['productcount'] = $this->_productcount;
		return $this->update($data);
	}

	/**
	 * 删除实体商家表
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
	 * 获取所有实体商家表--分页
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
	 * 获取所有实体商家表
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

				$this->_businessCode 	= null;

				$this->_businessname 	= null;

				$this->_idnumber 	= null;

				$this->_mobile 	= null;

				$this->_address 	= null;

				$this->_servicetel 	= null;

				$this->_description 	= null;

				$this->_businessintro 	= null;

				$this->_typeid 	= null;

				$this->_typename 	= null;

				$this->_discount 	= null;

				$this->_scorescount 	= null;

				$this->_delivery 	= null;

				$this->_productcount 	= null;

			}
}
?>