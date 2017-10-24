<?php
namespace app\lib\Db;

use app\lib\Db\BaseDb;

use \think\Db as ThinkDb;
use \think\Request;
use \think\Config;
use \think\Session;
use app\lib\Model;

use app\lib\Log;

class MysqlDb extends BaseDb{
	
	protected $db_connect = array();

    protected $max_limit = 200;

    //默认每页记录数
    protected $pagesize = 20;

    //设置数据表字段
    protected $_fields = [];
    //设置自动填充字段
    protected $_auto = [];
    //验证规则
    protected $_valid = [];
    //验证不通过的错误信息
    protected $_error = "";

    protected $_db_config = [];

    /*
    * 表前缀
    */
    protected $_table_prefix = "";

    //是否开启操作日志
    protected $_sqllogflag = true; 
    protected $_sqllogdata = [];
    protected $_sqllogmodel = ['admin','superadmin'];  //实施记录的模块

	public function __construct() {
		parent::__construct();
	}

    public function connect(){
        if($this->_db =='')
            exit('db is empty');

        if($this->_tableName =='')
            exit('tablename is empty');

        if(empty($this->_db_config[$this->_db])){
        
            if(!empty($this->_db)){
                $this->_db_config[$this->_db] = Config::get("database.".$this->_db);
            }else{
                echo "mysql config is not exists!";
                throw new \Exception("mysql config is not exists!");
            }
        }

        if(empty($this->db_connect[$this->_db][$this->getTableName()])){
            $this->db_connect[$this->_db][$this->getTableName()] = ThinkDb::connect($this->_db_config[$this->_db]);
        }

        return $this->db_connect[$this->_db][$this->getTableName()];
            
    }

    /*
    * 获取表名
    */
    public function getTableName(){
        return $this->_table_prefix.$this->_tableName;
    }
    
    /*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
            //sql注入
            if(is_string($where) && !$this->checksql($where))
                return array();
            
            $parseWhere = $this->parseWhere($where);
            $parse_where  = $parseWhere['where'];
            $parse_values = $parseWhere['values'];

            $order = $this->parseOrder($order);
            if(!empty($parse_values))
                $select = $this->connect()->query('SELECT '.$field.' FROM '.$this->getTableName().' where '.$parse_where.' '.$order.' '.$otherstr.' limit 1',$parse_values);
            else
                $select = $this->connect()->query('SELECT '.$field.' FROM '.$this->getTableName().' where '.$parse_where.' '.$order.' '.$otherstr.' limit 1');
            
            return $select[0];
    }

    /*
    * 获取多行记录
    */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
        //if(!empty($where)){

            //sql注入
            if(is_string($where) && !$this->checksql($where))
                return array();
            
            $parseWhere = $this->parseWhere($where);
            $parse_where  = $parseWhere['where'];
            $parse_values = $parseWhere['values'];
            $order = $this->parseOrder($order);

            $limit_str = $limit>0?" limit ".$offset.",".($limit<=$this->max_limit?$limit:$this->max_limit):"";

            if(!empty($parse_values))
                $select = $this->connect()->query('SELECT '.$field.' FROM '.$this->getTableName().' where '.$parse_where.' '.$order.' '.$otherstr.' '.$limit_str,$parse_values);
            else
                $select = $this->connect()->query('SELECT '.$field.' FROM '.$this->getTableName().' where '.$parse_where.' '.$order.' '.$otherstr.' '.$limit_str);
            
