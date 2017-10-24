<?php
/**
*
* 运费模板扩展表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdTransportExtend.php 10319 2017-03-03 16:46:13Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class OrdTransportExtendDB extends MysqlDb {

	protected $_tableName = "ord_transport_extend";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_businessId 	= null;

	protected $_transportId 	= null;

	protected $_transportTitle 	= null;

	protected $_areaId 	= null;

	protected $_topAreaId 	= null;

	protected $_areaName 	= null;

	protected $_snum 	= null;

	protected $_sprice 	= null;

	protected $_xnum 	= null;

	protected $_xprice 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入运费模板扩展表
	 */
	public function add() {
		! is_null($this->_businessId) && $data['business_id'] = $this->_businessId;
		! is_null($this->_transportId) && $data['transport_id'] = $this->_transportId;
		! is_null($this->_transportTitle) && $data['transport_title'] = $this->_transportTitle;
		! is_null($this->_areaId) && $data['area_id'] = $this->_areaId;
		! is_null($this->_topAreaId) && $data['top_area_id'] = $this->_topAreaId;
		! is_null($this->_areaName) && $data['area_name'] = $this->_areaName;
		! is_null($this->_snum) && $data['snum'] = $this->_snum;
		! is_null($this->_sprice) && $data['sprice'] = $this->_sprice;
		! is_null($this->_xnum) && $data['xnum'] = $this->_xnum;
		! is_null($this->_xprice) && $data['xprice'] = $this->_xprice;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新运费模板扩展表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_businessId) && $data['business_id'] = $this->_businessId;
		! is_null($this->_transportId) && $data['transport_id'] = $this->_transportId;
		! is_null($this->_transportTitle) && $data['transport_title'] = $this->_transportTitle;
		! is_null($this->_areaId) && $data['area_id'] = $this->_areaId;
		! is_null($this->_topAreaId) && $data['top_area_id'] = $this->_topAreaId;
		! is_null($this->_areaName) && $data['area_name'] = $this->_areaName;
		! is_null($this->_snum) && $data['snum'] = $this->_snum;
		! is_null($this->_sprice) && $data['sprice'] = $this->_sprice;
		! is_null($this->_xnum) && $data['xnum'] = $this->_xnum;
		! is_null($this->_xprice) && $data['xprice'] = $this->_xprice;
		return $this->update($data);
	}

	/**
	 * 删除运费模板扩展表
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
	 * 获取所有运费模板扩展表--分页
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
	 * 获取所有运费模板扩展表
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

				$this->_businessId 	= null;

				$this->_transportId 	= null;

				$this->_transportTitle 	= null;

				$this->_areaId 	= null;

				$this->_topAreaId 	= null;

				$this->_areaName 	= null;

				$this->_snum 	= null;

				$this->_sprice 	= null;

				$this->_xnum 	= null;

				$this->_xprice 	= null;

			}
}
?>