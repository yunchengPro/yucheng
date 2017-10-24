<?php
/**
 * 公用方法文件
 */

use \think\Config;

/*
* 排序
*/
function SortTitle($field,$title,$sort_by='',$sort_order='') {
    $sort_order = strtoupper($sort_order);
    //$sort_order = $sort_order=='DESC'?"ASC":"DESC";
    $html = "<a href=\"javascript:listTable.sort('{$field}','{$sort_order}');\">{$title}";
    $order = isset($sort_order) ? strtolower($sort_order) : '';
    if (isset($sort_by) && strtolower($sort_by) == strtolower($field) && in_array($order,array('asc','desc'))){
        $html .= "<i class='sort_{$order}'></i>";
    }
    $html .= "</a>";
    return $html;
}

/*
* 缩略图
*/
function ShowThumb($path,$width='',$height=''){
    $res="";
    if(!empty($path))
    {
        if(empty($width)||empty($height))
            $res=Img::url($path, 100, 100);
        else
            $res=Img::url($path, $width,$height);
    }

    return $res;
    //return $path;
}

function uurl($method,$url,$data){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url); //设置请求的URL
    //echo " url:".$url."------";
    //echo $data;
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出 
   
    switch($method){
        case 'GET':
            curl_setopt($curl, CURLOPT_HTTPGET, true);
            break;
        case 'POST':
            curl_setopt($curl, CURLOPT_POST,true);   
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
            break;
        case 'PUT':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");   
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
            break;
        case 'DELETE':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");   
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
            break;
    }
    $result = curl_exec($curl);//执行预定义的CURL
    curl_close($curl);
    return $result;
}

/*
* 图片地址转换
*/
class Img{

    /**
    * 图片相对地址转为绝对地址
    */
    public static function url($url,$width='',$height=''){
        /*
        if($url=='') return '';
        //$pathinfo = pathinfo($url);
        $config = Config::get("image");
        $domain = $config['domain'];
        
        return strpos($url,"http://")===false?$domain.$url:$url;
        */

        if($url=='') return $url;
        
        $pathinfo = pathinfo($url);


        //获取域名+获取域名配置
        $config = Config::get("image");
        $domain = $config['domain'];
        
        /*
        if(!empty($width) || !empty($height) || !empty($other)){
            //$url.="_".$width."x".$height.".".$pathinfo['extension'];
            if($pathinfo['extension']!=''){
                $url.="@";
                $tmp = '';
                if($width!='')
                    $tmp.="_".$width."w";
                if($height!='')
                    $tmp.="_".$height."h";
                if($other!='')
                    $tmp.=$other;
                //$url.=(substr($tmp, 0,1)=='_'?substr($tmp,1):$tmp).".".$pathinfo['extension'];
                $url.=substr($tmp, 0,1)=='_'?substr($tmp,1):$tmp;
            }
        }else{
            $url.=strpos($url, "@")===false&&$compress?"@70q":"";
        }
        */
        if(strpos($url,"http://")===false && strpos($url,"https://")===false){

            if((!empty($width) || !empty($height))){
                $pathinfo = pathinfo($url);
                //$url.="_".$width."x".$height.".".$pathinfo['extension'];
                //1479528302940.jpg?x-oss-process=image/resize,m_lfit,h_100,w_100
                if($pathinfo['extension']!=''){
                    $url.="?x-oss-process=image/resize,m_lfit";
                    if($width!='')
                        $url.=",w_".$width;
                    if($height!='')
                        $url.=",h_".$height;
                    if($other!='')
                        $url.=$other;
                }
            }else{
                $url.=strpos($url, "?x-oss-process=image/resize")===false?"?x-oss-process=image/quality,q_80":"/quality,q_80";
            }
        }

        if(strtolower(substr($url,0,2))=='//')
            $url = "http:".$url;
        
        //随机域名
        if($randdomain && strpos($url,self::$domain['original'])!==false){
            $url = str_replace(self::$domain['original'],"",$url); // 原始域名
            $url = substr($url,0,1)=='/'?substr($url,1):$url;
        }

        $url = substr($url,0,1)=='/'?substr($url,1):$url;
        return strpos($url,"http://")===false?$domain.$url:$url;
    } 


}

/*
* 前端方法
*/
class Html{

    public static function text($param){
        $html.="<input type=\"text\"";
        $html.=" id=\"".($param['id']!=''?$param['id']:$param['name'])."\"";
        $html.=" name=\"".$param['name']."\"";
        $html.=" class=\"input-text width250 ".($param['class']!=""?$param['class']:"")."\"";
        //radius 圆角 
        $html.=" value=\"".$param['value']."\"";
        if(!empty($param['disabled'])){
            $html.=" disabled=\"".$param['disabled']."\"";
        }
        $html.=$param['placeholder']!=''?" placeholder=\"".$param['placeholder']."\"":"";
        $html.=$param['onclick']!=''?" onclick=\"".$param['onclick']."\"":"";
        $html.=$param['other']!=''?" ".$param['other']:"";
        $html.=">";
        return $html;
    }

    public static function password($param){
        $html.="<input type=\"password\"";
        $html.=" id=\"".($param['id']!=''?$param['id']:$param['name'])."\"";
        $html.=" name=\"".$param['name']."\"";
        $html.=" class=\"input-text width250 ".($param['class']!=""?$param['class']:"")."\"";
        $html.=" value=\"".$param['value']."\"";
        $html.=$param['placeholder']!=''?" placeholder=\"".$param['placeholder']."\"":"";
        $html.=$param['onclick']!=''?" onclick=\"".$param['onclick']."\"":"";
        $html.=$param['other']!=''?" ".$param['other']:"";
        $html.=">";
        return $html;
    }

