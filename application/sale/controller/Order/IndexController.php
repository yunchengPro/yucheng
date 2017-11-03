<?php
namespace app\sale\controller\Order;

use app\sale\ActionController;
use app\lib\Model;
use think\Config;
use think\Cookie;
use app\model\Sys\CommonModel;

class IndexController extends ActionController {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * [showorderAction 确认订单]
     * @return [type] [description]
     */
    public function showorderAction(){
    	$viewData = [
    		'title' => '确认订单'
    	];
    	return $this->view($viewData);
    }
}