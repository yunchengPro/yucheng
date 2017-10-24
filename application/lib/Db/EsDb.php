<?php
namespace app\lib\Db;

use app\lib\Db\BaseDb;

use think\Config;

use \think\Request;


class EsDb extends BaseDb{
	
	public $es = null;

    public $curl = null;

    protected $pagesize = 20;

    //连接状态
    public $connection = false;

    public $es_config = array();

    public function __construct(){
        //$es_config_path = DLERP_LIB_DIR.DS.'configs'.DS.'es.config.php';
        
        $es_config = Config::get("es.".$this->_db);
        if(!empty($es_config)){
            $this->es_config = $es_config;
        }else{
            echo "es config is not exists!";
            throw new \Exception("es config is not exists!");
        }
        // $this->_index = $_index;
        // $this->_type  = $_type;
    }

    /**
    * 设置curl配置
    * $param = array(
    *           index   库
    *           type    表
    *           url     url地址
    *           method  GET POST PUT
    *           data  array
    *       )
    */
    public function exec_curl($param){

        if(!empty($param['index'])) $this->_index = $param['index'];
        if(!empty($param['type'])) $this->_type  = $param['type'];

        //if(empty($this->_index) || empty($this->_type)) 
            //exit("index or type is empty");

        $url = $param['url']!=''?$param['url']:($this->es_config['host']."/".$this->_index."/".$this->_type.($param['id']!=''?"/".$param['id']:""));

        if(is_array($param['data']))
            $data = json_encode($param['data']);
        else
            $data = $param['data'];
        //echo $data;
        //print_r($param);
        // 初始化一个 cURL 对象
        //echo " start curl_int";
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $url); //设置请求的URL
        //echo " url:".$url."------\n";
        //echo $data."------";
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出 
       
        switch($param['method']){
            case 'GET':
                curl_setopt($this->curl, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($this->curl, CURLOPT_POST,true);   
                curl_setopt($this->curl, CURLOPT_POSTFIELDS,$data);
                break;
            case 'PUT':
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");   
                curl_setopt($this->curl, CURLOPT_POSTFIELDS,$data);
                break;
            case 'DELETE':
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");   
                curl_setopt($this->curl, CURLOPT_POSTFIELDS,$data);
                break;
        }


        //curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $param['method']); //设置请求方式
        //curl_setopt($this->curl, CURLOPT_POSTFIELDS, $param['data']);//设置提交的字符串  
        

        $result = curl_exec($this->curl);//执行预定义的CURL
        //echo " --execurl";
        //if(!curl_errno($this->curl)){ 
            //执行成功
            //$info = curl_getinfo($this->curl); 
            //print_r($info);
            //echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url']; 
        //} else { 
            //echo 'Curl error: ' . curl_error($this->curl)."<br>"; 
        //}
        //print_r($result);
        curl_close($this->curl);
        return json_decode($result,true);
    }

    public function getUrl($index,$type,$extend=''){
        $this->_index = $index;
        $this->_type  = $type;
        return $this->es_config['host']."/".$index."/".$type.($extend!=''?"/".$extend:"");
    }

    //get方式提交数据
    public function get($param){
        if(!empty($param)){
            $param['method']="GET";
            return $this->exec_curl($param);
        }
        return null;
    }

    //post方式提交数据
    public function post($param){
        if(!empty($param)){
            $param['method']="POST";
            return $this->exec_curl($param);
        }
        return null;
    }

    //put方式提交数据
    public function put($param){
        if(!empty($param)){
            $param['method']="PUT";
            return $this->exec_curl($param);
        }
        return null;
    }

    public function del($param){
        if(!empty($param)){
            $param['method']="DELETE";
            return $this->exec_curl($param);
        }
        return null;
    }

    /**
    * 添加数据
    * $param = array(
    *           index 库  可以在创建对象的时候初始化 可以可以在insert的时候初始化
    *           type  表  可以在创建对象的时候初始化 可以可以在insert的时候初始化
    *           id  主键编号
    *           data  array("name"=>"test","sex"=>"man")
    *       )
    */
    public function insert($param){
        if(!empty($this->_index) && !empty($this->_type) && !empty($param['id'])){
            $data = [];
            $data['url'] = $this->getUrl($this->_index,$this->_type,$param['id']);
            $data['data'] = $param;
            return $this->put($data);
        }else
            return null;
    }

    public function update($param,$where){
        if(!empty($this->_index) && !empty($this->_type) && !empty($where['id'])){
            $data = [];
            $data['url'] = $this->getUrl($this->_index,$this->_type,$where['id']."/_update");
            $data['data']= array('doc'=>$param);
            return $this->post($data);
        }else
            return null;
    }