    /*
    隐藏表单
    $param = array(
                "name"=>"keyword",
                "id"=>"",  //id可以不传，不传默认使用name
                "value"=>"", //值
            )
    */
    public static function hidden($param){
        $html.="<input type=\"hidden\"";
        $html.=" id=\"".($param['id']!=''?$param['id']:$param['name'])."\"";
        $html.=" name=\"".$param['name']."\"";
        $html.=" value=\"".$param['value']."\"";
        $html.=">";
        return $html;
    }

    /*
    A连接
    $param = array(
                "name"=>"keyword",
                "value"=>"", //值
                "type"=> "" 默认是普通的按钮   "popup","confirm","newpage"
                "id"=>"keyword",  //id可以不传，不传默认使用name

                "class"=>"", //样式
                "onclick"=>"", //onclick事件
    
                "_title"=>"",标题
                "_url"=>"",连接地址
                "other"=>  //其他的标签内容 可以自定义
            )
    */
    public static function a($param){
        //$type = $param['type']!=''?$param['type']:"";
        $width = $param['_width']!=""?$param['_width']:"450";
        $height = $param['_height']!=""?$param['_height']:"220";
        $html.="<a";
        $html.=$param['_rel']!=''?" _rel=\"".$param['_rel']."\"":($param['type']!=''?" _rel=\"".$param['type']."\"":"");
        $html.=" id=\"".($param['id']!=''?$param['id']:$param['name'])."\"";
        $html.=" name=\"".$param['name']."\"";
        $html.=" class=\"underline ".($param['class']!=''?$param['class']:"")."\"";
        $html.=" _title=\"".$param['_title']."\""; 
        $html.=" title=\"".$param['_title']."\""; 
        if($param['_url']!='')
            $html.=" _url=\"".$param['_url']."\""; //替换
        $html.=" _width=\"".$width."\"";
        $html.=" _height=\"".$height."\""; 
        $html.=$param['onclick']!=''?" onclick=\"".$param['onclick']."\"":"";
        $html.=$param['other']!=''?" "." ".$param['other']:"";
        $html.=">";
        $html.=$param['value'];
        $html.="</a>";
        return $html;
    }


    /*
    按钮
    $param = array(
                "name"=>"keyword",
                "value"=>"", //值
                "type"=> "" 默认是普通的按钮   "popup","confirm","newpage"
                "id"=>"keyword",  //id可以不传，不传默认使用name

                "class"=>"", //样式
                "onclick"=>"", //onclick事件
    
                "_title"=>"",标题
                "_widht"=>"", 宽度
                "_height"=>"",高度
                "_url"=>"",连接地址
                "other"=>  //其他的标签内容 可以自定义
            )
    */
    public static function button($param){
        $type = $param['type']!=''?$param['type']:"button";
        $width = $param['_width']!=""?$param['_width']:"450";
        $height = $param['_height']!=""?$param['_height']:"220";
        
        $html.="<button";
        $html.=$param['_rel']!=''?" _rel=\"".$param['_rel']."\"":($param['type']!=''?" _rel=\"".$param['type']."\"":"");
        $html.=" id=\"".($param['id']!=''?$param['id']:$param['name'])."\"";
        $html.=" name=\"".$param['name']."\"";
        $html.=" class=\"btn btn-default ".($param['class']!=''?$param['class']:"")."\"";
        $html.=" _title=\"".$param['_title']."\"";
        $html.=" _width=\"".$width."\"";
        $html.=" _height=\"".$height."\"";  
        $html.="type=\"".$type."\"";  
        if($param['_url']!='')
            $html.=" _url=\"".$param['_url']."\""; //替换

        $html.=$param['onclick']!=''?" onclick=\"".$param['onclick']."\"":"";
        $html.=$param['other']!=''?" "." ".$param['other']:"";
        $html.=">";
        $html.=$param['value'];
        $html.="</button>";
        return $html;
    }


    /*
    内容编辑框
    $param = array(
                "name"=>"keyword",
                "value"=>"", //值


                "id"=>"keyword",  //id可以不传，不传默认使用name
                "rows"=>"3",   //行数
                "cols"=>"2",   //列数
                
                "class"=>"", //样式
                "onclick"=>"", //onclick事件
                "other"=>  //其他的标签内容 可以自定义
            )
    */
    public static function textarea($param){
        $html.="<textarea ";
        $html.=$param['rows']!=''?" rows=\"".$param['rows']."\"":"";
        $html.=$param['cols']!=''?" cols=\"".$param['cols']."\"":"";
        $html.=" id=\"".($param['id']!=''?$param['id']:$param['name'])."\"";
        $html.=" name=\"".$param['name']."\"";
        $html.=" class=\"".($param['class']!=""?$param['class']:"textarea")."\"";
        $html.=$param['click']!=''?" onclick=\"".$param['onclick']."\"":"";
        $html.=$param['other']!=''?" "." ".$param['other']:"";
        $html.=">";
        $html.=$param['value'];
        $html.="</textarea>";
        return $html;
    }

