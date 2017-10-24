<?php
/**
*
* 分润表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdShoppingcart.php 10319 2017-03-03 15:39:12Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class OrdShoppingcartItemDB extends MysqlDb {

	protected $_tableName = "ord_shoppingcart_item";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_businessid 	= null;

	protected $_productid 	= null;

	protected $_productnum 	= null;

	protected $_skuid 	= null;

	protected $_skuCode 	= null;

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
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_productnum) && $data['productnum'] = $this->_productnum;
		! is_null($this->_skuid) && $data['skuid'] = $this->_skuid;
		! is_null($this->_skuCode) && $data['sku_code'] = $this->_skuCode;
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
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_productnum) && $data['productnum'] = $this->_productnum;
		! is_null($this->_skuid) && $data['skuid'] = $this->_skuid;
		! is_null($this->_skuCode) && $data['sku_code'] = $this->_skuCode;
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

				$this->_businessid 	= null;

				$this->_productid 	= null;

				$this->_productnum 	= null;

				$this->_skuid 	= null;

				$this->_skuCode 	= null;

				$this->_addtime 	= null;

			}
}
?>