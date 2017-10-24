<?php
/**
*
* 商城公告表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: MallAnnouncement.php 10319 2017-03-07 14:09:08Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class MallAnnouncementDB extends MysqlDb {

	protected $_tableName = "mall_announcement";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_title 	= null;

	protected $_urltype 	= null;

	protected $_url 	= null;

	protected $_sort 	= null;

	protected $_mark	= null;

	protected $_enable 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商城公告表
	 */
	public function add() {
		! is_null($this->_title) && $data['title'] = $this->_title;
		! is_null($this->_urltype) && $data['urltype'] = $this->_urltype;
		! is_null($this->_url) && $data['url'] = $this->_url;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_mark) && $data['sort'] = $this->_mark;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商城公告表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_title) && $data['title'] = $this->_title;
		! is_null($this->_urltype) && $data['urltype'] = $this->_urltype;
		! is_null($this->_url) && $data['url'] = $this->_url;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_mark) && $data['sort'] = $this->_mark;
		! is_null($this->_enable) && $data['enable'] = $this->_enable;
		return $this->update($data);
	}

	/**
	 * 删除商城公告表
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
	 * 获取所有商城公告表--分页
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
	 * 获取所有商城公告表
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

				$this->_title 	= null;

				$this->_urltype 	= null;

				$this->_url 	= null;

				$this->_sort 	= null;

				$this->_enable 	= null;

			}
}
?>