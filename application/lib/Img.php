<?php
namespace app\lib;


use think\Config;

/*
* 图片地址转换
*/
class Img{

    public static $domain = array();  

	/**
    * 图片相对地址转为绝对地址
    * $param url 图片相对地址
    * $compress 是否压缩 默认true
    * $randdomain 是否用随机域名
    */
	public static function url($url,$width=0,$height=0,$other='',$compress=true,$randdomain=false){
        if($url=='') return $url;
        
        $pathinfo = pathinfo($url);

        //获取域名+获取域名配置
        if($randdomain)
            $domain = self::getRandDoMain($pathinfo['filename']);
        else
            $domain = self::getDoMain();
        

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
            //$url.=strpos($url, "@")===false&&$compress?"@90q":"";
        }

        if(strtolower(substr($url,0,2))=='//')
            $url = "https:".$url;
        
        //随机域名
        if($randdomain && strpos($url,self::$domain['original'])!==false){
            $url = str_replace(self::$domain,"",$url); // 原始域名
            $url = substr($url,0,1)=='/'?substr($url,1):$url;
        }

        $url = substr($url,0,1)=='/'?substr($url,1):$url;
        //$url = strpos($url,"https://")===false?$domain.$url:$url;
        if(strpos($url,"http://")===false&&strpos($url,"https://")===false)
        {
            $url = $domain.$url;
        }
        
        // 将http://替换成https://
        return str_replace("http://","https://",$url);
        */

        if(strpos($url,"http://")===false && strpos($url,"https://")===false){

            if((!empty($width) || !empty($height))){
                $pathinfo = pathinfo($url);
                if($pathinfo['extension']!=''){
                    $url.="?x-oss-process=image/resize,m_fill"; // m_lfit
                    if($width!='')
                        $url.=",w_".$width;
                    if($height!='')
                        $url.=",h_".$height;
                }
            }else{
                if($compress)
                    $url.=strpos($url, "?x-oss-process=image/resize")===false?"?x-oss-process=image/quality,q_80":"/quality,q_80";
            }

            if($other!='')
                $url.=$other;

            if($watermark)
                $url.=self::getWatermark();
            
        }

        if(strtolower(substr($url,0,2))=='//')
            $url = "http:".$url;

        $url = substr($url,0,1)=='/'?substr($url,1):$url;

        return strpos($url,"http://")===false&&strpos($url,"https://")===false?$domain.$url:$url;

    } 



    public static function getDoMain(){
        if(empty(self::$domain)){
            self::$domain = Config::get("image.domain");
        }

        return self::$domain;

        /*
        $rand = rand(0,(count(self::$domain['domain1'])-1));
        return self::$domain['domain_rand'][$rand];
        */
    }

    /*
    * 获取随机域名
    */
    public static function getRandDoMain($filename){
        if(empty(self::$domain))
            self::$domain = Config::get("image.domain");
        return self::$domain;
        //$rand = rand(0,(count(self::$domain['domain_rand']);-1));
        $rand = substr($filename,-1,1)%count(self::$domain['domain_rand']);
        return self::$domain['domain_rand'][$rand];
    }

    //不做图片压缩
    public static function cpurl($url){
        return self::url($url,'','','',false); 
    }

    public static function randurl($url){
        return self::url($url,'','','',true);
    }

    /*
    * 对内容的img表情图片地址做转换
    */
    public static function textimg($text,$width=0,$height=0,$other=''){
        $pattern='/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/i'; 
        preg_match_all($pattern,$text,$match); 
        //print_r($match); 
        if(!empty($match[1])){
            foreach($match[1] as $url){
                $text = str_replace($url,self::url($url,$width,$height,$other),$text); 
            }
        }
        return $text;
    }

    /*
    * 对内容的img图片取出
    */
    public static function gettextimg($text,$width=0,$height=0,$other=''){
        $pattern='/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/i'; 
        preg_match_all($pattern,$text,$match);
        $imgArr=array();
        //print_r($match); 
        if(!empty($match[1])){
            foreach($match[1] as $url){
                //$text = str_replace($url,self::url($url,$width,$height,$other),$text); 
                $imgArr[]=self::url($url,$width,$height,$other);
            }
        }
        return $imgArr;
    }

     /*
    * 对内容的img表情图片地址做转换
    */
    public static function cptextimg($text,$width=0,$height=0,$other=''){
        $pattern='/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/i'; 
        preg_match_all($pattern,$text,$match); 
        //print_r($match); 
        if(!empty($match[1])){
            foreach($match[1] as $url){
                $text = str_replace($url,self::cpurl($url),$text); 
            }
        }
        return $text;
    }

    public static function texturl($text){
        $pattern='/<[a|A].*?href=[\'|\"](.*?)[\'|\"].*?[\"\>]/i'; 
        preg_match_all($pattern,$text,$match); 
        //print_r($match);
        if(!empty($match[1])){
            foreach ($match[1] as $url) {
               //;"/html/product-yh-detail.html?id=283"
               $text = str_replace($url,self::replaceUrl($url),$text); 
            
            }
        }
        return $text;
    }

    /**
     * [replaceUrl 替换url WebAPP使用 跳转网页链接 相对url  ]
     * 商品类型 2为 实物商品 3 为服务商品
     * 雅活 服务 3 html/product-yh-detail.html?id=283 雅品 实物 2 html/product-detail.html?id=3126
     * @return [type] [description]
     */
    public static function replaceUrl($url){
        if($url=='') return $url;
        
        $pathinfo = pathinfo($url);
        //print_r($pathinfo);
        $geturl = $pathinfo['basename'];
        // goods.php?xuchengtype=
        // goods.php?xuchengtype=2&amp;proudctid=
        if(strtolower(substr($geturl,22,1))=='2'){
            $id = str_replace(strtolower(substr($geturl,0,38)),'',$geturl);
            $weburl = "html/product-detail.html?id=".$id."&from=php";
        }else if(strtolower(substr($geturl,22,1))=='3'){
            $id = str_replace(strtolower(substr($geturl,0,38)),'',$geturl);
            $weburl = "html/product-yh-detail.html?id=".$id."&from=php";
        }
        return $weburl;

    }

}