<?php
/**
*
* app首页显示模块表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: MallModule.php 10319 2017-03-07 14:13:58Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class MallModuleDB extends MysqlDb {

	protected $_tableName = "mall_module";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_modulename 	= null;

	protected $_modulecode 	= null;

	protected $_parenid 	= null;

	protected $_sort 	= null;

	protected $_type 	= null;

	protected $_moduleType 	= null;

	protected $_thumb 	= null;

	protected $_isshow 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";

	public function __construct() {
	    $this->_fields = ['type', 'modulename', 'modulecode', 'module_type', 'thumb', 'isshow', 'sort', 'parenid'];
	    //$this->_auto   = [array('addtime', 'function', 'time')];
	}

	/**
	 *
	 * 插入app首页显示模块表
	 */
	public function add() {
		! is_null($this->_modulename) && $data['modulename'] = $this->_modulename;
		! is_null($this->_modulecode) && $data['modulecode'] = $this->_modulecode;
		! is_null($this->_parenid) && $data['parenid'] = $this->_parenid;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_type) && $data['type'] = $this->_type;
		! is_null($this->_moduleType) && $data['module_type'] = $this->_moduleType;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_isshow) && $data['isshow'] = $this->_isshow;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新app首页显示模块表
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_modulename) && $data['modulename'] = $this->_modulename;
		! is_null($this->_modulecode) && $data['modulecode'] = $this->_modulecode;
		! is_null($this->_parenid) && $data['parenid'] = $this->_parenid;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_type) && $data['type'] = $this->_type;
		! is_null($this->_moduleType) && $data['module_type'] = $this->_moduleType;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_isshow) && $data['isshow'] = $this->_isshow;
		return $this->update($data);
	}

	/**
	 * 删除app首页显示模块表
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
	 * 获取所有app首页显示模块表--分页
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
	 * 获取所有app首页显示模块表
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

				$this->_modulename 	= null;

				$this->_parenid 	= null;

				$this->_sort 	= null;

				$this->_type 	= null;

				$this->_moduleType 	= null;

				$this->_thumb 	= null;

				$this->_isshow 	= null;

			}
}
?>