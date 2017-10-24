<?php
namespace app\api\controller\Demo;
use app\api\ActionController;

use app\api\model\ModelDemo;

use app\lib\Model;


class EsController extends ActionController
{
    /**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }

    public function insertAction(){

        $row = Model::ins("ProProduct")->getRow(["id"=>131]);
        print_r($row);

        var_dump(Model::Es("ProProduct")->insert($row));

        exit;
    }

    public function updateAction(){
        var_dump(Model::Es("ProProduct")->update(["categoryid"=>1],["id"=>53]));
        
    }

    public function listAction(){
        $param = array(
                "index"=>"pro_product",
                "type"=>"product",
                "size"=>"5",
                "fields"=>"id,productname",
                "sort"=>array("_score"=>"desc"),   //按匹配结果准确度排序
                
                "filter"=>'{
                            "term":{ 
                                "categoryid":28
                            }
                        }',  
                
                /*"query"   =>  '{
                            "match":{ "productname":"一升"}
                        }',*/
                
            );

        $where = [
            "size"=>"5",
            "fields"=>"id,productname",
            "sort"=>array("_score"=>"desc"),
            "filter"=>'{"term":{ "id":110}}',
        ];
        $list = Model::Es("ProProduct")->search($where);

        print_r($list);

        exit;
    }


    public function pagelistAction(){
        $param = array(
                "index"=>"pro_product",
                "type"=>"product",
                "size"=>"5",
                "fields"=>"id,productname",
                "sort"=>array("_score"=>"desc"),   //按匹配结果准确度排序
                
                "filter"=>'{
                        "bool":{
                            "must":[
                                {"term":{"enable":1}},
                                {"range": {
                                    "addtime": {
                                        "gte":  "2017-04-15 00:00:00",
                                        "lt":   "2017-04-16 00:00:00"
                                    }
                                }}
                            ]
                        }
                    }',   
                
                /*"query"   =>  '{
                            "match":{ "productname":"一升"}
                        }',*/
                
            );

        $where = [
            
                    //"id"=>"110",
                    "productname"=>["like","热水壶"],
                    "categoryname"=>["like","家用"],
        ];
        $list = Model::Es("ProProduct")->search($param);

        print_r($list);

        exit;
    }

    public function searchAction(){

        $keyword = "电器";

        //if($param['keyword']=='') return array("code"=>'3001');
        //print_r($param);

        $keyword = trim($keyword);


        //if($keyword=='')
            //return array("code"=>200,"desc"=>"","data"=>array());

        //查询

        $size = empty($param['size'])||$param['size']>30?10:$param['size'];
        $param['page'] = empty($param['page'])?1:$param['page'];
        $from = $size*($param['page']-1);
        
        //$sort = empty($param['sort'])?array("_score"=>"desc"):$param['sort'];

        /*
            排序: 人气(f_viewcount)，销量(f_sales)真实销量 + 虚拟销量，新品(f_news)，价格(f_productprice)
            
        */
        $sort = array();
        $sort["_score"] = "desc";
        $field_value_factor = "";
        if(!empty($param['sort'])){
           
            if(is_array($param['sort'])){
                $sort_tmp = $param['sort'];
            }elseif(is_string($param['sort'])){
                // 字符串格式 "f_productprice desc"
                $sort_arr = explode(" ", $param['sort']);
                $sort_tmp = array($sort_arr[0]=>$sort_arr[1]);
            }


            

            foreach($sort_tmp as $key=>$value){
                if(strtolower($value)=='desc'){
                    $factor=30;
                }else{
                    $factor=-20;
                }
                if($key == 'productprice')
                    $field_value_factor = '
                            "functions":[
                                '.$user_factor.'
                                {
                                    "field_value_factor": {
                                        "field":    "'.$key.'",
                                        "factor":   '.$factor.'
                                    }
                                }
                            ],
                            "boost_mode": "sum",
                            "score_mode": "sum"';
                
                
                break;
            }
             

        }

        $sort_key_arr = array_keys($sort);

        $sort_key = $sort_key_arr[0];

       
        $filter = '{"term":{"enable":1}},';

        if(!empty($brandid)&&$brandid>0)
            $filter.='{"term":{"brandid":'.$brandid.'}},';

        if(!empty($businessid)&&$businessid>0)
            $filter.='{"term":{"businessid":'.$businessid.'}},';

        //精品
        if($param['f_isspecial'] == 2)
            $filter.='{"term":{"f_isspecial":2}},';

        if(!empty($param['categoryid'])){
            $filter.='{"terms":{"categoryid":['.$categoryid.']}},';
        }

        //判断是否按"新品"排序，如果是需加上商品标识为新品的商品
        /*
        if(array_key_exists("f_news", $sort)){
            $filter.='{"term":{"f_productflag":3}},';
            $sort = array("f_addtime"=>$sort["f_news"]);
        }
        */
        
        $filter = substr($filter,-1,1)==','?substr($filter,0,-1):$filter;

        $fields = "id,productname,productprice,categoryname";

        //print_r($filter);
        

        $query = "";


        /*
        2016-07-27 隐藏关键词的搜索
        { 
            "match":{
                "f_filterkeyword":{
                    "query":"'.$keyword.'",
                    "minimum_should_match": "2",
                    "boost":3
                }
            }                                    
        },
        */
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

        print_r($data);
        //$ProProductspec = Db::DbTable('ProProductspec');
        /*
        foreach($data['list'] as $k=>$v){
            $data['list'][$k]['f_tag'] = 0;
            if(!empty($v['f_thumb'])){
                $data['list'][$k]['f_thumb'] = Img::url($v['f_thumb'], 200, 200);
            }            
            if(!empty($v['f_specialpic'])){
                $data['list'][$k]['f_specialpic'] = Img::url($v['f_specialpic']);
            }            

            if($v['f_productflag'] != 0){
                $data['list'][$k]['f_tag'] = $v['f_productflag'];
            }elseif($v['f_isspecial'] == 2){
                $data['list'][$k]['f_tag'] = 4;
            }

            unset($data['list'][$k]['f_productflag'], $data['list'][$k]['f_isspecial']);

            //
            $resl=ModelRednet::checkCustomerRednet($memberid);
            if($resl)
            {
                $deliData=$ProProductspec->getRow("f_productid='".$v['id']."'","f_first_deli_rednet","f_first_deli_rednet DESC");
                if(empty($deliData)||$deliData['f_first_deli_rednet']<=0)
                    $data['list'][$k]['f_deli_rednet']="";
                else
                    $data['list'][$k]['f_deli_rednet']="赚￥".floatval($deliData['f_first_deli_rednet']);
            }else
            {
                $data['list'][$k]['f_deli_rednet']="";
            }
            
        }

        Es::close();*/

        //通过gearman异步处理
        
        
        /*
        //记录搜索关键词
        self::addsearchkeyword(array(
                "keyword"=>$tmp_keyword!=''?$tmp_keyword."/".$keyword:$keyword,
                "memberid"=>$memberid,
                "type"=>"1", 
                "addtime"=>date("Y-m-d H:i:s"),
                "count"=>$data['total'], 
            ));
        */

        

        //return array("code"=>200,"desc"=>"","data"=>$data);
    }

    // 初始化数据
    public function bulkinsertAction(){
        
        $list = Model::ins("ProProduct")->getList([]);
        print_r($list);

        $obj = Model::Es("ProProduct");

        for($i=0;$i<count($list);$i++){
            $obj->insert($list[$i]);
        }
        echo "OK";

        exit;
    }
}
