<?php
// +----------------------------------------------------------------------
// |  [ 2016-02-28 商城信息模型层 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------
namespace app\model;

use app\lib\Db;

use app\lib\Img;

class MallModel{

	/**
	 * [getBanner 获取快捷方式]
	 * @Author   ISir<673638498@qq.com>
     * @Date 2017-03-01 
	 * @return [type] [description]
	 */
	public static function getSaleway($param){

		$where =  !empty($param['where']) ? $param['where'] : '';

		//售卖方式
       	$MallSaleway = Db::Model("MallSaleway");
       	return $saleWay = $MallSaleway->getList($where);
	}

	/**
	 * [getBanner 获取banner图片]
	 * @Author   ISir<673638498@qq.com>
     * @Date 2017-03-01 
	 * @param  [type] $param [1为商城首页2现金专区3现金加牛币专区]
	 * @return [type]        [description]
	 */
	public static function getTypeBanner($type){
		$MallBannerDB = Db::Model("MallBanner");
		return $brand = $MallBannerDB->getList(['type'=>$type]);
	}
}