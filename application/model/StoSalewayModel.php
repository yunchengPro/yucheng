<?php
/**
* 店铺快捷方式表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 16:22:16Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class StoSalewayModel {

	protected $_id 	= null;

	protected $_cityId 	= null;

	protected $_city 	= null;

	protected $_name 	= null;

	protected $_thumb 	= null;

	protected $_urltype 	= null;

	protected $_url 	= null;

	protected $_addtime 	= null;

	protected $_sort 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoSaleway');
	}

	/**
	 *
	 * 添加店铺快捷方式表
	 */
	public function add() {
		$this->_modelObj->_cityId  		= $this->_cityId;
		$this->_modelObj->_city  		= $this->_city;
		$this->_modelObj->_name  		= $this->_name;
		$this->_modelObj->_thumb  		= $this->_thumb;
		$this->_modelObj->_urltype  		= $this->_urltype;
		$this->_modelObj->_url  		= $this->_url;
		$this->_modelObj->_addtime  		= $this->_addtime;
		$this->_modelObj->_sort  		= $this->_sort;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新店铺快捷方式表
	 */
	public function modify($id) {
		$this->_modelObj->_cityId  = $this->_cityId;
		$this->_modelObj->_city  = $this->_city;
		$this->_modelObj->_name  = $this->_name;
		$this->_modelObj->_thumb  = $this->_thumb;
		$this->_modelObj->_urltype  = $this->_urltype;
		$this->_modelObj->_url  = $this->_url;
		$this->_modelObj->_addtime  = $this->_addtime;
		$this->_modelObj->_sort  = $this->_sort;
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

	/**
	 *
	 * 店铺快捷方式表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr='') {
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/**
	 * 获取所有店铺快捷方式表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除店铺快捷方式表
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
	 * 设置地区编号
	 *
	 */
	public function setCityId($cityId) {
		$this->_cityId = $cityId;
		return $this;
	}

	/**
	 * 设置地区名称
	 *
	 */
	public function setCity($city) {
		$this->_city = $city;
		return $this;
	}

	/**
	 * 设置名称
	 *
	 */
	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	/**
	 * 设置图片地址
	 *
	 */
	public function setThumb($thumb) {
		$this->_thumb = $thumb;
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
	 * 设置跳转链接有可能是H5页面url也有可能是id具体看app跳转方式
	 *
	 */
	public function setUrl($url) {
		$this->_url = $url;
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
	 * 设置排序
	 *
	 */
	public function setSort($sort) {
		$this->_sort = $sort;
		return $this;
	}

	public static function getModelObj() {
		return new StoSalewayDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>