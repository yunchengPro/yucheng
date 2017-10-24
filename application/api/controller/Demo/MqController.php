<?php
namespace app\api\controller\Demo;
use app\api\ActionController;


use app\lib\Model;

class MqController extends ActionController
{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    

    public function mqtestAction(){
        
        Model::new("Sys.Mq")->add([
                "url"=>"Demo.Mq.test",
                "param"=>[
                    "bull_code"=>rand(1000,9999).time().rand(1000,9999),
                    "addtime"=>date("Y-m-d H:i:s"),
                ],
            ]);
        Model::new("Sys.Mq")->add([
                "url"=>"Demo.Mq.test",
                "param"=>[
                    "bull_code"=>rand(1000,9999).time().rand(1000,9999),
                    "addtime"=>date("Y-m-d H:i:s"),
                ],
            ]);
        Model::new("Sys.Mq")->submit();
        echo "OK";
        exit;
    }
}