            return $select;
        //}else{
            //return array();
        //}
    }

    /*
    * 分页列表
    * $flag = 0 表示不返回总条数
    */
    public function pageList($where,$field='',$order='',$flag=1,$page='',$pagesize=''){
        $request    = Request::instance();
        
    	$page       = !empty($page)?$page:$request->param('page');
        $page       = !empty($page)&&is_numeric($page)?$page:1;

        $pagesize   = !empty($pagesize)?$pagesize:$request->param('pagesize');
        $pagesize   = !empty($pagesize)&&is_numeric($pagesize)?$pagesize:$this->pagesize;

        $list = $this->getList($where,$field,$order,$pagesize,(($page-1)*$pagesize));

        if($flag==1){
            $count = $this->getRow($where,"count(*) as count");
            return array(
                        "total"=>$count['count'],
                        "list"=>$list,
                    );
        }else{
            return $list;
        }
    }

    /*
    * 分页列表
    * $flag = 0 表示不返回总条数
    */
    public function pageAllList($where,$field='',$order='',$page='1',$pagesize='',$flag=0){
        $page       = !empty($page)&&is_numeric($page)?$page:1;
        $pagesize   = !empty($pagesize)&&is_numeric($pagesize)?$pagesize:$this->pagesize;

        $list = $this->getList($where,$field,$order,$pagesize,(($page-1)*$pagesize));

        if($flag==1){
            $count = $this->getRow($where,"count(*) as count");
            return array(
                        "total"=>$count['count'],
                        "list"=>$list,
                    );
        }else{
            return $list;
        }
    }

    /*
    * 写入数据
    * $insertData = array() 如果ID是自增 返回生成主键ID
    */
    public function insert($insertData) {
        //检测数据库连接
        if(!empty($insertData)){

            if(array_key_exists("id", $insertData) && empty($insertData['id']))
                return false;

            $field   = "";
            $tmp_str = "";
            $values  = array();
            foreach($insertData as $key=>$value) {
                $field.="`".$key."`,";
                $tmp_str.="?,";
                $values[]=$value;
            }

            
            $sql = 'INSERT INTO '.$this->getTableName().' ('.substr($field,0,-1).') values ('.substr($tmp_str,0,-1).')';
            
            try{
                $return = $this->connect()->execute($sql, $values);
                if(!isset($insertData['id']))
                    return $this->connect()->getLastInsID();
                else
                    return $return?$insertData['id']:$return;

            } catch (\Exception $e) {
                // print_r($e);
                
                Log::add($e,__METHOD__,[
                        "sql"=>$sql,
                        "sqlvalue"=>$insertData,
                    ]);

                return false;
            }
        } else {
            return false;
        }
    }

    /*
    * 更新数据
    * $updateData = array()
    */
    public function update($updateData,$where,$limit=''){
        if(!empty($updateData) && !empty($where)){

            //操作日志
            $this->sqlactionlog(array(
                "action" =>'update',
                "data" => $updateData,
                "where"=> $where,
            ));

            $set_str = '';
            if(is_array($updateData)){
                foreach($updateData as $key=>$value){
                    $set_str.="`".$key."`='".$value."',";
                }
                $set_str = substr($set_str ,0,-1);
            }else{
                $set_str = $updateData;
            }

            $parseWhere = $this->parseWhere($where);
            $parse_where  = $parseWhere['where'];
            $parse_values = $parseWhere['values'];

            $limit_str = !empty($limit)?" limit ".$limit:"";

            $sql = "UPDATE ".$this->getTableName()." set ".$set_str." where ".$parse_where.$limit_str;

            try{
                
                if(!empty($parse_values))
                    $result = $this->connect()->execute($sql, $parse_values);
                else
                    $result = $this->connect()->execute($sql);

            } catch (\Exception $e) {
                // print_r($e);
                
                Log::add($e,__METHOD__,[
                        "sql"=>$sql,
                        "sqlvalue"=>$parse_values,
                    ]);

                return false;
            }


            $this->addsqllog(array(
                "action" =>'update',
                "data" => $data,
                "where"=> $where,
            ));

            return $result;

        }else{
            return false;
        }
    }

    /*
    * replace写入数据
    * $insertData = array()
    */
    public function replace($insertData){
        //检测数据库连接
        if(!empty($insertData)){
            
            $field   = "";
            $tmp_str = "";
            $values  = array();
            foreach($insertData as $key=>$value){
                $field.=$key.",";
                $tmp_str.="?,";
                $values[]=$value;
            }

            
            $sql = 'REPLACE INTO '.$this->getTableName().' ('.substr($field,0,-1).') values ('.substr($tmp_str,0,-1).')';

            $return = $this->connect()->execute($sql, $values);
            if(!isset($insertData['id']))
                return $this->connect()->getLastInsID();
            else
                return $return?$insertData['id']:$return;

        }else{
            return false;
        }
    }

    /*
    * 删除数据
    */
    public function delete($where,$limit=''){
        if(!empty($where)){

            if(!is_array($where))
                return false;

            //操作日志
            $this->sqlactionlog(array(
                "action" =>'delete',
                "where"=> $where,
            ));

            $parseWhere = $this->parseWhere($where);
            $parse_where  = $parseWhere['where'];
            $parse_values = $parseWhere['values'];

            $limit_str = !empty($limit)?" limit ".$limit:"";

            if(!empty($parse_values))
                $result = $this->connect()->execute("DELETE FROM ".$this->getTableName()." where ".$parse_where.$limit_str, $parse_values);
            else
                $result = $this->connect()->execute("DELETE FROM ".$this->getTableName()." where ".$parse_where.$limit_str);

            $this->addsqllog(array(
                "action" =>'delete',
                "where"=> $where,
            ));

            return $result;

        }else{

            return false;

        }
    }

    public function query(){
    }

    //开启事务
    public function startTrans(){
        $this->connect()->startTrans();
    }

    //提交事务
    public function commit(){
        $this->connect()->commit();
    }


    //回滚事务
    public function rollback(){
        $this->connect()->rollback();
    }

    /*
    * 分析where条件
    * @author zhuangqm
    * @date 2016-07-06
    * $where
    * 防注入
    */
    /*
      使用场景1:
      array(
          "f_categoryid"=>852,
          "f_moduleid"=>15,
      )
      等价于 f_categoryid=852 and f_moduleid=15
      
      使用场景2：
      array(
          "id"=>array(">",80),
          "catid"=>array("<=",81),
      )
      等价于 id>80 and catid<=81
        
      使用场景3：
      array(
          "id"=>array("in","1,2,45,60"),
          "id"=>array("in","select id from demo where test=1")
          id => array(
              array("in","1,2,45,60"),
              array("in","select id from demo where test=1"),
          ),
          "mobile"=>array("like","%150%"),
      )
      等价于 (id in (1,2,45,60) and id in (select id from demo where test=1)) and mobile like '%150%'

      使用场景4：
      array(
          f_addtime => array(
            array(">=","2016-04-19 00:00:00"),
            array("<=","2016-04-20 00:00:00","or"),
          ),
          "catid"=>array("<=",81),
      )
      等价于 (f_addtime>='2016-04-19 00:00:00' or f_addtime<='2016-04-20 00:00:00') and catid<=81

      使用场景5：
      array(
          "id"=>80,
          "catid"=>array("<=",81,'or'),
      )
      等价于 id=80 or (catid<=81)

      使用场景6：
      array(
          "f_addtime"=>array(
            array("=",""),
            array("=","0000-00-00 00:00:00","or"),
            array("<=","2016-04-20 00:00:00","or"),
          )
      )

      等价于 (f_addtime = '' or f_addtime='0000-00-00 00:00:00' or f_addtime <='2016-04-20 00:00:00')
      

      */
    protected function parseWhere($where){
        if(!empty($where)){

            $tmp_where  = '';
            $tmp_values = array();

            if(is_array($where)){

                $tmp_where  = "1=1";
                
                foreach($where as $k => $v){

                    if($k=='other_where_str'){
                        $tmp_where.=" ".$v;
                        continue;
                    }

                    if(!is_array($v)){
                        
                        $tmp_where.=" and $k=:".$k;
                        $tmp_values[$k]= $v;

                    }else{
                        /*
                        "id"=>array(">",80,"and | or"),
                        $v[0] 表示 >
                        $v[1] 表示 80
                        $v[2] 表示 and|or
                        */
                        if(!is_array($v[0])){
                            //字符串
                            if(strtolower($v[0])=='in'){

                                $tmp_where.=(isset($v[2])?" ".$v[2]:" and")." ".$k." in (".$v[1].")";
                                //$tmp_values[$k]= $v[1];
                            }else if(strtolower($v[0])=='like'){
                                $tmp_where.=(isset($v[2])?" ".$v[2]:" and")." ".$k." like :".$k; //相当于 and id>:id
                                $tmp_values[$k]= $v[1];

                            }else{

                                $tmp_where.=(isset($v[2])?" ".$v[2]:" and")." ".$k.$v[0].":".$k; //相当于 and id>:id
                                $tmp_values[$k]= $v[1];
                                
                            }
                        }else{
                            //数组
                            /*
                            "f_addtime"=>array(
                                    array("=",""),
                                    array("=","0000-00-00 00:00:00","or"),
                                    array("<=","2016-04-20 00:00:00","or"),
                              )
                            */
                            $tt_str = "";
                            $i = 1;
                            $andstr = '';
                            foreach($v as $tmp_v){
                                if(is_array($tmp_v)){

                                    //array("=",""),
                                    $tmp_k = $k."_".$i;
        
                                    $tmp_and_str = $i>1?(isset($tmp_v[2])&&$tt_str!=''?" ".$tmp_v[2]:" and"):"";

                                    if(strtolower($tmp_v[0])=='in'){
                                        $tt_str.=$tmp_and_str." ".$k." in (".$tmp_v[1].")";
                                        //$tmp_values[$tmp_k]= $tmp_v[1];
                                    }else if(strtolower($tmp_v[0])=='like'){
                                        $tmp_where.=$tmp_and_str." ".$k." like :".$tmp_k; //相当于 and id>:id
                                        $tmp_values[$tmp_k]= $tmp_v[1];
                                    }else{
                                        $tt_str.=$tmp_and_str." ".$k." ".$tmp_v[0]." :".$tmp_k; //相当于 and id>:id
                                        $tmp_values[$tmp_k]= $tmp_v[1];
                                    }
                                    //$tt_str.=(isset($tmp_v[2])&&$tt_str!=''?" ".$tmp_v[2]:" and")." ".$tmp_k.$tmp_v[0].":".$tmp_k; //相当于 and id>:id
                                    $i++;
                                    if($andstr=='')
                                        $andstr = isset($tmp_v[2])?$tmp_v[2]:" and ";
                                }
                            }
                            if(!empty($tt_str))
                                $tmp_where.=$andstr."(".$tt_str.")";
                        }

                    }
                }
            }else{

                $tmp_where = $where;

            }

            return array(
                    "where"=>$tmp_where,
                    "values"=>$tmp_values,
                );
        }else{
            return array(
                    "where"=>'1=1',
                    "values"=>array(),
                );
        }
    }

    /*
    * order by 分析
    * $order 可以是字符串，也可以是数组
    * $order = "addtime desc" | $order = array("addtime"=>'desc')
    * if $order == strig  return "'"
    */
    protected function parseOrder($order){
        if(!empty($order)){
            if(is_string($order)){

                return " ORDER BY ".$order;

            }

            if(is_array($order)){

                $tmp_str = "";

                foreach($order as $key=>$value){
                    $tmp_str.=$key." ".$value.",";
                }

                $tmp_str = substr($tmp_str,0,-1);
                return " ORDER BY ".$tmp_str;

            }
        }else{
            return '';
        }
    }

    /*
        负责把表单提交来的数组
        清除掉不用的单元
        留下与表的字段对应的单元
    */
    public function _facade($array = []){
        $data = [];
        foreach($array as $k=>$v){
            if(in_array($k, $this->_fields)){
                $data[$k] =$v;
            }
        }

        //自动填充
        if(!empty($this->_auto)){
            $data = $this->_autoFill($data);
        }

        return $data;
    }


    /*
        自动填充
        负责把表中需要值，而$_POST又没有传的字段给附上值
        如：addtime添加时间，则自动把time()的返回值赋过来
    */
    protected function _autoFill($data){
        foreach($this->_auto as $k=>$v){
            if(!array_key_exists($v[0],$data)){
                switch($v[1]){
                    case 'value':
                        $data[$v[0]] = $v[2];
                        break;
                    case 'function':
                        $data[$v[0]] = call_user_func($v[2]);
                        break;
                }

            }
        }

        return $data;
    }


    /*
        格式
        $this->_valid = [
            ['验证的字段名', '0/1/2(验证场景)', 'required/in(某几种情况)/between(范围)/length(某个范围)'],
        ];
    */

    public function _validate($data){
        if(empty($this->_valid)){
            return true;
        }

        $this->_error = "";

        foreach($this->_valid as $k=>$v){
            switch($v[1]){
                //必须检查
                case 1:
                    if(!isset($data[$v[0]]) || empty($v[0])){
                        $this->_error = $v[2];
                        return false;
                    }

                    if(!isset($v[4])) {
                        $v[4] = '';
                    }

                    if(!$this->check($data[$v[0]],$v[3],$v[4])) {
                        $this->_error = $v[2];
                        return false;
                    }

                    break;

                //有就检查，没有就跳过
                case 0:
                    if(isset($data[$v[0]])){
                        if(!$this->check($data[$v[0]], $v[3], $v[4])){
                            $this->_error = $v[2];
                            return false;
                        }
                    }

                    break;

                //如果有且非空，就检查
                case 2:
                    if(isset($data[$v[0]]) && !empty($data[$v[0]])){
                        if(!$this->check($data[$v[0]], $v[3], $v[4])){
                            $this->_error = $v[2];
                            return false;
                        }
                    }

                    break;

            }
        }

        return true;
    }


    protected function check($val,$rule='',$param=''){
        switch($rule){
            case 'required':
                return $val !== '';

            case 'number':
                return is_numeric($val);

            case 'in':
                $tmp = explode(",", $param);
                return in_array($val,$tmp);

            case 'between':
                list($min,$max) = explode(",", $param);
                return $val >= $min && $val <= $max;

            case 'length':
                list($min,$max) = explode(",", $param);
                $len = strlen($val);
                return $len >= $min && $len <= $max;

            default:
                return $this->regex($val,$rule);


        }
    }

    /**
     * 使用正则验证数据
     * @access public
     * @param string $value  要验证的数据
     * @param string $rule 验证规则
     * @return boolean
     */
    public function regex($value,$rule) {
        $validate = array(
            'email'     =>  '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
            'url'       =>  '/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(:\d+)?(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',
            'currency'  =>  '/^\d+(\.\d+)?$/',
            'zip'       =>  '/^\d{6}$/',
            'double'    =>  '/^[-\+]?\d+(\.\d+)?$/',
            'english'   =>  '/^[A-Za-z]+$/',
            'phone'     =>  '/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/',
        );
        // 检查是否有内置的正则表达式
        if(isset($validate[strtolower($rule)]))
            $rule       =   $validate[strtolower($rule)];
        return preg_match($rule,$value)===1;
    }

    public function _getError(){
        return $this->_error;
    }


    /*
    * 检查sql注入
    */
    public function checksql($str){
        $check=preg_match("/insert into|update |delete from|drop table|union |union all|load_file|outfile/i",$str);
        if($check)
            return false;
        else
            return true;
    }

    /*
    * sql 的update delete 操作的log 更新前的数据记录 和更新后的sql记录
    * $param  = array(
                "action" => update delete
                "data" => 更新的数据 update
                "where"=> where条件
        
            )
    */
    private function sqlactionlog($param){

        if(!$this->_sqllogflag || !in_array(BIND_MODULE, $this->_sqllogmodel)) return false;

        //update操作
        if($param['action'] =='update' && !empty($param['data']) && !empty($param['where'])){

            $field ='';
            if(is_array($param['data'])){
                foreach($param['data'] as $key=>$value){
                    $field.=$key.",";
                }
                $field = substr($field,0,-1);
            }else{
                //$field = $param['data'];
                preg_match_all('/,?([^,]*?)=(.*?),?/i',$param['data'],$match);
                foreach($match[1] as $tmp){
                    if($tmp!=''){
                        $field.=$tmp.",";
                    }
                }
                $field = $field!=''?substr($field,0,-1):"";
            }
            //echo "=====================$field==========================";
            if($field!=''){
                $row = $this->getRow($param['where'],"count(*) as count");
                
                if($row['count']==1){
                    //echo "=============aaaaaaaaaaaaaaaa=====".$param['where']."=============";
                    $this->_sqllogdata = $this->getRow($param['where'],$field);
                }elseif($row['count']>1){
                    $count      = 20;
                    $offset     = 0;   //从0位置开始
                    while(true){
                        $list = $this->getList($param['where'],$field,'',$count,$offset);
                        if(count($list)>0){
                            $this->_sqllogdata[] = $list;
                            $offset = $offset+$count;
                        }else{
                            break;
                        }
                    }
                }
            }
            
        }

        //delete操作
        if($param['action'] =='delete' && !empty($param['where'])){

            $row = $this->getRow($param['where'],"count(*) as count");
            
            if($row['count']==1){
                $this->_sqllogdata = $this->getRow($param['where'],'*');
            }elseif($row['count']>1){
                $count      = 20;
                $offset     = 0;   //从0位置开始
                while(true){
                    $list = $this->getList($param['where'],'*','',$count,$offset);
                    if(count($list)>0){
                        $this->_sqllogdata[] = $list;
                        $offset = $offset+$count;
                    }else{
                        break;
                    }
                }
            }
            
        }

        return true;
    }

    /*
    * 记录日志
    * $param  = array(
                "action" => update insert
                "data" => 更新的数据 update
                "where"=> where条件
        
            )
    */
    private function addsqllog($param){

        if(!$this->_sqllogflag || !in_array(BIND_MODULE, $this->_sqllogmodel)) return false;

        if(!empty($this->_sqllogdata)){
            $_SQLLOG        = Model::ins("SysSqlLog");
            $insertData = array(
                    "model"=>BIND_MODULE,
                    "action"=>$param['action'],
                    "createby"=>Session::get('customerid'),
                    "createtime"=>date("Y-m-d H:i:s"),
                    "path"=>Session::get('_path'),
                    "ip"=>$_SERVER["REMOTE_ADDR"],
                    "table"=>$this->_tableName,
                    "sql"=>json_encode(array(
                            "where"=>$param['where'],
                            "field"=>$param['data'],
                        ),JSON_UNESCAPED_UNICODE),
                    "content"=>json_encode($this->_sqllogdata,JSON_UNESCAPED_UNICODE),
                );
            $_SQLLOG->insert($insertData);
        }
    }

    // 关闭连接
    public function close(){
        $this->connect()->close();
    }
}
?>