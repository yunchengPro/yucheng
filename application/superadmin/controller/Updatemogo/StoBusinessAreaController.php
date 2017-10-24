<?php
// +----------------------------------------------------------------------
// |  [ 更新是实体店经纬度 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017年7月11日16:46:39}}
// +----------------------------------------------------------------------
namespace app\superadmin\controller\Updatemogo;

use app\superadmin\ActionController;
use app\lib\Model;
use app\model\Sys\CommonModel;
use app\model\Sys\CommonRoleModel;
use app\model\StoBusiness\StobusinessModel;


class StoBusinessAreaController extends ActionController {

	public function __construct() {
        parent::__construct();
    }

    public function updateareaAction(){

    	$flag=$this->getParam('flag');
    	$businessid=$this->getParam('businessid');

    	$BusinessInfo = Model::ins('StoBusinessInfo')->getRow(['id'=>$businessid],'businessname,lngx,laty,area,area_code');
    	$BusinessBaseinfo = Model::ins('StoBusinessBaseinfo')->getRow(['id'=>$businessid],'address');
    	echo "<table width='800' align='center' border=1>
				<tr><th>实体店名称</th>
				<th>经度</th>
				<th>纬度</th>
				<th>区域</th>
				<th>区域编码</th>
				<th>地址</th></tr>
				<tr>
				<td>".$BusinessInfo['businessname']."</td>
				<td>".$BusinessInfo['lngx']."</td>
				<td>".$BusinessInfo['laty']."</td>
				<td>".$BusinessInfo['area']."</td>
				<td>".$BusinessInfo['area_code']."</td>
				<td>".$BusinessBaseinfo['address']."</td>
				</tr>
    		</table>";
    	$address = Model::ins('SysArea')->getRow(['id'=>$BusinessInfo['area_code']]);
    	$topaddress = Model::ins('SysArea')->getRow(['id'=>$address['parentid']]);
    	//print_r($address);
    	//print_r($topaddress);
    	$alladdress = $topaddress['areaname'].$address['areaname'].$BusinessBaseinfo['address'];
    	$AddressLngLat = CommonModel::getAddressLngLat($alladdress);
    
    	echo "<table width='800' align='center' border=1>
				<tr><th>地图</th>
				<th>经度</th>
				<th>纬度</th>
				<tr>
				<td>高德地图</td>
				<td>".$AddressLngLat['data']['lngx']."</td>
				<td>".$AddressLngLat['data']['laty']."</td>
				</tr>
    		</table>";

    	if($flag==1){

    		var_dump(Model::ins('StoBusinessInfo')->update(['lngx'=>$AddressLngLat['data']['lngx'],'laty'=>$AddressLngLat['data']['laty']],['id'=>$businessid]));
    		$loc = [
                'type' => 'Point',
                'coordinates' => [
                    doubleval($AddressLngLat['data']['lngx']),
                    doubleval($AddressLngLat['data']['laty'])
                ]
            ];
            print_r($loc);
    		var_dump(Model::Mongo('StoBusinessInfo')->update(['id'=>$businessid],['lngx'=>$AddressLngLat['data']['lngx'],'laty'=>$AddressLngLat['data']['laty'],'loc'=>$loc]));
    		echo "更新成功";
    	}
    }	
}