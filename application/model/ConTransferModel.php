<?php
/**
* 订单表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:19:56Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

use app\lib\Model;


class ConTransferModel {

	protected $_modelObj;

	public function __construct() {
		$this->_modelObj = Db::Table('ConTransfer');
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
	 * 生成订单编号
	 * @Author   zhuangqm
	 * @DateTime 2017-03-03T16:30:47+0800
	 * @return   [type]                   [description]
	 */
	public function getOrderNo(){
		return "Con".date("YmdHis").rand(100000,999999);
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
        return $this->_modelObj->pageList($where,$field,$order,$flag);
    }
    
    public function getPageList($where, $fields="*", $order='') {
        return $this->_modelObj->pageList($where, $fields, $order);
    }
    
    /*
    * 写入数据
    * $insertData = array() 如果ID是自增 返回生成主键ID
    */
    public function insert($insertData) {
        return $this->_modelObj->insert($insertData);
    }
	
//     public function pageList($where, $fields="*", $order='') {
//         return $this->_modelObj->pageList($where, $fields, $order);
//     }

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
	 * 获取所有订单表
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}

	

	/**
	 *
	 * 删除订单表
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}

	
}
?>