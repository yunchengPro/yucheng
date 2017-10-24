<?php
/**
 * Redis 存储类
 */
namespace app\lib;


class MongodbConnection {

		/*
		* mongo 连接状态
		*/
		protected $mongo = null;

		protected $connection = false;

		protected $host = null;

		protected $db = array();

		protected $db_name = null;

		protected $collection_name = null;

		protected $collection = null; 

		protected $write = null;

		public function __construct($db_name,$collection_name,$config){

			$mongo_config = array();
			if(!empty($config)){
				$mongo_config = $config;
			}else{
				echo "mongo config is not exists!";
            	throw new \Exception("mongo config is not exists!");
			}

			$this->host 			= $mongo_config['host'];
			//$this->db 				= $mongo_config['db']; //限定的库
			$this->db_name      	= $db_name;
			$this->collection_name  = $collection_name;

			return true;
			//return $this->connect($db_name,$collection_name);
		}

		/**
		* 连接
		*/
		protected function connect(){
			try {
				//$this->mongo = new \MongoClient($this->host);
				$this->mongo = new \MongoDB\Driver\Manager($this->host);
				$this->connection = true;

				if(!empty($this->db_name) && !empty($this->collection_name)){
					// $this->set($this->db_name,$this->collection_name);//设置库和表
				}

				
			} catch (Exception $e) {
	            $this->connection = false;
	            return null;
	        }
		}

		protected function check_connect(){
			if(!$this->connection){
				$this->connect();
			}
		}

		/**
		* 定位到指定的库和表
		* 如果库和表都不存在，系统会自动新增
		*/
		public function set($db_name,$collection_name){

			if(empty($db_name) || empty($collection_name)) return false;

			/*
			//暂不限制
			if(!in_array($db_name, $this->db))
				exit($db_name." not exists!");
			*/
			//if($this->db_name == $db_name && $this->collection_name == $collection_name)
				//return true;

			$this->db_name = $db_name;
			$this->collection_name = $collection_name;

			/*
			//三种写法：
			$col = $m->selectCollection(db_name, col_name);
			或
			$col = $m->selectDB(db_name)->selectCollection(col_name);
			或
			$col = $m->db_name->col_name;
			*/
			//echo $this->db_name."====".$this->collection_name;
			// if($this->mongo)
			// 	$this->collection = $this->mongo->selectCollection($this->db_name,$this->collection_name);

			return true;
		}

		/**
		* 获取mongo原对象
		*/
		public function mongo(){
			$this->check_connect();//判断已连接，系统默认不产生连接
			return $this->mongo;
		}

		public function getDbCollection(){
			return $this->db_name.'.'.$this->collection_name;
		}
		
		/**
		* 插入数据 单条数据
		* $data = array("_id"=>"1","name"=>"张三","sex"=>"男")
		* $_idflag false表示使用mongodb的主键方式 true 使用自己的主键
		* return 返回_id mongo的自定义主键和 操作状态
		*/
		public function insert($data,$_idflag=true){
			$this->check_connect();//判断已连接，系统默认不产生连接
			
			//单条数据插入
			/*if($_idflag && empty($data['_id']) && !empty($data['id']))
				$data['_id'] = $data['id'];
			$result=$this->collection->insert($data,$options); //简单插入
			return array(
					"_id"=>$data['_id'],
					"status"=>$result,
				);*/
			if($_idflag && empty($data['_id']) && !empty($data['id']))
				$data['_id'] = $data['id'];

			$write = new \MongoDB\Driver\BulkWrite;	
			$write->insert($data);

			$res = $this->mongo->executeBulkWrite($this->getDbCollection(),$write);

			return array(
					"_id"=>$data['_id'],
					"result"=>$res,
				);
		}

