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

class ArtArticleDB extends MysqlDb {

	protected $_tableName = "art_article";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_title 	= null;

	protected $_shorttitle 	= null;

	protected $_categoryid 	= null;

	protected $_thumb 	= null;

	protected $_author 	= null;

	protected $_addtime 	= null;

	protected $_sort 	= null;

	protected $_istop 	= null;

	protected $_citycode 	= null;

	protected $_newstype 	= null;

	protected $_tag 	= null;

	protected $_hits 	= null;

	protected $_isrelease 	= null;

	protected $_createbyid 	= null;

	protected $_isdelete 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入商品关联图片
	 */
	public function add() {
		! is_null($this->_title) && $data['title'] = $this->_title;
		! is_null($this->_shorttitle) && $data['shorttitle'] = $this->_shorttitle;
		! is_null($this->_categoryid) && $data['categoryid'] = $this->_categoryid;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_author) && $data['author'] = $this->_author;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_istop) && $data['istop'] = $this->_istop;
		! is_null($this->_citycode) && $data['citycode'] = $this->_citycode;
		! is_null($this->_newstype) && $data['newstype'] = $this->_newstype;
		! is_null($this->_tag) && $data['tag'] = $this->_tag;
		! is_null($this->_hits) && $data['hits'] = $this->_hits;
		! is_null($this->_isrelease) && $data['isrelease'] = $this->_isrelease;
		! is_null($this->_createbyid) && $data['createbyid'] = $this->_createbyid;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
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
		! is_null($this->_title) && $data['title'] = $this->_title;
		! is_null($this->_shorttitle) && $data['shorttitle'] = $this->_shorttitle;
		! is_null($this->_categoryid) && $data['categoryid'] = $this->_categoryid;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		! is_null($this->_author) && $data['author'] = $this->_author;
		! is_null($this->_sort) && $data['sort'] = $this->_sort;
		! is_null($this->_istop) && $data['istop'] = $this->_istop;
		! is_null($this->_citycode) && $data['citycode'] = $this->_citycode;
		! is_null($this->_newstype) && $data['newstype'] = $this->_newstype;
		! is_null($this->_tag) && $data['tag'] = $this->_tag;
		! is_null($this->_hits) && $data['hits'] = $this->_hits;
		! is_null($this->_isrelease) && $data['isrelease'] = $this->_isrelease;
		! is_null($this->_createbyid) && $data['createbyid'] = $this->_createbyid;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
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

				$this->_title 	= null;

				$this->_shorttitle 	= null;

				$this->_productname 	= null;

				$this->_categoryid 	= null;

				$this->_thumb 	= null;

				$this->_author 	= null;

				$this->_addtime 	= null;

				$this->_sort 	= null;

				$this->_istop 	= null;

				$this->_citycode 	= null;

				$this->_newstype 	= null;

				$this->_tag 	= null;

				$this->_hits 	= null;

				$this->_isrelease 	= null;

				$this->_createbyid 	= null;

				$this->_isdelete 	= null;

			}
}
?>