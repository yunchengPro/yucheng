<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="format-detection" content="telephone=no" />
    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css"> -->
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}
    	
    <style>
    	.verify-wrap .item{
    		height: 45px;
    		line-height: 45px;
    		padding:0 10px;
    		position: relative;
    	}
    	.verify-wrap .item input[type=tel]{
    		
    		 width: 100%;  
	        height: 45px;  
	        color: transparent; 
	      position: absolute; 
	        top: 0;  
	        left: 0;  
	        border: none;  
	        font-size: 18px;  
	       opacity: 0;
	        z-index: 10;  
  			padding-left: 10px;
  			letter-spacing: 1px; 
  			 
   		}
   		.verify-wrap .item .showBox{
   			  width: 100%;
		    height: 45px;
		    /* color: transparent; */
		    position: absolute;
		    top: 0;
		    left: 0;
		    border: none;
		    font-size: 18px;
		   
		    z-index: 1;
		    padding-left: 10px;
   		}
    	.verify-wrap .eye-half{
    		z-index: 20;
    		position: absolute;
    		top: 8px;
    		right: 10px;
    		height: 30px;
    		width: 30px;
    		background: url(/mobile/img/icon/eye-half.png) center no-repeat;
    		background-size:100% ;
    	}
    	
    	.verify-wrap .eye{
    		z-index: 20;
    		position: absolute;
    		top: 8px;
    		right: 10px;
    		height: 30px;
    		width: 30px;
    		background: url(/mobile/img/icon/eye.png) center no-repeat;
    		background-size:100% ;
    	}
    	.verify-wrap .item button[disabled]{
    		background: #B8B8B8;
    		border: #B8B8B8;
    	}
    </style>
</head>

<body style="background: #EDEDED;">
   <!--  <header data-am-widget="header" class="am-header am-header-default am-header-fixed">
        <div class="am-header-left am-header-nav">
            <a href="pay.html">
                <i class="am-icon-angle-left am-icon-md gray"></i>
            </a>
        </div>
        <h1 class="am-header-title">
            <a href="#title-link">修改支付密码</a>
        </h1>
    </header> -->
    <div class="verify-wrap">
    	
    	<div class="item bg-white">
    		
    		<input id="pwd-input" type="tel" maxlength="6" />
    		<input type="password" class="showBox" placeholder="请输入6位数字支付密码"/>
    		<span id="eye" class="eye-half"></span>
    		
    	</div>
    	<div class="item"></div>
    	<div class="item">
    		<button class="am-btn am-btn-danger am-btn-block" id="sure-btn" disabled="disabled">确定</button>
    	</div>
    </div>
    


<script>
	$("#eye").on("click",function(){
		$(this).toggleClass("eye","eye-half");
		if($(this).hasClass("eye")){
			$(".showBox").attr("type","text");
		}else{
			$(".showBox").attr("type","password");
		}
	});
	
	
	var $input = $(".showBox");  
            $("#pwd-input").on("input", function() {  
                var pwd = $(this).val().trim();  
                $input.val(pwd);
                
                $("#sure-btn").attr("disabled",false);
             
            }); 
    $("#sure-btn").on("click",function(){
      
        var paypwd = $("#pwd-input").val().trim();
        var reg = new RegExp(/^\d{6}\b/);

        if(!reg.test(paypwd)){
            layer.open({
                content: '请输入6位数字支付密码',
                skin: 'msg',
                time: 2 
            });
            return false;
        }

         $.ajax({
                type:'GET',
                dataType:'json',
                url:"/StoBusiness/Index/dochagepassword?customerid=<?=$customerid?>&paypwd=" + paypwd,
                success: function(result){
                    var _data=JSON.parse(result); 
                    if(_data.code == 200){
                        //alert('验证码正确');
                        layer.open({
                            content: '成功修改',
                            skin: 'msg',
                            time: 2 
                        });
                        
                        window.location.href = "/StoBusiness/index/setpayamount?business_code=<?=$business_code?>";

                    }else if(_data.code == 201){
                        layer.open({
                            content: '请输入6位数字支付密码',
                            skin: 'msg',
                            time: 2 
                        });
                        return false;
                    }else{
                        layer.open({
                            content: '修改失败',
                            skin: 'msg',
                            time: 2 
                        });
                        return false;
                    }
                }
            });
    }); 
</script>

</html>
