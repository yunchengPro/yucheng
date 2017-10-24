<?php
/**
* 用户信息表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:03:01Z robert zhao $
*/

namespace app\model;
use app\lib\Db;
use app\lib\Model;
use think\Cache;
use app\model\Sys\CommonModel;

class SysUserBountyModel {

	protected $_id 	= null;

	protected $_isget 	= null;

	protected $_customerid	= null;

	protected $_addtime 	= null;

	protected $_gettime 	= null;

	protected $_amount 	= null;

	public $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;
	// 初始值
	const initNumber = 0;

	public function __construct() {
		$this->_modelObj = Db::Table('SysUserBounty');
	}

	/**
	 *
	 * 添加用户信息表
	 */
// 	public function add() {
// 		$this->_modelObj->_mobile  		= $this->_mobile;
// 		$this->_modelObj->_username  		= $this->_username;
// 		$this->_modelObj->_userpwd  		= $this->_userpwd;
// 		$this->_modelObj->_enable  		= $this->_enable;
// 		$this->_modelObj->_createtime  		= $this->_createtime;
// 		return $this->_modelObj->add();
// 	}

	public function add($data) {
// 	    $this->_modelObj->_mobile  		= $data['mobile'];
// 		$this->_modelObj->_username  		= $data['username'];
// 		$this->_modelObj->_userpwd  		= $data['userpwd'];
// 		$this->_modelObj->_enable  		= $data['enable'];
// 		$this->_modelObj->_createtime  		= $data['createtime'];
// 		return $this->_modelObj->add();
        return $this->insert($data);
	}
	
	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新用户信息表
	 */
	public function modifyUpdate($data, $where) {
// 	    $this->_modelObj->_mobile  = $data['mobile'];
// 	    $this->_modelObj->_username  = $data['username'];
// 	    $this->_modelObj->_userpwd  = $data['userpwd'];
// 	    $this->_modelObj->_enable  = $data['enable'];
// 	    $this->_modelObj->_createtime  = $data['createtime'];
	    return $this->modify($data, $where);
	}
	
	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	/**
	 *
	 * 详细
	 */
// 	public function getById($id = null) {
// 		$this->_id = is_null($id)? $this->_id : $id;
// 		$this->_dataInfo = $this->_modelObj->getById($this->_id);
// 		return $this->_dataInfo;
// 	}

	public function getById($id = null) {
	    $this->_id = is_null($id)? $this->_id : $id;
	    $this->_dataInfo = $this->_modelObj->getRow(array("id" => $this->_id));
	    return $this->_dataInfo;
	}
	
	/**
	* @user 通过手机号码查询用户id
	* @param $mobile 手机号码
	* @author jeeluo
	* @date 2017年3月2日下午5:09:26
	*/
	public function getIdByMobile($mobile = null) {
	    //$this->_mobile = is_null($mobile) ? $this->_mobile : $mobile;
	    $this->_dataInfo = $this->_modelObj->getRow(array("mobile" => $mobile), "id");
	    return $this->_dataInfo;
	}

	
	/*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
    	return $this->_modelObj->getRow($where,$field,$order,$otherstr);
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
	public function pageList($where,$field='*',$order='',$flag=1){
	    return $this->_modelObj->pageList($where,$field,$order,$flag);
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
	 * 获取所有用户信息表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}

	/**
	 * [userGetBounty 用户领取奖励金]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-07-24T11:58:18+0800
	 * @param    [type]                   $param [customerid ] array("customerid"=>$customerid) ; 用户id
	 * @return   [type]                          [description]
	 */
	public function userGetBounty($param){

		if(empty($param['customerid']))
			return false;

		$data = $this->getRow(['customerid'=>$param['customerid']],'id,isget,amount');

		if(empty($data))
			return false;

		if($data['isget'] == 1)
			return false;
		//更新用户已领取
		$ret = $this->update(['isget'=>1],['id'=>$data['id']]);

		if($ret > 0){
			//更新领取记录
			$bountMallData = Model::ins('SysBountyMall')->getRow(['id'=>1],'id,amount,disamount,hasamount');
			$disamount = $bountMallData['disamount'] + $data['amount'];
			$hasamount = $bountMallData['hasamount'] - $data['amount'];
			Model::ins('SysBountyMall')->update(['disamount'=>$disamount,'hasamount'=>$hasamount],['id'=>$bountMallData['id']]);
			return true;
		}else{
			return false;
		}
	}
}
?>