    /*
    * 时间控件
    * $param = array(
                "name"=>"addtime",
                "value"=>"", //值 如果输入 "now" 默认当前时间
                "dateFmt"=> 1数字 或者'yyyy-MM-dd' 默认年月日     1:yyyy-MM-dd （年月日） 2:yyyy-MM-dd HH:mm:00 3:yyyy-MM（年月） 4:yyyy（年） 
                "id"=>"keyword",  //id可以不传，不传默认使用name
                
                "class"=>"", //样式
                "readonly"=>true | false
                "width"=>"",  //宽度 默认100px
                "style"=>"",  //附加样式
                "WdatePicker"=>"" , //时间控件附加配置 如 maxDate:'#F{$dp.$D(\'endtime\')}'
                "placeholder"=> "" 
                "other"=>  //其他的标签内容 可以自定义
            )
    */
    public static function time($param){
        
        $html.="<input type=\"text\"";
        $html.=" id=\"".($param['id']!=''?$param['id']:$param['name'])."\"";
        $html.=" name=\"".$param['name']."\"";
        $html.=" class=\"input-text width250 ".($param['class']!=''?$param['class']:'')."\"";
        // class radius 圆角
        $dateFmt ='yyyy-MM-dd';
        $valueFmt = 'Y-m-d';
        if(!empty($param['dateFmt'])){
            if(is_numeric($param['dateFmt'])){
                switch($param['dateFmt']){
                    case 1:
                        $dateFmt  = 'yyyy-MM-dd';
                        $valueFmt = 'Y-m-d';
                        break;
                    case 2:
                        $dateFmt  = 'yyyy-MM-dd HH:mm:00';
                        $valueFmt = 'Y-m-d H:i:00';
                        break;
                    case 3:
                        $dateFmt  = 'yyyy-MM';
                        $valueFmt = 'Y-m';
                        break;
                    case 4:
                        $dateFmt  = 'yyyy';
                        $valueFmt = 'Y';
                        break;
                    case 5:
                        $dateFmt  = 'HH:mm:ss';
                        $valueFmt = '';
                        break;
                    case 6:
                        $dateFmt  = 'MM-dd';
                        $valueFmt = '';
                        break;

                }
            }else{
                $dateFmt =$param['dateFmt'];
                if($dateFmt=='yyyy-MM-dd')
                    $valueFmt='Y-m-d';
                else if($dateFmt=='yyyy-MM-dd HH:mm:00')
                    $valueFmt='Y-m-d H:i:00';
                else if($dateFmt=='yyyy-MM')
                    $valueFmt='Y-m';
                else if($dateFmt=='yyyy')
                    $valueFmt='Y';
            }
        }

        $minDate = "";
        if(!empty($param['minDate'])){
            $minDate = ",minDate:'{$param['minDate']}'";
        }

        $maxDate = "";
        if(!empty($param['maxDate'])){
            $maxDate = ",maxDate:'{$param['maxDate']}'";
        }


        if(!empty($param['WdatePicker'])){
            $WdatePicker = $param['WdatePicker'];
            $WdatePicker = substr($WdatePicker,0,1)!=','?",".$WdatePicker:$WdatePicker;
            $WdatePicker = substr($WdatePicker,-1,1)==','?substr($WdatePicker,0,-1):$WdatePicker;
        }
        $html.=" onFocus=\"WdatePicker({lang:'zh-cn',dateFmt:'".$dateFmt."'".$WdatePicker.$minDate.$maxDate."})\"";
        $html.=" value=\"".($param['value']=='now'?date($valueFmt):$param['value'])."\"";
        //$html.=" style=\"".($param['width']!=''?"width:".$param['width'].";":"").($param['style']!=''?$param['style'].";":"")."\"";
        $html.=$param['readonly']?" readonly=\"readonly\"":"";
        $html.=$param['other']!=''?" "." ".$param['other']:"";
        $html.=$param['placeholder']!=''?" placeholder=\"".$param['placeholder']."\"":"";
        $html.=">";
        return $html;
        
    }

    /*
    * 时间范围
    * $param = array(
                "start"=>"", //开始时间控件名
                "start_value"=> //开始时间值
                "end"=>"", //结束时间控件名
                "end_value"=>"" //结束时间值
                ////////////////////////////////////
                "dateFmt"=> 1数字 或者'yyyy-MM-dd' 默认1-年月日     1:yyyy-MM-dd （年月日） 2:yyyy-MM-dd HH:mm:00 3:yyyy-MM（年月） 4:yyyy（年） 
                "maxminflag"=>false 默认false  用于设置开始时间不能大于结束时间，结束时间不能小于开始时间
                "class"=>"", //样式
                "width"=>"",  //宽度 默认100px
                "style"=>"",  //附加样式
                "readonly"=>true | false
                "placeholder"=> "" 
                "other"=>  //其他的标签内容 可以自定义
                "space"=>"-" time控件之间的连接符 默认“-”
            )
    */
    public static function times($param){

        $maxminflag = isset($param['maxminflag'])?$param['maxminflag']:false;

        $temp = $param;
        $temp['name']=$param['start'];
        $temp['value']=$param['start_value'];
        $temp['placeholder']=$param['placeholder']."-开始";
        $temp['minDate'] = $param['minDate'];
        if($maxminflag)
            $temp['WdatePicker']="maxDate:'#F\{\$dp.\$D(\'".$param['end']."\')\}'";
        $html.=self::time($temp);
        $temp = null;
        
        $html.=$param['space']!=''?$param['space']:" - ";

        $temp = $param;
        $temp['name']=$param['end'];
        $temp['value']=$param['end_value'];
        $temp['placeholder']=$param['placeholder']."-结束";
        $temp['maxDate'] = $param['maxDate'];
        $temp['other'] = $param['other'];
        $temp['class'] = $param['class'];
        if($maxminflag)
            $temp['WdatePicker']="minDate:'#F\{\$dp.\$D(\'".$param['start']."\')\}'";
       
        $html.=self::time($temp);
        
        $temp = null;
        return $html;
    }

