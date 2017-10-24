<?php
    /**
     * 快递信息
     */
    namespace app\lib;
    use app\model\Sys\CommonModel;
    use think\Config;
    //use app\ComController;
    
    class Express {

        public static function getState($state){
            $state_arr = [
                "0"=>"在途",
                "1"=>"揽件",
                "2"=>"疑难",
                "3"=>"签收",
                "4"=>"退签",
                "5"=>"派件",
                "6"=>"退回",
            ];

            return $state_arr[$state];
        }

        /**
         * function: 物流信息
         * @Author   :xurui
         * @DateTime 2017-03-08 am
         * @param  company_code string 快递公司编码 公司名全拼且都小写： 例如：顺丰速递 => shunfeng ;圆通快递 => yuantong等
         * @param  express_number string   快递单号  一串数字组成的快递单号
         * @param  is_newest    int  0:查询全部快递信息;1:查询查询最新一条快递动态信息
         * @return  json
         */
        public static function search($company_code,$express_number,$is_newest = 0) {
            $appkey = Config::get('express.appkey');//快递100授权秘钥
            $customerCode = Config::get('express.customer_code');//快递100客户code
            $expressCodeUrl = Config::get('express.getExpressCodeUrl');//快递公司编码接口地址
            $expressListUrl = Config::get('express.getExpressListUrl');//快递100的快递列表接口
            $url_company_code = $expressCodeUrl.'?num='.$express_number.'&key='.$appkey;//单号归属公司智能判断接口
            $arr_company_code = json_decode(file_get_contents($url_company_code), true); //获得实时的快递公司的编号
            $company_code = $arr_company_code[0]['comCode'] ?: $company_code; //快递公司对应的编码
          
            $post_data = array();
            $post_data["customer"] = $customerCode;
            $post_data["param"] = '{"com":"'.strtolower($company_code).'","num":"'.$express_number.'"}';
            $url_express_info = $expressListUrl;
            $post_data["sign"] = strtoupper(md5($post_data["param"].$appkey.$post_data["customer"]));
            $url_param = "";
            foreach ($post_data as $k => $v) {
                $url_param .= "$k=".urlencode($v)."&";//默认UTF-8编码格式
            }
            $post_data = substr($url_param, 0, -1);
            unset($url_param);
            $powered = '查询数据由：<a href="http://kuaidi100.com" target="_blank">KuaiDi100.Com （快递100）</a> 网站提供 '; ////请勿删除变量$powered 的信息，否者本站将不再为你提供快递接口服务。
            $express_info_json = CommonModel::post_curl($url_express_info.'?'.$post_data);
            $express_info_arr = json_decode($express_info_json, true);
            if ($is_newest == 1) {
                $data_tmp = [];
                foreach($express_info_arr['data'] as $k=>$v){
                    $data_tmp[] = $v;
                    break;
                }
                $express_info_arr['data'] = $data_tmp;
            }
            $express_info_arr['state'] = self::getState($express_info_arr['state']);
            return $express_info_arr;
            /*
            $obj = new ComController();
            if ($express_info_arr['status'] != 200) {echo $obj->json('400',array(),$express_info_arr['message']);exit;}
            if ($express_info_arr['status'] == 200) {
                if ($is_newest == 1) { //返回最新的快递实时信息
                    $express_info_arr['newest_info'] = $express_info_arr['data']['0'];
                    unset($express_info_arr['data']);
                }
                return $obj ->json(200,$express_info_arr,'获取最新快递信息成功') ;
            }
            */
        }
    }