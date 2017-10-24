<?php
// +----------------------------------------------------------------------
// |  [ 地区管理 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime {{datetime}}
// +----------------------------------------------------------------------

namespace app\admin\controller\Setup;
use app\admin\ActionController;

use \think\Config;
use app\lib\Model;
use app\lib\Db;
use app\model\Sys\AreaModel;

class AreaController extends ActionController{


   /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /*
    * 生成缓存数据
    */
    public function updatecacheAction(){
        $DbTable_sysarea = new AreaModel();
        $DbTable_sysarea->updateAllCache();
        echo "OK";
        exit;
    }

    //获取省数据
    public function getprovinceAction(){
        $areaid = $this->getParam('areaid');
        $DbTable_sysarea = new AreaModel();
        $Province = $DbTable_sysarea->getProvince();

        $html = "<option value=''>选择省份</option>";
        foreach($Province as $key=>$value){
            $html .= "<option value='".$key."'".($key==$areaid?" selected":"").">".$value['areaname']."</option>"; 
        }

        echo $html;
        exit;
    }

    //获取市数据
    public function getcityAction(){
        $areaid         = $this->getParam('areaid');
        $parentid   = $this->getParam('parentid');
        $DbTable_sysarea = new AreaModel();
        $City = $DbTable_sysarea->getCity();

        $html = "<option value=''>选择城市</option>";
        foreach($City as $key=>$value){
            if($value['parentid']==$parentid)
                //$html .= "<option value='".$key."'".($key==$areaid?" selected":"").">".$value['areaname']."</option>"; 
            {
                if($key==$areaid or $value['areaname']==$areaid)
                {
                    $html .= "<option value='".$key."'"." selected".">".$value['areaname']."</option>"; 
                        
                }
                else
                {
                        $html .= "<option value='".$key."'"."".">".$value['areaname']."</option>"; 
                }
                
            }
        }

        echo $html;
        exit;
    }




    //获取市的值
    public function getcityvalueAction(){
        $areaid         = $this->_request->getParam('areaid');
        $parentid   = $this->_request->getParam('parentid');
        $DbTable_sysarea = new AreaModel();
        $City = $DbTable_sysarea->getCity();

        foreach($City as $key=>$value){
            if($value['parentid']==$parentid)
                //$html .= "<option value='".$key."'".($key==$areaid?" selected":"").">".$value['areaname']."</option>"; 
            {
                if($key==$areaid or $value['areaname']==$areaid)
                {
                    $html = $key;
                        
                }
               
                
            }
        }

        echo $html;
        exit;
    }

    //获取区数据
    public function getcountyAction(){
        $areaid = $this->getParam('areaid');
        $parentid   = $this->getParam('parentid');
        $DbTable_sysarea = new AreaModel();
        $County = $DbTable_sysarea->getCounty();

        $html = "<option value=''>选择区县</option>";
        foreach($County as $key=>$value){
            if($value['parentid']==$parentid)
                //$html .= "<option value='".$key."'".($key==$areaid?" selected":"").">".$value['areaname']."</option>"; 
            {
                if($key==$areaid or $value['areaname']==$areaid)
                {
                    $html .= "<option value='".$key."'"." selected".">".$value['areaname']."</option>"; 
                        
                }
                else
                {
                    $html .= "<option value='".$key."'"."".">".$value['areaname']."</option>"; 
                }
                
            }
        }

        echo $html;
        exit;
    }
}