    /*
    select 控件 
    $param = array(
            "name"
            "id"=>"keyword",  //id可以不传，不传默认使用name
            "option"=>array("1"=>"选项1","2"=>"选项2")
            "top_option"=>"" 顶部默认的选项 string或者bool值 默认为 true 并且内容为:"====请选择===="值为空，也可以自定义，传false表示不设该option
            "value"  值
            "class"=>"", //样式
            "other"=>  //其他的标签内容 可以自定义
        );
    */
    public static function select($param){
        $html.="<select";
        $html.=" id=\"".($param['id']!=''?$param['id']:$param['name'])."\"";
        $html.=" name=\"".$param['name']."\"";
        $html.=" class=\"select width250 ".($param['class']!=''?$param['class']:"")."\"";
        $html.=$param['click']!=''?" onclick=\"".$param['onclick']."\"":"";
        $html.=$param['other']!=''?" "." ".$param['other']:"";
        $html.=">";
        
        $top_option = true;
        $top_option_value="==请选择==";
        if(isset($param['top_option'])){
            if(is_bool($param['top_option'])){
                $top_option = $param['top_option'];
            }else{
                $top_option = true;
                $top_option_value = $param['top_option'];
            }       
        }
        if($top_option==true){
            $html.="<option value=''>".$top_option_value."</option>";  
        }
        if(is_array($param['option'])){
            $value_arr = array();
            if(is_array($param['value']))
                $value_arr = $param['value'];
            else if($param['value']!=='')
                $value_arr = explode(",",$param['value']);

            if(count($value_arr)==1){
                foreach($param['option'] as $key=>$value){
                    $html.="<option value=\"".$key."\"".($value_arr[0]!=''&&$key==$value_arr[0]?" selected":"").">".$value."</option>";
                }

            }elseif(count($value_arr)>1){
                foreach($param['option'] as $key=>$value){
                    $html.="<option value=\"".$key."\"".(in_array($key, $value_arr)?" selected":"").">".$value."</option>";
                }
            }else{
                foreach($param['option'] as $key=>$value){
                    $html.="<option value=\"".$key."\">".$value."</option>";
                }
            }
        }
        //$html.=" value=\"".$param['value']."\"";
        
        $html.="</select>";
        return $html;
    }

    /*
     radio 控件
     $param = array(
            "name"=>"keyword",    没有id属性
            "terms"=>"", string 或者 array 
                         string 表示生成一个radio，<input type='radio' name='' value='$radio'>$radio
                         array表示生成多个radio
                         array("类型1"=>"类型1","类型2"=>"类型2","类型3"=>"类型3") 
                         表示 <input type='radio' name='' value='类型1'>类型1 <input type='radio' name='' value='类型2'>类型2 
                         或者
                         array("1"=>"类型1","2"=>"类型2","3"=>"类型3")
                         表示 <input type='radio' name='' value='1'>类型1 <input type='radio' name='' value='2'>类型2 
            "value"=>    选中值
            "valueshow"=>true |false  默认true 表示radio后面是否跟着显示value 
            "class"=>"", 
            "onclick"=>"", //onclick事件
            "other"=> ""  //其他的标签内容 可以自定义
            "space"=> "&nbsp;" radio 与radio之间的间距符，默认是&nbsp; 也可以自己定义       
        );
    */
    public static function radio($param){

        $radioarr = array();
        if(is_array($param['terms'])){
            $radioarr = $param['terms'];
        }else{
            $radioarr = array($param['terms']=>$param['terms']);
        }
        $valueshow = true;
        if(isset($param['valueshow']))
            $valueshow = $param['valueshow'];
        $count = count($radioarr);
        foreach($radioarr as $key=>$value){
            $html.="<input type=\"radio\"";
            $html.=" class=\"".$param['class']."\"";
			$html.=" name=\"".$param['name']."\"";
            $html.=$param['click']!=''?" onclick=\"".$param['onclick']."\"":"";
            $html.=$param['other']!=''?" "." ".$param['other']:"";
            $html.=" value=\"".$key."\"";
            $html.=$key==$param['value']?" checked=\"checked\"":"";
            $html.=">";
            if($valueshow)
                $html.=" ".$value;
            if($count>1)
                $html.=$param['space']==''?"&nbsp;":$param['space'];
        }
        return $html;
    }

    /*
     checkbox 控件
     $param = array(
            "name"=>"keyword",    没有id属性 也可以 "keyword[]"
            "terms"=>"", string 或者 array 
                         string 表示生成一个radio，<input type='radio' name='' value='$radio'>$radio
                         array表示生成多个checkbox
                         array("类型1"=>"类型1","类型2"=>"类型2","类型3"=>"类型3") 
                         表示 <input type='radio' name='' value='类型1'>类型1 <input type='radio' name='' value='类型2'>类型2 
                         或者
                         array("1"=>"类型1","2"=>"类型2","3"=>"类型3")
                         表示 <input type='radio' name='' value='1'>类型1 <input type='radio' name='' value='2'>类型2 
            "value"=>   选中值 string or array  string 可以是"1,4,5" array("1","4","5")
            "valueshow"=>true |false  默认true 表示radio后面是否跟着显示value 
            "class"=>"", 
            "onclick"=>"", //onclick事件
            "other"=> ""  //其他的标签内容 可以自定义
            "space"=> "&nbsp;" checkbox 与checkbox之间的间距符，默认是&nbsp; 也可以自己定义        
        );
    */
    public static function checkbox($param){
        $checkboxarr = array();
        if(is_array($param['terms'])){
            $checkboxarr = $param['terms'];
        }else{
            $checkboxarr = array($param['terms']=>$param['terms']);
        }
        $valuearr = array();
        if(isset($param['value'])){
            if(is_array($param['value']))
                $valuearr = $param['value'];
            else
                $valuearr = explode(",",$param['value']);
        }

        $valueshow = true;
        if(isset($param['valueshow']))
            $valueshow = $param['valueshow'];
        $count = count($checkboxarr);
        foreach($checkboxarr as $key=>$value){
            $html.="<input type=\"checkbox\"";
            //$html.=" id=\"".($param['id']!=''?$param['id']:$param['name'])."\"";
            $html.=" name=\"".$param['name']."[]\"";
            $html.=" class=\"".$param['class']."\"";
            $html.=$param['click']!=''?" onclick=\"".$param['onclick']."\"":"";
            $html.=$param['other']!=''?" "." ".$param['other']:"";
            $html.=" value=\"".$key."\"";
            $html.=in_array($key, $valuearr)?" checked=\"checked\"":"";
            $html.=">";
            if($valueshow)
                $html.=" ".$value;
            if($count>1)
                $html.=$param['space']==''?"&nbsp;":$param['space'];
        }
        return $html;
    }

