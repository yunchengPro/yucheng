<?php
/**
*
* 商品类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: Product.php 10319 2017-03-02 14:33:12Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ProductDB extends MysqlDb {

	protected $_tableName = "pro_product";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_spu 	= null;

	protected $_businessid 	= null;

	protected $_productname 	= null;

	protected $_categoryid 	= null;

	protected $_categoryname 	= null;

	protected $_addtime 	= null;

	protected $_enable 	= null;

	protected $_enabletime 	= null;

	protected $_thumb 	= null;

	protected $_productunit 	= null;

	protected $_weight 	= null;

	protected $_weightGross 	= null;

	protected $_volume 	= null;

	protected $_stateremark 	= null;

	protected $_prouctprice 	= null;

	protected $_bullamount 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "nn_";


	/**
	 *
	 * 插入商品
	 */
	public function add() {
		! is_null($this->_spu) && $data['spu'] = $this->_spu;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_productname) && $data['productname'] = $this->_productname;
		! is_null($this->_categoryid) && $data['categoryid'] = $this->_categoryid;
		! is_null($this->_categoryname) && $data['categoryname'] = $this->_categoryname;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		! is_null($this->_enabletime) && $data['enabletime'] = $this->_enabletime;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_productunit) && $data['productunit'] = $this->_productunit;
		! is_null($this->_weight) && $data['weight'] = $this->_weight;
		! is_null($this->_weightGross) && $data['weight_gross'] = $this->_weightGross;
		! is_null($this->_volume) && $data['volume'] = $this->_volume;
		! is_null($this->_stateremark) && $data['stateremark'] = $this->_stateremark;
		! is_null($this->_prouctprice) && $data['prouctprice'] = $this->_prouctprice;
		! is_null($this->_bullamount) && $data['bullamount'] = $this->_bullamount;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商品
	 */
	public function modify($id) {
		$data[$this->_pk] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_spu) && $data['spu'] = $this->_spu;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_productname) && $data['productname'] = $this->_productname;
		! is_null($this->_categoryid) && $data['categoryid'] = $this->_categoryid;
		! is_null($this->_categoryname) && $data['categoryname'] = $this->_categoryname;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		! is_null($this->_enabletime) && $data['enabletime'] = $this->_enabletime;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_productunit) && $data['productunit'] = $this->_productunit;
		! is_null($this->_weight) && $data['weight'] = $this->_weight;
		! is_null($this->_weightGross) && $data['weight_gross'] = $this->_weightGross;
		! is_null($this->_volume) && $data['volume'] = $this->_volume;
		! is_null($this->_stateremark) && $data['stateremark'] = $this->_stateremark;
		! is_null($this->_prouctprice) && $data['prouctprice'] = $this->_prouctprice;
		! is_null($this->_bullamount) && $data['bullamount'] = $this->_bullamount;
		return $this->update($data);
	}

	/**
	 * 删除商品
	 */
	public function del($id) {
		$data[$this->_pk] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		return $this->where($data)->delete();
	}

	/**
	 *
	 * 根据ID获取一行
	 * @param interger $id
	 */
	public function getById($id) {
		$this->_id = is_null($id)? $this->_id : $id;
		return $this->where(array($this->_pk => $this->_id))->find();
	}

	/**
	 *
	 * 获取所有商品--分页
	 * @param interger $status
	 */
	public function getAllForPage($page = 0, $pagesize = 20) {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		$this->_totalPage = $this->count();
		return $this->page($page, $pagesize)->order("{$this->_pk} desc")->select();
	}

	/**
	 * 获取所有商品
	 * @return Ambigous 
	 */
	public function getAll() {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		return $this->select();
	}
}
?>