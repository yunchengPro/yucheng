<?php
/*
通用的model继承层
 */
namespace app\lib\model;

use app\lib\Model;

class RedisModel{

	protected $_redisObj = null;

	/**
     * 初始化对象
     */
    public function RedisObj(){
        if(empty($this->_redisObj)){  
        	if(property_exists($this, "_modelname") && $this->_modelname)     
            	$this->_redisObj = Model::Redis($this->_modelname);
        }
        return $this->_redisObj;
    }

    /**
     * redis写数据
     */
    public function insertRedis($key){
    	return $this->RedisObj()->insert($key,$this->_modelObj->getRow(["id"=>$key]));
    }

    /**
     * redis更新
     * $key 可以是数组或者单个key
     */
    public function updateRedis($key,$data){
        $Redis = $this->RedisObj();
        $tmp = array();
        if(is_array($key)){
            $tmp = $key;
        }else{
            $tmp[]=$key;
        }
        foreach($tmp as $id){
            if($Redis->exists($id)){
                $Redis->update($id,$data);
            }else{
                $this->insertRedis($id);
            }
        }
        return true;
    }

    /**
     * 累加字段值
	 * 
 	 * $data =[
 	 * 		"amount"=>10,   //表示amount字段累加10，如果为负数表示扣减
 	 * 		"cashamount"=>11,  //可以多字段
 	 * ]
     */
    public function hincrbyRedis($key,$data){
        $Redis = $this->RedisObj();
        if($Redis->exists($key)){
            return $Redis->hincrby($key,$data);
        }else{
            $this->insertRedis($key);
        }
    	//return $this->RedisObj()->hincrby($key,$data);
    }

    /**
     * 删除redis
     */
    public function delRedis($key){
    	return $this->RedisObj()->del($key);
    }

    //从redis中获取单条数据
    public function getRedisRow($key,$field='*'){
    	return $this->RedisObj()->getRow($key,$field);
    }

    //获取redis列表
    public function getRedisList($keys,$field='*'){
        return $this->RedisObj()->getList($keys,$field);
    }

    /*
    * 获取单条数据
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
    	if(is_array($where) && isset($where['id'])){
    		$Redis = $this->RedisObj();
    		if($Redis->exists($where['id'])){
    			$row = $this->getRedisRow($where['id'],$field);
    		}else{
    			$row = $this->_modelObj->getRow($where,$field,$order,$otherstr);
    			if(!empty($row)){
    				$this->insertRedis($where['id']);
    			}
    		}
    		return $row;
    	}else{
    		return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    	}
    }

    /**
     * 写入操作
     */
    public function insert($data){
    	$key = $this->_modelObj->insert($data);

    	$this->insertRedis($key);

    	return $key;
    }

    //更新操作
    public function update($updateData,$where){
    	$result = $this->_modelObj->update($updateData,$where);

    	//更新redis
    	$key = '';
    	if(is_array($where) && isset($where['id'])){
    		$key = $where['id'];
    	}
    	if(is_string($where)){
    		$row = $this->_modelObj->getRow($where,"id");
    		$key = $row['id'];
    	}

    	$this->updateRedis($key,$updateData);

    	return $result;
    }

    //删除操作
    public function delete($where){
    	$result = $this->_modelObj->delete($where);

    	//更新redis
    	$key = '';
    	if(is_array($where) && isset($where['id'])){
    		$key = $where['id'];
    	}
    	if(is_string($where)){
    		$row = $this->_modelObj->getRow($where,"id");
    		$key = $row['id'];
    	}

    	$this->delRedis($key);

    	return $result;
    }
}