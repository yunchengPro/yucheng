<?php
/**
*
* 实体店banner类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: StoBanner.php 10319 2017-03-09 15:52:28Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ArtArticleContentDB extends MysqlDb {

	protected $_tableName = "art_article_content";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_keywords 	= null;

	protected $_description	= null;

	protected $_content 	= null;
	

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";

	public function __construct() {
	    $this->_fields = ['keywords','description','content'];
	    //$this->_auto   = [array('addtime', 'function', 'time')];
	}


	/**
	 *
	 * 插入实体店banner
	 */
	public function add() {
		! is_null($this->_keywords) && $data['keywords'] = $this->_keywords;
		! is_null($this->_description) && $data['description'] = $this->_description;
		! is_null($this->_content) && $data['content'] = $this->_content;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新实体店banner
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_keywords) && $data['keywords'] = $this->_keywords;
		! is_null($this->_description) && $data['description'] = $this->_description;
		! is_null($this->_content) && $data['content'] = $this->_content;
		return $this->update($data);
	}

	/**
	 * 删除实体店banner
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
	 * 获取所有实体店banner--分页
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
	 * 获取所有实体店banner
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

				$this->_keywords 	= null;

				$this->_description 	= null;

				$this->_content 	= null;

			}
}
?>