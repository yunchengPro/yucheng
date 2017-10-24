<?php
namespace app\lib\Db;

use app\lib\Db\BaseDb;

use \think\Config;

use \think\Request;

class MongoDb extends BaseDb{

	/*
	* mongo 连接状态
	*/
	protected $mongo = null;

	protected $connection = false;

	protected $host = null;

	protected $db = array();

	protected $_db_name = null;

	protected $_collection_name = null;

	protected $collection = null; 

	protected $write = null;

	protected $max_limit = 200;

    //默认每页记录数
    protected $pagesize = 20;

	public function __construct(){
		parent::__construct();
		
		$mongo_config = array();
		if(!empty($this->_db)){
			$mongo_config = Config::get("mongodb.".$this->_db);
		}else{
			echo "mongo config is not exists!";
        	throw new \Exception("mongo config is not exists!");
		}

		$this->host 			= $mongo_config['host'];
		//$this->db 				= $mongo_config['db']; //限定的库

		//$this->_db_name      	= $db_name;
		//$this->_collection_name  = $collection_name;

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

			if(!empty($this->_db_name) && !empty($this->_collection_name)){
				// $this->set($this->_db_name,$this->_collection_name);//设置库和表
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
		//if($this->_db_name == $db_name && $this->_collection_name == $collection_name)
			//return true;

		$this->_db_name = $db_name;
		$this->_collection_name = $collection_name;

		/*
		//三种写法：
		$col = $m->selectCollection(db_name, col_name);
		或
		$col = $m->selectDB(db_name)->selectCollection(col_name);
		或
		$col = $m->db_name->col_name;
		*/
		//echo $this->_db_name."====".$this->_collection_name;
		// if($this->mongo)
		// 	$this->collection = $this->mongo->selectCollection($this->_db_name,$this->_collection_name);

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
		return $this->_db_name.'.'.$this->_collection_name;
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
	* 插入数据 单条数据
	* $data = array("_id"=>"1","name"=>"张三","sex"=>"男")
	* $_idflag false表示使用mongodb的主键方式 true 使用自己的主键
	* return 返回_id mongo的自定义主键和 操作状态
	*/
	public function insert($data,$merge_data=[],$_idflag=true){
		$this->check_connect();//判断已连接，系统默认不产生连接
		
		foreach($data as $k=>$v){
    		$data[$k] = !is_array($v)&&!is_object($v)?strval($v):$v;
    	}

    	$data = array_merge($data,$merge_data);

		//单条数据插入
		/*if($_idflag && empty($data['_id']) && !empty($data['id']))
			$data['_id'] = $data['id'];
		$result=$this->collection->insert($data,$options); //简单插入
		return array(
				"_id"=>$data['_id'],
				"status"=>$result,·
			);*/
		if($_idflag && empty($data['_id']) && !empty($data['id']))
			$data['_id'] = $data['id']; // new \MongoDB\BSON\ObjectID

		$write = new \MongoDB\Driver\BulkWrite;	
		$_id = $write->insert($data);

		$writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 0);
		$res = $this->mongo->executeBulkWrite($this->getDbCollection(), $write ,$writeConcern);

		return array(
				"_id"=>$data['_id'],
				"result"=>$res,
			);
	}

	
	/** 
     * 根据条件更新数据 
     * @param array $query  条件 例如：array('_id' => '1')
     * @param array $data   需要更新的数据 例如：array('title' => '1000', 'username' => 'xcxx')
     * @param array $option 参数 
     */  
    public function update($where, $data,$merge_data=[], $options = array()) {  
    	$this->check_connect();//判断已连接，系统默认不产生连接

    	foreach($where as $k=>$v){
    		$where[$k] = !is_array($v)?strval($v):$v;
    	}

    	foreach($data as $k=>$v){
    		$data[$k] = !is_array($v)&&!is_object($v)?strval($v):$v;
    	}

    	$data = array_merge($data,$merge_data);

    	$write = new \MongoDB\Driver\BulkWrite;	
		$write->update(
		    $where,
		    ['$set' => $data],
		    ['multi' => false, 'upsert' => false]
		);

		$writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 0);
		$result = $this->mongo->executeBulkWrite($this->getDbCollection(), $write, $writeConcern);

		return $result;
    }  

    /** 
     * 根据条件移除 
     * @param array $where  条件 例如：array('_id' => '1') 注意所有where条件都要转字符型
     * @param array $limit 参数  limit 为 1 时，删除第一条匹配数据  为 0 时，删除所有匹配数据
     */  
    public function delete($where, $limit=1) {  
    	$this->check_connect();//判断已连接，系统默认不产生连接

    	foreach($where as $k=>$v){
    		$where[$k] = strval($v);
    	}

        $write = new \MongoDB\Driver\BulkWrite;
		$write->delete($where, ['limit' => $limit]);   // limit 为 0 时，删除所有匹配数据 limit 为 1 时，删除第一条匹配数据

		$writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 0);
		$result = $this->mongo->executeBulkWrite($this->getDbCollection(), $write, $writeConcern);

