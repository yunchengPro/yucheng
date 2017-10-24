<?php
/**
* 商城公告表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-07 14:09:07Z robert zhao $
*/

namespace app\model;
use app\lib\Db;
use app\lib\model\RedisModel;

class MallAnnouncementModel  extends RedisModel {

	protected $_id 	= null;

	protected $_title 	= null;

	protected $_urltype 	= null;

	protected $_url 	= null;

	protected $_sort 	= null;

	protected $_enable 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;


    protected $_modelname = "MallAnnouncement";

    public function __construct() {
         $this->_modelObj = Db::Table($this->_modelname);
    }

	/**
	 *
	 * 添加商城公告表
	 */
	public function add() {
		$this->_modelObj->_title  		= $this->_title;
		$this->_modelObj->_urltype  		= $this->_urltype;
		$this->_modelObj->_url  		= $this->_url;
		$this->_modelObj->_sort  		= $this->_sort;
		$this->_modelObj->_enable  		= $this->_enable;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新商城公告表
	 */
	public function modify($id) {
		$this->_modelObj->_title  = $this->_title;
		$this->_modelObj->_urltype  = $this->_urltype;
		$this->_modelObj->_url  = $this->_url;
		$this->_modelObj->_sort  = $this->_sort;
		$this->_modelObj->_enable  = $this->_enable;
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
    * 获取多行记录
    */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/*
    * 分页列表
    * $flag = 0 表示不返回总条数
    */
    public function pageList($where,$field='',$order='',$flag=1){
    	return $this->_modelObj->getList($where,$field,$order,$flag);
    }
    
	/**
	 * 获取所有商城公告表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除商城公告表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


	/**
	 * 设置主键id
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置公告标题
	 *
	 */
	public function setTitle($title) {
		$this->_title = $title;
		return $this;
	}

	/**
	 * 设置跳转类型1为跳原生app2为跳转H5等
	 *
	 */
	public function setUrltype($urltype) {
		$this->_urltype = $urltype;
		return $this;
	}

	/**
	 * 设置跳转链接
	 *
	 */
	public function setUrl($url) {
		$this->_url = $url;
		return $this;
	}

	/**
	 * 设置排序
	 *
	 */
	public function setSort($sort) {
		$this->_sort = $sort;
		return $this;
	}

	/**
	 * 设置状态1显示0不现实
	 *
	 */
	public function setEnable($enable) {
		$this->_enable = $enable;
		return $this;
	}

	public static function getModelObj() {
		return new MallAnnouncementDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>