    /*
     文本编辑框
     $param = array(
            "name"
            "id"=>"keyword",  //id可以不传，不传默认使用name
            "value"=>"" 初始化内容
            "width"=>"" 宽度 默认 100%
            "height"=>"" 高度  默认300 
            "config"=>"" 配置 如"customDomain:true,saveInterval:300"
        );
    */
    public static function edit($param){
        $param['id'] = $param['id']!=''?$param['id']:$param['name'];
        $html.="<script id=\"".$param['id']."\" name=\"".$param['name']."\" type=\"text/plain\">\n";
        $html.=$param['value'];
        $html.="</script>\n";
        //$html.="<script charset=\"utf-8\" src=\"/Ueditor/_examples/addCustomizeDialog.js\"></script>";
        $html.="<script charset=\"utf-8\" src=\"/Ueditor/_examples/addCustomizebutton.js\"></script>";
        //$html.="<script charset=\"utf-8\" src=\"/Ueditor/_examples/addCustomizeService.js\"></script>";
        $html.="<script type=\"text/javascript\">";
        $html.=" var ue_".$param['name']."=UE.getEditor('".$param['id']."',{";

        //配置
        $config = '';
        $config.="initialFrameWidth:'".($param['width']!=''?$param['width']:"100%")."',";
        $config.="initialFrameHeight:'".($param['height']!=''?$param['height']:"300")."',";
        if(isset($param['config'])) $config.=$param['config'];
        $html.=substr($config,-1,1)==','?substr($config,0,-1):$config;
        $html.="});";
        $html.="</script>\n";

        return $html;
    }

    
    
    /*
     上传控件
     $param = array(
            
        );
    */
    public static function upload($param){
        return $html;
    }

    /*
     地区控件
     前提：页面先加载js：<script src="/js/linkage_address.js"></script>
     $param = array(
                "name"=>"",  //生成的省市县的各个下拉name为：$name."_province",$name."_city",$name."_county"
                "value"=>"", //值
                "class"=>""   默认input
                "other"=> ""  //其他的标签内容 可以自定义
            );
    */
    public static function area($param){
        $class      = $param['class']!=''?$param['class']:"input";
        $province   = $param['name']."_province";
        $city       = $param['name']."_city";
        $county     = $param['name']."_county";
        $class      = $param['class']!=''?$param['class']:"select width250";

        $html.="<select name=\"".$province."\" id=\"".$province."\" class=\"".$class."\" ".$param['other'].">";
        $html.="</select>";
        $html.="<select name=\"".$city."\" id=\"".$city."\" class=\"".$class."\" ".$param['other'].">";
        $html.="</select>";
        $html.="<select name=\"".$county."\" id=\"".$county."\" class=\"".$class."\" ".$param['other'].">";
        $html.="</select>";
        $html.="<script type=\"text/javascript\">";
        $html.='$(document).ready(function(){';
        $html.="iniarea('".$province."','".$city."','".$county."','".$param['value']."');";
        $html.='});';
        $html.="</script>";
        return $html;
    }

    public static function fileUpload($param){
        
        return $html;
    } 

    public static function Htmlact($param){
        if (count($param)==0){
            return ;
        }
        if (count($param)==1){
            $html = self::a($param[0]);
        }
        if (count($param)>1){

            // $html.= "<div class=\"button-group\">\n";
            $html.= self::a($param[0]);
            // $html.= "               <button type=\"button\" class=\"btn dropdown-toggle\">\n";
            // $html.= "                   <span class=\"downward\"></span>\n";
            // $html.= "               </button>\n";
            // $html.= "               <ul class=\"drop-menu\">\n";
            for($i=1;$i<count($param);$i++){
                $html.= "&nbsp;|&nbsp;". self::href($param[$i]);
            }
            // $html.= "               </ul>\n";
            $html.= "           </div>";
        }
        return $html;
    }
    
    public static function href($param){
        $type = $param['type']!=''?$param['type']:"";
        $width = $param['_width']!=""?$param['_width']:"450";
        $height = $param['_height']!=""?$param['_height']:"220";
        $html.="<a ";
        $html.=$param['_rel']!=''?" _rel=\"".$param['_rel']."\"":($param['type']!=''?" _rel=\"".$param['type']."\"":"");
        $html.=" id=\"".($param['id']!=''?$param['id']:$param['name'])."\"";
        $html.=" name=\"".$param['name']."\"";
        $html.=" class=\"".($param['class']!=''?$param['class']:"")."\"";
        $html.=" _title=\"".$param['_title']."\"";
        $html.=" _width=\"".$width."\"";
        $html.=" _height=\"".$height."\"";
        if($param['_url']!='')
            $html.=" _url=\"".$param['_url']."\"";

        $html.=$param['onclick']!=''?" onclick=\"".$param['onclick']."\"":"";
        $html.=$param['other']!=''?" "." ".$param['other']:"";
        $html.=">";
        $html.=$param['value'];
        $html.="</a>";
        return $html;
    }

  

