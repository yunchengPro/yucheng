<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>发表评价</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    {include file="Pub/assetcss" /}
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/jQueryFileUpload/css/fileupload.css">
    {include file="Pub/assetjs" /}

    <!---上传图片的js-->
    <script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/vendor/jquery.ui.widget.js"></script>
    <script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/load-image.all.min.js"></script>
    <script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/canvas-to-blob.min.js"></script>
    <script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/jquery.iframe-transport.js"></script>
    <script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/jquery.fileupload.js"></script>
    <script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/jquery.fileupload-process.js"></script>
    <script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/jquery.fileupload-image.js"></script>
    <script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/jquery.fileupload-validate.js"></script>
    <script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/fileupload.js"></script>

    <style type="text/css">
            [class*=am-u-]+[class*=am-u-]:last-child { float: left;}
            .baguetteBox{margin-bottom: 1rem;}
            .baguetteBox .am-u-sm-12{padding: 0}
            .one-upload{
                min-width: 8.5rem;
                min-height: 8.5rem;
                position: relative;
                padding: 0 5px;
                margin-bottom: 1rem;
                text-align: center;
            }
            .one-upload .close{
                display: block;
                width: 13px;
                height: 13px;
                position: absolute;
                background: url(<?=$publicDomain?>/mobile/img/icon/close.png) 0 -13px no-repeat;
                background-size: 100%;
                z-index: 10;
                right: 0;
                top: -5px;
            }
            .fileupload{
                position: absolute;
                font-size: 18px;
                left: 0;
                top: 0;
                opacity: 0;
                -moz-opacity: 0;
                filter: alpha(opacity=0);
                cursor: pointer;
                min-height: 100%;
                width: 100%;
            }
            .camera{
              border: 1px dashed #999;
              height:100%;
              width: 100%;
              border-radius: 4px;

           /*   line-height: 50px;*/
             /* background-color: #f13437 ;*/
             color: #999;
              font-weight: bold;
            }
            .camera .plus{font-size: 40px;font-weight: normal;}
            
        </style>
</head>

<body>

    <div class="comment-container">
        <div class="comment-title">我要评价</div>
        <div class="comment-star">
            <i class="icon icon-star active"></i>
            <i class="icon icon-star active"></i>
            <i class="icon icon-star active"></i>
            <i class="icon icon-star active"></i>
            <i class="icon icon-star active"></i><span class="red">5.0</span>&nbsp;分
        </div>
        <input type="hidden" name="scores" value="5" id="scores" />
        <textarea rows="8" style="width: 100%;" placeholder="限100字内" id="content" maxlength="100"></textarea>


       <div class="am-g baguetteBox">
            
           <!--   <div class="am-u-sm-12">


                <div class="am-u-sm-4 one-upload">
                    <i class="close"></i>
                   <a href="#"><img src="<?=$publicDomain?>/mobile/img/quilt-1.png" /></a> 
                </div>
               <div class="am-u-sm-4 one-upload">
                    <i class="close"></i>
                    <a href="#"><img src="<?=$publicDomain?>/mobile/img/quilt-2.png" /></a>
                </div>
                <div class="am-u-sm-4 one-upload">
                   <i class="close"></i>
                    <a href="#"><img src="<?=$publicDomain?>/mobile/img/quilt-3.png" /></a>
                </div>
                <div class="am-u-sm-4 one-upload">
                     <i class="close"></i>
                    <a href="#"><img src="<?=$publicDomain?>/mobile/img/quilt-1.png" /></a>
                </div> 
                 <div class="am-u-sm-4 one-upload">
                
                    <input type="file" name="" multiple class="fileupload">
                    <div class="camera"></div>
                </div>
             </div>  -->
            
            <div class="button-group button-group-small radio">   
              
               
                <div id="uploadfiles_photos" class="uploadfiles" style="margin-top:5px;">
                    <input id="photos" name="photos" value="" type="hidden">

                </div>
                  <!-- <span class="btn btn-danger fileinput-button camera" ><span>添加图片</span><input name="file" multiple="" type="file">
                </span>&nbsp; -->

                 <!-- <div class="">


                    <div class="am-u-sm-4 one-upload">
                        <i class="close"></i>
                       <a href="#"><img src="<?=$publicDomain?>/mobile/img/quilt-1.png" /></a> 
                    </div>
                   <div class="am-u-sm-4 one-upload">
                        <i class="close"></i>
                        <a href="#"><img src="<?=$publicDomain?>/mobile/img/quilt-2.png" /></a>
                    </div>
                    <div class="am-u-sm-4 one-upload">
                       <i class="close"></i>
                        <a href="#"><img src="<?=$publicDomain?>/mobile/img/quilt-3.png" /></a>
                    </div>
                    <div class="am-u-sm-4 one-upload">
                         <i class="close"></i>
                        <a href="#"><img src="<?=$publicDomain?>/mobile/img/quilt-1.png" /></a>
                    </div> 
                    
                 </div> -->

                    <div class="am-u-sm-4 one-upload">
                
                    
                    <div class=" fileinput-button camera " id="photos_upload" name="photos_upload">
                        <input name="file" multiple="" type="file">
                         <span class="plus">+</span><br/>
                         <span>上传图片</span>
                    </div>

                </div>
                
                <script type="text/javascript">
                    fileupload('photos','{"url":"\/uploadfile\<?=$publicDomain?>/mobile/jQueryFileUpload.php","domain":"\/\/nnhtest.oss-cn-shenzhen.aliyuncs.com\/","maxFileSize":4194304,"maxNumberOfFiles":5,"savefileurl":"\/Sys\/upload\/getfile","getParamUrl":"\/Sys\/upload\/policy","server_type":"NNH\/images","formData":{"server_type":"NNH\/images"}}');
                </script>
            </div>

        </div>
     

    
                    
        <div><a href="#" type="button" class="am-btn am-btn-danger am-btn-block">发表评价</a></div>
    </div>
