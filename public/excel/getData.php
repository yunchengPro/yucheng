<?php 
	error_reporting(E_ERROR);
    /*
    * 生成excel文件
    */
    class Excel{

        var $excel_key;
        var $head ;
        var $data  ;
        var $count ; //总条数
        var $excel_count; //总文件数
        var $this_excel_count; //当前文件的记录数

        var $dir;
        var $filepath;
        var $file;
        var $maxcount = 20000;

        public function __construct(){
            $this->excel_key  = $_POST['excel_key'];
            $this->head       = $_POST['head'];
            $this->data       = $_POST['data'];
            $this->count      = $_POST['count']; //总条数
            $this->excel_count= $_POST['excel_count']; //总文件数
            $this->this_excel_count = $_POST['this_excel_count']; //当前文件的记录数

            $this->dir        = dirname(__FILE__)."/excelfile/";

        }

        public function buildExcel(){
            if($this->excel_key!='' && !empty($this->data)){
                if($this->mkdirs($this->dir.date("Ymd"))){

                    //超过指定数量
                    if((intval($this->count)+count($this->data))>20000){

                    }

                    $this->filepath = date("Ymd")."/".$this->excel_key.".csv";
                    $this->file();

                    if($this->this_excel_count==0 && !empty($this->head)){
                        $this->putData($this->head);
                    }

                    foreach($this->data as $value){
                        
                        if($this->this_excel_count>=$this->maxcount){
                            //1 关闭file连接
                            $this->closefile();
                            //2 把文件修改名字
                            $this->refilename();
                            //3 重新创建新的文件
                            $this->file();
                            $this->excel_count++;
                            $this->this_excel_count=0;

                            //4 增加头部
                            if(!empty($this->head))
                                $this->putData($this->head);
                        }

                        $this->putData($value);
                        $this->count++;
                        $this->this_excel_count++;
                    }

                    $this->data = null;

                    $this->closefile();

                    return json_encode(array(
                        "status"=>"200", //400 错误 200 完成
                        "filepath"=>$this->filepath,
                        "count"=>$this->count,
                        "excel_count"=>$this->excel_count,
                        "this_excel_count"=>$this->this_excel_count,
                        "info"=>"",
                    ));
                }else{
                    return json_encode(array(
                        "status"=>"400", //400 错误 200 完成
                        "info"=>"dir error",
                    ));
                }
            }else{
                return json_encode(array(
                    "status"=>"400", //400 错误 200 完成
                    "info"=>"empty key or empty data",
                ));
            }

        }

        public function file(){
            $this->file = fopen($this->dir."/".$this->filepath,"a");
        }

        public function refilename(){
            $pathinfo = pathinfo($this->filepath);
            rename($this->dir."/".$this->filepath,$this->dir."/".($pathinfo['dirname']!=''?$pathinfo['dirname']."/":"").$pathinfo['filename']."_".$this->excel_count.".".$pathinfo['extension']);
        }

        public function closefile(){
            fclose($this->file);
        }

        public function putData($data){
            $tmp = array();
            foreach($data as $key=>$value){
                $value = trim($value);
                /*
                //最后，去掉非space 的空白，用一个空格代替
                $value = preg_replace('/[nrt]/', ' ', $value);
                //去掉跟随别的挤在一块的空白
                $value = preg_replace('/s(?=s)/', '+', $value);
                
                $value = iconv("utf-8","GBK",$value);
                */
                $value = $this->charsetToGBK($value);
                $tmp[] = $value;
            }
            fputcsv($this->file, $tmp);

            unset($tmp);  
            //刷新缓冲区  
            ob_flush();  
            flush(); 
        }

        public function mkdirs($dir, $mode = 0777){
            if (!is_dir($dir))
            {
                $ret = @mkdir($dir, $mode, true);
                chmod($dir,$mode);
                if (!$ret)
                {
                    throw new Exception($dir);
                }
            }
            return true;
        }

        public function returnExcel(){
            $this->filepath = $_POST['filepath'];
            if($this->excel_count>1){
                
                //帮当前文件名修改
                $this->refilename();

                //打包成zip
                $pathinfo = pathinfo($this->filepath);
                $path = $this->dir."/".($pathinfo['dirname']!=''?$pathinfo['dirname']."/":"");
                $zip_path = $path.$pathinfo['filename'].".zip";
                $filepath = $path.$pathinfo['filename']."_*.".$pathinfo['extension'];
                //打包文件
                exec("zip -rj $zip_path $filepath");

                //删除文件
                exec("rm -f $filepath");
                return json_encode(array(
                        "filepath"=>($pathinfo['dirname']!=''?$pathinfo['dirname']."/":"").$pathinfo['filename'].".zip",
                    ));
            }else{
                return json_encode(array(
                        "filepath"=>$this->filepath,
                    )); 
            }
        }

        public function charsetToGBK($mixed){

            if (is_array($mixed)) {
                foreach ($mixed as $k => $v) {
                    if (is_array($v)) {
                        $mixed[$k] = charsetToGBK($v);
                    } else {
                        $encode = mb_detect_encoding($v, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
                        if ($encode == 'UTF-8') {
                            $mixed[$k] = iconv('UTF-8', 'GBK', $v);
                        }
                    }
                }
            } else {
                $encode = mb_detect_encoding($mixed, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
                //var_dump($encode);
                if ($encode == 'UTF-8') {
                    //$mixed = iconv('UTF-8', 'GBK', $mixed);
                    $mixed = iconv("UTF-8", "GB2312//IGNORE", $mixed);
                }
            }
            return $mixed;
        }
    }

    

    $endflag  = $_POST['endflag']; 
    $excel = new Excel();
    if($endflag==1)
        $result = $excel->returnExcel();
    else 
        $result = $excel->buildExcel();

    header('Access-Control-Allow-Origin: *'); //设置http://www.baidu.com允许跨域访问
    header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
    echo $result;
    exit;
?>