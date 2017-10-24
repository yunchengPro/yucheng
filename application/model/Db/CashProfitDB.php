<?php
/**
*
* 分润表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: CashProfit.php 10319 2017-03-03 15:46:56Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class CashProfitDB extends MysqlDb {

	protected $_tableName = "cash_profit";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_orderno 	= null;

	protected $_productid 	= null;

	protected $_parentid 	= null;

	protected $_parentprofit 	= null;

	protected $_grandpaid 	= null;

	protected $_grandpaprofit 	= null;

	protected $_bindbusinessid 	= null;

	protected $_bindbusinessprofit 	= null;

	protected $_businessSelfEntrepreid 	= null;

	protected $_businessSelfEntrepreprofit 	= null;

	protected $_businessParentEntrepreid 	= null;

	protected $_businessParentEntrepreprofit 	= null;

	protected $_businessGrandpaEntrepreid 	= null;

	protected $_businessGrandpaEntrepreprofit 	= null;

	protected $_factoryentrepreid 	= null;

	protected $_factoryentrepreprofit 	= null;

	protected $_factoryparententrepreid 	= null;

	protected $_factoryparententrepreprofit 	= null;

	protected $_companyprofit 	= null;

	protected $_procedure 	= null;

	protected $_charitable 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入分润表
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_parentid) && $data['parentid'] = $this->_parentid;
		! is_null($this->_parentprofit) && $data['parentprofit'] = $this->_parentprofit;
		! is_null($this->_grandpaid) && $data['grandpaid'] = $this->_grandpaid;
		! is_null($this->_grandpaprofit) && $data['grandpaprofit'] = $this->_grandpaprofit;
		! is_null($this->_bindbusinessid) && $data['bindbusinessid'] = $this->_bindbusinessid;
		! is_null($this->_bindbusinessprofit) && $data['bindbusinessprofit'] = $this->_bindbusinessprofit;
		! is_null($this->_businessSelfEntrepreid) && $data['business_self_entrepreid'] = $this->_businessSelfEntrepreid;
		! is_null($this->_businessSelfEntrepreprofit) && $data['business_self_entrepreprofit'] = $this->_businessSelfEntrepreprofit;
		! is_null($this->_businessParentEntrepreid) && $data['business_parent_entrepreid'] = $this->_businessParentEntrepreid;
		! is_null($this->_businessParentEntrepreprofit) && $data['business_parent_entrepreprofit'] = $this->_businessParentEntrepreprofit;
		! is_null($this->_businessGrandpaEntrepreid) && $data['business_grandpa_entrepreid'] = $this->_businessGrandpaEntrepreid;
		! is_null($this->_businessGrandpaEntrepreprofit) && $data['business_grandpa_entrepreprofit'] = $this->_businessGrandpaEntrepreprofit;
		! is_null($this->_factoryentrepreid) && $data['factoryentrepreid'] = $this->_factoryentrepreid;
		! is_null($this->_factoryentrepreprofit) && $data['factoryentrepreprofit'] = $this->_factoryentrepreprofit;
		! is_null($this->_factoryparententrepreid) && $data['factoryparententrepreid'] = $this->_factoryparententrepreid;
		! is_null($this->_factoryparententrepreprofit) && $data['factoryparententrepreprofit'] = $this->_factoryparententrepreprofit;
		! is_null($this->_companyprofit) && $data['companyprofit'] = $this->_companyprofit;
		! is_null($this->_procedure) && $data['procedure'] = $this->_procedure;
		! is_null($this->_charitable) && $data['charitable'] = $this->_charitable;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新分润表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_parentid) && $data['parentid'] = $this->_parentid;
		! is_null($this->_parentprofit) && $data['parentprofit'] = $this->_parentprofit;
		! is_null($this->_grandpaid) && $data['grandpaid'] = $this->_grandpaid;
		! is_null($this->_grandpaprofit) && $data['grandpaprofit'] = $this->_grandpaprofit;
		! is_null($this->_bindbusinessid) && $data['bindbusinessid'] = $this->_bindbusinessid;
		! is_null($this->_bindbusinessprofit) && $data['bindbusinessprofit'] = $this->_bindbusinessprofit;
		! is_null($this->_businessSelfEntrepreid) && $data['business_self_entrepreid'] = $this->_businessSelfEntrepreid;
		! is_null($this->_businessSelfEntrepreprofit) && $data['business_self_entrepreprofit'] = $this->_businessSelfEntrepreprofit;
		! is_null($this->_businessParentEntrepreid) && $data['business_parent_entrepreid'] = $this->_businessParentEntrepreid;
		! is_null($this->_businessParentEntrepreprofit) && $data['business_parent_entrepreprofit'] = $this->_businessParentEntrepreprofit;
		! is_null($this->_businessGrandpaEntrepreid) && $data['business_grandpa_entrepreid'] = $this->_businessGrandpaEntrepreid;
		! is_null($this->_businessGrandpaEntrepreprofit) && $data['business_grandpa_entrepreprofit'] = $this->_businessGrandpaEntrepreprofit;
		! is_null($this->_factoryentrepreid) && $data['factoryentrepreid'] = $this->_factoryentrepreid;
		! is_null($this->_factoryentrepreprofit) && $data['factoryentrepreprofit'] = $this->_factoryentrepreprofit;
		! is_null($this->_factoryparententrepreid) && $data['factoryparententrepreid'] = $this->_factoryparententrepreid;
		! is_null($this->_factoryparententrepreprofit) && $data['factoryparententrepreprofit'] = $this->_factoryparententrepreprofit;
		! is_null($this->_companyprofit) && $data['companyprofit'] = $this->_companyprofit;
		! is_null($this->_procedure) && $data['procedure'] = $this->_procedure;
		! is_null($this->_charitable) && $data['charitable'] = $this->_charitable;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除分润表
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
	 * 获取所有分润表--分页
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
	 * 获取所有分润表
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

				$this->_orderno 	= null;

				$this->_productid 	= null;

				$this->_parentid 	= null;

				$this->_parentprofit 	= null;

				$this->_grandpaid 	= null;

				$this->_grandpaprofit 	= null;

				$this->_bindbusinessid 	= null;

				$this->_bindbusinessprofit 	= null;

				$this->_businessSelfEntrepreid 	= null;

				$this->_businessSelfEntrepreprofit 	= null;

				$this->_businessParentEntrepreid 	= null;

				$this->_businessParentEntrepreprofit 	= null;

				$this->_businessGrandpaEntrepreid 	= null;

				$this->_businessGrandpaEntrepreprofit 	= null;

				$this->_factoryentrepreid 	= null;

				$this->_factoryentrepreprofit 	= null;

				$this->_factoryparententrepreid 	= null;

				$this->_factoryparententrepreprofit 	= null;

				$this->_companyprofit 	= null;

				$this->_procedure 	= null;

				$this->_charitable 	= null;

				$this->_addtime 	= null;

			}
}
?>