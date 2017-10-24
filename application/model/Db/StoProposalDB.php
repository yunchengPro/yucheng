<?php
/**
*
* 店铺投诉建议表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: StoProposal.php 10319 2017-03-09 16:21:00Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class StoProposalDB extends MysqlDb {

	protected $_tableName = "sto_proposal";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_businessid 	= null;

	protected $_businessname 	= null;

	protected $_productname 	= null;

	protected $_customerid 	= null;

	protected $_content 	= null;

	protected $_proposalId 	= null;

	protected $_proposalName 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入店铺投诉建议表
	 */
	public function add() {
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_productname) && $data['productname'] = $this->_productname;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_content) && $data['content'] = $this->_content;
		! is_null($this->_proposalId) && $data['proposal_id'] = $this->_proposalId;
		! is_null($this->_proposalName) && $data['proposal_name'] = $this->_proposalName;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新店铺投诉建议表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_businessname) && $data['businessname'] = $this->_businessname;
		! is_null($this->_productname) && $data['productname'] = $this->_productname;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_content) && $data['content'] = $this->_content;
		! is_null($this->_proposalId) && $data['proposal_id'] = $this->_proposalId;
		! is_null($this->_proposalName) && $data['proposal_name'] = $this->_proposalName;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除店铺投诉建议表
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
	 * 获取所有店铺投诉建议表--分页
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
	 * 获取所有店铺投诉建议表
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

				$this->_businessid 	= null;

				$this->_businessname 	= null;

				$this->_productname 	= null;

				$this->_customerid 	= null;

				$this->_content 	= null;

				$this->_proposalId 	= null;

				$this->_proposalName 	= null;

				$this->_addtime 	= null;

			}
}
?>