		return $result;
    } 


    

    /** 
     * 根据条件查找多条数据 
     * @param array $where 查询条件  注意所有where条件都要转字符型
     * @param array str $fields返回的字段 array('name'=>true,'sex'=>true); 或者 'name,sex'
     *
     *  $filter = ['x' => 1];
		$options = arrray(
	        'projection' => array('id' => 1, 'age' => 1, 'name' => 1), // 指定返回哪些字段
	        'sort' => array('id' => -1), // 指定排序字段
	        'limit' => 10, // 指定返回的条数
	        'skip' => 0, // 指定起始位置
	    );

	    $field 字段 默认获取所有字段 id,name,sex
	    $sort id desc addtime asc
     */  
    public function getRow($where=[], $field="",$sort="") {  
    	$this->check_connect();//判断已连接，系统默认不产生连接

    	//$query = new \MongoDB\Driver\Query($filter, $options);
    	//$res = $this->mongo->executeQuery($this->getDbCollection(),$query);
    	
    	foreach($where as $k=>$v){
    		$where[$k] = !is_array($v)?strval($v):$v;
    	}
    	
    	$options = [];
	    // 查询数据
	    if(!empty($field)){
	    	$fieldarr = explode(",",$field);
	    	$projection = [];
	    	foreach($fieldarr as $key){
	    		$projection[$key] = 1; 
	    	}
	    	$options['projection'] = $projection;
	    }

	    if(!empty($sort)){
	    	$sortarr = explode(" ",$sort);
	    	$options['sort'] = [$sortarr[0]=>(strpos($sort,"asc")!==false?1:-1)];
	    }

	    $options['limit']=1;

	    $query = new \MongoDB\Driver\Query($where,$options);

	    $cursor = $this->mongo->executeQuery($this->getDbCollection(), $query);

	    $result =[];
	    foreach ($cursor as $document) {
            $result = $this->object2array($document);
            break;
        }

		return $result;
    }

    /**
     * 查多条记录
     * @Author   zhuangqm
     * @DateTime 2017-03-30T19:51:51+0800
     * @param    [type]                   $where    [description] 注意所有where条件都要转字符型
     * @param    string                   $field    [description]
     * @param    string                   $order    [description]
     * @param    integer                  $limit    [description]
     * @param    integer                  $offset   [description]
     * @param    string                   $otherstr [description]
     * @return   [type]                             [description]
     */
    public function getList($where=[],$field='',$sort='',$limit=0,$offset=0){
    	$this->check_connect();//判断已连接，系统默认不产生连接

    	//$query = new \MongoDB\Driver\Query($filter, $options);
    	//$res = $this->mongo->executeQuery($this->getDbCollection(),$query);
    	
    	foreach($where as $k=>$v){
    		$where[$k] = !is_array($v)?strval($v):$v;
    	}

    	$where = $this->handle_where($where); // where条件的处理

    	$options = [];
	    // 查询数据
	    if(!empty($field)){
	    	$fieldarr = explode(",",$field);
	    	$projection = [];
	    	foreach($fieldarr as $key){
	    		$projection[$key] = 1; 
	    	}
	    	$options['projection'] = $projection;
	    }

	    if(!empty($sort)){
	    	$sortarr = explode(" ",$sort);
	    	$options['sort'] = [$sortarr[0]=>(strpos($sort,"asc")!==false?1:-1)];
	    }

	    $options['limit']	=	$limit>$this->max_limit||$limit==0?$this->max_limit:$limit;
	    $options['skip']	=	$offset;


	    $query = new \MongoDB\Driver\Query($where,$options);

	    $cursor = $this->mongo->executeQuery($this->getDbCollection(), $query);

	    $result =[];
	    foreach ($cursor as $document) {
            $result[] = $this->object2array($document);
        }

		return $result;
    }

    // 分页
    public function pageList($where=[],$field='',$order=''){
    	$page       = Request::instance()->param('page');
        $page       = !empty($page)&&is_numeric($page)?$page:1;
        $pagesize   = Request::instance()->param('pagesize');
        $pagesize   = !empty($pagesize)&&is_numeric($pagesize)?$pagesize:$this->pagesize;

        $list = $this->getList($where,$field,$order,$pagesize,(($page-1)*$pagesize));

        $count = $this->getCount($where);
        return array(
                "total"=>$count,
                "list"=>$list,
            );
    }


    public function getCount($where){
    	$this->check_connect();//判断已连接，系统默认不产生连接
    	$where = $this->handle_where($where); // where条件的处理
	    $cmd = array(
	        'count' => $this->_collection_name,
	        'query' => $where,
	    );
	    $command = new \MongoDB\Driver\Command($cmd);
	    $result = $this->mongo->executeCommand($this->_db_name, $command);
	    $response = current($result->toArray());
	    if($response->ok==1){
	        return $response->n;
	    }
	    return 0;
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

    public function object2array($object) {
        $object =  json_decode( json_encode($object),true);
        return  $object;
    }

    public function handle_where($where){
    	$merge_data = [];
    	if(!empty($where['merge_data']) && is_array($where['merge_data'])){
    		$merge_data = $where['merge_data'];
    		unset($where['merge_data']);
    	}

    	return array_merge($where,$merge_data);
    }

	public function close(){
		//var_dump($this->redis);
		$this->connection = false;
		// if(!empty($this->mongo)){
		// 	$this->mongo->close();
		// 	$this->mongo = null;
		// 	$this->connection = false;
		// }
	}

	public function __destruct(){
		//关闭redis连接
		$this->close();
	}
}
?>