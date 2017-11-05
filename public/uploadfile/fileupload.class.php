<?php
    /** 
    *  file: fileupload.class.php 文件上传类FileUpload
    *  本类的实例对象用于处理上传文件，可以上传一个文件，也可同时处理多个文件上传
    *  author:zhuangqm
    *  date:20160105
    */
    class fileupload { 
        
        private $path = "./uimg/";          //默认上传文件保存的路径
        private $allowtype = array('jpg','jpeg','gif','png'); //设置限制上传文件的类型
        private $maxsize = 2048000;           //限制文件上传大小（字节）
        private $israndname = true;           //设置是否随机重命名文件， false不随机
        private $ext_path = "sdf2349dsfhn2picmz2"; //保密层
        private $thumb = "thumb"; //缩略图路径
        private $mode = 0755; //文件夹权限
        private $server_type = array("uimg","member","user","product","evaluate","business","branch","article","comment");

        private $dir;                      //当前的系统路径
        private $originName;              //源文件名
        private $tmpFileName;              //临时文件名
        private $fileType;               //文件类型(文件后缀)
        private $fileSize;               //文件大小
        private $newFileName;              //新文件名
        private $errorNum = 0;             //错误号
        private $errorMess="";             //错误报告消息

        public function __construct(){
            $this->dir = dirname(__FILE__)."/";
        }
        /**
         * 用于设置成员属性（$path, $allowtype,$maxsize, $israndname）
         * 可以通过连贯操作一次设置多个属性值
         *@param  string $key  成员属性名(不区分大小写)
         *@param  mixed  $val  为成员属性设置的值
         *@return  object     返回自己对象$this，可以用于连贯操作
         */
        public function set($key, $val){
            //$key = strtolower($key); 
            //if( array_key_exists( $key, get_class_vars(get_class($this) ) ) ){
            $this->setOption($key, $val);
            //}
            return $this;
        }

        public function get($key){
            return $this->$key;
        }

        /**
         * 调用该方法上传文件
         * @param  string $fileFile  上传文件的表单名称 
         * @return bool        如果上传成功返回数true 
         */

        public function upload($fileField) {
            $return = true;
            /* 检查文件路径是滞合法 */
            if( !$this->checkFilePath() ) {       
                $this->errorMess = $this->getError();
                return false;
            }
            /* 将文件上传的信息取出赋给变量 */
            $name = $_FILES[$fileField]['name'];
            $tmp_name = $_FILES[$fileField]['tmp_name'];
            $size = $_FILES[$fileField]['size'];
            $error = $_FILES[$fileField]['error'];

            $error_name   = array();
            $fileNames    = array();
            $originNames  = array();
            $fileTypes    = array();  
            $fileSizes    = array();
            /* 如果是多个文件上传则$file["name"]会是一个数组 */
            $name_arr = array();
            $tmp_name_arr = array();
            $size_arr = array();
            $error_arr = array();
            if(is_array($name)){
                $name_arr       = $name;
                $tmp_name_arr   = $tmp_name;
                $size_arr       = $size;
                $error_arr      = $error;
            }else{
                $name_arr[]     = $name;
                $tmp_name_arr[] = $tmp_name;
                $size_arr[]     = $size;
                $error_arr[]    = $error;
            }

            $errors=array();
            /*多个文件上传则循环处理 ，如果有误则不上传 */
            for($i = 0; $i < count($name_arr); $i++){ 
                /*设置文件信息 */
                if($this->setFiles($name_arr[$i],$tmp_name_arr[$i],$size_arr[$i],$error_arr[$i] )) {
                    if($this->checkFileSize() && $this->checkFileType()){
                        //正常  
                        /* 为上传文件设置新文件名 */
                        $this->setNewFileName();
                        if($this->copyFile()){ 
                            $fileNames[]    = $this->newFileName;
                            $originNames[]  = $this->originName;
                            $fileTypes[]    = $this->fileType;
                            $fileSizes[]    = $this->fileSize;
                        }
                    }else{
                        $errors[] = $this->getError();
                        $return=false; 
                    }
                }else{
                    $errors[] = $this->getError();
                    $return=false;
                }
            }
            $this->newFileName    = $fileNames;
            $this->originName     = $originNames;
            $this->fileType       = $fileTypes;
            $this->fileSize       = $fileSizes;

            $this->errorMess = $errors;

            $fileNames = null;
            $originNames = null;
            $fileTypes = null;
            $fileSizes = null;
            $name_arr = null;
            $tmp_name_arr = null;
            $size_arr = null;
            $error_arr = null;

            return $return;
        }

        /** 
         * 获取上传后的文件名称
         * @param  void   没有参数
         * @return string 上传后，新文件的名称， 如果是多文件上传返回数组
         */
        public function getFileName(){
          return $this->newFileName;
        }

        /**
         * 上传失败后，调用该方法则返回，上传出错信息
         * @param  void   没有参数
         * @return string  返回上传文件出错的信息报告，如果是多文件上传返回数组
         */
        public function getErrorMsg(){
          return $this->errorMess;
        }

        /* 设置上传出错信息 */
        private function getError() {
          $str = "上传文件<font color='red'>{$this->originName}</font>时出错 : ";
          switch ($this->errorNum) {
            case 4: $str .= "no file upload"; break; //没有文件被上传
            case 3: $str .= "part file uploaded"; break; //文件只有部分被上传
            case 2: $str .= "excess file size"; break; //上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值
            case 1: $str .= "excess file size"; break; //上传的文件超过了php.ini中upload_max_filesize选项限制的值
            case -1: $str .= "not allow file type"; break; //未允许类型
            case -2: $str .= "excess file size"; break; //文件过大,上传的文件不能超过{$this->maxsize}个字节
            case -3: $str .= "upload error"; break; //上传失败
            case -4: $str .= "error filedir"; break; //建立存放上传文件目录失败，请重新指定上传目录
            case -5: $str .= "no filepath"; break; //必须指定上传文件的路径
            default: $str .= "error";//未知错误
          }
          return $str.'<br>';
        }

        /* 设置和$_FILES有关的内容 */
        private function setFiles($name="", $tmp_name="", $size=0, $error=0) {
          $this->setOption('errorNum', $error);
          if($error)
            return false;
          $this->setOption('originName', $name);
          $this->setOption('tmpFileName',$tmp_name);
          $aryStr = explode(".", $name);
          $this->setOption('fileType', strtolower($aryStr[count($aryStr)-1]));
          $this->setOption('fileSize', $size);
          return true;
        }

        /* 为单个成员属性设置值 */
        private function setOption($key, $val) {
          $this->$key = $val;
        }

        /* 设置上传后的文件名称 */
        private function setNewFileName() {
            /*
            if ($this->israndname) {
              $this->setOption('newFileName', $this->proRandName());  
            } else{ 
              $this->setOption('newFileName', $this->originName);
            }
            */
            $this->setOption('newFileName', $this->proRandName()); 
        }

        /* 检查上传的文件是否是合法的类型 */
        private function checkFileType() {
          if (in_array(strtolower($this->fileType), $this->allowtype)) {
            return true;
          }else {
            $this->setOption('errorNum', -1);
            return false;
          }
        }

        /* 检查上传的文件是否是允许的大小 */
        private function checkFileSize() {
          if ($this->fileSize > $this->maxsize) {
            $this->setOption('errorNum', -2);
            return false;
          }else{
            return true;
          }
        }

        /* 
        * 检查是否有存放上传文件的目录 
        * 目录路径  /前缀/uimg(业务文件夹)/日期
        */
        private function checkFilePath() {
            if(empty($this->path)){
                $this->setOption('errorNum', -5);
                return false;
            }
            
            if (!$this->mkdirs($this->ext_path."/".$this->path.date("Ymd")."/")) {
                $this->setOption('errorNum', -4);
                return false;
            }
            $this->path = $this->path.date("Ymd")."/";
            return true;
        }

        public function mkdirs($dir){
            if (!is_dir($dir)){
                $ret = @mkdir($dir, $this->mode, true);
                chmod($dir,$this->mode);
                if (!$ret){
                    return false;
                }
            }
            return true;
        }

        /* 设置随机文件名 */
        private function proRandName() {    
          //$fileName = date('YmdHis')."_".rand(100,999);    
          $str_arr = array('a','b','c','d','e','f','g','h','i','j','k','l','n','m','o','p','q','r','s','t','u','v','w','x','y','z');
          $fileName = time().$str_arr[rand(0,25)].$str_arr[rand(0,25)].rand(100,999);
          return $this->path.$fileName.'.'.$this->fileType; 
        }

        /* 复制上传文件到指定的位置 */
        private function copyFile() {
            if(!$this->errorNum) {
                //$path = $this->newFileName;
                if (@move_uploaded_file($this->tmpFileName, $this->dir.$this->ext_path."/".$this->newFileName)) {
                    return true;
                }else{
                    $this->setOption('errorNum', -3);
                    return false;
                }
            } else {
                return false;
            }
        }
}