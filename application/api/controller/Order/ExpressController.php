<?php
namespace app\api\controller\Order;
use app\api\ActionController;
use think\Config;
use app\lib\Express;


class ExpressController extends ActionController
{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    
    /**
     * 获取最新一条快递信息
     * @Author   xurui
     * @DateTime 2017-03-14T09:32:58+0800
     * @return   [type]                   [description]
     */
    
    public function getExpressAction() {
        $express_number = $shop_id = $this->params['express_number']; //快递单号
        $company_code = $shop_id = $this->params['company_code']; //快递单号
        $return = Express::search($company_code,$express_number,1);
        return $this->json("200",$return);
    }
    
    /**
     * 获取最新一条快递信息
     * @Author   xurui
     * @DateTime 2017-03-14T09:32:58+0800
     * @return   [type]     [description]
     */
    public function getExpressListAction() {
        $express_number = $shop_id = $this->params['express_number']; //快递单号
        $company_code = $shop_id = $this->params['company_code']; //快递单号
        $return = Express::search($company_code,$express_number);
        
        if($return['status']!='200'){
            // 拼接list数据给app端
            $return['message'] = "wait";
            $return['nu'] = $express_number;
            $return['ischeck'] = 0;
            $return['condition'] = '';
            $return['com'] = '';
            $return['status'] = $return['status'];
            $return['state'] = '未获取到物流状态';
            $return['data'] = array();
            return $this->json("200",$return);
//             return $this->json("400",$return['message']);
        }else{
            
            // context 可能存在着:影响到json转义
            foreach ($return['data'] as $k => $v) {
                $return['data'][$k]['context'] = str_replace(":","：",$v['context']);
            }
            
            return $this->json("200",$return);
        }
        
    }
}
