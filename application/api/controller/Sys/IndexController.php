<?php
// +----------------------------------------------------------------------
// |  [ 2016-02-28 购物车信息 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ISir <673638498@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller\Sys;
use app\api\ActionController;

use app\lib\Log;

class IndexController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }


    public function versionupdateAction(){
        
        $now_version = $this->params['version'];

        $new_version = "103050";//103030

        $update = 0;

        if($now_version<$new_version)
            $update = 1;

        return $this->json("200",[
                "update"=>$update,
                "url"=>'http://nnhtest.oss-cn-shenzhen.aliyuncs.com/Android/download/103050/NiuNiuHui1.3.5.apk',
                "title"=>"本次更新启用了黑科技",
                "info"=>"热修复功能-原来字段",
                "remark"=>"以后遇到小问题不用再等新版本就可以解决了",
                "new_version"=>$new_version,

            ]);
    }

    public function errorlogAction(){

        Log::addlog($this->params['content']);
        
        return $this->json("200");
    }
}