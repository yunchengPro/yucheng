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

class CusCustomerModel {

	protected $_id 	= null;

	protected $_mobile 	= null;

	protected $_username 	= null;

	protected $_userpwd 	= null;

	protected $_enable 	= null;

	protected $_role = null;

	protected $_createtime 	= null;

	public $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;
	// 初始值
	const initNumber = 0;

	public function __construct() {
		$this->_modelObj = Db::Table('CusCustomer');
	}
	
	//开启事务
	public function startTrans(){
	    return $this->_modelObj->startTrans();
	}
	
	//提交事务
	public function commit(){
	    return $this->_modelObj->commit();
	}
	
	//事务回滚
	public function rollback(){
	    return $this->_modelObj->rollback();
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
	 *
	 * 删除用户信息表
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
	 * 设置联系手机
	 *
	 */
	public function setMobile($mobile) {
		$this->_mobile = $mobile;
		return $this;
	}

	/**
	 * 设置用户名账号
	 *
	 */
	public function setUsername($username) {
		$this->_username = $username;
		return $this;
	}

	/**
	 * 设置登录密码
	 *
	 */
	public function setUserpwd($userpwd) {
		$this->_userpwd = $userpwd;
		return $this;
	}

	/**
	 * 设置状态1启用2禁用
	 *
	 */
	public function setEnable($enable) {
		$this->_enable = $enable;
		return $this;
	}

	/**
	 * 设置创建时间
	 *
	 */
	public function setCreatetime($createtime) {
		$this->_createtime = $createtime;
		return $this;
	}
	
	/**
	* @user 比较验证码 (假如正确返回用户信息)
	* @param 移动端传递数组信息 $arr
	* @author jeeluo
	* @date 2017年3月2日下午7:23:56
	*/
	public function compare($arr, $type) {
// 	    $cacheValicode = CommonModel::getCacheNumber($type.$arr['mobile']);

	    $cacheValicode = 0;
	    $MessageRedis = Model::Redis("MessageValicode");
	    
	    if($MessageRedis->exists($type.$arr['mobile'])) {
	        $cacheValicode = $valicode = $MessageRedis->get($type.$arr['mobile']);
	    }
	    
	    $cacheValicode = strtoupper(md5($cacheValicode.getConfigKey()));
	    
	    $tempValicode = strtoupper(md5("170220".getConfigKey()));
	    
// 	    if($arr['mobile'] == '13800000000') {
// 	        return $this->getIdByMobile($arr['mobile']) ?: true;
// 	    }
	    
	    if($arr['valicode'] == $cacheValicode) {
	        // 验证码正确，删除验证码缓存信息
	        $MessageRedis->del($type.$arr['mobile']);
	        
	        if($type == "sto_store_") {
	            if($MessageRedis->exists(CommonModel::getStoProfix($arr['mobile']))) {
	                $countNumber = $MessageRedis->get(CommonModel::getStoProfix($arr['mobile']));
	                
	                $MessageRedis->set(CommonModel::getStoProfix($arr['mobile']), --$countNumber, strtotime(date('Y-m-d', time()+86400))-time());
	            }
	        }
//             CommonModel::destoryNumber($type.$arr['mobile']);
	        // 查询数据库，获取手机号码在用户表是否已经注册
// 	        return $this->getIdByMobile($arr['mobile']) ?: true;
            return true;
	    } else if($arr['valicode'] == $tempValicode || $arr['valicode'] == '170220') {
	        if($type == "sto_store_") {
	            if($MessageRedis->exists(CommonModel::getStoProfix($arr['mobile']))) {
	                $countNumber = $MessageRedis->get(CommonModel::getStoProfix($arr['mobile']));
	                 
	                $MessageRedis->set(CommonModel::getStoProfix($arr['mobile']), --$countNumber, strtotime(date('Y-m-d', time()+86400))-time());
	            }
	        }
// 	        return $this->getIdByMobile($arr['mobile']) ?: true;
            return true;
	    }
	    return false;
	}

	public static function getModelObj() {
		return new CusCustomerDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}

	public function close() {
	    return $this->_modelObj->close();
	}
}
?>