<?php
namespace app\lib\Db;

use app\lib\Db\BaseDb;
use \think\Config;

class RedisDb extends BaseDb{

	/**
	 * redis配置
	 * @var array
	 */
	protected $settings = array();
	
	/*
	* 关键词 前缀
	*/
	protected $prefix ='';
	

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		parent::__construct();

		$redis_config = array();
		if(!empty($this->_db)){
			$redis_config = Config::get("redis.".$this->_db);
		}else{
			echo "redis config is not exists!";
        	throw new \Exception("redis config is not exists!");
		}

		$this->settings = array(
			'host'          => $redis_config['host'],  
			'port'          => $redis_config['port'],
			'time_out'      => $redis_config['time_out'],
			'password'		=> $redis_config['password'],
		);

		$this->prefix = $this->_prefix;
		$this->connect();
	}

	
	/**
	 * 创建redis实例
	 */
	protected function connect()
	{
		//init_set('default_socket_timeout',-1);
		$this->redis = new \Redis();
		try {
		    //$this->redis->connect($this->settings["host"],$this->settings["port"],$this->settings["time_out"]);
		    $this->redis->pconnect($this->settings["host"],$this->settings["port"]);
		    $this->redis->auth($this->settings["password"]);
			$this->connection = true;
		 } catch (Exception $e) {
            $this->connection = false;
        }
	}
   
	/**
	 * 关闭连接
	 *
	 * @param int $flag 关闭选择 0:关闭 Master 1:关闭 Slave 2:关闭所有
	 * @return boolean
	 */
	public function closeConnection($flag=2){
		if(!empty($this->redis)){
			$this->redis->close();
			$this->redis = null;
			$this->connection = false;
		}
		return true;
	}


	/**
	* 切换库
	*/
	public function selectDB($db_name){

		if(!empty($db_name)){
			//echo "";
			if(array_key_exists($db_name, $this->settings['db_config']) ){
				//$this->redis->select($this->settings['db_config'][$db_name]);	
				$this->prefix = $this->settings['db_config'][$db_name];
			}else{
				echo $db_name." not exists";
				return false;
			}
		}else{
			echo "db_name is empty";
			return false;
		}
		return $this;
	}

	public function check_connect(){
		if($this->connection===false){
			$this->connect();
		}
	}

	protected function resetkey($key){
		return $this->prefix.":".$key;
	}

	/*
	* 判断key是否存在
	*/
	public function exists($key){
		$key = $this->resetkey($key);
		return $this->redis->exists($key);
	}

	/*
	* 删除key
	*/
	public function del($key){
		$key = $this->resetkey($key);
		//echo $key;
		if($this->redis)
			return $this->redis->delete($key);
		else
			return false;
	}


	/**
	* string 设置值 如果key已经存在，则强制替换  
	* seconds 过期时间 0表示永久有效 
	*/
	public function set($key,$value,$seconds=5184000){
		$key = $this->resetkey($key);
		//return $this->redis->set($key,$value);
		if($seconds==0)
			return $this->redis->set($key,$value);
		else
			return $this->redis->setex($key,$seconds,$value);
	}

	/**
	 * [获取数据]
	 */
	public function get($key){
		$key = $this->resetkey($key);
		return $this->redis->get($key);
	}

	/**
	* 获取key数组
	* key 可以是字符串 也可以是数字 如 $key="key1,key2,key3"或者 $key = array('key1','key2','key3')
	*/
	public function getMultiple($key){
		$key = $this->resetkey($key);
		if(is_array($key))
			array_filter($key);
		else
			$key = array_filter(explode(",",$key));
		return $this->redis->getMultiple($key);
	}


	/**
	* hash插入内容
	* $key 主键
	* $value 内容
	*	如：array(
    *			"id"=>$id,
    *			"ordertype"=>"1",
    *			"orderremark"=>"",
    *		);
	* $seconds 有效时间 单位：秒；0为永久有效
	*/
	public function h_insert($key,$vaule,$seconds=5184000){
		$key = $this->resetkey($key);
		if($seconds==0){
			return $this->redis->hmset($key,$vaule);
		}else{
			$this->redis->hmset($key,$vaule);
			$this->redis->expire($key,$seconds);
			return true;
		}
	}

	/**
	* hash插入内容
	* $key 主键
	* $value 内容
	*	如：array(
    *			"id"=>$id,
    *			"ordertype"=>"1",
    *			"orderremark"=>"",
    *		);
	* $seconds 有效时间 单位：秒；0为永久有效
	*/
	public function insert($key,$vaule,$seconds=5184000){
		$key = $this->resetkey($key);
		if($seconds==0){
			return $this->redis->hmset($key,$vaule);
		}else{
			$this->redis->hmset($key,$vaule);
			$this->redis->expire($key,$seconds);
			return true;
		}
	}

	/*
	* hash 内容更新
	* $key 主键
	* $value array() 如：array("ordertype"=>2,"orderremark"=>"test")
	*/
	public function h_update($key,$value){
		$key = $this->resetkey($key);
		if(is_array($value)){
			return $this->redis->hmset($key,$value);
		}
		return false;
	}

	/*
	* hash 内容更新
	* $key 主键
	* $value array() 如：array("ordertype"=>2,"orderremark"=>"test")
	*/
	public function update($key,$value){
		$key = $this->resetkey($key);
		if(is_array($value)){
			return $this->redis->hmset($key,$value);
		}
		return false;
	}

	/*
	* 获取hash内容
	* $key 主键
	* 获取的字段 如：'orderremark' 或者 'ordertype,orderremark'
	* return array()
	*/
	public function h_get($key,$field='*'){
		$key = $this->resetkey($key);
		if($field=='*' || $field==''){
			return $this->redis->hgetall($key);
		}else{
			$field_arr = explode(',', $field);
			return $this->redis->hmget($key,$field_arr);
			
		}
	}

	/*
	* 获取hash内容
	* $key 主键
	* 获取的字段 如：'orderremark' 或者 'ordertype,orderremark'
	* return array()
	*/
	public function getRow($key,$field='*'){
		$key = $this->resetkey($key);
		if($field=='*' || $field==''){
			return $this->redis->hgetall($key);
		}else{
			$field_arr = explode(',', $field);
			return $this->redis->hmget($key,$field_arr);
			
		}
	}

	/*
	* 获取多条数据
	* $keys string or array()
			"100,101,102,103"
			array(100,101,102,103)
	* $field 获取的字段值 * 或空 表示获取所有字段值 如：'orderremark' 或者 'ordertype,orderremark'
	*/
	public function h_gets($keys,$field='*'){
		$field_flag = false;
		if($field=='*' || $field=='')
			$field_flag = true;
		else
			$field_arr = is_array($field)?$field:explode(',', $field);
		
		$keys_arr = is_array($keys)?$keys:explode(",",$keys);
		$tmp = array();
		foreach($keys_arr as $key){
			$key = $this->resetkey($key);
			if($field_flag)
				$tmp[] = $this->redis->hgetall($key);
			else
				$tmp[] = $this->redis->hmget($key,$field_arr);
		}
		return $tmp;
	}

	/*
	* 获取多条数据
	* $keys string or array()
			"100,101,102,103"
			array(100,101,102,103)
	* $field 获取的字段值 * 或空 表示获取所有字段值 如：'orderremark' 或者 'ordertype,orderremark'
	*/
	public function getList($keys,$field='*'){
		$field_flag = false;
		if($field=='*' || $field=='')
			$field_flag = true;
		else
			$field_arr = is_array($field)?$field:explode(',', $field);
		
		$keys_arr = is_array($keys)?$keys:explode(",",$keys);
		$tmp = array();
		foreach($keys_arr as $key){
			$key = $this->resetkey($key);
			if($field_flag)
				$tmp[] = $this->redis->hgetall($key);
			else
				$tmp[] = $this->redis->hmget($key,$field_arr);
		}
		return $tmp;
	}

	/*
	hIncrBy
	$redis->hincrby('h', 'x', 2);
	将名称为h的hash中x的value增加2
	 */
	public function hincrby($key,$data){
		$key = $this->resetkey($key);
		foreach($data as $k=>$v){
    		$this->redis->hincrby($key,$k,$v);
    	}
    	return true;
	}

	/**
	* 重加载 可以直接调用redis的原方法
	*/


	public function __call($func,$arg){
		//echo $func;
		if(!empty($this->redis)){

			if($func == 'hmset'){

				return $this->h_insert($arg[0],$arg[1]);

			}else{

				$arg[0] = $this->resetkey($arg[0]);
				return call_user_func_array(array($this->redis, $func),$arg);

			}
		}
		return false;
	}

	// 事务
	// 
	// 监控
	public function watch($key){
		$key = $this->resetkey($key);
		return $this->redis->watch($key);
	}

	// 用于取消 WATCH 命令对所有 key 的监视。
	public function unwatch($key=''){
		if($key!=''){
			$key = $this->resetkey($key);
			return $this->redis->unwatch($key);
		}else{
			return $this->redis->unwatch();
		}
	}

	// 用于标记一个事务块的开始
	public function multi(){
		return $this->redis->multi();
	}

	// 用于执行所有事务块内的命令。
	public function exec(){
		return $this->redis->exec();
	}

	// 用于取消事务放弃执行事务块内的所有命令。
	public function discard(){
		return $this->redis->discard();
	}
	
	public function close(){
		$this->redis->close();
	}

	public function __destruct(){
		//关闭redis连接
		$this->close();
	}
	
}
?>