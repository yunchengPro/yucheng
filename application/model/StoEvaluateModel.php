<?php
/**
* 实体商家评价表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-09 16:10:21Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class StoEvaluateModel {

	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_businessid 	= null;

	protected $_scores 	= null;

	protected $_isanonymous 	= null;

	protected $_addtime 	= null;

	protected $_frommemberid 	= null;

	protected $_frommembername 	= null;

	protected $_headpic 	= null;

	protected $_content 	= null;

	protected $_state 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('StoEvaluate');
	}

	/**
	 *
	 * 添加实体商家评价表
	 */
	public function add() {
		$this->_modelObj->_orderno  		= $this->_orderno;
		$this->_modelObj->_businessid  		= $this->_businessid;
		$this->_modelObj->_scores  		= $this->_scores;
		$this->_modelObj->_isanonymous  		= $this->_isanonymous;
		$this->_modelObj->_addtime  		= $this->_addtime;
		$this->_modelObj->_frommemberid  		= $this->_frommemberid;
		$this->_modelObj->_frommembername  		= $this->_frommembername;
		$this->_modelObj->_headpic  		= $this->_headpic;
		$this->_modelObj->_content  		= $this->_content;
		$this->_modelObj->_state  		= $this->_state;
		return $this->_modelObj->add();
	}

	/**
	 *
	 * 更新实体商家评价表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_orderno  = $this->_orderno;
// 		$this->_modelObj->_businessid  = $this->_businessid;
// 		$this->_modelObj->_scores  = $this->_scores;
// 		$this->_modelObj->_isanonymous  = $this->_isanonymous;
// 		$this->_modelObj->_addtime  = $this->_addtime;
// 		$this->_modelObj->_frommemberid  = $this->_frommemberid;
// 		$this->_modelObj->_frommembername  = $this->_frommembername;
// 		$this->_modelObj->_headpic  = $this->_headpic;
// 		$this->_modelObj->_content  = $this->_content;
// 		$this->_modelObj->_state  = $this->_state;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}
	
	public function getRow($where,$field='*',$order='',$otherstr=''){
	    return $this->_modelObj->getRow($where,$field,$order,$otherstr);
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
	 * 实体商家分类表列表
	 */
	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr='') {
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}



	/**
	 * 获取所有实体商家评价表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
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
	 *
	 * 删除实体商家评价表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


	/**
	 * 设置评价id
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置订单号
	 *
	 */
	public function setOrderno($orderno) {
		$this->_orderno = $orderno;
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
	 * 设置1-5分
	 *
	 */
	public function setScores($scores) {
		$this->_scores = $scores;
		return $this;
	}

	/**
	 * 设置0表示不是 1表示是匿名评价
	 *
	 */
	public function setIsanonymous($isanonymous) {
		$this->_isanonymous = $isanonymous;
		return $this;
	}

	/**
	 * 设置评价时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	/**
	 * 设置评价人id
	 *
	 */
	public function setFrommemberid($frommemberid) {
		$this->_frommemberid = $frommemberid;
		return $this;
	}

	/**
	 * 设置评价人昵称
	 *
	 */
	public function setFrommembername($frommembername) {
		$this->_frommembername = $frommembername;
		return $this;
	}

	/**
	 * 设置用户头像
	 *
	 */
	public function setHeadpic($headpic) {
		$this->_headpic = $headpic;
		return $this;
	}

	/**
	 * 设置评价内容
	 *
	 */
	public function setContent($content) {
		$this->_content = $content;
		return $this;
	}

	/**
	 * 设置评价信息的状态 0为正常 1为禁止显示
	 *
	 */
	public function setState($state) {
		$this->_state = $state;
		return $this;
	}

	public static function getModelObj() {
		return new StoEvaluateDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
    
    public function pageList($where,$field='',$order='',$flag=1,$page='',$pagesize=''){
        return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
    }

    public function insert($insertData){
        return $this->_modelObj->insert($insertData);
    }
}
?>