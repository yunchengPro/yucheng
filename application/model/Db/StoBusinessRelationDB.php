<?php
/**
*
* 实体店banner类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: StoBanner.php 10319 2017-03-09 15:52:28Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class StoBusinessRelationDB extends MysqlDb {

	protected $_tableName = "sto_business_relation";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_businessid 	= null;

	protected $_customerid 	= null;

	protected $_mobile 	= null;

	protected $_service_code 	= null;

	protected $_addtime 	= null;


	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";

	public function __construct() {
	    $this->_fields = ['businessid', 'customerid','mobile','service_code', 'addtime'];
	    //$this->_auto   = [array('addtime', 'function', 'time')];
	}


	/**
	 *
	 * 插入实体店banner
	 */
	public function add() {
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_city) && $data['city'] = $this->_city;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_service_code) && $data['service_code'] = $this->_service_code;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新实体店banner
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_type) && $data['type'] = $this->_type;
		! is_null($this->_cityId) && $data['city_id'] = $this->_cityId;
		! is_null($this->_city) && $data['city'] = $this->_city;
		! is_null($this->_bname) && $data['bname'] = $this->_bname;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_urltype) && $data['urltype'] = $this->_urltype;
		! is_null($this->_url) && $data['url'] = $this->_url;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		return $this->update($data);
	}

	/**
	 * 删除实体店banner
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
	 * 获取所有实体店banner--分页
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
	 * 获取所有实体店banner
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

				$this->_type 	= null;

				$this->_cityId 	= null;

				$this->_city 	= null;

				$this->_bname 	= null;

				$this->_thumb 	= null;

				$this->_urltype 	= null;

				$this->_url 	= null;

				$this->_addtime 	= null;

				$this->_sort 	= null;

			}
}
?>