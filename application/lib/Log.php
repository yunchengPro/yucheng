<?php 
namespace app\lib;

use \think\Request;

class Log{

	public static function add($exception,$method='',$sql=[]){

        $request = Request::instance();

        $data                = array();
        $data['method']	 	 = $method;
        $data['time']        = date("Y-m-d H:i:s");
        $data['IP']          = $_SERVER["HTTP_X_FORWARDED_FOR"];
        $data['IP_in']       = $_SERVER["REMOTE_ADDR"];      
        $data['_apiname']    = $request->param('_apiname');
        $data['mtoken']      = $request->param('mtoken');   
        $data['error']       = [
                                    'file'    => $exception->getFile(),
                                    'line'    => $exception->getLine(),
                                    'message' => $exception->getMessage(),
                                    //'trace'   => $exception->getTrace(),
                                    'code'    => $exception->getCode(),
                                    //'source'  => $this->getSourceCode($exception),
                                    //'datas'   => $exception->getData(),
                                ];
        if(!empty($sql))
            $data['sql'] = $sql;
        self::filelogdata($data);
    }

    public static function addlog($str,$method=''){
        $request = Request::instance();

        $data                = array();
        $data['method']      = $method;
        $data['time']        = date("Y-m-d H:i:s");
        $data['IP']          = $_SERVER["HTTP_X_FORWARDED_FOR"];
        $data['IP_in']       = $_SERVER["REMOTE_ADDR"];      
        $data['_apiname']    = $request->param('_apiname');
        $data['mtoken']      = $request->param('mtoken');   
        $data['log']         = $str;

        if(!empty($sql))
            $data['sql'] = $sql;
        self::filelogdata($data,"apperror");
    }

    /*
    $data 数组
     */
    public static function filelogdata($data,$filename=''){

        $file = ROOT_PATH."runtime".DS."log".DS.($filename!=''?$filename:BIND_MODULE)."_".date("Y-m-d").".txt";
        
        $f = fopen($file,'a');
        $data = json_encode($data,JSON_UNESCAPED_UNICODE)."\n";
        fwrite($f,$data);
        fclose($f);
    }
}