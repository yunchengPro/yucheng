<?php 
	/*
	Gmagick 二次封装
	*/
	class GmagickClass{
		
		public $gmagick;

		protected $img = '';

		protected $mode = 0755;

		protected $fillcolor = "#FFFFFF";

		public $type; //文件类型
 
		public function __construct($img=''){
			$this->int_img($img);
			$this->gmagick = new Gmagick();
		}

		/*
		* 初始化图片
		*/
		public function int_img($img){
			if(!empty($img)){
				$this->img = $img;
				$this->gmagick->readimage($img);
				$this->type = strtolower ( $this->gmagick->getImageFormat());
			}
			return $this;
		}

		/*
		* 生成文件名
		*/
		protected function getFileName($ext){
			//获取原文件名
			//array ( [dirname] => 20160106/06/01 [basename] => 1452047097el710.jpg [extension] => jpg [filename] => 1452047097el710 ) 
			if(!empty($this->img)){
				$pathinfo = pathinfo($this->img);
				return $pathinfo['filename'].$ext.'.'.$pathinfo['extension'];
			}else{
				return '';
			}
		}

		//压缩图片质量并生成
		public function buildQualityImg($img_path,$new_img_path,$quality=80){
			$this->int_img($img_path);
			if($this->type=='png'){
				$this->new_img($this->gmagick->getImageWidth(),$this->gmagick->getImageHeight());
			}
	        $this->setCompressionQuality($quality);
	        $this->gmagick->stripImage(); 
	        $this->write($new_img_path);
	        return true;
		}

		/*
		* 压缩图片质量
		*/
		public function setCompressionQuality($quality=90){
			if($quality!=0 && $quality!='')
				$this->gmagick->setCompressionQuality($quality);
		}

		public function write($filepath){
			if ($this->type == 'gif') { 
				$this->gmagick->write($filepath); 
			} else { 
				$this->gmagick->writeImage($this->type=='png'?"png8:".$filepath:$filepath); 
			}
			//$this->gmagick->write($filepath);
		}

		/**
		*
		*@param 
		*	$path为生成文件的路径（可以包含文件名，也可以不包含文件名); 如果是路径则按系统方式生成
		*	$thumb_width: 最终宽度; 
		*	$thumb_height: 最终高度; 
		*	$crop: 是否裁剪,默认为否.  true:一般生成正方形或者固定长宽缩略图的时候用这个方法！
		*	$quality 为生成图片的压缩质量; 
		*@return 创建成功返回生成的文件名，否则返回false
		*/
		public function make_thumb($path='',$thumb_width = 0, $thumb_height = 0,$crop=false,$quality=90){

			/*判断文件是否存在*/
			if(!file_exists($this->img)){
				return array(
						"state"=>false,
					);
			}

			/* 检查缩略图宽度和高度是否合法 */
			if ($thumb_width == 0 && $thumb_height == 0){
				return array(
						"state"=>false,
					);
			}

			/* 检查原始文件是否存在及获得原始文件的信息 */
			/*
			$org_info = @getimagesize($img);
			if (!$org_info)
			{
				throw new Exception("image not exists");
				return false;
			}
			*/

			/* 创建当月目录 */
			if (empty($path) || !$this->mkdirs($path)){
				//按预先设定的路径
				return array(
						"state"=>false,
					);
			}
			$pathinfo = pathinfo($path);
			if(empty($pathinfo['extension'])){
				/* 生成文件名 */
				$filename = $this->getFileName('_'.$thumb_width."x".$thumb_height);
				$filepath = rtrim($path,'/')."/".$filename;
			}else{
				$filename = $pathinfo['basename'];
				$filepath = $path;
			}
			try{

				if($crop==true){
					//切割
					//一般生成正方形或者固定长宽缩略图的时候用这个方法！
					$this->gmagick->cropthumbnailimage($thumb_width,$thumb_height);
				}else{
					$size = $this->gmagick->getImagePage();
					//原始宽高
					$src_width = $size['width'];
					$src_height = $size['height'];
					//按高度缩放 宽度自适应
					
					if($thumb_width!=0 && $thumb_height==0){
						if($src_width>$thumb_width){
							$height = intval($thumb_width*$src_height/$src_width);
							$this->gmagick->thumbnailImage ( $thumb_width, $height, true );
						}else{
							return array(
								"state"=>false,
							);
						}
					}else if($thumb_width==0 && $thumb_height!=0){
						if($src_height>$thumb_height){
							$width = intval($src_width*$thumb_height/$src_height);
							$this->gmagick->thumbnailImage( $width, $thumb_height, true );
						}else{
							return array(
								"state"=>false,
							);
						}
					}else{
						$this->new_img($thumb_width,$thumb_height);
					}
				}
				$this->setCompressionQuality($quality);
				$this->gmagick->stripImage(); 
				$this->write($filepath);
				
				return array(
							"state"=>true,
							"return"=>$filepath,
						);
				
			}catch(Exception $e){
				//print_r($e->getMessage()); 
				return array(
						"state"=>false,
					);
			}
		}

		private function mkdirs($dir){
            if (!is_dir($dir)){
                $ret = @mkdir($dir, $this->mode, true);
                chmod($dir,$this->mode);
                if (!$ret){
                    return false;
                }
            }
            return true;
        }

		/**
		* 图片裁剪  
		* 裁剪规则：
		*   1. 高度为空或为零   按宽度缩放 高度自适应
		*   2. 宽度为空或为零  按高度缩放 宽度自适应
		*      3. 宽度，高度到不为空或为零  按宽高比例等比例缩放裁剪  默认从头部居中裁剪
		* @param number $width
		* @param number $height
		*/
		public function resize($width=0, $height=0){
			if($width==0 && $height==0){
				return;
			}

			$color = '';// 'rgba(255,255,255,1)';
			$size = $this->gmagick->getImagePage ();
			//原始宽高
			$src_width = $size ['width'];
			$src_height = $size ['height'];

			//按宽度缩放 高度自适应
			if($width!=0 && $height==0){
				if($src_width>$width){
					$height = intval($width*$src_height/$src_width);

					if ($this->type == 'gif') {
						$this->_resizeGif($width, $height);
					}else{
						$this->gmagick->thumbnailImage ( $width, $height, true );
					}
				}
				return;
			}
			//按高度缩放 宽度自适应
			if($width==0 && $height!=0){
				if($src_height>$height){
					$width = intval($src_width*$height/$src_height);

					if ($this->type == 'gif') {
						$this->_resizeGif($width, $height);
					}else{
						$this->gmagick->thumbnailImage ( $width, $height, true );
					}
				}
				return;
			}

			//缩放的后的尺寸
			$crop_w = $width;
			$crop_h = $height;

			//缩放后裁剪的位置
			$crop_x = 0;
			$crop_y = 0;

			if(($src_width/$src_height) < ($width/$height)){
				//宽高比例小于目标宽高比例  宽度等比例放大      按目标高度从头部截取
				$crop_h = intval($src_height*$width/$src_width);
				//从顶部裁剪  不用计算 $crop_y
			}else{
				//宽高比例大于目标宽高比例   高度等比例放大      按目标宽度居中裁剪
				$crop_w = intval($src_width*$height/$src_height);
				$crop_x = intval(($crop_w-$width)/2);
			}

			if ($this->type == 'gif') {
				$this->_resizeGif($crop_w, $crop_h, true, $width, $height,$crop_x, $crop_y);
			} else {
				$this->gmagick->thumbnailImage ( $crop_w, $crop_h, true );
				$this->gmagick->cropImage($width, $height,$crop_x, $crop_y);
			}
		}

		/**
		* 处理gif图片 需要对每一帧图片处理
		* @param unknown $t_w  缩放宽
		* @param unknown $t_h  缩放高
		* @param string $isCrop  是否裁剪
		* @param number $c_w  裁剪宽
		* @param number $c_h  裁剪高
		* @param number $c_x  裁剪坐标 x
		* @param number $c_y  裁剪坐标 y
		*/
		private function _resizeGif($t_w, $t_h, $isCrop=false, $c_w=0, $c_h=0, $c_x=0, $c_y=0){
			$dest = new Gmagick();
			$dest->newimage($t_w,$t_h,$this->fillcolor,$this->type);
			$color_transparent = new GmagickPixel("transparent"); //透明色
			$cout = 0;
			echo count($this->gmagick)."====";
			$images = $this->gmagick->coalesceImages();
			echo count($images)."----";
			foreach($images as $img){
				echo "-----cout:$cout--";
				$cout++;
				$page = $img->getImagePage();
				$tmp = new Gmagick();
				$tmp->newImage($page['width'], $page['height'], $color_transparent, 'gif');
				$tmp->compositeImage($img, Gmagick::COMPOSITE_OVER, $page['x'], $page['y']);

				$tmp->thumbnailImage ( $t_w, $t_h, true );
				if($isCrop){
					$tmp->cropImage($c_w, $c_h, $c_x, $c_y);
				}

				$dest->addImage($tmp);
				$dest->setImagePage($tmp->getImageWidth(), $tmp->getImageHeight(), 0, 0);
				$dest->setImageDelay($img->getImageDelay());
				$dest->setImageDispose($img->getImageDispose());
			}
			$this->gmagick->destroy();
			$this->gmagick = $dest;
		}

		/*
		* 构造新图
		* 尺寸和原来一致
		* 
		*/
		public function new_img($thumb_width,$thumb_height){

			//$gmagick->getImageWidth(),$gmagick->getImageHeight()

			$canvas = new Gmagick();
			$canvas->newimage($thumb_width,$thumb_height,$this->fillcolor,$this->type);
			//图片缩放效果,参数：宽度（为0时按高度等比缩放图片），高度（为0时按宽度等比缩放图片）
			$this->gmagick->scaleimage($thumb_width,$thumb_height,true);
			
			/* 取得缩图的实际大小 */
			$gw = $this->gmagick->getimagewidth();
			$gh = $this->gmagick->getimageheight();

			$x = ( $thumb_width-$gw) / 2;
			$y = ( $thumb_height-$gh ) / 2;
			//$tempgmagick = $this->gmagick;
			/*
			//组合图片，将两张图片组合为一张图
			$imgMain = new Gmagick(‘images/1-1.jpg’);
			$width = (int) ($imgMain->getimagewidth() /2) – 150;
			$imgBarcode = new Gmagick(‘images/1-2.jpg’);
			$imgMain->compositeimage($imgBarcode, 1, $width, 150);
			$imgMain->write(‘images/withBarcode.jpg’);
			**/
			$canvas->compositeimage($this->gmagick,Gmagick::COMPOSITE_OVER,$x,$y);
			//$canvas->write(strtolower($extension)=='png'?"png8:".$filepath:$filepath);
			$this->gmagick->destroy();
			$this->gmagick = $canvas;
		}

		// 保存到指定路径 
		public function save_to($path,$quality=80) { 
			//压缩图片质量 
			$this->gmagick->setImageFormat('JPEG'); 
			$this->gmagick->setImageCompression(Gmagick::COMPRESSION_JPEG); 
			$a = $this->gmagick->getImageCompressionQuality() * 0.60; 
			if ($a == 0) { 
				$a = 60; 
			} 
			$this->gmagick->setImageCompressionQuality($a); 
			$this->gmagick->stripImage(); 

			if ($this->type == 'gif') { 
				$this->gmagick->writeImages ( $path, true ); 
			} else { 
				$this->gmagick->writeImage ( $path ); 
			} 
		} 

		/*
		* 压缩图片质量
		*/
		public function image_quality($quality=80){
			//if($this->type=='png'){}
			$this->gmagick->setImageFormat('JPEG'); 
			$this->gmagick->setImageCompression(Gmagick::COMPRESSION_JPEG); 
			$a = $this->gmagick->getImageCompressionQuality() * 0.60; 
			if ($a == 0) { 
				$a = 60; 
			} 
			$this->gmagick->setImageCompressionQuality($a); 
			$this->gmagick->stripImage(); 
		}


        /**
		* 重加载 可以直接调用redis的原方法
		*/
		public function __call($func,$arg){
			//echo $func;
			if(!empty($this->gmagick)){
				return call_user_func_array(array($this->gmagick, $func),$arg);
			}
			return false;
		}

		public function close(){
			$this->gmagick->clear();
			$this->gmagick->destroy();
		}

		public function __destruct(){
			//关闭redis连接
			$this->close();
		}
	}