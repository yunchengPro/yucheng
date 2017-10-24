<?php
namespace app\model\Sys;
use app\lib\Model;

class ActLimitModel {

	/**
	 * 操作限制次数
	 * $key  判断的唯一key  可以用手机设备号或者用户ID
	 * $count 0表示不做限制
	 * $seconds 有效默认10分钟
	 * return true |false  true表示表示没有达到限制次数 false 已经达到限制次数
	 */
	public function check($key,$count=0){
		if($count>0 && !empty($key)){
			$actlimitOBJ = Model::Redis("ActLimit");

			if($actlimitOBJ->exists($key)){

				$row = $actlimitOBJ->getRow($key);

				$check = true;

				if($row['limitcount']>=$count)
					$check = false;
				
				return [
					"check"=>$check,
					"limitcount"=>$row['limitcount'], //次数
				];
			}else{

				return [
					"check"=>true,
					"limitcount"=>0,
				];
			}
		}else{
			return [
				"check"=>true,
				"limitcount"=>0,
			];
		}
	}

	// 更新限制次数
	public function update($key,$seconds=600){
		$actlimitOBJ = Model::Redis("ActLimit");
		if($actlimitOBJ->exists($key)){
			$actlimitOBJ->hincrby($key,["limitcount"=>1]);
		}else{
			$actlimitOBJ->insert($key,["limitcount"=>1],$seconds);
		}

		return true;
	}

	public function del($key){
		Model::Redis("ActLimit")->del($key);
	}

}