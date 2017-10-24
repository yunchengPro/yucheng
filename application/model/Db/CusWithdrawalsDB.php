<?php
/**
*
* 用户提现申请表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CusWithdrawals.php 10319 2017-04-06 10:25:23Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CusWithdrawalsDB extends MysqlDb {

	protected $_tableName = "cus_withdrawals";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_bankid 	= null;

	protected $_accountName 	= null;

	protected $_accountNumber 	= null;

	protected $_branch 	= null;

	protected $_mobile 	= null;

	protected $_amount 	= null;

	protected $_addtime 	= null;

	protected $_status 	= null;

	protected $_payMoney 	= null;

	protected $_payTime 	= null;

	protected $_handleUser 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	public function __construct() {
	    $this->_fields = ['customerid', 'bankid', 'account_name', 'account_number', 'branch', 'mobile', 
	        'amount', 'addtime', 'status', 'remark', 'pay_money', 'pay_time', 'handle_user', 'bank_type_name'];
	    //$this->_auto   = [array('addtime', 'function', 'time')];
	}
	
	/**
	 *
	 * 插入用户提现申请表
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_bankid) && $data['bankid'] = $this->_bankid;
		! is_null($this->_accountName) && $data['account_name'] = $this->_accountName;
		! is_null($this->_accountNumber) && $data['account_number'] = $this->_accountNumber;
		! is_null($this->_branch) && $data['branch'] = $this->_branch;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_amount) && $data['amount'] = $this->_amount;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_payMoney) && $data['pay_money'] = $this->_payMoney;
		! is_null($this->_payTime) && $data['pay_time'] = $this->_payTime;
		! is_null($this->_handleUser) && $data['handle_user'] = $this->_handleUser;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户提现申请表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_bankid) && $data['bankid'] = $this->_bankid;
		! is_null($this->_accountName) && $data['account_name'] = $this->_accountName;
		! is_null($this->_accountNumber) && $data['account_number'] = $this->_accountNumber;
		! is_null($this->_branch) && $data['branch'] = $this->_branch;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_amount) && $data['amount'] = $this->_amount;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_payMoney) && $data['pay_money'] = $this->_payMoney;
		! is_null($this->_payTime) && $data['pay_time'] = $this->_payTime;
		! is_null($this->_handleUser) && $data['handle_user'] = $this->_handleUser;
		return $this->update($data);
	}

	/**
	 * 删除用户提现申请表
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
	 * 获取所有用户提现申请表--分页
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
	 * 获取所有用户提现申请表
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

				$this->_bankid 	= null;

				$this->_accountName 	= null;

				$this->_accountNumber 	= null;

				$this->_branch 	= null;

				$this->_mobile 	= null;

				$this->_amount 	= null;

				$this->_addtime 	= null;

				$this->_status 	= null;

				$this->_payMoney 	= null;

				$this->_payTime 	= null;

				$this->_handleUser 	= null;

			}
}
?>