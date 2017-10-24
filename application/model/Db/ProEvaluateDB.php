<?php
/**
*
* 商品评价表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ProEvaluate.php 10319 2017-03-03 15:26:20Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ProEvaluateDB extends MysqlDb {

	protected $_tableName = "pro_evaluate";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_businessid 	= null;

	protected $_productid 	= null;

	protected $_skuid 	= null;

	protected $_productname 	= null;

	protected $_productprice 	= null;

	protected $_scores 	= null;

	protected $_content 	= null;

	protected $_isanonymous 	= null;

	protected $_addtime 	= null;

	protected $_frommemberid 	= null;

	protected $_frommembername 	= null;

	protected $_headpic 	= null;

	protected $_state 	= null;

	protected $_remark 	= null;

	protected $_parentid 	= null;

	protected $_rootid 	= null;

	protected $_istop 	= null;

	protected $_desccredit 	= null;

	protected $_servicecredit 	= null;

	protected $_deliverycredit 	= null;

	protected $_evaluateauto 	= null;

	protected $_complaints 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商品评价表
	 */
	public function add() {
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_skuid) && $data['skuid'] = $this->_skuid;
		! is_null($this->_productname) && $data['productname'] = $this->_productname;
		! is_null($this->_productprice) && $data['productprice'] = $this->_productprice;
		! is_null($this->_scores) && $data['scores'] = $this->_scores;
		! is_null($this->_content) && $data['content'] = $this->_content;
		! is_null($this->_isanonymous) && $data['isanonymous'] = $this->_isanonymous;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_frommemberid) && $data['frommemberid'] = $this->_frommemberid;
		! is_null($this->_frommembername) && $data['frommembername'] = $this->_frommembername;
		! is_null($this->_headpic) && $data['headpic'] = $this->_headpic;
		! is_null($this->_state) && $data['state'] = $this->_state;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_parentid) && $data['parentid'] = $this->_parentid;
		! is_null($this->_rootid) && $data['rootid'] = $this->_rootid;
		! is_null($this->_istop) && $data['istop'] = $this->_istop;
		! is_null($this->_desccredit) && $data['desccredit'] = $this->_desccredit;
		! is_null($this->_servicecredit) && $data['servicecredit'] = $this->_servicecredit;
		! is_null($this->_deliverycredit) && $data['deliverycredit'] = $this->_deliverycredit;
		! is_null($this->_evaluateauto) && $data['evaluateauto'] = $this->_evaluateauto;
		! is_null($this->_complaints) && $data['complaints'] = $this->_complaints;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商品评价表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_orderno) && $data['orderno'] = $this->_orderno;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_skuid) && $data['skuid'] = $this->_skuid;
		! is_null($this->_productname) && $data['productname'] = $this->_productname;
		! is_null($this->_productprice) && $data['productprice'] = $this->_productprice;
		! is_null($this->_scores) && $data['scores'] = $this->_scores;
		! is_null($this->_content) && $data['content'] = $this->_content;
		! is_null($this->_isanonymous) && $data['isanonymous'] = $this->_isanonymous;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_frommemberid) && $data['frommemberid'] = $this->_frommemberid;
		! is_null($this->_frommembername) && $data['frommembername'] = $this->_frommembername;
		! is_null($this->_headpic) && $data['headpic'] = $this->_headpic;
		! is_null($this->_state) && $data['state'] = $this->_state;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_parentid) && $data['parentid'] = $this->_parentid;
		! is_null($this->_rootid) && $data['rootid'] = $this->_rootid;
		! is_null($this->_istop) && $data['istop'] = $this->_istop;
		! is_null($this->_desccredit) && $data['desccredit'] = $this->_desccredit;
		! is_null($this->_servicecredit) && $data['servicecredit'] = $this->_servicecredit;
		! is_null($this->_deliverycredit) && $data['deliverycredit'] = $this->_deliverycredit;
		! is_null($this->_evaluateauto) && $data['evaluateauto'] = $this->_evaluateauto;
		! is_null($this->_complaints) && $data['complaints'] = $this->_complaints;
		return $this->update($data);
	}

	/**
	 * 删除商品评价表
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
	 * 获取所有商品评价表--分页
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
	 * 获取所有商品评价表
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

				$this->_orderno 	= null;

				$this->_businessid 	= null;

				$this->_productid 	= null;

				$this->_skuid 	= null;

				$this->_productname 	= null;

				$this->_productprice 	= null;

				$this->_scores 	= null;

				$this->_content 	= null;

				$this->_isanonymous 	= null;

				$this->_addtime 	= null;

				$this->_frommemberid 	= null;

				$this->_frommembername 	= null;

				$this->_headpic 	= null;

				$this->_state 	= null;

				$this->_remark 	= null;

				$this->_parentid 	= null;

				$this->_rootid 	= null;

				$this->_istop 	= null;

				$this->_desccredit 	= null;

				$this->_servicecredit 	= null;

				$this->_deliverycredit 	= null;

				$this->_evaluateauto 	= null;

			}
}
?>