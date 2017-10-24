<?php
namespace app\model\Sys;
use app\lib\Model;

class AreaModel {

	/**
	 * [getProvince description]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-30T17:59:53+0800
	 * @return   [type]                   [description]
	 */
	public function getProvince(){

		$list = Model::ins("SysArea")->getList(['level'=>1]);

		$tmparr = array();
		foreach($list as $value){

			$tmparr[$value['id']]=$value;
		}
		
		return $tmparr;
	}

	public function getCity(){
		$list = Model::ins("SysArea")->getList(['level'=>2]);
		$tmparr = array();
		foreach($list as $value){
			$tmparr[$value['id']]=$value;
		}

		return $tmparr;
	}

	public function getCounty(){
		$list = Model::ins("SysArea")->getList(['level'=>3]);
		$tmparr = array();
		foreach($list as $value){
			$tmparr[$value['id']]=$value;
		}

		return $tmparr;
	}
}