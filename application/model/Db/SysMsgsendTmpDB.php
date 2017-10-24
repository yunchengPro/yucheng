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

class SysMsgsendTmpDB extends MysqlDb {

	protected $_tableName = "sys_msgsend_tmp";

	protected $_pk = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_title 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_title) && $data['title'] = $this->_title;
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
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_title) && $data['title'] = $this->_title;
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

	/**
	 *
	 * 获取所有--分页
	 * @param interger $status
	 */
	public function getAllForPage($field='*',$order='',$page = 0, $pagesize = 20,$flag=0) {

		$data = [];

		                                                                                                                                                                ! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_title) && $data['title'] = $this->_title;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;

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