    /*
    * 更新数据
    * $param = array(
    *   index 库  可以在创建对象的时候初始化 可以可以在insert的时候初始化
*       *   type  表  可以在创建对象的时候初始化 可以可以在insert的时候初始化
*        *   id  主键编号
        data array("name"=>"test")  
    );
    */
    public function delete($where){
        if(!empty($this->_index) && !empty($this->_type) && !empty($where['id'])){
            $data = [];
            $data['url'] = $this->getUrl($this->_index,$this->_type,$where['id']);
            return $this->del($data);
        }else
            return null;
    }

    /*
    * 批量操作 批量操作数保持在1000以内
    * $param = array(
    *           index 库  可以在创建对象的时候初始化 可以可以在insert的时候初始化
    *           type  表  可以在创建对象的时候初始化 可以可以在insert的时候初始化
    *           data  array(
    *                       //当文档不存在时创建之
    *                       array(
                                "action"=>"create",//必填
*                                   
                                "id"=>"",必填
*                                   "data"=>array(//必填
                                        "name"=>"test",
                                        "sex"=>"man",
                                    ),
    *                       ),
    *                       //创建新文档或替换已有文档----少用这种方式,这种方式自动生成主键id
    *                       array(
                                "action"=>"index",//必填
                                "id"=>"",必填
                                "data"=>array(//必填
                                        "name"=>"test",
                                        "sex"=>"man",   
                                ),
                            ),
    *                       //局部更新文档
    *                       array(
                                "action"=>"update",//必填
                                
                                "id"=>"",//必填
                                "data"=>array(//必填
                                        "name"=>"test",
                                        "sex"=>"man",   
                                ),
                            ),
    *                       //删除一个文档
    *                       array(
                                "action"=>"delete",//必填
                                
                                "id"=>"",//必填
                            ),
    *                   )
                    POST /_bulk
                    { "delete": { "_index": "website", "_type": "blog", "_id": "123" }} 
                    { "create": { "_index": "website", "_type": "blog", "_id": "123" }}
                    { "title":    "My first blog post" }
                    { "index":  { "_index": "website", "_type": "blog" }}
                    { "title":    "My second blog post" }
                    { "update": { "_index": "website", "_type": "blog", "_id": "123", "_retry_on_conflict" : 3} }
                    { "doc" : {"title" : "My updated blog post"} }
    *       )
    */
    public function bulk($param){
        if(!empty($param['index']) && !empty($param['type'])){
            $param['url'] = $this->getUrl($param['index'],$param['type'],"_bulk");
            $data_str = '';
            foreach($param['data'] as $data){
                if($data['action']=='create'){  
                    $data_str.='{"'.$data['action'].'":{'.(!empty($data['id'])?'"_id":"'.$data['id'].'"':"").'}}
                    ';
                    $data_str.=json_encode($data['data'])."
                    ";
                }else if($data['action']=='index'){
                    $data_str.='{"'.$data['action'].'":{'.(!empty($data['id'])?'"_id":"'.$data['id'].'"':"").'}}
                    ';
                    $data_str.=json_encode($data['data'])."
                    ";
                }else if($data['action']=='update'){
                    $data_str.='{"'.$data['action'].'":{"_id":"'.$data['id'].'"}}
                    ';
                    $data_str.=json_encode(array('doc'=>$data['data']))."
                    ";
                }else if($data['action']=='delete'){
                    $data_str.='{"'.$data['action'].'":{"_id":"'.$data['id'].'"}}
                    ';
                }
            }
            $param['data'] = $data_str;
            //print_r($param);
            //return ;
            return $this->post($param);
        }else
            return null;
    }

    /**
    * 查询
    * $param = array(
    *           "index"=>"dl_pro_product" 库  可以在创建对象的时候初始化 可以可以在insert的时候初始化
    *           "type"=>"product"         表  可以在创建对象的时候初始化 可以可以在insert的时候初始化
    *           "fields" =>"id,name"  //返回指定字段值 _source 为空时表示返回所有字段
    *           "size"=>"5", //每页多少条
    *           "from"=>"10", //从第几条开始
    *           "sort" 排序   array("date"=>"desc","status"=>"asc","_score"=>"desc") 多个排序 按顺序进行排序
    *           "query"  模糊查询 用于查询如：商品名称，商品内容，商品关键字
    *           "filter" 过滤条件 用于查询如：时间范围，是否删除，是否审核，提交人等条件
    *           "highlight"=>"name,content"高亮字段 或者 "_all" 表示所有字段
    *       );
    */

