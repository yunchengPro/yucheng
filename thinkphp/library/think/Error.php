<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://zjzit.cn>
// +----------------------------------------------------------------------

namespace think;

use think\console\Output as ConsoleOutput;
use think\exception\ErrorException;
use think\exception\Handle;
use think\exception\ThrowableError;

use \think\Request;
use \think\App;

class Error
{
    /**
     * 注册异常处理
     * @return void
     */
    public static function register()
    {
        error_reporting(E_ALL);
        //set_error_handler([__CLASS__, 'appError']);
        set_exception_handler([__CLASS__, 'appException']);
        register_shutdown_function([__CLASS__, 'appShutdown']);
    }

    /**
     * Exception Handler
     * @param  \Exception|\Throwable $e
     */
    public static function appException($e)
    {
        self::logfile($e);
        if (App::$debug) {
            if (!$e instanceof \Exception) {
                $e = new ThrowableError($e);
            }

            self::getExceptionHandler()->report($e);
            if (IS_CLI) {
                self::getExceptionHandler()->renderForConsole(new ConsoleOutput, $e);
            } else {
                self::getExceptionHandler()->render($e)->send();
            }
        }else{
            if(BIND_MODULE=='api'){
                echo json_encode([
                        "code"=>"400",
                        "msg"=>"网络异常",
                    ]);
                exit;
            }
        }


    }

    /**
     * Error Handler
     * @param  integer $errno   错误编号
     * @param  integer $errstr  详细错误信息
     * @param  string  $errfile 出错的文件
     * @param  integer $errline 出错行号
     * @param array    $errcontext
     * @throws ErrorException
     */
    public static function appError($errno, $errstr, $errfile = '', $errline = 0, $errcontext = [])
    {
        $exception = new ErrorException($errno, $errstr, $errfile, $errline, $errcontext);
        if (error_reporting() & $errno) {
            // 将错误信息托管至 think\exception\ErrorException
            throw $exception;
        } else {
            self::getExceptionHandler()->report($exception);
        }
    }

    /**
     * Shutdown Handler
     */
    public static function appShutdown()
    {
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            // 将错误信息托管至think\ErrorException
            $exception = new ErrorException($error['type'], $error['message'], $error['file'], $error['line']);

            self::appException($exception);
        }

        // 写入日志
        Log::save();
    }

    /**
     * 确定错误类型是否致命
     *
     * @param  int $type
     * @return bool
     */
    protected static function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }

    /**
     * Get an instance of the exception handler.
     *
     * @return Handle
     */
    public static function getExceptionHandler()
    {
        static $handle;
        if (!$handle) {
            // 异常处理handle
            $class = Config::get('exception_handle');
            if ($class && class_exists($class) && is_subclass_of($class, "\\think\\exception\\Handle")) {
                $handle = new $class;
            } else {
                $handle = new Handle;
            }
        }
        return $handle;
    }

    public static function logfile($exception){

        $request = Request::instance();

        $data                = array();
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
  
        self::filelogdata($data);
    }

    /*
    $data 数组
     */
    public static function filelogdata($data){

        $file = ROOT_PATH."runtime".DS."log".DS.BIND_MODULE."_".date("Y-m-d").".txt";
        
        $f = fopen($file,'a');
        $data = json_encode($data,JSON_UNESCAPED_UNICODE)."\n";
        fwrite($f,$data);
        fclose($f);
    }
}
