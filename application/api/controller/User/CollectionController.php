<?php
// +----------------------------------------------------------------------
// |  [ 用户收藏接口 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{2017-5-18 16:24:09}}
// +----------------------------------------------------------------------
namespace app\api\controller\User;
use app\api\ActionController;

use app\lib\Model;

use app\model\User\CollectionModel;

class CollectionController extends ActionController {
	
	public function __construct() {
        parent::__construct();
    }

    /**
     * [collection 用户收藏接口]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-18T16:28:00+0800
     * @return   [type]                   [description]
     */
	public function addCollectionAction(){

		if(!empty($this->userid) || !empty($this->params)){
			$params = [
				'userid' => $this->userid,
				'type' => $this->params['type'],
				'obj_id' => $this->params['obj_id']
			];
			$ret = CollectionModel::addCollection($params);
			if($ret['code'] == 200){

				return $this->json(200,$ret['data']);
			}else{
				return $this->json($ret['code']);
			}
		}else{
			return $this->json(404);
		}
	}

	/**
	 * [cancelCollectionAction 用户取消收藏]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-05-18T17:21:48+0800
	 * @return   [type]                   [description]
	 */
	public function cancelCollectionAction(){
		if(!empty($this->userid) || !empty($this->params)){
			$params = [
				'userid' => $this->userid,
				'type' => $this->params['type'],
				'obj_id' => $this->params['obj_id']
			];
			$ret = CollectionModel::cancelCollection($params);
			if($ret['code'] == 200){
				
				return $this->json(200,$ret['data']);
			}else{
				return $this->json($ret['code']);
			}
		}else{
			return $this->json(404);
		}
	}

	/**
	 * [collectionlistAction 收藏列表]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-05-18T17:59:45+0800
	 * @return   [type]                   [description]
	 */
	public function collectionListAction(){
		if(!empty($this->userid) || !empty($this->params)){
			$params = [
				'userid' => $this->userid,
				'type' => $this->params['type']
			];
			$ret = CollectionModel::collectionList($params);
			if($ret['code'] == 200){

				return $this->json(200,$ret['data']);
			}else{
				return $this->json($ret['code']);
			}
		}else{
			return $this->json(404);
		}
	}

}