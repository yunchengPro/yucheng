<?php
namespace app\model\Sys;
use app\lib\Model;

class CategoryModel {

		/**
		 * [getProvince description]
		 * @Author   ISir<673638498@qq.com>
		 * @DateTime 2017-03-30T17:59:53+0800
		 * @return   [type]                   [description]
		 */
		public function getCategory(){

			$list = Model::ins("ProCategory")->getList(['parent_id'=>0,'status'=>['>=',0]]);

			$tmparr = array();
			foreach($list as $value){

				$tmparr[$value['id']]=$value;
			}
			
			return $tmparr;
		}

		/**
		 * [getProvince description]
		 * @Author   ISir<673638498@qq.com>
		 * @DateTime 2017-03-30T17:59:53+0800
		 * @return   [type]                   [description]
		 */
		public function getCategoryno(){

			$list = Model::ins("ProCategory")->getList(['parent_id'=>0,'status'=>['>=',0],'id'=>['<>',211]]);
			
			$tmparr = array();
			foreach($list as $value){

				$tmparr[$value['id']]=$value;
			}
			
			return $tmparr;
		}


		/**
		 * [getProvince description]
		 * @Author   ISir<673638498@qq.com>
		 * @DateTime 2017-03-30T17:59:53+0800
		 * @return   [type]                   [description]
		 */
		public function getSonCategory($categoryid=0){

			$list = Model::ins("ProCategory")->getList(['parent_id'=>$categoryid,'status'=>['>=',0]]);

			$tmparr = array();
			foreach($list as $value){

				$tmparr[$value['id']]=$value;
			}
			
			return $tmparr;
		}

	public static function judgeStoCategory($params) {
	    // 根据传递的分类id，查询其父类
	    // $categoryInfo = Model::ins("StoCategory")->getRow(["id"=>$params['id']],"parentid,categoryname");
	    // if(!empty($categoryInfo)) {
	    //     if($categoryInfo['parentid'] != 0) {
	    //         // 说明有上级  查询其上级
	    //         $parentCategory = Model::ins("StoCategory")->getRow(["id"=>$categoryInfo['parentid']],"categoryname");
	    //         if($parentCategory['categoryname'] == "餐饮美食") {
	    //             return true;
	    //         }
	    //     } else {
	    //         if($categoryInfo['categoryname'] == "餐饮美食") {
	    //             return true;
	    //         }
	    //     }
	    // }
	    // return false;

	    return true;
	}
}