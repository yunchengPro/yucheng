<?php
namespace app\auto;

use app\ComController;
use \think\Config;

class ActionController extends ComController
{   
    public $business_roleid = null;
    public $username = null;
    public $businessid = null;

    public function __construct(){

        parent::__construct();

    }

}
