<?php
/**
*
* 用户信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CusCustomerInfo.php 10319 2017-03-03 16:06:18Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CusCustomerInfoDB extends MysqlDb {

	protected $_tableName = "cus_customer_info";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerCode 	= null;

	protected $_fromtype 	= null;

	protected $_realname 	= null;

	protected $_nickname 	= null;

	protected $_sex 	= null;

	protected $_headerpic 	= null;

	protected $_email 	= null;

	protected $_idnumber 	= null;

	protected $_borndate 	= null;
	
	protected $_area   = null;
	
	protected $_address     = null;
	
	protected $_areaCode = null;

	protected $_lastlogintime 	= null;

	protected $_loginnum 	= null;

	protected $_isnameauth 	= null;

	protected $_payamount 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入用户信息表
	 */
	public function add() {
		! is_null($this->_customerCode) && $data['customer_code'] = $this->_customerCode;
		! is_null($this->_fromtype) && $data['fromtype'] = $this->_fromtype;
		! is_null($this->_realname) && $data['realname'] = $this->_realname;
		! is_null($this->_nickname) && $data['nickname'] = $this->_nickname;
		! is_null($this->_sex) && $data['sex'] = $this->_sex;
		! is_null($this->_headerpic) && $data['headerpic'] = $this->_headerpic;
		! is_null($this->_email) && $data['email'] = $this->_email;
		! is_null($this->_idnumber) && $data['idnumber'] = $this->_idnumber;
		! is_null($this->_borndate) && $data['borndate'] = $this->_borndate;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_lastlogintime) && $data['lastlogintime'] = $this->_lastlogintime;
		! is_null($this->_loginnum) && $data['loginnum'] = $this->_loginnum;
		! is_null($this->_isnameauth) && $data['isnameauth'] = $this->_isnameauth;
		! is_null($this->_payamount) && $data['payamount'] = $this->_payamount;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerCode) && $data['customer_code'] = $this->_customerCode;
		! is_null($this->_fromtype) && $data['fromtype'] = $this->_fromtype;
		! is_null($this->_realname) && $data['realname'] = $this->_realname;
		! is_null($this->_nickname) && $data['nickname'] = $this->_nickname;
		! is_null($this->_sex) && $data['sex'] = $this->_sex;
		! is_null($this->_headerpic) && $data['headerpic'] = $this->_headerpic;
		! is_null($this->_email) && $data['email'] = $this->_email;
		! is_null($this->_idnumber) && $data['idnumber'] = $this->_idnumber;
		! is_null($this->_borndate) && $data['borndate'] = $this->_borndate;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_lastlogintime) && $data['lastlogintime'] = $this->_lastlogintime;
		! is_null($this->_loginnum) && $data['loginnum'] = $this->_loginnum;
		! is_null($this->_isnameauth) && $data['isnameauth'] = $this->_isnameauth;
		! is_null($this->_payamount) && $data['payamount'] = $this->_payamount;
		return $this->update($data);
	}

	/**
	 * 删除用户信息表
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
	 * 获取所有用户信息表--分页
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
	 * 获取所有用户信息表
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

				$this->_customerCode 	= null;

				$this->_fromtype 	= null;

				$this->_realname 	= null;

				$this->_nickname 	= null;

				$this->_sex 	= null;

				$this->_headerpic 	= null;

				$this->_email 	= null;

				$this->_idnumber 	= null;

				$this->_borndate 	= null;
				
				$this->_area    = null;
				
				$this->_address = null;
				
				$this->_areaCode = null;

				$this->_lastlogintime 	= null;

				$this->_loginnum 	= null;

				$this->_isnameauth 	= null;

				$this->_payamount 	= null;

			}
}
?>