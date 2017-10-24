<?php
/**
 * Redis 存储类
 */

namespace app\lib;

/*
* 使用方式
   Redis::ins('product')->hmset(1,array("id",""));
*/

class RedisConnection {

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
		public function __construct($host, $port, $time_out, $password,$prefix)
		{
			$this->settings = array(
				'host'          => $host,
				'port'          => $port,
				'time_out'      => $time_out,
				'password'		=> $password,
			);
			$this->prefix = $prefix;
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
	    *			"f_ordertype"=>"1",
	    *			"f_orderremark"=>"",
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

		/*
		* hash 内容更新
		* $key 主键
		* $value array() 如：array("f_ordertype"=>2,"f_orderremark"=>"test")
		*/
		public function h_update($key,$value){
			$key = $this->resetkey($key);
			if(is_array($value)){
				return $this->redis->hmset($key,$value);
			}
			return false;
		}

		/*
		* 获取hash内容
		* $key 主键
		* 获取的字段 如：'f_orderremark' 或者 'f_ordertype,f_orderremark'
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
		* 获取多条数据
		* $keys string or array()
				"100,101,102,103"
				array(100,101,102,103)
		* $field 获取的字段值 * 或空 表示获取所有字段值 如：'f_orderremark' 或者 'f_ordertype,f_orderremark'
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
				if($field_flag)
					$tmp[] = $this->redis->hgetall($key);
				else
					$tmp[] = $this->redis->hmget($key,$field_arr);
			}
			return $tmp;
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

		/*

		public function __destruct(){
			//关闭redis连接
			$this->close();
		}
		*/
		

}

