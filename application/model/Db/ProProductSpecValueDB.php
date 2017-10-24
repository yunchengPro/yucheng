<?php
/**
*
* 商品所有规格信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ProProductSpecValue.php 10319 2017-03-03 15:03:36Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ProProductSpecValueDB extends MysqlDb {

	protected $_tableName = "pro_product_specvalue";

	protected $_primary = "productid";

	protected $_productid 	= null;

	protected $_specName 	= null;

	protected $_specVlaue 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商品所有规格信息表
	 */
	public function add() {
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_specName) && $data['spec_name'] = $this->_specName;
		! is_null($this->_specVlaue) && $data['spec_vlaue'] = $this->_specVlaue;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商品所有规格信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_specName) && $data['spec_name'] = $this->_specName;
		! is_null($this->_specVlaue) && $data['spec_vlaue'] = $this->_specVlaue;
		return $this->update($data);
	}

	/**
	 * 删除商品所有规格信息表
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
	 * 获取所有商品所有规格信息表--分页
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
	 * 获取所有商品所有规格信息表
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
				$this->_productid 	= null;

				$this->_specName 	= null;

				$this->_specVlaue 	= null;

			}
}
?>