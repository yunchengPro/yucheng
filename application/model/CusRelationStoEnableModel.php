<?php
/**
* 创业者关系表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:13:58Z robert zhao $
*/

namespace app\model;
use app\lib\Db;
use app\lib\Model;

class CusRelationStoEnableModel {

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_role 		= null;

	protected $_parentid 	= null;

	protected $_grandpaid 	= null;

	protected $_addtime 	= null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('CusRelationStoEnable');
	}

	/**
	 *
	 * 添加创业者关系表
	 */
// 	public function add() {
// 		$this->_modelObj->_customerid  		= $this->_customerid;
// 		$this->_modelObj->_parentid  		= $this->_parentid;
// 		$this->_modelObj->_grandpaid  		= $this->_grandpaid;
// 		$this->_modelObj->_addtime  		= $this->_addtime;
// 		return $this->_modelObj->add();
// 	}

	public function insert($data) {
	    return $this->_modelObj->insert($data);
	}

	public function add($data) {
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新创业者关系表
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_customerid  = $this->_customerid;
// 		$this->_modelObj->_parentid  = $this->_parentid;
// 		$this->_modelObj->_grandpaid  = $this->_grandpaid;
// 		$this->_modelObj->_addtime  = $this->_addtime;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}
	
	public function getRow($where, $field="*", $order='', $otherstr='') {
	    return $this->_modelObj->getRow($where, $field, $order, $otherstr);
	}
	
    public function getInfoRow($where, $field="*", $order='', $otherstr='') {
	    return $this->getRow($where, $field, $order, $otherstr);
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
	 * 创业者关系表列表
	 */
// 	public function getList($page, $pagesize) {
// 		return $this->_modelObj->getAllForPage($page, $pagesize);
// 	}

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

	/**
	 * 获取所有创业者关系表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}

	public function getDescendantCount($customerId)
	{
	    // $where = array(
	    // 	'role' => array('=', 2),
	    //     'parentid'  => array('=', $customerId),
	    //     'grandpaid' => array('=', $customerId, 'or')
	    // );
	    $where = "role = 2 and (parentid = ".$customerId." or grandpaid = ".$customerId.")";
	    $row = $this->getRow($where,"count(*) as count");
	    //var_dump($row);
	    $fansCount = $row['count'];
	    //var_dump($fansCount);
	    
	    return $fansCount;
	}

	/**
	* @user 启用实体店操作
	* @param 
	* @author jeeluo
	* @date 2017年8月21日下午7:31:58
	*/
    public function startUsingSto($params) {
        // 启用实体店
        // 把该表数据复制到cus_relation,再删除该表数据
        $where['customerid'] = $params['customerid'];
        $where['role'] = $params['role'];
        $cusRelationSto = $this->getRow($where,"*");
        
        $data = $cusRelationSto;
        unset($data['id']);
        $insert_id = Model::ins("CusRelation")->insert($data);
        if($insert_id) {
            // 添加成功，删除原数据
            self::delete(["id"=>$cusRelationSto['id']]);
        }
        return true;
    }
    
    /**
    * @user 禁用实体店操作
    * @param 
    * @author jeeluo
    * @date 2017年8月21日下午7:32:08
    */
    public function forbiddenSto($params) {
        // 禁用实体店
        // 把cus_relation 数据复制到该表，再删除cus_relation 对应的表数据
        $where['customerid'] = $params['customerid'];
        $where['role'] = $params['role'];
        $cusRelation = Model::ins("CusRelation")->getRow($where,"*","id desc");

        $data = $cusRelation;
        unset($data['id']);
        $insert_id = self::insert($data);
        if($insert_id) {
            // 添加成功，删除原数据
            Model::ins("CusRelation")->delete(["id"=>$cusRelation['id']]);
        }
        return true;
    }

	/**
	 *
	 * 删除创业者关系表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}
	
	public function delete($where,$limit='') {
	    return $this->_modelObj->delete($where,$limit);
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
	 * 设置用户id
	 *
	 */
	public function setCustomerid($customerid) {
		$this->_customerid = $customerid;
		return $this;
	}

	/**
	 * 设置上级创业者id(customer)
	 *
	 */
	public function setParentid($parentid) {
		$this->_parentid = $parentid;
		return $this;
	}

	/**
	 * 设置上上级创业者id(customer)
	 *
	 */
	public function setGrandpaid($grandpaid) {
		$this->_grandpaid = $grandpaid;
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

	public static function getModelObj() {
		return new CusRelationEnDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>