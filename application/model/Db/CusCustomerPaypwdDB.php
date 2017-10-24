<?php
/**
*
* 用户表API登录令牌类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CusCustomerPaypwd.php 10319 2017-03-03 16:08:24Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CusCustomerPaypwdDB extends MysqlDb {

	protected $_tableName = "cus_customer_paypwd";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_paypwd 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户表API登录令牌
	 */
	public function add() {
	    ! is_null($this->_id) && $data['id'] = $this->_id;
		! is_null($this->_paypwd) && $data['paypwd'] = $this->_paypwd;
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
		! is_null($this->_paypwd) && $data['paypwd'] = $this->_paypwd;
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

				$this->_paypwd 	= null;

			}
}
?>