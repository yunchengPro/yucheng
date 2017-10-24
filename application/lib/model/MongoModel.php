<?php
/*
通用的model继承层
 */
namespace app\lib\model;

use app\lib\Model;

class MongoModel{

	protected $_mongoObj = null;

	/**
     * 初始化对象
     */
    public function MongoObj(){
        if(empty($this->_mongoObj)){  
        	if(property_exists($this, "_modelname") && $this->_modelname)     
            	$this->_mongoObj = Model::Mongo($this->_modelname);
        }
        return $this->_mongoObj;
    }

    /**
     * Mongo写数据
     */
    public function insertMongo($key){
    	return $this->MongoObj()->insert($this->_modelObj->getRow(["id"=>$key]));
    }

    /**
     * Mongo更新
     * $key 可以是数组或者单个key
     */
    public function updateMongo($where,$data){
        return $this->MongoObj()->update($where,$data);
    }

    /**
     * 删除Mongo
     */
    public function delMongo($where){
    	return $this->MongoObj()->delete($where);
    }

    //从redis中获取单条数据
    public function getMongoRow($where=[], $field="",$sort="") {
    	return $this->MongoObj()->getRow($where, $field,$sort);
    }

    //获取redis列表
    public function getMongoList($where=[],$field='',$order='',$limit=0,$offset=0){
        return $this->MongoObj()->getList($where,$field,$order,$limit,$offset);
    }

}