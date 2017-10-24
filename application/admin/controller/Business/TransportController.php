<?php
// +----------------------------------------------------------------------
// |  [ 运费模板管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author gbfun<1952117823@qq.com>
// | @DateTime 2017年3月25日 14:23:41
// +----------------------------------------------------------------------\
namespace app\admin\controller\Business;
use app\admin\ActionController;

use \think\Config;
use app\lib\Model;
use app\lib\Db;
use app\form\OrdTransport\OrdTransportAdd;
use app\form\OrdTransportExtend\OrdTransportExtendAdd;

class TransportController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

	/**
	 * [indexAction 运费模板列表]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017-03-25T10:47:03+0800
	 * @return   [type]                   [description]
	 */
	public function indexAction(){
        
	    $where['business_id'] = $this->businessid;
	    $where = $this->searchWhere([
	        "title"=>"like"
	    ],$where);
	    //var_dump($where); exit();
       
        //运费模板列表
        $list = Model::ins("OrdTransport")->pageList($where,'*','id desc');
        //var_dump($list); exit();
        
        $viewData = array(
                "pagelist"=>$list['list'], //列表数据
                "total"=>$list['total'], //总数
            );

        return $this->view($viewData);
	}

	/**
	 * [addTransportAction 添加运费模板]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年3月27日 下午4:37:25
	 * @return [type]    [description]
	 */
	public function addTransportAction(){

        $action = '/Business/Transport/doaddOreditTransport';
        //var_dump($action); exit();
        
        //form验证token
        $formtoken = $this->Btoken('Business-Transport-addTransport');
        $viewData = array(
                "title"=>"添加运费模板",
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        
        return $this->view($viewData);
	}


    /**
     * [editTransportAction 编辑运费模板]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月27日 下午4:37:25
     * @return   [type]                   [description]
     */
    public function editTransportAction(){

        $id = $this->getParam('id');

        $action = '/Business/Transport/doaddOreditTransport';
        $transportData =  Model::ins('OrdTransport')->getRow(['id'=>$id]);
        //var_dump($transportData); exit();
        
        //form验证token
        $formtoken = $this->Btoken('Business-Transport-editTransport');
        $viewData = array(
                "title"=>"编辑品牌",
                "transportData"=>$transportData,
                'formtoken'=>$formtoken,
                "action"=>$action
            );
        
        return $this->view($viewData);
    }

    /**
     * [doaddOreditTransportAction 添加或修改模板信息]
     * @Author   gbfun<1952117823@qq.com>
     * @DateTime 2017年3月27日 下午4:37:25
     * @return   [type]                   [description]
     */
	public function doaddOreditTransportAction(){
        if($this->Ctoken()){
        //if(1){
            $post = $this->params;
            $id = $post['id'];
             //自动验证表单 需要修改form对应表名
            $OrdTransportAdd = new OrdTransportAdd();

            $post['business_id'] = intval($this->businessid); 
            $post = Model::ins('OrdTransport')->_facade($post);
            //var_dump($post); exit();
            
            if(!$OrdTransportAdd->isValid($post)){//验证是否正确 
                $this->showError($OrdTransportAdd->getErr());//提示报错信息
            }else{               
                if(empty($id)){
                    if($post['transport_type'] == 1)
                        Model::ins('OrdTransport')->update(['transport_type'=>2],['business_id' => $post['business_id']]);

                    $actionName = '添加';
                    $post['addtime'] = date('Y-m-d H:i:s');
                    $data = Model::ins('OrdTransport')->insert($post);  
                }else{
                     if($post['transport_type'] == 1)
                        Model::ins('OrdTransport')->update(['transport_type'=>2],['business_id' => $post['business_id']]);
                    $actionName = '编辑';
                    $data = Model::ins('OrdTransport')->update($post,['id'=>$id]);  
                }
                if($data > 0){
                    $this->showSuccess($actionName . '成功');
                }else{
                    $this->showError($actionName . '错误，请联系管理员');
                }
            }

        }else{
            $this->showError('token错误，禁止操作');
        }
	}


	/**
	 * [delTransportAction 删除模板]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年3月27日 下午4:37:25
	 * @return   [type]                   [description]
	 */
	public function delTransportAction(){
        $transportId = $this->getParam('ids');

        if(empty($transportId)){
            $this->showError('请选择需要删除的运费模板');
        }

//         $updateData = array(
//                 'is_delete'=> -1
//             );

        $transportId = explode(',', $transportId);
        //批量删除用户
        foreach ($transportId as $value) {
            //$transportData = Model::ins('OrdTransport')->update($updateData,['id'=>$value]);
            
            // $transportExtendData = Model::ins('OrdTransportExtend')->getRow(['transport_id' => $value]);
            // if(!empty($transportExtendData)){
            //     $this->showError('当前运费模板下有地区运费模板，请先删除地区运费模板');
            // }else{
                Model::ins('OrdTransportExtend')->delete(['transport_id' => $value]);
                $transportData = Model::ins('OrdTransport')->delete(['id'=>$value]);
            // }
        }

        $this->showSuccess('成功删除');
	}

	/**
	 * [editTransportExtendAction 运费模板配置参数]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-25T14:29:31+0800
	 * @return   [type]                   [description]
	 */
	public function editTransportExtendAction(){
        $businessid = $this->businessid;
        if(empty($businessid))
            $this->showErrorPage('请登录','/Login');	
	    $tid = $this->getParam('tid');
	    $info = Model::ins('OrdTransport')->getRow(['business_id'=>$businessid,'id'=>$tid]);
	    
	  
	    $transport_extend = Model::ins('OrdTransportExtend')->getList(['business_id'=>$businessid,'transport_id'=>$tid],'*','id desc');
	   
        foreach($transport_extend as $k=>$v){
            if($v['is_default'] == 1)
                $transport_extend[$k]['is_default'] = 1;
            $transport_extend[$k]['sprice'] = DePrice($v['sprice']);
            $transport_extend[$k]['xprice'] = DePrice($v['xprice']);
        }
	   
	    $action = '/Business/Transport/saveTransportExtend';
	    $formtoken = $this->Btoken('Business-Transport-editTransportExtend');
	    
        $zejiang = Model::ins('SysArea')->getRow(['shortname'=>'浙江'],'id,shortname');
        $zejiang_city = Model::ins('SysArea')->getList(['parentid'=>$zejiang['id']],'id,areaname');
        $zejiang['city'] = $zejiang_city;

        $jiangxi = Model::ins('SysArea')->getRow(['shortname'=>'江西'],'id,shortname');
        $jiangxi_city = Model::ins('SysArea')->getList(['parentid'=>$jiangxi['id']],'id,areaname');
        $jiangxi['city'] = $jiangxi_city;

        $anhui = Model::ins('SysArea')->getRow(['shortname'=>'安徽'],'id,shortname');
        $anhui_city = Model::ins('SysArea')->getList(['parentid'=>$anhui['id']],'id,areaname');
        $anhui['city'] = $anhui_city;

        $anhui = Model::ins('SysArea')->getRow(['shortname'=>'安徽'],'id,shortname');
        $anhui_city = Model::ins('SysArea')->getList(['parentid'=>$anhui['id']],'id,areaname');
        $anhui['city'] = $anhui_city;

        $jiangsu = Model::ins('SysArea')->getRow(['shortname'=>'江苏'],'id,shortname');
        $jiangsu_city = Model::ins('SysArea')->getList(['parentid'=>$jiangsu['id']],'id,areaname');
        $jiangsu['city'] = $jiangsu_city;

        $shanghai = Model::ins('SysArea')->getRow(['shortname'=>'上海'],'id,shortname');
        $shanghai_city = Model::ins('SysArea')->getList(['parentid'=>$shanghai['id']],'id,areaname');
        $shanghai['city'] = $shanghai_city;

        $neimeng = Model::ins('SysArea')->getRow(['shortname'=>'内蒙古'],'id,shortname');
        $neimeng_city = Model::ins('SysArea')->getList(['parentid'=>$neimeng['id']],'id,areaname');
        $neimeng['city'] = $neimeng_city;

        $shanxi = Model::ins('SysArea')->getRow(['shortname'=>'山西'],'id,shortname');
        $shanxi_city = Model::ins('SysArea')->getList(['parentid'=>$shanxi['id']],'id,areaname');
        $shanxi['city'] = $shanxi_city;

        $beijing = Model::ins('SysArea')->getRow(['shortname'=>'北京'],'id,shortname');
        $beijing_city = Model::ins('SysArea')->getList(['parentid'=>$beijing['id']],'id,areaname');
        $beijing['city'] = $beijing_city;

        $hebei = Model::ins('SysArea')->getRow(['shortname'=>'河北'],'id,shortname');
        $hebei_city = Model::ins('SysArea')->getList(['parentid'=>$hebei['id']],'id,areaname');
        $hebei['city'] = $hebei_city;

        $tianjin = Model::ins('SysArea')->getRow(['shortname'=>'天津'],'id,shortname');
        $tianjin_city = Model::ins('SysArea')->getList(['parentid'=>$tianjin['id']],'id,areaname');
        $tianjin['city'] = $tianjin_city;

        $shandong = Model::ins('SysArea')->getRow(['shortname'=>'山东'],'id,shortname');
        $shandong_city = Model::ins('SysArea')->getList(['parentid'=>$shandong['id']],'id,areaname');
        $shandong['city'] = $shandong_city;

        $hubei = Model::ins('SysArea')->getRow(['shortname'=>'湖北'],'id,shortname');
        $hubei_city = Model::ins('SysArea')->getList(['parentid'=>$hubei['id']],'id,areaname');
        $hubei['city'] = $hubei_city;

        $henan = Model::ins('SysArea')->getRow(['shortname'=>'河南'],'id,shortname');
        $henan_city = Model::ins('SysArea')->getList(['parentid'=>$henan['id']],'id,areaname');
        $henan['city'] = $henan_city;

        $hunan = Model::ins('SysArea')->getRow(['shortname'=>'湖南'],'id,shortname');
        $hunan_city = Model::ins('SysArea')->getList(['parentid'=>$hunan['id']],'id,areaname');
        $hunan['city'] = $hunan_city;

       
        $guangxi = Model::ins('SysArea')->getRow(['shortname'=>'广西'],'id,shortname');
        $guangxi_city = Model::ins('SysArea')->getList(['parentid'=>$guangxi['id']],'id,areaname');
        $guangxi['city'] = $guangxi_city;

        $hainan = Model::ins('SysArea')->getRow(['shortname'=>'海南'],'id,shortname');
        $hainan_city = Model::ins('SysArea')->getList(['parentid'=>$hainan['id']],'id,areaname');
        $hainan['city'] = $hainan_city;

        $guangdong = Model::ins('SysArea')->getRow(['shortname'=>'广东'],'id,shortname');
        $guangdong_city = Model::ins('SysArea')->getList(['parentid'=>$guangdong['id']],'id,areaname');
        $guangdong['city'] = $guangdong_city;
    
        $fujian = Model::ins('SysArea')->getRow(['shortname'=>'福建'],'id,shortname');
        $fujian_city = Model::ins('SysArea')->getList(['parentid'=>$fujian['id']],'id,areaname');
        $fujian['city'] = $fujian_city;

        $jilin = Model::ins('SysArea')->getRow(['shortname'=>'吉林'],'id,shortname');
        $jilin_city = Model::ins('SysArea')->getList(['parentid'=>$jilin['id']],'id,areaname');
        $jilin['city'] = $jilin_city;

        $heilong = Model::ins('SysArea')->getRow(['shortname'=>'黑龙江'],'id,shortname');
        $heilong_city = Model::ins('SysArea')->getList(['parentid'=>$heilong['id']],'id,areaname');
        $heilong['city'] = $heilong_city;

        $liaonin = Model::ins('SysArea')->getRow(['shortname'=>'辽宁'],'id,shortname');
        $liaonin_city = Model::ins('SysArea')->getList(['parentid'=>$liaonin['id']],'id,areaname');
        $liaonin['city'] = $liaonin_city;

        $ninxia = Model::ins('SysArea')->getRow(['shortname'=>'宁夏'],'id,shortname');
        $ninxia_city = Model::ins('SysArea')->getList(['parentid'=>$ninxia['id']],'id,areaname');
        $ninxia['city'] = $ninxia_city;

        $xinjiang = Model::ins('SysArea')->getRow(['shortname'=>'新疆'],'id,shortname');
        $xinjiang_city = Model::ins('SysArea')->getList(['parentid'=>$xinjiang['id']],'id,areaname');
        $xinjiang['city'] = $xinjiang_city;

        $qinhai = Model::ins('SysArea')->getRow(['shortname'=>'青海'],'id,shortname');
        $qinhai_city = Model::ins('SysArea')->getList(['parentid'=>$qinhai['id']],'id,areaname');
        $qinhai['city'] = $qinhai_city;

        $gansu = Model::ins('SysArea')->getRow(['shortname'=>'甘肃'],'id,shortname');
        $gansu_city = Model::ins('SysArea')->getList(['parentid'=>$gansu['id']],'id,areaname');
        $gansu['city'] = $gansu_city;

        $shangxi = Model::ins('SysArea')->getRow(['shortname'=>'陕西'],'id,shortname');
        $shangxi_city = Model::ins('SysArea')->getList(['parentid'=>$shangxi['id']],'id,areaname');
        $shangxi['city'] = $shangxi_city;

        $guizhou = Model::ins('SysArea')->getRow(['shortname'=>'贵州'],'id,shortname');
        $guizhou_city = Model::ins('SysArea')->getList(['parentid'=>$guizhou['id']],'id,areaname');
        $guizhou['city'] = $guizhou_city;

        $xizang = Model::ins('SysArea')->getRow(['shortname'=>'西藏'],'id,shortname');
        $xizang_city = Model::ins('SysArea')->getList(['parentid'=>$xizang['id']],'id,areaname');
        $xizang['city'] = $xizang_city;

        $yunan = Model::ins('SysArea')->getRow(['shortname'=>'云南'],'id,shortname');
        $yunan_city = Model::ins('SysArea')->getList(['parentid'=>$yunan['id']],'id,areaname');
        $yunan['city'] = $yunan_city;

        $sichuan = Model::ins('SysArea')->getRow(['shortname'=>'四川'],'id,shortname');
        $sichuan_city = Model::ins('SysArea')->getList(['parentid'=>$sichuan['id']],'id,areaname');
        $sichuan['city'] = $sichuan_city;

        $chongqi = Model::ins('SysArea')->getRow(['shortname'=>'重庆'],'id,shortname');
        $chongqi_city = Model::ins('SysArea')->getList(['parentid'=>$chongqi['id']],'id,areaname');
        $chongqi['city'] = $chongqi_city;

        $aomen = Model::ins('SysArea')->getRow(['shortname'=>'澳门'],'id,shortname');
        $aomen_city = Model::ins('SysArea')->getList(['parentid'=>$aomen['id']],'id,areaname');
        $aomen['city'] = $aomen_city;

        $xianggang = Model::ins('SysArea')->getRow(['shortname'=>'香港'],'id,shortname');
        $xianggang_city = Model::ins('SysArea')->getList(['parentid'=>$xianggang['id']],'id,areaname');
        $xianggang['city'] = $xianggang_city;

        $taiwan = Model::ins('SysArea')->getRow(['shortname'=>'台湾'],'id,shortname');
        $taiwan_city = Model::ins('SysArea')->getList(['parentid'=>$taiwan['id']],'id,areaname');
        $taiwan['city'] = $taiwan_city;

	    $viewData = [
	        'action'=>$action,
	        'info'=>$info,
	        'transport_extend'=>$transport_extend,
	        'formtoken'=>$formtoken,
            'zejiang' => $zejiang,
            'jiangxi' => $jiangxi,
            'anhui'   => $anhui,
            'jiangsu' => $jiangsu,
            'shanghai'=> $shanghai,
            'neimeng' => $neimeng,
            'shanxi'  => $shanxi,
            'beijing' => $beijing,
            'hebei'   => $hebei,
            'tianjin' => $tianjin,
            'shandong'=> $shandong,
            'hubei'   => $hubei,
            'henan'   => $henan,
            'hunan'   => $hunan,
            'guangxi' => $guangxi,
            'hainan'  => $hainan,
            'guangdong' => $guangdong,
            'fujian'   => $fujian,
            'jilin'    => $jilin,
            'heilong'  => $heilong,
            'liaonin'  => $liaonin,
            'ninxia'   => $ninxia,
            'xinjiang' => $xinjiang,
            'qinhai'   => $qinhai,
            'gansu'    => $gansu,
            'shangxi'  => $shangxi,
            'guizhou'  => $guizhou,
            'xizang'   => $xizang,
            'yunan'    => $yunan,
            'sichuan'  => $sichuan,
            'chongqi'  => $chongqi,
            'aomen'    => $aomen,
            'xianggang'=> $xianggang,
            'taiwan'   => $taiwan
	    ];
	    return $this->view($viewData);
	}
	
	/**
	 * [saveTransportExtendAction 保存运费模板]
	 * @Author   gbfun<1952117823@qq.com>
	 * @DateTime 2017年3月27日 下午5:21:25
	 * @return   [type]                   [description]
	 */
	public function saveTransportExtendAction(){
	    if($this->Ctoken()){
        //if(1){
    	    $businessId = $this->businessid;
    	    
    	    $post = $this->params;
            //$transport_id = $post['transport_id'];
    	    //@start 主模板
    	    $transportId = intval($post['transport_id']);	
    	    $transportTitle = strval($post['title']);
    	    $transportValuationType = intval($post['valuation_type']);
            $transportType =  intval($post['transport_type']);
    	    $info = Model::ins('OrdTransport')->getRow(['business_id' => $businessId]);
           
            if(empty($transportId)){
                
                if($transportType == 1)
                    Model::ins('OrdTransport')->update(['transport_type'=>2],['business_id' => $businessId]);
        	    $transport = array(
                        'business_id'    => $businessId,
                        'title'          => $transportTitle,
                        'valuation_type' => $transportValuationType,
                        'transport_type'  => $transportType
                    );
                    
                $OrdTransportAdd = new OrdTransportAdd();
                if(!$OrdTransportAdd->isValid($transport)){
                    $this->showError($OrdTransportAdd->getErr());//提示报错信息
                }else{
                    $transport_id = Model::ins('OrdTransport')->insert($transport);
                }
            }else{
                if($transportType == 1)
                    Model::ins('OrdTransport')->update(['transport_type'=>2],['business_id' => $businessId]);
                $transport = array(
                        'business_id'    => $businessId,
                        'title'          => $transportTitle,
                        'valuation_type' => $transportValuationType,
                        'transport_type'  => $transportType
                    );
                
                $transport_id = Model::ins('OrdTransport')->update($transport,['id'=>$transportId]);
                
            }
           
    	    // if(!empty($info)){
        	//     $transport = array(
         //    	    //'business_id'    => $businessId,
         //    	    'title'          => $transportTitle,
        	//         'valuation_type' => $transportValuationType,
        	//     );
        	    
        	//     $OrdTransportAdd = new OrdTransportAdd();
        	//     if(!$OrdTransportAdd->isValid($transport)){
        	//         $this->showError($OrdTransportAdd->getErr());//提示报错信息
        	//     }else{
        	//         $data = Model::ins('OrdTransport')->update($transport,['business_id'=>$businessId]);
        	//     }
    	    // }else{
         //        $transportData = [
         //            'title'          => $transportTitle,
         //            'business_id' => $businessId,
         //            'valuation_type'=> $transportValuationType,
         //            'addtime' => date('Y-m-d H:i:s')
         //        ];
         //        Model::ins('OrdTransport')->insert($transportData);
    	    //     $info = Model::ins('OrdTransport')->getRow(['business_id' => $businessId]);
    	    // }
    	    //@end 主模板
    	    
    	    //@start 地区模板
    	    $default = $post['default']['kd']; 
    	    $areas   = $post['areas']['kd'];
    	    $special = $post['special']['kd'];
    	    
    	    //删除所有对应地区模板
    	    //$transportExtendData = Model::ins('OrdTransportExtend')->delete(['transport_id'=>$transportId,'business_id'=>$businessId]);
    	    //var_dump($transportExtendData); exit();
            
    	    $defaultData = Model::ins('OrdTransportExtend')->getRow(['is_default'=>1,'business_id'=>$businessId,'transport_id'=>$transportId],'id');
            if($default['start'] <= 0){
                $this->showErrorPage('运费起始数量必须大于0','/Business/Transport/editTransportExtend');
            }
    	    //增加默认地区模板
    	    $defaultTransportExtend = array(
    	        'business_id' => $businessId,
    	        'transport_id' => $transportId,
    	        'transport_title' => $transportTitle,
    	        //'area_id' => '',
    	        //'area_name' => '',
    	        'snum' => intval($default['start']),
    	        'sprice' => EnPrice($default['postage']),
    	        'xnum' => intval($default['plus']),
    	        'xprice' => EnPrice($default['postageplus']),
    	        'is_default' => 1,	 
                'transport_type' => $transportType           
    	    );
           
            if(empty($defaultData)){
                Model::ins('OrdTransportExtend')->insert($defaultTransportExtend);
            }else{
                Model::ins('OrdTransportExtend')->update($defaultTransportExtend,['id'=>$defaultData['id']]); 
            }
    	    //var_dump($defaultTransportExtend); exit();
    	    //$result = Model::ins('OrdTransportExtend')->insert($defaultTransportExtend);
    	    // $OrdTransportExtendAdd = new OrdTransportExtendAdd();
    	    // if(!$OrdTransportExtendAdd->isValid($defaultTransportExtend)){
    	    //     $this->showError($OrdTransportExtendAdd->getErr());//提示报错信息
    	    // }else{
    	    //     //$result = Model::ins('OrdTransportExtend')->insert($defaultTransportExtend);
    	    // }
    	 
    	    

    	    //增加地区模板
    	    foreach($areas as $key => $area){
    	        $areaInfo = explode('|||', $area);
    	        //var_dump($areaInfo); exit();
    	        $areaId   = $areaInfo[0];
    	        if(!empty($areaId)){
        	        $areaName = $areaInfo[1];
        	        $transportExtend = array(
        	            'business_id' => $businessId,
        	            'transport_id' => $transportId,
        	            'transport_title' => $transportTitle,
        	            'area_id' => $areaId,
        	            'area_name' => $areaName,
        	            'snum' => intval($special[$key]['start']),
        	            'sprice' => EnPrice($special[$key]['postage']),
        	            'xnum' => intval($special[$key]['plus']),
        	            'xprice' => EnPrice($special[$key]['postageplus']),
                        'transport_type' => $transportType         
        	            //'is_default' => -1,
        	        );

                    $tid = $post['kd_n'.$key];
                    $getRow = Model::ins('OrdTransportExtend')->getRow(['id'=>$tid],'id');
                    
                    if(empty($getRow)){
                       
        	           $result = Model::ins('OrdTransportExtend')->insert($transportExtend);
                    }else{
                     
                        Model::ins('OrdTransportExtend')->update($transportExtend,['id'=>$tid]);
                    }
        	      
    	        }else{
                  
    	            $this->showError('请选择应地区', '/Business/Transport/editTransportExtend?tid=' . $transportId);
    	        }
    	    }	

    	    //@end 地区模板
	    }else{
	        $this->showError('token错误，禁止操作');
	    }
	    
	    $this->showSuccessPage('编辑成功','/Business/Transport/editTransportExtend?tid=' . $transportId);
	}

    /**
     * [delTransportExtend 删除模板]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-05-15T16:41:12+0800
     * @return   [type]                   [description]
     */
    public function delTransportExtendAction(){

        $tid = $this->getParam('tid');
        $del = Model::ins('OrdTransportExtend')->delete(['id'=>$tid]);

        if($del){
            echo json_encode(['code'=>'200']);
        }else{
            echo json_encode(['code'=>'404']);
        }
        exit();
    }
}