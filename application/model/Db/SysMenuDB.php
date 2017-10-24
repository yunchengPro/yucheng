<?php
/**
*
* 
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: MallBanner.php 10319 2017-03-03 15:20:31Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class SysMenuDB extends MysqlDb {

	protected $_tableName = "sys_menu";

	protected $_primary = "id";

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商城平台banner图表
	 */
	public function add() {
	
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商城平台banner图表
	 */
	public function modify($id) {
		
		return $this->update($data);
	}

	/**
	 * 删除商城平台banner图表
	 */
	public function del($id) {
		
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
	 * 获取所有商城平台banner图表--分页
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
	 * 获取所有商城平台banner图表
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

				$this->_type 	= null;

				$this->_bname 	= null;

				$this->_thumb 	= null;

				$this->_urltype 	= null;

				$this->_url 	= null;

				$this->_addtime 	= null;

				$this->_sort 	= null;

			}
}
?>