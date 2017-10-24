<?php
namespace app\lib\Db;
use \think\Model;

class BaseDb {
	
	public function __construct($name='') {
		//$dbConfigArr = config('DB_CONFIG_DEFAULT');
		//parent::__construct($name, $dbConfigArr['DB_PREFIX'], 'DB_CONFIG_DEFAULT');
	}

	public function __set($name, $value) {
		$this->{$name} = $value;
	}
	
	public function __get($name) {
		return $this->{$name};
	}

}
?>