<?php
/**
*
* 牛人推荐信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: RoleRecoOr.php 10319 2017-03-14 11:22:45Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class RoleRecoTalentDB extends MysqlDb {

	protected $_tableName = "role_reco_talent";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_realname 	= null;

	protected $_mobile 	= null;

	protected $_area 	= null;

	protected $_address 	= null;

	protected $_areaCode 	= null;

	protected $_instroducerid 	= null;

	protected $_payStatus 	= null;

	protected $_status 	= null;

	protected $_remark 	= null;

	protected $_examinetime 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	public function __construct() {
	    $this->_fields = [
	        'realname', 'mobile', 'area', 'address', 'area_code', 'instroducerid', 
	        'pay_status', 'status', 'remark', 'examinetime', 'addtime'
	    ];
	    //$this->_auto   = [array('addtime', 'function', 'time')];
	}
	
	/**
	 *
	 * 插入牛人推荐信息表
	 */
	public function add() {
		! is_null($this->_realname) && $data['realname'] = $this->_realname;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_instroducerid) && $data['instroducerid'] = $this->_instroducerid;
		! is_null($this->_payStatus) && $data['pay_status'] = $this->_payStatus;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_examinetime) && $data['examinetime'] = $this->_examinetime;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新牛人推荐信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_realname) && $data['realname'] = $this->_realname;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_area) && $data['area'] = $this->_area;
		! is_null($this->_address) && $data['address'] = $this->_address;
		! is_null($this->_areaCode) && $data['area_code'] = $this->_areaCode;
		! is_null($this->_instroducerid) && $data['instroducerid'] = $this->_instroducerid;
		! is_null($this->_payStatus) && $data['pay_status'] = $this->_payStatus;
		! is_null($this->_status) && $data['status'] = $this->_status;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_examinetime) && $data['examinetime'] = $this->_examinetime;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除牛人推荐信息表
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
	 * 获取所有牛人推荐信息表--分页
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
	 * 获取所有牛人推荐信息表
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

				$this->_realname 	= null;

				$this->_mobile 	= null;

				$this->_area 	= null;

				$this->_address 	= null;

				$this->_areaCode 	= null;

				$this->_instroducerid 	= null;

				$this->_payStatus 	= null;

				$this->_status 	= null;

				$this->_remark 	= null;

				$this->_examinetime 	= null;

				$this->_addtime 	= null;

			}
}
?>