</body>

<script>
    $(function(){
        
        setUploadImg();
        $(window).resize(function(){
            setUploadImg();
        });
    });
    
    //动态设置商品图片宽高
    function setUploadImg(){
        pro_with=$(".one-upload").width();
        //这里设置宽高一样
        $(".one-upload").css({
            "with":pro_with,
            'height':pro_with
        });
    }

    $(function(){
        var num = 0;
        $(".comment-star i").on("click",function(){
            var _this = $(this);
            if(_this.hasClass('active')){
                _this.removeClass('active').nextAll().removeClass('active');
                num = parseInt(_this.index());
            }else{
                _this.addClass('active').prevAll().addClass('active');
                num = parseInt(_this.index()) + 1;
            }
            $(".red").html(num+'.0');
            $("#scores").val(num);
        })

        $(".am-btn-block").on("click",function(){

            var content = $("#content").val();

            var scores = $("#scores").val();

            var photos = $("#photos").val();
            if(scores < 0  || scores > 5){
                //alert('评分错误');
                layer.open({
                            content: '评分错误',
                            skin: 'msg',
                            time: 2 
                        });
                return false;
            }

            if(content.length <= 0 ){

                //alert('评论内容不能为空');
                 layer.open({
                            content: '评论内容不能为空',
                            skin: 'msg',
                            time: 2 
                        });
                return false;
            }

            if(content.length > 200){
                //alert('评论字数超出限制');
                layer.open({
                            content: '评论字数超出限制',
                            skin: 'msg',
                            time: 2 
                        });
                return false;
            }
            var shopid = '<?=$order_row['businessid']?>';
             //提交订单
            $.get("/StoBusiness/Index/doAddComment?orderno=<?=$order_row['pay_code']?>&customerid=<?=$order_row['customerid']?>&content="+content + "&scores=" + scores + "&photos=" + photos, function(data){
                    //alert('评价成功');
                     layer.open({
                            content: '评价成功',
                            skin: 'msg',
                            time: 2 
                        });
                    window.location.href="/index/index/storeindex?storeid="+shopid;
              
            });

        });
    });
</script>
</html>
