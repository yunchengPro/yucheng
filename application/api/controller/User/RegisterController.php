<?php
namespace app\api\controller\User;

use app\api\ActionController;

class RegisterController extends ActionController {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * @uses 推荐后的操作(成为其推荐者的下级消费者)
     * 暂时不开发
     */
    public function pushAction() {
        // 
    }
}