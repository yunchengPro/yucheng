<?php
// +----------------------------------------------------------------------
// |  [ 快捷方式 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-03-02
// +----------------------------------------------------------------------
namespace app\model;

use app\lib\Db;

use app\lib\Img;
use app\lib\model\RedisModel;

class MallSalewayModel extends RedisModel {  

    protected $_modelObj;
    
    protected $_modelname = "MallSaleway";

    public function __construct() {
         $this->_modelObj = Db::Table($this->_modelname);
    }
    
    /*
     * 负责把表单提交来的数组
     * 清除掉不用的单元
     * 留下与表的字段对应的单元
     */
    public function _facade($array = []){
        return $this->_modelObj->_facade($array);
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
     *
     * 详细
     */
    public function getById($id = null,$field="*") {
        return $this->_modelObj->getRow(["id"=>$id],$field);
    }
    
    public function getRow($where,$field="*",$order='',$otherstr=''){
        return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    }

    /**
     * [getList 获取信息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-02T17:02:51+0800
     * @param    [type]                   $where [description]
     * @return   [type]                          [description]
     */
    public function getList($where,$field='*',$order=''){
    	return $this->_modelObj->getList($where, $field, $order);
    }
}