    /*
    query项写法说明：
            写法1：匹配某个字段：
            "query"   =>  '{
                            "match":{ "字段":"匹配内容"}
                        }'
                        相当于：where 字段 like '%匹配内容%'
                        operator 默认为or  可以设为and
                        使用方式：
                        "字段": {      
                            "query":    "匹配内容1 匹配内容2",
                            "operator": "and"
                        }
            或
            "query"   =>  '{
                                "multi_match": {
                                    "query":    "full text search",
                                    "fields":   [ "title", "body" ]
                                }
                            }'
                        相当于：where title like '%full text search%' and body like '%full text search%'
            写法2：合并条件
            "query"   =>   '{
                            "bool":{
                                "must":{ "match":{ "字段":"字段内容"}},      //必须包含
                                "must_no":{ "match":{ "字段":"字段内容"}},   //不包含
                                "should":{ "match":{ "字段":"字段内容"}}     //优先包含
                            }   
                        }'
            或
            "query"   =>   '{
                            "bool":{
                                "must":[
                                    { "match":{ "字段1":"字段内容1"}},
                                    { "match":{ "字段1":"字段内容1"}}
                                    ],     
                                "should":[
                                    { "match":{ "字段3":"字段内容3"}},
                                    { "match":{ "字段4":"字段内容4"}}
                                ]
                            }   
                        }'          
            
    filter写法说明
            "相等"过滤条件
            "filter"   =>   '{
                            "term":{ 
                                "age":30
                            }
                        }'
                        相当于：where age=30
            或
            "filter"   =>   '{
                            "terms":{ 
                                "tag":["search","full_text","nosql"]
                            }
                        }'      
                        相当于：where tag in('search','full_text','nosql')
            "范围"过滤条件
            "filter"   =>   '{
                            "range":{
                                "age":{
                                    "gte":25,
                                    "lt":30
                                }
                            }
                        }'
                        相当于：where age>=25 and age<30
                        gt::大于  gte::大于等于 lt::小于 lte::小于等于
            多条件
            "filter"   =>'{
                            "bool":{
                                "must":[
                                    {"term":{"f_isdelete":0}},
                                    {"term":{"f_isshelves":1}}
                                ]
                            }
                        }'
                        相当于：where f_isdelete=0 and f_isshelves=1
            "非"过滤条件
            "filter"   =>   '{
                            "exists":   {
                                "field":    "title"
                            }
                        }'
                        相当于：where title is null
    * 返回结果：array(
                    "total"=>$total,//总的匹配记录数
                    "list"=>$list, //当前返回的结果 二维数组
                );

    */
    public function search($param){
        //if($param['index']=='' || $param['type']=='') 
            //exit("index or type is null");

        $param['url']= $this->getUrl($this->_index,$this->_type,'_search');
        
        $search_josn = '';
        ///////////////分页///////////////
        if($param['size']>0) $search_json.=' "size":"'.intval($param['size']).'",';
        if($param['from']>0) $search_json.=' "from":"'.intval($param['from']).'",';
        /////////////////////////////////
        ////////////////指定字段////////////
        if(!empty($param['fields'])){
            $arr = explode(",",$param['fields']);
            $tmp = '';
            foreach($arr as $value){
                if($value!='')
                    $tmp.='"'.$value.'",';
            }
            $tmp = substr($tmp,0,-1);
            $search_json.=' "_source":['.$tmp.'],';
            $tmp = null;
            $arr = null;
        }
        ///////////////排序/////////////////
        if(!empty($param['sort']) && is_array($param['sort'])){
            $sort_tmp = '';
            foreach($param['sort'] as $key=>$value){
                if($key!='_geo_distance')
                    $sort_tmp.='{ "'.$key.'": { "order": "'.$value.'" }},';
                else
                    $sort_tmp.='{ "'.$key.'": '.json_encode($value).'},';
            }
            $sort_tmp = substr($sort_tmp,0,-1);
            if(count($param['sort'])==1){
                $search_json.=' "sort":'.$sort_tmp.',';
            }else{
                $search_json.=' "sort":['.$sort_tmp.'],';
            }
            $sort_tmp = null;
        }
        ///////////////查询//////////////////
        $param['query'] = trim($param['query']);
        $param['filter'] = trim($param['filter']);
        if(!empty($param['query']) || !empty($param['filter'])){
            $query_str = '';
            if($param['query']!='')
                $query_str.='"must":'.$param['query'].',';
            if($param['filter']!='')
                $query_str.='"filter":'.$param['filter'].',';
            $query_str = substr($query_str,0,-1);

            $search_json.='"query":{
                "bool":{
                    '.$query_str.'
                }
            },';
        } 
        /////////////////高亮////////////////
        if(!empty($param['highlight'])){
            $arr = explode(",",$param['highlight']);
            $tmp = '';
            foreach($arr as $value){
                if($value!=''){
                    $tmp.='"'.$value.'" : {},';
                }
            }
            $tmp = substr($tmp,0,-1);
            $search_json.='"highlight" : {
                                "pre_tags" : ["<tag1>", "<tag2>"],
                                "post_tags" : ["</tag1>", "</tag2>"],
                                "fields" : {
                                    '.$tmp.'
                                }
                            },';
        }
        $search_json =substr($search_json,-1,1)==','?substr($search_json,0,-1):$search_json;
        $search_json = '{'.$search_json.'}';
        //echo "(search_json:".$search_json.")";
        $result = $this->post(array('url'=>$param['url'],'data'=>$search_json));
      
        return $this->changeresult($result);
    }

    /**
     * [新的写法]
        没有query和filter之分
        直接只传query
     */
    public function search_new($param){
        //if($param['index']=='' || $param['type']=='') 
            //exit("index or type is null");

        $param['url']= $this->getUrl($this->_index,$this->_type,'_search');
        
        $search_josn = '';
        ///////////////分页///////////////
        if($param['size']>0) $search_json.=' "size":"'.intval($param['size']).'",';
        if($param['from']>0) $search_json.=' "from":"'.intval($param['from']).'",';
        /////////////////////////////////
        ////////////////指定字段////////////
        if(!empty($param['fields'])){
            $arr = explode(",",$param['fields']);
            $tmp = '';
            foreach($arr as $value){
                if($value!='')
                    $tmp.='"'.$value.'",';
            }
            $tmp = substr($tmp,0,-1);
            $search_json.=' "_source":['.$tmp.'],';
            $tmp = null;
            $arr = null;
        }
        ///////////////排序/////////////////
        if(!empty($param['sort']) && is_array($param['sort'])){
            $sort_tmp = '';
            foreach($param['sort'] as $key=>$value){
                if($key!='_geo_distance')
                    $sort_tmp.='{ "'.$key.'": { "order": "'.$value.'" }},';
                else
                    $sort_tmp.='{ "'.$key.'": '.json_encode($value).'},';
            }
            $sort_tmp = substr($sort_tmp,0,-1);
            if(count($param['sort'])==1){
                $search_json.=' "sort":'.$sort_tmp.',';
            }else{
                $search_json.=' "sort":['.$sort_tmp.'],';
            }
            $sort_tmp = null;
        }
        ///////////////查询//////////////////
        $param['query'] = trim($param['query']);

        if(!empty($param['query'])){
            $search_json.='"query":{
                "bool":{
                    "must":[
                       '.$param['query'].'
                    ]
                }
            },';
        } 
        /////////////////高亮////////////////
        if(!empty($param['highlight'])){
            $arr = explode(",",$param['highlight']);
            $tmp = '';
            foreach($arr as $value){
                if($value!=''){
                    $tmp.='"'.$value.'" : {},';
                }
            }
            $tmp = substr($tmp,0,-1);
            $search_json.='"highlight" : {
                                "pre_tags" : ["<tag1>", "<tag2>"],
                                "post_tags" : ["</tag1>", "</tag2>"],
                                "fields" : {
                                    '.$tmp.'
                                }
                            },';
        }
        $search_json =substr($search_json,-1,1)==','?substr($search_json,0,-1):$search_json;
        $search_json = '{'.$search_json.'}';
        //echo "(search_json:".$search_json.")";
        $result = $this->post(array('url'=>$param['url'],'data'=>$search_json));
      
        return $this->changeresult($result);
    }

    /*
    * 列表(二次封装)
    * $param = array(
    *           "index"=>"dl_pro_product" 库  可以在创建对象的时候初始化 可以可以在insert的时候初始化
    *           "type"=>"product"         表  可以在创建对象的时候初始化 可以可以在insert的时候初始化
    *           "fields" =>"id,name"  //返回指定字段值 _source 为空时表示返回所有字段
    *           "size"=>"5", //每页多少条
    *           "page"=>"0", //从第几页
    *           "sort" 排序   array("date"=>"desc","status"=>"asc","_score"=>"desc") 多个排序 按顺序进行排序
    *           "query"  模糊查询 用于查询如：商品名称，商品内容，商品关键字
    *           "filter" 过滤条件 用于查询如：时间范围，是否删除，是否审核，提交人等条件
    *           "highlight"=>"name,content"高亮字段 或者 "_all" 表示所有字段
    *       );
            filter 项的写法  (过滤项)
            'filter'=>array(
                        "age"=>"30"  //相当于age=30
                        "age"=>array(">"=>"10","<"=>"30"),  //相当于 age>10 and age<30
                        "f_categroy"=>"10", //相当于f_category=10
                        "f_category"=>array("11","21","121","141"), 相当于f_category in("11","21","121","141")

                    ),
                    gt::大于  gte::大于等于 lt::小于 lte::小于等于
            query 项的写法 (查询项)
            'query'=>array(
                        "title"=>"测试", //相当于 title like '%测试%'
                        "content"=>"测试"
                    ),
    */
    public function pageList($where,$field='',$order='',$flag=1,$page='',$pagesize=''){
    //public function getList($param){
        $param  = [];
        $param['fields'] = $field; // 字段

        if(!empty($order)){
            $param['sort'] = [];
            $sort_arr = explode(",",$order);
            foreach($sort_arr as $v){
                $tmp_arr = explode(" ",$v);
                $param['sort'][$tmp_arr[0]] = $tmp_arr[1];
            }
        }

        // --------------分页---------------
        $request    = Request::instance();
        $page       = !empty($page)?$page:$request->param('page');
        $page       = !empty($page)&&is_numeric($page)?$page:1;

        $pagesize   = !empty($pagesize)?$pagesize:$request->param('pagesize');
        $pagesize   = !empty($pagesize)&&is_numeric($pagesize)?$pagesize:$this->pagesize;

        $param['page'] = $page;
        $param['size'] = $pagesize;

        // -----------------------------
        $filter = [];
        $query  = [];
        foreach($where as $k=>$v){
            if(is_array($v)){
                if($v[0]=='like')
                    $query[$k] = $v[1];
                else
                    $filter[$k] = $v;
            }else{
                $filter[$k] = $v;
            }
        }
        
        $filter_str = '';
        if(!empty($filter) && is_array($filter)){
            foreach($filter as $key=>$value){
                if(is_array($value)){
                    if(in_array(key($value),array(">",">=","<","<="))){
                        $tmpstr='';
                        foreach($value as $k=>$v){
                            //gt::大于  gte::大于等于 lt::小于 lte::小于等于
                            if($k=='>') $k='gt';
                            if($k=='>=') $k='gte';
                            if($k=='<') $k='lt';
                            if($k=='<=') $k='lte';
                            $tmpstr.='"'.$k.'":"'.$v.'",';
                        }
                        $tmpstr = substr($tmpstr,0,-1);
                        $filter_str.='{"range":{"'.$key.'":{'.$tmpstr.'}}},';
                    }else{
                        $filter_str.='{"terms":{ "'.$key.'":'.json_encode($value).'}},';
                    }
                }else{
                    $filter_str.='{"term":{"'.$key.'":"'.$value.'"}},';
                }
            }

            $filter_str = substr($filter_str,-1,1)==','?substr($filter_str,0,-1):$filter_str;

        }

        $query_str = '';
        if(!empty($query) && is_array($query)){
            foreach($query as $key=>$value){
                $query_str.='{ "match":{ "'.$key.'":"'.$value.'"}},';   
            }
            $query_str = substr($query_str,-1,1)==','?substr($query_str,0,-1):$query_str;
        }

        $param["query"] = $filter_str.($filter_str!=''?",":"").$query_str;


        if($param['page']>=1){
            $param['from'] = ($param['page']-1)*$param['size'];
        }else{
            $param['from'] = 0;
        }

        return $this->search_new($param);
    }

    //结果处理
    public function changeresult($result){
        $list = array();
        
        foreach($result['hits']['hits'] as $item){
            if(!empty($item['highlight']))
                $item['_source']['highlight'] = $item['highlight'];
            $item['_source']['_score']  =   $item['_score'];
            $item['_source']['_id']     =   $item['_id'];
            if(!empty($item['sort']))
                $item['_source']['sort'] = $item['sort'];

            $list[]=$item['_source'];
        }
        //print_r($list);
        $return = array(
                "total"=>$result['hits']['total'],
                "list"=>$list,
            );
        return $return;
    }

    public function close(){
        //var_dump($this->redis);
        if(!empty($this->curl)){
            curl_close($this->curl);
            $this->curl = null;
            $this->connection = false;
        }
    }

    public function __destruct(){
        //关闭redis连接
        //$this->close();
    }

}
?>