<?php
namespace app\model\Sys;
use app\lib\Model;

class LbsModel {

	/*
	获取位置距离--统一入口
	 */
	public static function Distance($longitude1, $latitude1, $longitude2, $latitude2){
		$distance = self::getDistance($longitude1, $latitude1, $longitude2, $latitude2, 1);
		$unit = '';
		if($distance<1000){
			$unit = 'm';
		}else{
			$distance = $distance / 1000;
			$unit = 'km';
		}

		return [
			"distance"=>$distance,
			"unit"=>$unit,
		];
	}

	/**
	 * 计算两点地理坐标之间的距离
	 * @param Decimal $longitude1 起点经度
	 * @param Decimal $latitude1 起点纬度
	 * @param Decimal $longitude2 终点经度 
	 * @param Decimal $latitude2 终点纬度
	 * @param Int   $unit    单位 1:米 2:公里
	 * @param Int   $decimal  精度 保留小数位数
	 * @return Decimal
	 */
	public static function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=2, $decimal=0){
 		/*
 		$longitude1 = 113.330405;
		$latitude1 = 23.147255;
		 
		// 终点坐标
		$longitude2 = 113.314271;
		$latitude2 = 23.1323;
		 
		$distance = getDistance($longitude1, $latitude1, $longitude2, $latitude2, 1);
		echo $distance.'m'; // 2342.38m
		 
		$distance = getDistance($longitude1, $latitude1, $longitude2, $latitude2, 2);
		echo $distance.'km'; // 2.34km
		*/

		$EARTH_RADIUS = 6370.996; // 地球半径系数
		$PI = 3.1415926;

		$radLat1 = $latitude1 * $PI / 180.0;
		$radLat2 = $latitude2 * $PI / 180.0;

		$radLng1 = $longitude1 * $PI / 180.0;
		$radLng2 = $longitude2 * $PI /180.0;

		$a = $radLat1 - $radLat2;
		$b = $radLng1 - $radLng2;

		$distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
		$distance = $distance * $EARTH_RADIUS * 1000;

		if($unit==2){
			$distance = $distance / 1000;
		}

		return abs(round($distance, $decimal));
	 	
	}


}