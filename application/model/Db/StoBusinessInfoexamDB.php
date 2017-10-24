<?php
/**
*
* 店铺资料修改审核表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: StoBusinessInfoexam.php 10319 2017-03-24 14:16:38Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class StoBusinessInfoexamDB extends MysqlDb {

	protected $_tableName = "sto_business_infoexam";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_stoTypeId 	= null;

	protected $_stoName 	= null;

	protected $_mobile 	= null;

	protected $_discount 	= null;

	protected $_stoHourBegin 	= null;

	protected $_stoHourEnd 	= null;

	protected $_serviceType 	= null;
	
	protected $_delivery = null;

	protected $_nearbyVillage 	= null;

	protected $_stoMobile 	= null;

	protected $_area 	= null;

	protected $_areaCode 	= null;

	protected $_address 	= null;

	protected $_longitude 	= null;

	protected $_latitude 	= null;

	protected $_metroId 	= null;

	protected $_busDistrict 	= null;

	protected $_idnumber 	= null;

	protected $_stoImage 	= null;

	protected $_licenceImage 	= null;

	protected $_idnumberImage 	= null;

	protected $_description 	= null;

	protected $_examinetime 	= null;

	protected $_status 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入店铺资料修改审核表
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_stoTypeId) && $data['sto_type_id'] = $this->_stoTypeId;
		! is_null($this->_stoName) && $data['sto_name'] = $this->_stoName;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_discount) && $data['discount'] = $this->_discount;
		! is_null($this->_stoHourBegin) && $data['sto_hour_begin'] = $this->_stoHourBegin;
		! is_null($this->_stoHourEnd) && $data['sto_hour_end'] = $this->_stoHourEnd;
		! is_null($this->_serviceType) && $data['service_type'] = $this->_serviceType;
		! is_null($this->_delivery) && $data['delivery'] = $this->_delivery;
		! is_null($this->_nearbyVillage) && $data['nearby_village'] = $this->_nearbyVillage;
		! is_null($this->_stoMobile) && $data['sto_mobile'] = $this->_stoMobile;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_longitude) && $data['longitude'] = $this->_longitude;
		! is_null($this->_latitude) && $data['latitude'] = $this->_latitude;
		! is_null($this->_metroId) && $data['metro_id'] = $this->_metroId;
		! is_null($this->_busDistrict) && $data['bus_district'] = $this->_busDistrict;
		! is_null($this->_idnumber) && $data['idnumber'] = $this->_idnumber;
		! is_null($this->_stoImage) && $data['sto_image'] = $this->_stoImage;
		! is_null($this->_licenceImage) && $data['licence_image'] = $this->_licenceImage;
		! is_null($this->_idnumberImage) && $data['idnumber_image'] = $this->_idnumberImage;
		! is_null($this->_description) && $data['description'] = $this->_description;
		! is_null($this->_examinetime) && $data['examinetime'] = $this->_examinetime;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新店铺资料修改审核表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_stoTypeId) && $data['sto_type_id'] = $this->_stoTypeId;
		! is_null($this->_stoName) && $data['sto_name'] = $this->_stoName;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_discount) && $data['discount'] = $this->_discount;
		! is_null($this->_stoHourBegin) && $data['sto_hour_begin'] = $this->_stoHourBegin;
		! is_null($this->_stoHourEnd) && $data['sto_hour_end'] = $this->_stoHourEnd;
		! is_null($this->_serviceType) && $data['service_type'] = $this->_serviceType;
		! is_null($this->_delivery) && $data['delivery'] = $this->_delivery;
		! is_null($this->_nearbyVillage) && $data['nearby_village'] = $this->_nearbyVillage;
		! is_null($this->_stoMobile) && $data['sto_mobile'] = $this->_stoMobile;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_longitude) && $data['longitude'] = $this->_longitude;
		! is_null($this->_latitude) && $data['latitude'] = $this->_latitude;
		! is_null($this->_metroId) && $data['metro_id'] = $this->_metroId;
		! is_null($this->_busDistrict) && $data['bus_district'] = $this->_busDistrict;
		! is_null($this->_idnumber) && $data['idnumber'] = $this->_idnumber;
		! is_null($this->_stoImage) && $data['sto_image'] = $this->_stoImage;
		! is_null($this->_licenceImage) && $data['licence_image'] = $this->_licenceImage;
		! is_null($this->_idnumberImage) && $data['idnumber_image'] = $this->_idnumberImage;
		! is_null($this->_description) && $data['description'] = $this->_description;
		! is_null($this->_examinetime) && $data['examinetime'] = $this->_examinetime;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除店铺资料修改审核表
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
	 * 获取所有店铺资料修改审核表--分页
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
	 * 获取所有店铺资料修改审核表
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

				$this->_customerid 	= null;

				$this->_stoTypeId 	= null;

				$this->_stoName 	= null;

				$this->_mobile 	= null;

				$this->_discount 	= null;

				$this->_stoHourBegin 	= null;

				$this->_stoHourEnd 	= null;

				$this->_serviceType 	= null;
				
				$this->_delivery = null;

				$this->_nearbyVillage 	= null;

				$this->_stoMobile 	= null;

				$this->_area 	= null;

				$this->_areaCode 	= null;

				$this->_address 	= null;

				$this->_longitude 	= null;

				$this->_latitude 	= null;

				$this->_metroId 	= null;

				$this->_busDistrict 	= null;

				$this->_idnumber 	= null;

				$this->_stoImage 	= null;

				$this->_licenceImage 	= null;

				$this->_idnumberImage 	= null;

				$this->_description 	= null;

				$this->_examinetime 	= null;

				$this->_status 	= null;

				$this->_addtime 	= null;

			}
}
?>