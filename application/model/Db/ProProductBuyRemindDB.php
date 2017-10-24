<?php
/**
*
* 商品关联图片类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ProProduct.php 10319 2017-03-03 14:52:14Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ProProductBuyRemindDB extends MysqlDb {

	protected $_tableName = "pro_product_buy_remind";

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

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商品关联图片
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
	 * 更新商品关联图片
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
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
	 * 删除商品关联图片
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
	 * 获取所有商品关联图片--分页
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
	 * 获取所有商品关联图片
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

				$this->_spu 	= null;

				$this->_businessid 	= null;

				$this->_productname 	= null;

				$this->_categoryid 	= null;

				$this->_categoryname 	= null;

				$this->_addtime 	= null;

				$this->_enable 	= null;

				$this->_enabletime 	= null;

				$this->_thumb 	= null;

				$this->_productunit 	= null;

				$this->_weight 	= null;

				$this->_weightGross 	= null;

				$this->_volume 	= null;

				$this->_stateremark 	= null;

				$this->_prouctprice 	= null;

				$this->_bullamount 	= null;

			}
}
?>