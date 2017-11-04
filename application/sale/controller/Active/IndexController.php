<?php
namespace app\mobile\controller\Active;
use app\mobile\ActionController;

use app\model\Product\ProductModel;
use think\Config;

use app\lib\Model;

use app\lib\Img;

class IndexController extends ActionController{


	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    

    /**
     * [indexAction 专题活动首页]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-13T12:32:26+0800
     * @return   [type]                   [description]
     */
    public function indexAction(){
        $config = Config::get("webview");
    	return $this->view([
                "domain"=>$config['webviewUrl'],
            ]);
    }

    public function categorylistAction(){
        $categoryid = $this->params['categoryid'];

        $page = $this->params['page'];

        $categoryitem = Model::ins("ProCategory")->getRow(["id"=>$categoryid],"name");

        $childcategorylist = Model::ins("ProCategory")->getList(["parent_id"=>$categoryid],"id");

        $childcid = '';
        foreach($childcategorylist as $v){
            if($v['id']!='')
                $childcid.=$v['id'].",";
        }
        $childcid = $childcid!=''?$childcid.$categoryid:$categoryid;

        $productlist = Model::ins("ProProduct")->getList(["categoryid"=>["in",$childcid],"enable"=>1,'checksatus'=>1],"id,productname,thumb,prouctprice,productunit,weight,volume,freight,bullamount","sort desc,id desc",6);

        foreach($productlist as $k=>$v){
            $prospecsdata = ProductModel::prospecsdata($v['id']);
           
            $productlist[$k]['thumb'] = Img::url($productlist[$k]['thumb'],300,300);
            $productlist[$k]['prouctprice'] = DePrice($productlist[$k]['prouctprice']);
            $productlist[$k]['bullamount'] = DePrice($productlist[$k]['bullamount']);
            $productlist[$k]['spec'] = $prospecsdata;
            $productlist[$k]['info'].= "单位:".$v['productunit']." ";
            $productlist[$k]['info'].= "重量:".$v['weight']."kg ";
            $productlist[$k]['info'].= "体积长*宽*高:".$v['volume']." ";
            $productlist[$k]['info'].= "运费:".$v['freight']>0?$v['freight']."元":"包邮";

        }

        
        $goodList =  Model::ins("ProProduct")->pageList(["categoryid"=>["in",$childcid],"enable"=>1,'checksatus'=>1],"id,productname,thumb,prouctprice,productunit,weight,volume,freight,bullamount","sort desc,id desc",1,2,6);

       

        foreach($goodList['list'] as $k=>$v){
            $prospecsdata = ProductModel::prospecsdata($v['id']);
           
            $goodList['list'][$k]['thumb'] = Img::url($v['thumb'],300,300);
            $goodList['list'][$k]['prouctprice'] = DePrice($v['prouctprice']);
            $goodList['list'][$k]['bullamount'] = DePrice($v['bullamount']);
            $goodList['list'][$k]['spec'] = $prospecsdata;
            $goodList['list'][$k]['info'].= "单位:".$v['productunit']." ";
            $goodList['list'][$k]['info'].= "重量:".$v['weight']." ";
            $goodList['list'][$k]['info'].= "体积长*宽*高:".$v['volume']." ";
            $goodList['list'][$k]['info'].= "运费:".$v['freight']>0?$v['freight']."元":"包邮";
            
        }

        return $this->view([
                "categoryid"=>$categoryid,
                "category_name"=>$categoryitem['name'],
                "productlist"=>$productlist,
                "goodList" => $goodList['list']
            ]);
    }


    /**
     * [ajaxGoodsListAction 加载]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-28T11:20:16+0800
     * @return   [type]                   [description]
     */
    public function ajaxGoodsListAction(){

        $categoryid = $this->params['categoryid'];

        $page = $this->params['page'];

        $categoryitem = Model::ins("ProCategory")->getRow(["id"=>$categoryid],"name");

        $childcategorylist = Model::ins("ProCategory")->getList(["parent_id"=>$categoryid],"id");

        $childcid = '';
        foreach($childcategorylist as $v){
            if($v['id']!='')
                $childcid.=$v['id'].",";
        }
        $childcid = $childcid!=''?$childcid.$categoryid:$categoryid;

        $goodList =  Model::ins("ProProduct")->pageList(["categoryid"=>["in",$childcid],"enable"=>1,'checksatus'=>1],"id,productname,thumb,prouctprice,productunit,weight,volume,freight,bullamount","sort desc,id desc",1,$page,6);

       

        foreach($goodList['list'] as $k=>$v){
            $prospecsdata = ProductModel::prospecsdata($v['id']);
           
            $goodList['list'][$k]['thumb'] = Img::url($v['thumb'],300,300);
            $goodList['list'][$k]['prouctprice'] = DePrice($v['prouctprice']);
            $goodList['list'][$k]['bullamount'] = DePrice($v['bullamount']);
            $goodList['list'][$k]['spec'] = $prospecsdata;
            $goodList['list'][$k]['info'].= "单位:".$v['productunit']." ";
            $goodList['list'][$k]['info'].= "重量:".$v['weight']." ";
            $goodList['list'][$k]['info'].= "体积长*宽*高:".$v['volume']." ";
            $goodList['list'][$k]['info'].= "运费:".$v['freight']>0?$v['freight']."元":"包邮";
            
        }
      
        echo json_encode($goodList['list']);
    }
}