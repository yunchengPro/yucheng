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

class StoCusRelationDB extends MysqlDb {

	protected $_tableName = "sto_cus_relation";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_businessid 	= null;

	protected $_customerid 	= null;

	protected $_recustomerid	= null;

	protected $_addtime 	= null;


	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";

	public function __construct() {
	    $this->_fields = ['businessid', 'customerid','recustomerid','addtime'];
	    //$this->_auto   = [array('addtime', 'function', 'time')];
	}


	/**
	 *
	 * 插入实体店banner
	 */
	public function add() {
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_recustomerid) && $data['recustomerid'] = $this->_recustomerid;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
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
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_recustomerid) && $data['recustomerid'] = $this->_recustomerid;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
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