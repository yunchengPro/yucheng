<?php
/**
*
* 商品关联图片类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ProProductPhoto.php 10319 2017-03-03 14:53:23Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;


class ProProductPhotoDB extends MysqlDb {


	protected $_tableName = "pro_product_photo";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_productid 	= null;

	protected $_productpic 	= null;

	protected $_istop 	= null;

	protected $_sort 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商品关联图片
	 */
	public function add() {
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_productpic) && $data['productpic'] = $this->_productpic;
		! is_null($this->_istop) && $data['istop'] = $this->_istop;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
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
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_productpic) && $data['productpic'] = $this->_productpic;
		! is_null($this->_istop) && $data['istop'] = $this->_istop;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
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

		$this->_productid 	= null;

		$this->_productpic 	= null;

		$this->_istop 	= null;

		$this->_sort 	= null;

		$this->_addtime 	= null;

	}
}
?>