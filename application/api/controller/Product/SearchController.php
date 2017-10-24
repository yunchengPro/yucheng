<?php
namespace app\api\controller\Product;
use app\api\ActionController;

use app\lib\Db;
use app\model\Product\ProductModel;

use app\lib\Model;

use app\lib\Img;

use app\model\Profit\Cash_abstract;

class SearchController extends ActionController
{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }   

    /**
     * 获取单条数据Demo
     * @Author   zhuangqm
     * @DateTime 2017-02-25T10:20:39+0800
     * @return   [type]                   [description]
     */
    public function indexAction(){
        /*
        $where = '';
        $keywords = $this->params['keywords'];
        $cid = $this->params['cid'];

        if(!empty($keywords)){
         
            $where = "(productname like '%".$keywords."%' or categoryname like '%".$keywords."%')";
        }

        //$list = Db::Table("ProProduct")->pageList("productname like '%".$keyswords."%' or categoryname like '%".$keyswords."%'");

        // return $this->json("200",[
        //         "searchresult"=>$list,
        //     ]);

        if(!empty($cid ) && !empty($keywords)){
            $where .= " and categoryid ='".$cid."'";
        }else if(!empty($cid) && empty($keywords)){
             $where .= "categoryid ='".$cid."'";
        }

        $where .= ' AND checksatus = 1';

        
        $proData = ProductModel::searchProduct(["where"=>$where,"fields"=>"id as productid,productname,thumb,prouctprice/100 as prouctprice,bullamount"]);

            //返回json数据
        return $this->json('200',['prolist'=>$proData]);
        */
       
        $productbuy = Model::new("Product.ProductBuy");
       
        $this->params['keywords'] = trim($this->params['keywords']);
        $result = Model::new("Product.search")->search($this->params);
        // print_r($result['data']);
        foreach($result['data']['list'] as $k=>$v){
            $result['data']['list'][$k]['productid']        = $v['id'];
            $result['data']['list'][$k]['thumb']            = Img::url($v['thumb'],500,500);
            $result['data']['list'][$k]['prouctprice']      = DePrice($v['prouctprice']);
            $result['data']['list'][$k]['bullamount']       = DePrice($v['bullamount']);


            //判断是否有抢购活动
            $productbuy_price = $productbuy->getProductPrice([
                "prouctprice"=>$result['data']['list'][$k]['prouctprice'],
                "bullamount"=>$result['data']['list'][$k]['bullamount'],
                "supplyprice"=>$result['data']['list'][$k]['supplyprice'],
            ]);
            $result['data']['list'][$k]['prouctprice'] = $productbuy_price['prouctprice'];
            $result['data']['list'][$k]['bullamount']  = $productbuy_price['bullamount'];
            $result['data']['list'][$k]['supplyprice'] = $productbuy_price['supplyprice'];
            

            $v['supplyprice'] = DePrice($v['supplyprice']);

            // give_profitamount 牛粮  give_bullamount 牛豆
            if($v['bullamount']==0){
                $product_profit = Cash_abstract::getCashUserReturn($v['prouctprice'],$v['supplyprice']);
                $list['list'][$k]['give_profitamount']  = ForMatPrice($product_profit['userBind']);
                $list['list'][$k]['give_bullamount']    = ForMatPrice($product_profit['bullNumber']);
            }else{
                $list['list'][$k]['give_profitamount']  = 0;
                $list['list'][$k]['give_bullamount']    = 0;
            }

            unset($result['data']['list'][$k]['id']);
            unset($result['data']['list'][$k]['_score']);
            unset($result['data']['list'][$k]['_id']);
            
            $result['data']['list'][$k]['supplyprice'] = '0';
        }
        // print_r($result);
        return $this->json('200',['prolist'=>$result['data']]);
    }

    
}
