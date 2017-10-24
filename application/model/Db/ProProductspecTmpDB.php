<?php
/**
*
* 商品对应规格类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ProProductSpec.php 10319 2017-03-03 14:56:50Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;


class ProProductSpecTmpDB extends MysqlDb {

	protected $_tableName = "pro_product_spec_tmp";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_productid 	= null;

	protected $_productname 	= null;

	protected $_prouctprice 	= null;

	protected $_bullamount 	= null;

	protected $_productstorage 	= null;

	protected $_weight 	= null;

	protected $_weightGross 	= null;

	protected $_volume 	= null;

	protected $_productspec 	= null;

	protected $_spec 	= null;

	protected $_productimage 	= null;

	protected $_sku 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商品对应规格
	 */
	public function add() {
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_productname) && $data['productname'] = $this->_productname;
		! is_null($this->_prouctprice) && $data['prouctprice'] = $this->_prouctprice;
		! is_null($this->_bullamount) && $data['bullamount'] = $this->_bullamount;
		! is_null($this->_productstorage) && $data['productstorage'] = $this->_productstorage;
		! is_null($this->_weight) && $data['weight'] = $this->_weight;
		! is_null($this->_weightGross) && $data['weight_gross'] = $this->_weightGross;
		! is_null($this->_volume) && $data['volume'] = $this->_volume;
		! is_null($this->_productspec) && $data['productspec'] = $this->_productspec;
		! is_null($this->_spec) && $data['spec'] = $this->_spec;
		! is_null($this->_productimage) && $data['productimage'] = $this->_productimage;
		! is_null($this->_sku) && $data['sku'] = $this->_sku;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商品对应规格
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_productname) && $data['productname'] = $this->_productname;
		! is_null($this->_prouctprice) && $data['prouctprice'] = $this->_prouctprice;
		! is_null($this->_bullamount) && $data['bullamount'] = $this->_bullamount;
		! is_null($this->_productstorage) && $data['productstorage'] = $this->_productstorage;
		! is_null($this->_weight) && $data['weight'] = $this->_weight;
		! is_null($this->_weightGross) && $data['weight_gross'] = $this->_weightGross;
		! is_null($this->_volume) && $data['volume'] = $this->_volume;
		! is_null($this->_productspec) && $data['productspec'] = $this->_productspec;
		! is_null($this->_spec) && $data['spec'] = $this->_spec;
		! is_null($this->_productimage) && $data['productimage'] = $this->_productimage;
		! is_null($this->_sku) && $data['sku'] = $this->_sku;
		return $this->update($data);
	}

	/**
	 * 删除商品对应规格
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
	 * 获取所有商品对应规格--分页
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
	 * 获取所有商品对应规格
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

		$this->_productid 	= null;

		$this->_productname 	= null;

		$this->_prouctprice 	= null;

		$this->_bullamount 	= null;

		$this->_productstorage 	= null;

		$this->_weight 	= null;

		$this->_weightGross 	= null;

		$this->_volume 	= null;

		$this->_productspec 	= null;

		$this->_spec 	= null;

		$this->_productimage 	= null;

		$this->_sku 	= null;

	}
}
?>