    /*
    * 图片上传
    * $param = array(
            "id"=>"keyword",  
            "value"=>"uimg/20160112/1452562437il350.png" 图片路径
            "view"=>"添加图片"
            "options"=>array(
                    formData            => array(),  // post额外的数据，服务端通过post接收 arrray(username=>'jim')
                    maxNumberOfFiles    => 1,    // 最大上传数量 默认最大上传1个
                    previewMaxWidth     => 100, //预览 最大宽度
                    previewMaxHeight    => 100,//预览 最大高度
                    isdefault           => false // false为不需要设置主图，true为需要
                    specid              => 0  // 规格id，默认为0表示不存在规格  
                ),
            "server_type"=> "Uplooad/product"
        );
    */
    
    public static function imgupload($param){
        if($param['id']!=''){
            //加载上传配置
            //$upload_config = include(DLERP_LIB_DIR.DS.'configs'.DS.'upload.config.php'); //上传配置
            $upload_config = Config::get("upload");

            if(!empty($param['options']) && is_array($param['options']))
                $options = array_merge($upload_config,$param['options']);
            else
                $options = $upload_config;
            if($param['server_type']!='')
                $options['formData']['server_type'] = $param['server_type'];
            
            if(empty($options['formData']['server_type']))
                $options['formData']['server_type'] = $upload_config['server_type'];
            //print_r($options);
            $view = $param['view']!=''?$param['view']:"添加图片";
            $html .= '<span class="btn btn-primary fileinput-button" id="'.$param['id'].'_upload" name="'.$param['id'].'_upload">';
            //$html .= '<i class="glyphicon glyphicon-plus"></i>';
            $html .= '<span>'.$view.'</span>';
            $html .= '<input type="file" name="file" multiple>';
            $html .= '</span>';
            $html .= "&nbsp;<font>(文件数：".$options['maxNumberOfFiles']."；文件大小：".($options['maxFileSize']/(1024*1024))."M)</font>";
            $html .= '<div id="uploadfiles_'.$param['id'].'" class="uploadfiles" style="margin-top:5px;">';
            $html .= '<input type="hidden" id="'.$param['id'].'" name="'.($param['name']!=''?$param['name']:$param['id']).'" value="'.$param['value'].'">';
            if(!empty($param['value'])){
                if(is_array($param['value']))
                    $value_arr = $param['value'];
                else
                    $value_arr = explode(",", $param['value']);
                $rand =rand(1000,9999);
                foreach($value_arr as $value){
                    $html.='<div class="filediv_'.$param['id'].'" id="file_'.$param['id'].'_'.$rand.'" style="float: left; width: 100px; margin-right: 10px;">';
                    $html.='<div>';
                    $url = Img::url($value, 100, 100);
                    $gourl = Img::url($value);
                    $html.='<img src="'.$url.'">';
                    $html.='</div>';
                    $html.='<div style="text-align:center;">';
                    $html.='<a href="#" onclick="fileupload_removeupload(\'file_'.$param['id'].'_'.$rand.'\',\''.$param['id'].'\',\''.$value.'\')">删除</a>&nbsp;<a href="'.$gourl.'" target="_blank">预览</a>';

                    if($options['isdefault'] != false){
                        if($options['isdefault_url'] == $value){
                            $html .= '<br><input type="radio" name="isdefault['.$options['specid'].']" id="'.$rand.'" checked value="'.$value.'"><label for="'.$rand.'">设为首图</label>';
                        }else{
                            $html .= '<br><input type="radio" name="isdefault['.$options['specid'].']" id="'.$rand.'" value="'.$value.'"><label for="'.$rand.'">设为首图</label>';
                        }
                        
                    }

                    $html.='</div>';
                    $html.='<span id="url" style="display:none">'.$value.'</span>';
                    $html.='</div>';
                    $rand++;
                }
            }

            $html .= '</div>';
            $html .= '<script type="text/javascript">';
            $html .= "fileupload('".$param['id']."','".json_encode($options)."');";
            $html .= '</script>';
        }
        return $html;
    }