		/*
		* 插入数据 多条数据
		* $list 二维数组
		* $_idflag false表示使用mongodb的主键方式 true 使用自己的主键
		*/
		/** 
	     * 批量新增数据 
	     * @param array $list 需要新增的数据 例如：array(0=>array('title' => '1000', 'username' => 'xcxx')) 
	     * @param $_idflag false表示使用mongodb的主键方式 true 使用自己的主键
	     * @param array $option 参数 
	     */ 
		public function insertList($list,$_idflag=true,$options=array()){
			$this->check_connect();//判断已连接，系统默认不产生连接
			$write = new \MongoDB\Driver\BulkWrite;	

			foreach($list as $key=>$data){
				if($_idflag && empty($data['_id']) && !empty($data['id'])){
					$data['_id'] = $data['id'];
					$write->insert($data);
				}
			}

			$res = $this->mongo->executeBulkWrite($this->getDbCollection(),$write);

			return $res;
		}

		/** 
	     * 根据条件查找多条数据 
	     * @param array $query 查询条件 
	     * @param array str $fields返回的字段 array('name'=>true,'sex'=>true); 或者 'name,sex'
	     */  
	    public function find($filter, $options) {  
	    	$this->check_connect();//判断已连接，系统默认不产生连接
	    	
	    	$query = new \MongoDB\Driver\Query($filter, $options);

	    	$res = $this->mongo->executeQuery($this->getDbCollection(),$query);
			return $res;
	    }  

		

		/** 
	     * 根据条件更新数据 
	     * @param array $query  条件 例如：array('_id' => '1')
	     * @param array $data   需要更新的数据 例如：array('title' => '1000', 'username' => 'xcxx')
	     * @param array $option 参数 
	     */  
	    public function update($query, $data, $options = array()) {  
	    	$this->check_connect();//判断已连接，系统默认不产生连接

	    	$write = new \MongoDB\Driver\BulkWrite;	
			$write->update(
			    $query,
			    ['$set' => $data],
			    ['multi' => false, 'upsert' => false]
			);

			$writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
			$result = $this->mongo->executeBulkWrite($this->getDbCollection(), $write, $writeConcern);

			return $result;
	    }  

	    /** 
	     * 根据条件移除 
	     * @param array $query  条件 例如：array('_id' => '1')
	     * @param array $option 参数 
	     */  
	    public function remove($query, $options = array()) {  
	    	$this->check_connect();//判断已连接，系统默认不产生连接
	        return $this->collection->remove($query, $options);  

	        $write = new MongoDB\Driver\BulkWrite;
			$write->delete($query, ['limit' => 0]);   // limit 为 0 时，删除所有匹配数据 limit 为 1 时，删除第一条匹配数据

			$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
			$result = $this->mongo->executeBulkWrite($this->getDbCollection(), $write, $writeConcern);

			return $result;
	    } 

	    /** 
	     * 根据条件查找一条数据 
	     * @param array $query  条件 例如：array('_id' => '1') 注意：_id 传的是字符串类型
	     * @param array str $fields 参数 array('name'=>true,'sex'=>true); 或者 'name,sex'
	     */  
	    public function findOne($query, $fields = array()) {  
	    	/*$this->check_connect();//判断已连接，系统默认不产生连接
	    	if(!is_array($fields) && !empty($fields)){
	    		$tmp = explode(",",$fields);
	    		$fields = array();
	    		foreach($tmp as $value){
	    			if($value!='')
	    				$fields[$value]=true;
	    		}
	    		$tmp = null;
	    	}
	        return $this->collection->findOne($query, $fields);  */
	    }

	    /**
		* 获取 MongoId
		* $id = new MongoId('4d638ea1d549a02801000011');
	    */
	    public function getMongoId($id){

	    	/*return new MongoId($id);*/
	    }

	    /*
		* 通过主键id获取记录
	    */
	    public function findOneById($id,$fields = array()){

	    	/*return $this->findOne(array("_id"=>$id),$fields);*/
	    }

	    

	    /** 
	    * 数据统计 
	    */  
	    public function count() {  
	    	$this->check_connect();//判断已连接，系统默认不产生连接
	        return $this->collection->count();  
	    }  


		public function close(){
			//var_dump($this->redis);
			/*$this->connection = false;
			if(!empty($this->mongo)){
				$this->mongo->close();
				$this->mongo = null;
				$this->connection = false;
			}*/
		}

		public function __destruct(){
			//关闭redis连接
			$this->close();
		}

}

