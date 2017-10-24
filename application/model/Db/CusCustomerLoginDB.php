<?php
/**
*
* 用户表API登录令牌类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CusCustomerLogin.php 10319 2017-03-03 16:07:39Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CusCustomerLoginDB extends MysqlDb {

	protected $_tableName = "cus_customer_login";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_mtoken 	= null;

	protected $_devicetoken 	= null;

	protected $_logintime 	= null;

	protected $_devtype 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户表API登录令牌
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_mtoken) && $data['mtoken'] = $this->_mtoken;
		! is_null($this->_devicetoken) && $data['devicetoken'] = $this->_devicetoken;
		! is_null($this->_logintime) && $data['logintime'] = $this->_logintime;
		! is_null($this->_devtype) && $data['devtype'] = $this->_devtype;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户表API登录令牌
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_mtoken) && $data['mtoken'] = $this->_mtoken;
		! is_null($this->_devicetoken) && $data['devicetoken'] = $this->_devicetoken;
		! is_null($this->_logintime) && $data['logintime'] = $this->_logintime;
		! is_null($this->_devtype) && $data['devtype'] = $this->_devtype;
		return $this->update($data);
	}

	/**
	 * 删除用户表API登录令牌
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
	 * 获取所有用户表API登录令牌--分页
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
	 * 获取所有用户表API登录令牌
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

				$this->_mtoken 	= null;

				$this->_devicetoken 	= null;

				$this->_logintime 	= null;

				$this->_devtype 	= null;

			}
}
?>