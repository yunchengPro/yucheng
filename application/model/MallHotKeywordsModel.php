<?php
/**
 * 商家店铺
 */
namespace app\model;

use app\lib\Db;

use app\lib\Img;
use app\lib\model\RedisModel;
class MallHotKeywordsModel extends RedisModel {   

    protected $_modelObj;
    
    protected $_modelname = "MallHotKeywords";

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
    
  
 
    
    /**
     *
     * 详细
     */
    public function getById($id = null,$field="*") {
        return $this->_modelObj->getRow(["id"=>$id],$field);
    }
    
  
    /**
     * [getList 获取列表]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-03-02T17:06:45+0800
     * @param    [type]                   $where [description]
     * @return   [type]                          [description]
     */
    public function getList($where,$field='*',$order=''){
    	return $this->_modelObj->getList($where, $field, $order);
    }
    
//     /*
//      * 分页列表
//      * $flag = 0 表示不返回总条数
//      */
//     public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
//         return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
//     }
    
}