<?php
/**
*
* 牛商推荐信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: RoleRecoBus.php 10319 2017-03-14 11:43:45Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class RoleRecoBusDB extends MysqlDb {

	protected $_tableName = "role_reco_bus";

	protected $_primary = "id";

	protected $_id 	= null;
	
	protected $_cusRoleId = null;

	protected $_companyName 	= null;

	protected $_personCharge 	= null;

	protected $_mobile 	= null;

	protected $_corporation 	= null;
	
	protected $_area = null;

	protected $_areaCode 	= null;

	protected $_companyArea 	= null;

	protected $_longitude 	= null;

	protected $_latitude 	= null;

	protected $_priceType 	= null;

	protected $_idnumber 	= null;

	protected $_businessLicence 	= null;

	protected $_licenceImage 	= null;

	protected $_idnumberImage 	= null;

	protected $_companyLogo 	= null;
	
	protected $_remark = null;

	protected $_status 	= null;
	
	protected $_disabled = null;

	protected $_examinetime 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入牛商推荐信息表
	 */
	public function add() {
	    ! is_null($this->_cusRoleId) && $data['cus_role_id'] = $this->_cusRoleId;
		! is_null($this->_companyName) && $data['company_name'] = $this->_companyName;
		! is_null($this->_personCharge) && $data['person_charge'] = $this->_personCharge;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_corporation) && $data['corporation'] = $this->_corporation;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_companyArea) && $data['company_area'] = $this->_companyArea;
		! is_null($this->_longitude) && $data['longitude'] = $this->_longitude;
		! is_null($this->_latitude) && $data['latitude'] = $this->_latitude;
		! is_null($this->_priceType) && $data['price_type'] = $this->_priceType;
		! is_null($this->_idnumber) && $data['idnumber'] = $this->_idnumber;
		! is_null($this->_businessLicence) && $data['business_licence'] = $this->_businessLicence;
		! is_null($this->_licenceImage) && $data['licence_image'] = $this->_licenceImage;
		! is_null($this->_idnumberImage) && $data['idnumber_image'] = $this->_idnumberImage;
		! is_null($this->_companyLogo) && $data['company_logo'] = $this->_companyLogo;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_disabled) && $data['disabled'] = $this->_disabled;
		! is_null($this->_examinetime) && $data['examinetime'] = $this->_examinetime;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新牛商推荐信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_cusRoleId) && $data['cus_role_id'] = $this->_cusRoleId;
		! is_null($this->_companyName) && $data['company_name'] = $this->_companyName;
		! is_null($this->_personCharge) && $data['person_charge'] = $this->_personCharge;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_corporation) && $data['corporation'] = $this->_corporation;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_companyArea) && $data['company_area'] = $this->_companyArea;
		! is_null($this->_longitude) && $data['longitude'] = $this->_longitude;
		! is_null($this->_latitude) && $data['latitude'] = $this->_latitude;
		! is_null($this->_priceType) && $data['price_type'] = $this->_priceType;
		! is_null($this->_idnumber) && $data['idnumber'] = $this->_idnumber;
		! is_null($this->_businessLicence) && $data['business_licence'] = $this->_businessLicence;
		! is_null($this->_licenceImage) && $data['licence_image'] = $this->_licenceImage;
		! is_null($this->_idnumberImage) && $data['idnumber_image'] = $this->_idnumberImage;
		! is_null($this->_companyLogo) && $data['company_logo'] = $this->_companyLogo;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_disabled) && $data['disabled'] = $this->_disabled;
		! is_null($this->_examinetime) && $data['examinetime'] = $this->_examinetime;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除牛商推荐信息表
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
	 * 获取所有牛商推荐信息表--分页
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
	 * 获取所有牛商推荐信息表
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
				
				$this->_cusRoleId = null;

				$this->_companyName 	= null;

				$this->_personCharge 	= null;

				$this->_mobile 	= null;

				$this->_corporation 	= null;
				
				$this->_area = null;

				$this->_areaCode 	= null;

				$this->_companyArea 	= null;

				$this->_longitude 	= null;

				$this->_latitude 	= null;

				$this->_priceType 	= null;

				$this->_idnumber 	= null;

				$this->_businessLicence 	= null;

				$this->_licenceImage 	= null;

				$this->_idnumberImage 	= null;

				$this->_companyLogo 	= null;
				
				$this->_remark = null;

				$this->_status 	= null;
				
				$this->_disabled = null;

				$this->_examinetime 	= null;

				$this->_addtime 	= null;

			}
}
?>