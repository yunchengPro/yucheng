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

class RoleApplyChiefDB extends MysqlDb {

	protected $_tableName = "role_apply_chief";

	protected $_primary = "id";

	protected $_customerid 	= null;

	protected $_role 	= null;

	protected $_name 	= null;

	protected $_mobile 	= null;

	protected $_area 	= null;

	protected $_area_code 	= null;

	protected $_address 	= null;

	protected $_addtime 	= null;

	protected $_orderno 	= null;

	protected $_join_type 	= null;

	protected $_status 	= null;

	protected $_remark 	= null;

	protected $_examinetime = null;


	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商品规格值表
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_role) && $data['role'] = $this->_role;
		! is_null($this->_name) && $data['name'] = $this->_name;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_area_code) && $data['area_code'] = $this->_area_code;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_join_type) && $data['join_type'] = $this->_join_type;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_examinetime) && $data['examinetime'] = $this->_examinetime;
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
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_role) && $data['role'] = $this->_role;
		! is_null($this->_name) && $data['name'] = $this->_name;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_area_code) && $data['area_code'] = $this->_area_code;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_join_type) && $data['join_type'] = $this->_join_type;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_examinetime) && $data['examinetime'] = $this->_examinetime;
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