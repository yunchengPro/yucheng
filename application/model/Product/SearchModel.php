<?php
// +----------------------------------------------------------------------
// |  [ 商品相关模型 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2017-03-03
// +----------------------------------------------------------------------
namespace app\model\Product;

use app\lib\Db;

use app\lib\Img;
use app\lib\Model;
//获取配置
use \think\Config;

class SearchModel{

    public function search($param){
        $keyword = $param['keywords'];

        //if($param['keyword']=='') return array("code"=>'3001');
        //print_r($param);

        $keyword = trim($keyword);


        //if($keyword=='')
            //return array("code"=>200,"desc"=>"","data"=>array());

        //查询

        $size = 20;
        $param['page'] = empty($param['page'])?1:$param['page'];
        $from = $size*($param['page']-1);
        
        $sort = empty($param['sort'])?array("_score"=>"desc"):$param['sort'];

        /*
            排序: 人气(viewcount)，销量(sales)真实销量 + 虚拟销量，新品(news)，价格(productprice)
            
        */
        $field_value_factor = '';
        if(!empty($param['price_sort'])){

                if(strtolower($param['price_sort'])=='desc'){
                    $factor=30;
                }else{
                    $factor=-20;
                }

                $factor_key = 'prouctprice';

                if($param['producttype']==3)
                    $factor_key = 'bullamount';
                
                $field_value_factor = '
                        "functions":[
                            {
                                "field_value_factor": {
                                    "field":    "'.$factor_key.'",
                                    "factor":   '.$factor.'
                                }
                            }
                        ],
                        "boost_mode": "sum",
                        "score_mode": "sum"';
        }

       
        $filter = '{"term":{"enable":1}},';

        if(!empty($param['producttype']) && in_array($param['producttype'], [1,2,3])){

            // 专区 1现金专区 2现金+牛豆专区 3牛豆专区
            switch ($param['producttype']) {
                case '1':
                    $filter.='{"range":{
                                    "prouctprice":{
                                        "gt":0
                                    }
                                }
                            },';
                    $filter.='{"term":{"bullamount":0}},';
                    break;
                case '2':
                    $filter.='{"range":{
                                    "prouctprice":{
                                        "gt":0
                                    }
                                }
                            },';
                    $filter.='{"range":{
                                    "bullamount":{
                                        "gt":0
                                    }
                                }
                            },';
                    break;
                 case '3':
                    $filter.='{"range":{
                                    "prouctprice":{
                                        "gt":0
                                    }
                                }
                            },';
                    $filter.='{"range":{
                                    "bullamount":{
                                        "gt":0
                                    }
                                }
                            },';
                    break;
                // case '3':
                //     $filter.='{"term":{"prouctprice":0}},';
                //     $filter.='{"range":{
                //                     "bullamount":{
                //                         "gt":0
                //                     }
                //                 }
                //             },';
                //     break;
            }

        }


        $categoryid_name = "categoryid";

        if(!empty($param['businessid']) && $param['businessid']>0 && is_numeric($param['businessid'])){

            $filter.='{"term":{"businessid":'.$param['businessid'].'}},';

            $categoryid_name = 'businesscategoryid';
        }

        if(!empty($param['cid']) && $param['cid']>0 && is_numeric($param['cid'])){

            if($categoryid_name=='categoryid'){
                $childcateid = Model::new("Product.Product")->getChildCategoryStr($param['cid']);

                $param['cid'] = $childcateid!=''?$childcateid.",".$param['cid']:$param['cid'];
            }

            if($categoryid_name=='businesscategoryid'){

                $childcateid = Model::new("Business.BusinessCategory")->getChildCategoryStr($param['businessid'],$param['cid']);

                $param['cid'] = $childcateid!=''?$childcateid.",".$param['cid']:$param['cid'];
            }            

            $filter.='{"terms":{"'.$categoryid_name.'":['.$param['cid'].']}},';
        }

        //判断是否按"新品"排序，如果是需加上商品标识为新品的商品
        /*
        if(array_key_exists("f_news", $sort)){
            $filter.='{"term":{"f_productflag":3}},';
            $sort = array("f_addtime"=>$sort["f_news"]);
        }
        */
        
        $filter = substr($filter,-1,1)==','?substr($filter,0,-1):$filter;

        //$fields = "id,productname,productprice,categoryname";
        $fields = "id,productname,thumb,prouctprice,bullamount,supplyprice";

        $query = "";


       
        if(!empty($keyword)){
            $query = '
                "query": {
                    "dis_max":{
                        "queries":[
                            {
                                "match":{
                                    "productname":{
                                        "query":"'.$keyword.'",
                                        "minimum_should_match": "2",
                                        "boost":6
                                    }
                                }
                            },
                            {
                                "match":{
                                    "categoryname":{
                                        "query":"'.$keyword.'",
                                        "minimum_should_match": "2",
                                        "boost":3
                                    }
                                }
                            }
                            
                        ],
                        "tie_breaker": 0.3
                    }
                },';
                //"modifier": "log",
            
            $query = $field_value_factor==''?substr($query,0,-1):$query;
        }

        $search_param = array(
                
                "size"=>$size,
                "from"=>$from,
                "fields"=>$fields,
                "sort"=>$sort,   //按匹配结果准确度排序
                //"highlight"=>"f_filterkeyword,f_productname",
                "filter"   =>'{
                                "bool":{
                                    "must":[
                                        '.$filter.'
                                    ]
                                }
                            }',
                "query"=>'
                {
                    "function_score": {
                        '.$query.'
                        '.$field_value_factor.'
                    }
                }
                ',
            );

        
        //print_r($search_param);
        
        //如果要优先用户购买过的商品，可以通过关键字去查用户的订单，返回商品编号，然后再放到主查询里面做优先处理
        //print_r($search_param);

        //$data = $_ES_OBJ->search($search_param);
        $data = Model::Es("ProProduct")->search($search_param);
        // print_r($data);
        return ["code"=>200,"data"=>$data,];
    }
}