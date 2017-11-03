<?php
namespace app\sale\controller\User;

use app\sale\ActionController;
use app\lib\Model;
use think\Config;
use think\Cookie;
use app\model\Sys\CommonModel;

class ShopcartController extends ActionController {


    public function __construct() {
        parent::__construct();
    }
    
    public function indexAction() {
    	$viewData = [
    		'title' => '购物车'
    	];
    	return $this->view($viewData);
    }
}