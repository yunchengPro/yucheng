<?php
/**
*
* 商品分类类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ProCategoty.php 10319 2017-03-03 18:07:41Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ComNewsArticleContentDB extends MysqlDb {

	protected $_tableName = "com_news_article_content";

	protected $_primary = "id";

	protected $_keywords 	= null;

	protected $_description 	= null;

	protected $_content 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_com";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商品分类
	 */
	public function add() {
		! is_null($this->_keywords) && $data['keywords'] = $this->_keywords;
		! is_null($this->_description) && $data['description'] = $this->_description;
		! is_null($this->_content) && $data['content'] = $this->_content;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新商品分类
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
	 * 删除商品分类
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
	 * 获取所有商品分类--分页
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
	 * 获取所有商品分类
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

				$this->_parentId 	= null;

				$this->_name 	= null;

				$this->_status 	= null;

			}
}
?>