<?php
/**
* 店铺商品表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 16:18:54Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class ArtArticleModel {

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

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('ArtArticle');
	}

	/**
	 *
	 * 添加店铺商品表
	 */
	public function add() {
		$this->_modelObj->_title  		= $this->_title;
		$this->_modelObj->_shorttitle  		= $this->_shorttitle;
		$this->_modelObj->_categoryid  		= $this->_categoryid;
		$this->_modelObj->_thumb  		= $this->_thumb;
		$this->_modelObj->_author  		= $this->_author;
		$this->_modelObj->_addtime  		= $this->_addtime;
		$this->_modelObj->_sort  		= $this->_sort;
		$this->_modelObj->_istop  		= $this->_istop;
		$this->_modelObj->_citycode  		= $this->_citycode;
		$this->_modelObj->_newstype  		= $this->_newstype;
		$this->_modelObj->_tag  		= $this->_tag;
		$this->_modelObj->_hits  		= $this->_hits;
		$this->_modelObj->_isrelease  		= $this->_isrelease;
		$this->_modelObj->_createbyid  		= $this->_createbyid;
		$this->_modelObj->_isdelete  		= $this->_isdelete;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新店铺商品表
	 */
	public function modify($id) {
		$this->_modelObj->_title  		= $this->_title;
		$this->_modelObj->_shorttitle  		= $this->_shorttitle;
		$this->_modelObj->_categoryid  		= $this->_categoryid;
		$this->_modelObj->_thumb  		= $this->_thumb;
		$this->_modelObj->_author  		= $this->_author;
		$this->_modelObj->_addtime  		= $this->_addtime;
		$this->_modelObj->_sort  		= $this->_sort;
		$this->_modelObj->_istop  		= $this->_istop;
		$this->_modelObj->_citycode  		= $this->_citycode;
		$this->_modelObj->_newstype  		= $this->_newstype;
		$this->_modelObj->_tag  		= $this->_tag;
		$this->_modelObj->_hits  		= $this->_hits;
		$this->_modelObj->_isrelease  		= $this->_isrelease;
		$this->_modelObj->_createbyid  		= $this->_createbyid;
		$this->_modelObj->_isdelete  		= $this->_isdelete;
		return $this->_modelObj->modify($id);
	}

	/**
	 *
	 * 详细
	 */
	public function getById($id = null) {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getById($this->_id);
		return $this->_dataInfo;
	}

	/*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
        return $this->_modelObj->getRow($where,$field,$order,$otherstr='');
    }
    /*
    * 获取多行记录
    */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
        return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
    }

    /*
    * 分页列表
    * $flag = 0 表示不返回总条数
    */
    public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
        return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
    }
    
    /*
    * 写入数据
    * $insertData = array() 如果ID是自增 返回生成主键ID
    */
    public function insert($insertData) {
        return $this->_modelObj->insert($insertData);
    }

    /*
    * 更新数据
    * $updateData = array()
    */
    public function update($updateData,$where,$limit=''){
        return $this->_modelObj->update($updateData,$where,$limit='');
    }
    /*
    * 删除数据
    */
    public function delete($where,$limit=''){
        return $this->_modelObj->delete($where,$limit);
    }

	/**
	 * 获取所有店铺商品表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除店铺商品表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


	/**
	 * 设置商品ID
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置商家id
	 *
	 */
	public function setBusinessid($businessid) {
		$this->_businessid = $businessid;
		return $this;
	}

	/**
	 * 设置商家名称
	 *
	 */
	public function setBusinessname($businessname) {
		$this->_businessname = $businessname;
		return $this;
	}

	/**
	 * 设置商品名称
	 *
	 */
	public function setProductname($productname) {
		$this->_productname = $productname;
		return $this;
	}

	/**
	 * 设置分类id
	 *
	 */
	public function setCategoryid($categoryid) {
		$this->_categoryid = $categoryid;
		return $this;
	}

	/**
	 * 设置分类名称
	 *
	 */
	public function setCategoryname($categoryname) {
		$this->_categoryname = $categoryname;
		return $this;
	}

	/**
	 * 设置添加时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	/**
	 * 设置商品主图
	 *
	 */
	public function setThumb($thumb) {
		$this->_thumb = $thumb;
		return $this;
	}

	/**
	 * 设置商品价格
	 *
	 */
	public function setProuctprice($prouctprice) {
		$this->_prouctprice = $prouctprice;
		return $this;
	}

	/**
	 * 设置0不显示，1显示
	 *
	 */
	public function setEnable($enable) {
		$this->_enable = $enable;
		return $this;
	}

	public static function getModelObj() {
		return new StoProductDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>