<?php
// +----------------------------------------------------------------------
// |  [ 售卖专区搜索 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-04
// +----------------------------------------------------------------------
namespace app\api\controller\Saleway;
use app\api\ActionController;

use app\lib\Db;
use app\model\Product\ProductModel;
class SearchController extends ActionController{

    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    /**
     * [indexAction 售卖专区]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-04-01T11:27:00+0800
     * @return   [type]                   [description]
     */
    public function indexAction(){
        
        $type = !empty($this->params['type']) ? $this->params['type'] : 1;

        $keywords = $this->params['keywords'];
        $cid = $this->params['cid'];

        if($type > 4)
             $type = 1;
        switch ($type) {
            case 2:
                 //现金专区条件筛选
                $where = 'prouctprice > 0 AND bullamount = 0';
                break;
            case 3:
                //现金加牛币专区筛选
                $where = 'prouctprice > 0 AND bullamount > 0';
                break;
            case 4:
                //商品列表 牛豆专区筛选
                $where = 'prouctprice > 0 AND bullamount > 0';
            // case 4:
            //     //商品列表 牛豆专区筛选
            //     $where = 'prouctprice = 0 AND bullamount > 0';
              
                break;
            default:
                $where = ' 1=1';
                break;
        }


        if(empty($keywords)){
            return $this->json("404");
        }else{

            $where .= " AND (productname like '%".$keywords."%' or categoryname like '%".$keywords."%')";
        }

        //$list = Db::Table("ProProduct")->pageList("productname like '%".$keyswords."%' or categoryname like '%".$keyswords."%'");

        // return $this->json("200",[
        //         "searchresult"=>$list,
        //     ]);

        if(!empty($cid ))
            $where .= " and categoryid ='".$cid."'";

        
        $proData = ProductModel::searchProduct(["where"=>$where],"id,businessid,thumb,productname,categoryid,categoryname,marketprice,productprice,coin,transporttitle");

            //返回json数据
        return $this->json('200',['prolist'=>$proData]);

    }

	/**
	 * [cashAction 现金专区条件筛选]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-04T15:43:32+0800
	 * @return   [type]                   [description]
	 */
	public function cashAction(){
		$where = 'prouctprice > 0 AND bullamount = 0';
        $keywords = $this->params['keywords'];
        $cid = $this->params['cid'];

        if(empty($keywords)){
            return $this->json("404");
        }else{
            $where .= " AND (productname like '%".$keyswords."%' or categoryname like '%".$keyswords."%')";
        }

        //$list = Db::Table("ProProduct")->pageList("productname like '%".$keyswords."%' or categoryname like '%".$keyswords."%'");

        // return $this->json("200",[
        //         "searchresult"=>$list,
        //     ]);

        if(!empty($cid ))
            $where .= " and categoryid ='".$cid."'";

        
        $proData = ProductModel::searchProduct(["where"=>$where],"id,businessid,thumb,productname,categoryid,categoryname,marketprice,productprice,coin,transporttitle");

            //返回json数据
        return $this->json('200',['prolist'=>$proData]);
	}

	/**
	 * [bullAction 牛币专区筛选]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-04T15:45:35+0800
	 * @return   [type]                   [description]
	 */
	public function bullAction(){

		$where = 'prouctprice = 0 AND bullamount > 0';
        $keywords = $this->params['keywords'];
        $cid = $this->params['cid'];

        if(empty($keywords)){
            return $this->json("404");
        }else{
            $where .= " AND (productname like '%".$keyswords."%' or categoryname like '%".$keyswords."%')";
        }

        //$list = Db::Table("ProProduct")->pageList("productname like '%".$keyswords."%' or categoryname like '%".$keyswords."%'");

        // return $this->json("200",[
        //         "searchresult"=>$list,
        //     ]);

        if(!empty($cid ))
            $where .= " and categoryid ='".$cid."'";

       
        $proData = ProductModel::searchProduct(["where"=>$where],"id,businessid,thumb,productname,categoryid,categoryname,marketprice,productprice,coin,transporttitle");

            //返回json数据
        return $this->json('200',['prolist'=>$proData]);
	}

	/**
	 * [cashAndBullAction 现金加牛币专区筛选]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-04T15:46:48+0800
	 * @return   [type]                   [description]
	 */
	public function cashAndBullAction(){
		$where = 'prouctprice > 0 AND bullamount > 0';
       	$keywords = $this->params['keywords'];
        $cid = $this->params['cid'];

        if(empty($keywords)){
            return $this->json("404");
        }else{
            $where .= "  AND (productname like '%".$keyswords."%' or categoryname like '%".$keyswords."%')";
        }

        //$list = Db::Table("ProProduct")->pageList("productname like '%".$keyswords."%' or categoryname like '%".$keyswords."%'");

        // return $this->json("200",[
        //         "searchresult"=>$list,
        //     ]);

        if(!empty($cid ))
            $where .= " and categoryid ='".$cid."'";

        
        $proData = ProductModel::searchProduct(["where"=>$where],"id,businessid,thumb,productname,categoryid,categoryname,marketprice,productprice,coin,transporttitle");

            //返回json数据
        return $this->json('200',['prolist'=>$proData]);
	}
}