    public static function showImg($param){
        if($param['id']!=''){
            //加载上传配置
            //$upload_config = include(DLERP_LIB_DIR.DS.'configs'.DS.'upload.config.php'); //上传配置
            $upload_config = Config::get("upload");

            if(!empty($param['options']) && is_array($param['options']))
                $options = array_merge($upload_config,$param['options']);
            else
                $options = $upload_config;
            if($param['server_type']!='')
                $options['formData']['server_type'] = $param['server_type'];
            
            if(empty($options['formData']['server_type']))
                $options['formData']['server_type'] = $upload_config['server_type'];
            //print_r($options);
            // $view = $param['view']!=''?$param['view']:"添加图片";
            // $html .= '<span class="btn btn-success fileinput-button" id="'.$param['id'].'_upload" name="'.$param['id'].'_upload">';
            // //$html .= '<i class="glyphicon glyphicon-plus"></i>';
            // $html .= '<span>'.$view.'</span>';
            // $html .= '<input type="file" name="file" multiple>';
            // $html .= '</span>';
            // $html .= "&nbsp;<font>(文件数：".$options['maxNumberOfFiles']."；文件大小：".($options['maxFileSize']/(1024*1024))."M)</font>";
            $html .= '<div id="uploadfiles_'.$param['id'].'" class="uploadfiles" style="margin-top:5px;">';
            $html .= '<input type="hidden" id="'.$param['id'].'" name="'.($param['name']!=''?$param['name']:$param['id']).'" value="'.$param['value'].'">';
            if(!empty($param['value'])){
                if(is_array($param['value']))
                    $value_arr = $param['value'];
                else
                    $value_arr = explode(",", $param['value']);
                $rand =rand(1000,9999);
                foreach($value_arr as $value){
                    $html.='<div class="filediv_'.$param['id'].'" id="file_'.$param['id'].'_'.$rand.'" style="float: left; width: 100px; margin-right: 10px;">';
                    $html.='<div>';
                    $url = Default_Model_DbTable_SysFile::file($value, 100, 100);
                    $gourl = Default_Model_DbTable_SysFile::file($value);
                    $html.='<img src="'.$url.'">';
                    $html.='</div>';
                    $html.='<div style="text-align:center;">';
                    $html.='<a href="'.$gourl.'" target="_blank">预览</a>';

                    if($options['isdefault'] != false){
                        if($options['isdefault_url'] == $value){
                            $html .= '<br><input type="radio" name="isdefault['.$options['specid'].']" id="'.$rand.'" checked value="'.$value.'"><label for="'.$rand.'">设为首图</label>';
                        }else{
                            $html .= '<br><input type="radio" name="isdefault['.$options['specid'].']" id="'.$rand.'" value="'.$value.'"><label for="'.$rand.'">设为首图</label>';
                        }
                        
                    }

                    $html.='</div>';
                    $html.='<span id="url" style="display:none">'.$value.'</span>';
                    $html.='</div>';
                    $rand++;
                }
            }

            $html .= '</div>';
            $html .= '<script type="text/javascript">';
            $html .= "fileupload('".$param['id']."','".json_encode($options)."');";
            $html .= '</script>';
        }
        return $html;
    }

    /*
    * 对数组的值进行置换颜色
    * $param = array(
                "value"=>array(0=>"禁用","1"=>"启用"),
                "color"=>array(0=>"red","2"=>"yellow")
            );
    */
    public static function color($param){
        $value = $param['value'];
        $color = $param['color'];
        if(!empty($value)){
            if(is_array($value)){
                foreach($value as $k=>$v){
                    $value[$k] = '<font color="'.$color[$k].'">'.$v.'</font>';
                }
            }else{
                $value = "<font color=\"".$color."\">".$value."</font>";
            }
        }
        return $value;
    }

    /*
    * 设置标签
    * $value 为字符串或者数组
    * $type 1 绿色标签 2 红色标签
    */
    public static function label($value,$type){

        if(!empty($value)){

            if(!is_array($value)){
                $css_name = "";
                switch($type){
                    case 1:
                        $css_name = 'label-success';
                        break;

                    case 2:
                        $css_name = 'label-warning';
                        break;

                    default:
                        break;
                }
                return '<span class="label radius '.$css_name.'">'.$value.'</span>';

            }else{
                //value为数组
                foreach($value as $k=>$v){
                    $value[$k] = self::label($v,$type[$k]);
                }

                return $value;
            }

        }
    }

}


class Varencode{
       
    public static $expiry=86400;//有效时间
    public static $encode_token=null;
    public static $_ext = '_i#1k';//加密补充的后缀
    /**
    * 加密
    * @param $string 可以是单编号，可以是编号串如 123,234,435
    */
    public static function encode($string){
        if(!empty($string)){
            $temp_arr = explode(",",$string);
            if(count($temp_arr)==1){
                return self::Codecrypt($string,'E');
            }else{
                $temp_str="";
                foreach($temp_arr as $str){
                    if($str!='')
                        $temp_str.= self::Codecrypt($str,'E').",";
                }
                return $temp_str!=''?substr($temp_str,0,-1):'';
            }
        }else{
            return '';
        }
    }

    /**
    * 解密
    * @ $type 解密类型
    * @ @param $string 可以是单编号，可以是编号串如 sdfasf,sdfadf,sdfasdf
    */
    public static function decode($string){
        if(!empty($string)){
            $temp_arr = explode(",",$string);
            if(count($temp_arr)==1){
                return self::Codecrypt($string,'D');
            }else{
                $temp_str="";
                foreach($temp_arr as $str){
                    if($str!='')
                        $temp_str.= self::Codecrypt($str,'D').",";
                }
                return $temp_str!=''?substr($temp_str,0,-1):'';
            }
        }else{
            return '';
        }
        
    }
    
    //获取token
    public static function getToken(){
        //token有效时间2小时
        return rand(1000,9999).(time()+self::$expiry).rand(1000,9999);
    }
    
