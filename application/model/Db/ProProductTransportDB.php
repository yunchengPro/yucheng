<?php
/**
*
* 商品运费信息表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ProProductTransport.php 10319 2017-03-03 15:04:56Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ProProductTransportDB extends MysqlDb {

	protected $_tableName = "pro_product_transport";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_transportid 	= null;

	protected $_transporttitle 	= null;

	protected $_freight 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商品运费信息表
	 */
	public function add() {
		! is_null($this->_transportid) && $data['transportid'] = $this->_transportid;
		! is_null($this->_transporttitle) && $data['transporttitle'] = $this->_transporttitle;
		! is_null($this->_freight) && $data['freight'] = $this->_freight;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商品运费信息表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_transportid) && $data['transportid'] = $this->_transportid;
		! is_null($this->_transporttitle) && $data['transporttitle'] = $this->_transporttitle;
		! is_null($this->_freight) && $data['freight'] = $this->_freight;
		return $this->update($data);
	}

	/**
	 * 删除商品运费信息表
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
	 * 获取所有商品运费信息表--分页
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
	 * 获取所有商品运费信息表
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

				$this->_transportid 	= null;

				$this->_transporttitle 	= null;

				$this->_freight 	= null;

			}
}
?>