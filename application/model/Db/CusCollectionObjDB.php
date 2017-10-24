<?php
/**
*
* 类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CusCustomer.php 10319 2017-03-02 14:33:34Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CusCollectionObjDB extends MysqlDb {

	protected $_tableName = "cus_collection_obj";

	protected $_pk = "id";

	protected $_id 	= null;

	protected $_obj_id 	= null;

	protected $_customerid 	= null;

	protected $_type 	= null;

	protected $_addtime 	= null;


	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入
	 */
	public function add() {
		! is_null($this->_obj_id) && $data['obj_id'] = $this->_obj_id;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_type) && $data['type'] = $this->_type;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新
	 */
	public function modify($id) {
		$data[$this->_pk] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_obj_id) && $data['obj_id'] = $this->_obj_id;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_type) && $data['type'] = $this->_type;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除
	 */
	public function del($id) {
		$data[$this->_pk] = $this->_id = intval($id);
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
		return $this->getRow(array($this->_pk => $this->_id));
	}

	
}
?>