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

class CusCustomerOpenDB extends MysqlDb {

	protected $_tableName = "cus_customer_open";

	protected $_pk = "id";

	protected $_id 	= null;

	protected $_mobile 	= null;

	protected $_username 	= null;

	protected $_userpwd 	= null;

	protected $_enable 	= null;

	protected $_createtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入
	 */
	public function add() {
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_username) && $data['username'] = $this->_username;
		! is_null($this->_userpwd) && $data['userpwd'] = $this->_userpwd;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		! is_null($this->_createtime) && $data['createtime'] = $this->_createtime;
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
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_username) && $data['username'] = $this->_username;
		! is_null($this->_userpwd) && $data['userpwd'] = $this->_userpwd;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		! is_null($this->_createtime) && $data['createtime'] = $this->_createtime;
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

	/**
	 *
	 * 获取所有--分页
	 * @param interger $status
	 */
	public function getAllForPage($field='*',$order='',$page = 0, $pagesize = 20,$flag=0) {

		$data = [];

		! is_null($this->_id) && $data['id'] = $this->_id;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_username) && $data['username'] = $this->_username;
		! is_null($this->_userpwd) && $data['userpwd'] = $this->_userpwd;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		! is_null($this->_createtime) && $data['createtime'] = $this->_createtime;

		$where = $data; 
		return $this->pageAllList($where,$field,$order,$page,$pagesize,$flag);
	}

	/**
	 * 获取所有
	 * @return Ambigous 
	 */
	public function getAll($field='*',$order='',$limit=0,$offset=0,$otherstr='') {

		$data = [];

		! is_null($this->_id) && $data['id'] = $this->_id;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_username) && $data['username'] = $this->_username;
		! is_null($this->_userpwd) && $data['userpwd'] = $this->_userpwd;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		! is_null($this->_createtime) && $data['createtime'] = $this->_createtime;

		$where = $data; 
		return $this->getList($where,$field,$order,$limit,$offset,$otherstr);
	}
}
?>