    /**
    * 加密解密算法
    * $string：字符串，明文或密文
    * $operation：E表示加密，D或者其他表示解密
    * $token：密匙；
    * $expiry：密文有效期 默认2个小时 id=djkdj&name=dfsfdjk
    */
    public static function Codecrypt($string, $operation = 'E'){
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙   
        $ckey_length = 4;   
        //token有效时间2小时
        //新的token
        $new_encode_token = self::getToken();
        // 密匙
        if($operation=='E'){
            //加密的时候生成一个新的加密token /或者使用用户自定义的token
            //一个页面请求默认使用同一个token密钥
            if(!empty(self::$encode_token)){
                $key = self::$encode_token;
            }else{
                
                $session_encode_token = Session::get('encode_token');
                //echo "---".$session_encode_token."---";
                if(!empty($session_encode_token)){
                    if ((time()<=intval(substr($session_encode_token,4,10)))){
                        self::$encode_token = $key =$session_encode_token;
                    }else{
                        self::$encode_token = $key =$session_encode_token = $new_encode_token;
                    }
                }else{
                    
                    self::$encode_token = $key = $new_encode_token;

                    Session::put('encode_token', $new_encode_token);
                    Session::save();
                }
            }

        }else{
            
            //解密
            if(!empty(self::$encode_token)){
                $key = self::$encode_token;
            }else{
                 $session_encode_token = Session::get('encode_token');

                //echo "====".$session_encode_token."====";

                if($session_encode_token!=''){
                    //检测是否到期
                    
                    if ((time()<=intval(substr($session_encode_token,4,10)))){
                        //未到期
                        $key = self::$encode_token = $session_encode_token; 
                    }else{
                        //到期
                        return '';
                    }
                }else{
                    return '';
                }
            }
        }
        
        if($operation=='E'){
            return self::encrypt($string, $key);
            //return $tmp;
        }else{
            
            return self::decrypt($string, $key);
        }
    }

    public static function encrypt($txt ,$key = 'yrtnes', $len = 20){
        if ($len > 36)
        return '';
        $len--;
        $ikey ='sentry';        
        $chars = "NGHB689UR1WKP4AMZ2JF0YS5QTO7CXEI3VDL";
        $txt = strtoupper(str_replace('/[\W_]/','',$txt)); 
        $txt = substr($txt,0,$len);
        $strlen = strlen($txt);
        $i = 0;$knum = 0;$snum = 0;
        while(isset($txt[$i])) $snum +=ord($txt[$i++]);
        if ($strlen == $len)
            $mlen = $snum % (36 - $strlen) + $strlen;
        else
            $mlen = $strlen;
        $i = 0;
        while(isset($key[$i])) $knum +=ord($key[$i++]);
        $knum = $knum % 36; 
        $knum = ($knum + $mlen)%36;
        $ch = $chars[$knum];    
        $suffix = '';
        if ($strlen < $len)
            $suffix = strtoupper(substr(md5($txt.$key .$strlen).md5($txt),0, $len - $strlen));
        $mdKey = md5($key . $ch . $ikey .$len);
        $tmp = '';
        $i=0;$j=0;$k = 0;
        while(isset($txt[$i])){
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = ($knum + strpos($chars, $txt[$i++]) + ord($mdKey[$k++]))%36;
            $tmp .= $chars[$j];
        }
        $tmp = $suffix . $tmp;
        $tmp = substr($tmp,0,floor($len/3)) .$ch .substr($tmp,floor($len/3)); 
        return $tmp;
    }

    public static function decrypt($txt,$key = 'yrtnes'){
        $ikey = 'sentry';
        //$chars = "R1WO7CVDLNGHB6XEI3MZ2JKP4A5QTF0YS89U";
        $chars = "NGHB689UR1WKP4AMZ2JF0YS5QTO7CXEI3VDL";
        $len = strlen($txt) -1;
        $id = floor($len/3);
        $ch = $txt[$id];
        $txt = substr($txt,0,$id) . substr($txt,$id + 1);
        $num = strpos($chars,$ch);
        $knum = 0; $i = 0;
        while(isset($key[$i])) $knum +=ord($key[$i++]);
        $knum = $knum % 36; 
        $mlen = $num - $knum;
        while ($mlen < 0) $mlen += 36;
        if ($mlen < $len){
           $txt = substr($txt,$len - $mlen);
        }
        $mdKey = md5($key . $ch . $ikey .$len);
        $tmp = '';
        $i=0;$j=0; $k = 0;
        while(isset($txt[$i])){
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = strpos($chars, $txt[$i++])- $num  - ord($mdKey[$k++]);
            while ($j<0) $j +=36;
            $tmp .= $chars[$j];
        }
        return trim($tmp);
    }
}

function Encode($str){
    if($str=='')
        return '';
    return $str;
    //return Varencode::encode($str);
}

function Decode($str){
    if($str=='')
        return '';
    return $str;
    //return Varencode::decode($str);
}

/*
* 返回分页路径
*/
function goPageUrl($url,$page=''){
    if($url!=''){
       
        if(strpos($url,'?')!==false)
            $url.="&page=".$page;
        else
            $url.="?page=".$page; 

        return $url;
    }else{
        return "?page=".$page;
    }
}

/*
* 创建分页参数
*/
function pagesParam($param){
    $return = array();
    
    if($param['total']>0 && $param['pagesize']>0){
        $page = $param['page'];
        //总共有多少页
        $pages = floor($param['total']/$param['pagesize']);
        if(($param['total']%$param['pagesize'])>0)
            $pages+=1;
        $return['pages'] = $pages;

        //上一页
        if($page>1)
            $return['previous_page'] = $page-1;

        //下一页
        if($page<$pages)
            $return['next_page'] = $page+1;

        //显示当前10页码
        $pagesInRange = array();
        $maxpage = $pages>=10?10:$pages;
        if($page<=5){
            for($i=1;$i<=$maxpage;$i++)
                $pagesInRange[]=$i;
        }else if($page>$pages-5 && $page<=$pages){
            for($i=$pages-10;$i<=$pages;$i++)
                $pagesInRange[]=$i;
        }else{
            
            for($i=$page-4;$i<$page;$i++)
                $pagesInRange[]=$i;
            for($i=$page;$i<=$page+5;$i++)
                $pagesInRange[]=$i;
        }
        $return['pagesInRange'] = $pagesInRange;
    }else{
        $return['pages']=0;
        $return['pagesInRange'] = array();
    }
    return $return;
}

