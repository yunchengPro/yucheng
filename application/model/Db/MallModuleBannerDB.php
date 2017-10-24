<?php
/**
*
* app首页显示模块明细表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: MallModuleBanner.php 10319 2017-03-07 14:15:46Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class MallModuleBannerDB extends MysqlDb {

	protected $_tableName = "mall_module_banner";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_moduleid 	= null;

	protected $_bname 	= null;

	protected $_thumb 	= null;

	protected $_urltype 	= null;

	protected $_url 	= null;

	protected $_sort 	= null;

	protected $_typeid 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";

	public function __construct() {
	    $this->_fields = ['moduleid', 'bname', 'thumb', 'urltype', 'url', 'sort', 'mark', 'typeid'];
	    //$this->_auto   = [array('addtime', 'function', 'time')];
	}
	
	/**
	 *
	 * 插入app首页显示模块明细表
	 */
	public function add() {
		! is_null($this->_moduleid) && $data['moduleid'] = $this->_moduleid;
		! is_null($this->_bname) && $data['bname'] = $this->_bname;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_urltype) && $data['urltype'] = $this->_urltype;
		! is_null($this->_url) && $data['url'] = $this->_url;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_typeid) && $data['sort'] = $this->_typeid;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新app首页显示模块明细表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_moduleid) && $data['moduleid'] = $this->_moduleid;
		! is_null($this->_bname) && $data['bname'] = $this->_bname;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_urltype) && $data['urltype'] = $this->_urltype;
		! is_null($this->_url) && $data['url'] = $this->_url;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_typeid) && $data['sort'] = $this->_typeid;
		return $this->update($data);
	}

	/**
	 * 删除app首页显示模块明细表
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
	 * 获取所有app首页显示模块明细表--分页
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
	 * 获取所有app首页显示模块明细表
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

				$this->_moduleid 	= null;

				$this->_bname 	= null;

				$this->_thumb 	= null;

				$this->_urltype 	= null;

				$this->_url 	= null;

				$this->_sort 	= null;

			}
}
?>