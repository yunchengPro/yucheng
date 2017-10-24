<?php
/**
*
* 实体商家信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: StoBusinessInfo.php 10319 2017-03-09 16:02:21Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class StoBusinessInfoDB extends MysqlDb {

	protected $_tableName = "sto_business_info";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_businessname 	= null;

	protected $_categoryid 	= null;

	protected $_categoryname 	= null;

	protected $_typeid 	= null;

	protected $_area 	= null;

	protected $_areaCode 	= null;

	protected $_lngx 	= null;

	protected $_laty 	= null;
	
	protected $_metroId = null;
	
	protected $_districtId = null;
	
	protected $_nearbyVillage = null;

	protected $_salecount 	= null;

	protected $_busstartime 	= null;

	protected $_busendtime 	= null;
	
	protected $_licenceImage = null;
	
	protected $_idnumberImage = null;

	protected $_scores 	= null;

	protected $_isparking 	= null;

	protected $_iswifi 	= null;

	protected $_isdelivery 	= null;

	protected $_enable 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入实体商家信息表
	 */
	public function add() {
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_categoryid) && $data['categoryid'] = $this->_categoryid;
		! is_null($this->_categoryname) && $data['categoryname'] = $this->_categoryname;
		! is_null($this->_typeid) && $data['typeid'] = $this->_typeid;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_lngx) && $data['lngx'] = $this->_lngx;
		! is_null($this->_laty) && $data['laty'] = $this->_laty;
		! is_null($this->_metroId) && $data['metro_id'] = $this->_metroId;
		! is_null($this->_districtId) && $data['district_id'] = $this->_districtId;
		! is_null($this->_nearbyVillage) && $data['nearby_village'] = $this->_nearbyVillage;
		! is_null($this->_salecount) && $data['salecount'] = $this->_salecount;
		! is_null($this->_busstartime) && $data['busstartime'] = $this->_busstartime;
		! is_null($this->_busendtime) && $data['busendtime'] = $this->_busendtime;
		! is_null($this->_licenceImage) && $data['licence_image'] = $this->_licenceImage;
		! is_null($this->_idnumberImage) && $data['idnumber_image'] = $this->_idnumberImage;
		! is_null($this->_scores) && $data['scores'] = $this->_scores;
		! is_null($this->_isparking) && $data['isparking'] = $this->_isparking;
		! is_null($this->_iswifi) && $data['iswifi'] = $this->_iswifi;
		! is_null($this->_isdelivery) && $data['isdelivery'] = $this->_isdelivery;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新实体商家信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_categoryid) && $data['categoryid'] = $this->_categoryid;
		! is_null($this->_categoryname) && $data['categoryname'] = $this->_categoryname;
		! is_null($this->_typeid) && $data['typeid'] = $this->_typeid;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_lngx) && $data['lngx'] = $this->_lngx;
		! is_null($this->_laty) && $data['laty'] = $this->_laty;
		! is_null($this->_metroId) && $data['metro_id'] = $this->_metroId;
		! is_null($this->_districtId) && $data['district_id'] = $this->_districtId;
		! is_null($this->_nearbyVillage) && $data['nearby_village'] = $this->_nearbyVillage;
		! is_null($this->_salecount) && $data['salecount'] = $this->_salecount;
		! is_null($this->_busstartime) && $data['busstartime'] = $this->_busstartime;
		! is_null($this->_busendtime) && $data['busendtime'] = $this->_busendtime;
		! is_null($this->_licenceImage) && $data['licence_image'] = $this->_licenceImage;
		! is_null($this->_idnumberImage) && $data['idnumber_image'] = $this->_idnumberImage;
		! is_null($this->_scores) && $data['scores'] = $this->_scores;
		! is_null($this->_isparking) && $data['isparking'] = $this->_isparking;
		! is_null($this->_iswifi) && $data['iswifi'] = $this->_iswifi;
		! is_null($this->_isdelivery) && $data['isdelivery'] = $this->_isdelivery;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		return $this->update($data);
	}

	/**
	 * 删除实体商家信息表
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
	 * 获取所有实体商家信息表--分页
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
	 * 获取所有实体商家信息表
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

				$this->_businessname 	= null;

				$this->_categoryid 	= null;

				$this->_categoryname 	= null;

				$this->_typeid 	= null;

				$this->_area 	= null;

				$this->_areaCode 	= null;

				$this->_lngx 	= null;

				$this->_laty 	= null;
				
				$this->_metroId = null;
				
				$this->_districtId = null;
				
				$this->_nearbyVillage = null;

				$this->_salecount 	= null;

				$this->_busstartime 	= null;

				$this->_busendtime 	= null;
				
				$this->_licenceImage = null;
				
				$this->_idnumberImage = null;

				$this->_scores 	= null;

				$this->_isparking 	= null;

				$this->_iswifi 	= null;

				$this->_isdelivery 	= null;

				$this->_enable 	= null;

			}
}
?>