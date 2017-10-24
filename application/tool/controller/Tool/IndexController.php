<?php
namespace app\tool\controller\Tool;
use app\tool\ActionController;

use \think\Config;

class IndexController extends ActionController
{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    

    public function test(){
        print_r($this->params);
        return $this->view([
                "test1"=>"test1值",
                "test2"=>"test2值",
            ]);
    }

    public function aa(){
        echo "tool/index/aa";
        print_r($this->params);

        exit;
    }
}
