<?php
/**
*
* 孵化中心推荐信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: RoleRecoCounty.php 10319 2017-03-14 11:49:17Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class RoleRecoCountyDB extends MysqlDb {

	protected $_tableName = "role_reco_county";

	protected $_primary = "id";

	protected $_id 	= null;
	
	protected $_cusRoleId = null;

	protected $_type 	= null;

	protected $_realname 	= null;

	protected $_mobile 	= null;

	protected $_area 	= null;

	protected $_areaCode 	= null;

	protected $_address 	= null;

	protected $_longitude 	= null;

	protected $_latitude 	= null;

	protected $_joinCode 	= null;

	protected $_joinArea 	= null;

	protected $_companyName 	= null;

	protected $_chargeIdnumber 	= null;

	protected $_chargeName 	= null;

	protected $_chargeMobile 	= null;

	protected $_corporationName 	= null;

	protected $_corporationIdnumber 	= null;

	protected $_businessLicence 	= null;

	protected $_licenceImage 	= null;

	protected $_examinetime 	= null;
	
	protected $_remark = null;

	protected $_status 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入孵化中心推荐信息表
	 */
	public function add() {
	    ! is_null($this->_cusRoleId) && $data['cus_role_id'] = $this->_cusRoleId;
		! is_null($this->_type) && $data['type'] = $this->_type;
		! is_null($this->_realname) && $data['realname'] = $this->_realname;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_longitude) && $data['longitude'] = $this->_longitude;
		! is_null($this->_latitude) && $data['latitude'] = $this->_latitude;
		! is_null($this->_joinCode) && $data['join_code'] = $this->_joinCode;
		! is_null($this->_joinArea) && $data['join_area'] = $this->_joinArea;
		! is_null($this->_companyName) && $data['company_name'] = $this->_companyName;
		! is_null($this->_chargeIdnumber) && $data['charge_idnumber'] = $this->_chargeIdnumber;
		! is_null($this->_chargeName) && $data['charge_name'] = $this->_chargeName;
		! is_null($this->_chargeMobile) && $data['charge_mobile'] = $this->_chargeMobile;
		! is_null($this->_corporationName) && $data['corporation_name'] = $this->_corporationName;
		! is_null($this->_corporationIdnumber) && $data['corporation_idnumber'] = $this->_corporationIdnumber;
		! is_null($this->_businessLicence) && $data['business_licence'] = $this->_businessLicence;
		! is_null($this->_licenceImage) && $data['licence_image'] = $this->_licenceImage;
		! is_null($this->_examinetime) && $data['examinetime'] = $this->_examinetime;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新孵化中心推荐信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_cusRoleId) && $data['cus_role_id'] = $this->_cusRoleId;
		! is_null($this->_type) && $data['type'] = $this->_type;
		! is_null($this->_realname) && $data['realname'] = $this->_realname;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_longitude) && $data['longitude'] = $this->_longitude;
		! is_null($this->_latitude) && $data['latitude'] = $this->_latitude;
		! is_null($this->_joinCode) && $data['join_code'] = $this->_joinCode;
		! is_null($this->_joinArea) && $data['join_area'] = $this->_joinArea;
		! is_null($this->_companyName) && $data['company_name'] = $this->_companyName;
		! is_null($this->_chargeIdnumber) && $data['charge_idnumber'] = $this->_chargeIdnumber;
		! is_null($this->_chargeName) && $data['charge_name'] = $this->_chargeName;
		! is_null($this->_chargeMobile) && $data['charge_mobile'] = $this->_chargeMobile;
		! is_null($this->_corporationName) && $data['corporation_name'] = $this->_corporationName;
		! is_null($this->_corporationIdnumber) && $data['corporation_idnumber'] = $this->_corporationIdnumber;
		! is_null($this->_businessLicence) && $data['business_licence'] = $this->_businessLicence;
		! is_null($this->_licenceImage) && $data['licence_image'] = $this->_licenceImage;
		! is_null($this->_examinetime) && $data['examinetime'] = $this->_examinetime;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除孵化中心推荐信息表
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
	 * 获取所有孵化中心推荐信息表--分页
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
	 * 获取所有孵化中心推荐信息表
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

				$this->_type 	= null;

				$this->_realname 	= null;

				$this->_mobile 	= null;

				$this->_area 	= null;

				$this->_areaCode 	= null;

				$this->_address 	= null;

				$this->_longitude 	= null;

				$this->_latitude 	= null;

				$this->_joinCode 	= null;

				$this->_joinArea 	= null;

				$this->_companyName 	= null;

				$this->_chargeIdnumber 	= null;

				$this->_chargeName 	= null;

				$this->_chargeMobile 	= null;

				$this->_corporationName 	= null;

				$this->_corporationIdnumber 	= null;

				$this->_businessLicence 	= null;

				$this->_licenceImage 	= null;

				$this->_examinetime 	= null;
				
				$this->_remark = null;

				$this->_status 	= null;

				$this->_addtime 	= null;

			}
}
?>