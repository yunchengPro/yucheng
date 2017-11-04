<?php
namespace app\model\Sys;
use app\lib\Model;
use app\lib\Img;

class BannerModel {

	/**
	 * [bannerList 轮播图列表]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-10-21T10:20:09+0800
	 * @return   [type]                   [description]
	 */
	public static function bannerList(){
		$where['type'] = 1;
		$list = Model::ins('SysBanner')->getlist($where,'id,bname,thumb,urltype,url','sort asc,addtime desc');
		foreach ($list as $key => $value) {
			$list[$key]['thumb'] = Img::url($value['thumb']);
		}
		return ['code'=>200,'data'=>$list];
	}
}