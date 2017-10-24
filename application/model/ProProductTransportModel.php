<?php
/**
* 商品运费信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:04:56Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class ProProductTransportModel {

	protected $_id 	= null;

	protected $_transportid 	= null;

	protected $_transporttitle 	= null;

	protected $_freight 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('ProProductTransport');
	}


	/**
	 *
	 * 添加商品运费信息表
	 */
	public function add() {
		$this->_modelObj->_transportid  		= $this->_transportid;
		$this->_modelObj->_transporttitle  		= $this->_transporttitle;
		$this->_modelObj->_freight  		= $this->_freight;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新商品运费信息表
	 */
	public function modify($id) {
		$this->_modelObj->_transportid  = $this->_transportid;
		$this->_modelObj->_transporttitle  = $this->_transporttitle;
		$this->_modelObj->_freight  = $this->_freight;
		return $this->_modelObj->modify($id);
	}

	/**
	 *
	 * 详细
	 */
	public function getById($id = null,$field="*") {
		return $this->_modelObj->getRow(["id"=>$id ],$field);
	}

	/**
	 *
	 * 商品运费信息表列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有商品运费信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除商品运费信息表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


	/**
	 * 设置商品id
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置运费模板id
	 *
	 */
	public function setTransportid($transportid) {
		$this->_transportid = $transportid;
		return $this;
	}

	/**
	 * 设置运费模版名称
	 *
	 */
	public function setTransporttitle($transporttitle) {
		$this->_transporttitle = $transporttitle;
		return $this;
	}

	/**
	 * 设置固定运费
	 *
	 */
	public function setFreight($freight) {
		$this->_freight = $freight;
		return $this;
	}

	public static function getModelObj() {
		return new ProProductTransportDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>