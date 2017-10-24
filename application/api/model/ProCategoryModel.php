<?php
// +----------------------------------------------------------------------
// |  [ 2016-02-28 产品分类模型层 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------
namespace app\model;

use app\lib\Db;

use app\lib\Img;

class ProCategoryModel{

	/**
	 * [getCateData 获取分类数据]
	 * @Author   ISir<673638498@qq.com>
     * @Date 2017-03-01 
	 * @return [type] [description]
	 */
	public static function getCateData(){
		echo 111;
		$ProCategory = Db::Model("ProCategory");
		$topCate = $ProCategory->getList(['parent_id'=>0,'status'=>1]);

		foreach ($topCate as $key => $value) {
			$sonCate = $ProCategory->getList(['parent_id'=>$value['id'],'status'=>1]);
			$topCate[$key]['sonCate'] = $sonCate;
		}
		return $topCate;
	}
}