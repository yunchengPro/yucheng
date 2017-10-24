<?php
/**
*
* 用户物流信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdUserLogistics.php 10319 2017-03-03 16:42:54Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class OrdUserLogisticsInfoDB extends MysqlDb {

	protected $_tableName = "ord_user_logistics_info";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_mobile 	= null;

	protected $_realname 	= null;

	protected $_cityId 	= null;

	protected $_city 	= null;

	protected $_address 	= null;

	protected $_isdefault 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户物流信息表
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_realname) && $data['realname'] = $this->_realname;
		! is_null($this->_cityId) && $data['city_id'] = $this->_cityId;
		! is_null($this->_city) && $data['city'] = $this->_city;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_isdefault) && $data['isdefault'] = $this->_isdefault;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户物流信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_realname) && $data['realname'] = $this->_realname;
		! is_null($this->_cityId) && $data['city_id'] = $this->_cityId;
		! is_null($this->_city) && $data['city'] = $this->_city;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_isdefault) && $data['isdefault'] = $this->_isdefault;
		return $this->update($data);
	}

	/**
	 * 删除用户物流信息表
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
	 * 获取所有用户物流信息表--分页
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
	 * 获取所有用户物流信息表
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

				$this->_mobile 	= null;

				$this->_realname 	= null;

				$this->_cityId 	= null;

				$this->_city 	= null;

				$this->_address 	= null;

				$this->_isdefault 	= null;

			}
}
?>