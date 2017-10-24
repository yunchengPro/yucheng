<?php
namespace app\api\controller\User;
use app\api\ActionController;

use app\model\User\RecoStoBusinessModel;
use app\lib\Model;
use app\model\StoBusiness\StobusinessModel;
use app\model\User\UserModel;
use app\model\User\RoleRecoModel;

class RecoStoBusinessController extends ActionController {

	public function __construct() {
        parent::__construct();
    }
   	
   	/**
   	 * [businessListAction description]
   	 * @Author   ISir<673638498@qq.com>
   	 * @DateTime 2017-06-23T14:14:05+0800
   	 * @return   [type]                   [description]
   	 */
    public function StoBusinessListAction(){
    	$roleid = $this->params['roleid'];
    	if(empty($roleid)){
        	return $this->json(404);  
    	}
    	$params = [
    		'roleid'=> $roleid ,
    		'userid' => $this->userid,
    		'type' => $this->params['type']
    	];
    	$businessList = RecoStoBusinessModel::RecoStoBusinessList($params);
    	return $this->json(200,$businessList);
    }


    /**
     * [recoshopAction 推荐的店铺详情资料]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-26T10:31:01+0800
     * @return   [type]                   [description]
     */
    public function recoshopAction() {
        $params['customerid'] = $this->params['customerid'];

        if(empty($params['customerid']))
            return $this->json(404);

        $userOBJ = new UserModel();
        $stoId = $userOBJ->getUserSto($params);
        if(empty($stoId)) {
            return $this->json(60008);
        }
        
        $params['stoId'] = $stoId;
        $params['version'] = $this->getVersion();
        $stoInfo = StobusinessModel::getStoApiInfo($params);
        
        if($this->Version("1.0.1") || $this->Version("1.0.2") || $this->Version("1.0.3")) {
            $stoBusInfo = Model::ins("StoBusiness")->getRow(array("id" => $stoId), "isvip");
            $stoInfo['isvip'] = $stoBusInfo['isvip'];
            $stoInfo['businessid'] = $stoId;
        }
        
        return $this->json(200, $stoInfo);
    }


    /**
     * [recoupdateshopAction 编辑推荐的实体店]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-23T16:56:25+0800
     * @return   [type]                   [description]
     */
    public function recoupdateshopAction(){
        if(empty($this->params['sto_name']) || empty($this->params['discount']) || empty($this->params['sto_hour_begin']) || empty($this->params['sto_hour_end'])
            || empty($this->params['sto_mobile']) || empty($this->params['description'])) {
                return $this->json(404);
            }
        $this->params['customerid'] = $this->params['customerid'];
        $this->params['version'] = $this->getVersion();
        $result = StobusinessModel::cusUpdateStoInfo($this->params);
        
        return $this->json($result['code']);
    }

     /**
     * [recoexamfaildelAction 推荐店铺删除]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-23T17:05:23+0800
     * @return   [type]                   [description]
     */
    public function recoexamfaildelAction() {
        if(empty($this->params['recoRoleType']) || empty($this->params['mobile']) || empty($this->params['selfRoleType'])) {
            return $this->json(404);
        }
        // 确保role值在正确范围内
        $role_arr = array(4, 5);
        if(!in_array($this->params['recoRoleType'], $role_arr)) {
            return $this->json(1001);
        }
        //$this->params['customerid'] = $this->userid;
        
        $roleRecoOBJ = new RoleRecoModel();
        $result = $roleRecoOBJ->recoExamDisabled($this->params);
        
        return $this->json($result["code"]);
    }
}