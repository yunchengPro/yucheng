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
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}
    	
    <style>
    	.verify-wrap .item{
    		height: 45px;
    		line-height: 45px;
    		padding:0 10px;
    		position: relative;
    	}
    	.verify-wrap .item input{width: 100%;    
    		height: 45px;
   		 	color: #333;
   
   			 outline: 0;
    		position: absolute;
   		}
    	.verify-wrap .get-code{
    		position: absolute;
    		top: 5px;
    		right: 10px;
    		font-size: 14px;
    		background: #F13437;
    		color: #FFFFFF;
    		border-radius: 4px;
    		height: 35px;
    		padding: 0 10px;
    	}
    </style>
</head>

<body style="background: #EDEDED;">
  
    <div class="verify-wrap">
    	<div class="item">
    		<span>手机号：</span><span class="gray"><?=$mobile?></span>
    	</div>
    	<div class="item bg-white">
    		<input type="tel" id="valicode" maxlength="6" />
    		<button class="get-code" id="ko" onclick="settime()" >获取验证码</button>
    		
    	</div>
    	<div class="item"></div>
    	<div class="item">
    		<button onclick="checkvalicode()" class="am-btn am-btn-danger am-btn-block">下一步</button>
    	</div>
    </div>


<script type="text/javascript">
    
     var countdown=60; 
        var timeout;
        function settime() { 

            var mobile = "<?=$realmobile?>";
            if(mobile ==''){
               // alert('请填写手机号码');
               
                  layer.open({
                    content: '请填写手机号码',
                    skin: 'msg',
                    time: 2 
                  });
                return false
            }
            if (countdown == 0) { 
                $("#ko").attr("disabled",false);    
                $("#ko").html("获取验证码"); 
                countdown = 60; 
                clearTimeout(timeout);
               // return;
            }else{ 

                if(countdown == 60){

                    $.ajax({
                        type:'GET',
                        dataType:'json',
                        url:"/StoBusiness/Indextest/Send?mobile=" + mobile,
                        success: function(result){
                            if(result.code == 200){
                                //alert('发送成功');
                                layer.open({
                                    content: '发送成功',
                                    skin: 'msg',
                                    time: 2 
                                });
                            }
                        }
                    });
                }
               
                $("#ko").attr("disabled", true); 
                $("#ko").html("重新发送(" + countdown + ")"); 
                countdown--;

                timeout= setTimeout(function() { 
                    settime() 
                },1000); 
            } 

           
        } 


        function checkvalicode(){
          
            var valicode = $("#valicode").val();
            if(valicode == ''){
                layer.open({
                    content: '验证码不能为空',
                    skin: 'msg',
                    time: 2 
                });
                return false;
            }
            $.ajax({
                type:'GET',
                dataType:'json',
                url:"/StoBusiness/Indextest/checkvalicode?mobile=<?=$realmobile?>&valicode=" + valicode,
                success: function(result){
                    var _data=JSON.parse(result); 
                    if(_data.code == 200){
                         //alert('验证码正确');
                          layer.open({
                            content: '验证码正确',
                            skin: 'msg',
                            time: 2 
                        });
                        window.location.href = "/StoBusiness/Indextest/changepassword?customerid=<?=$customerid?>&business_code=<?=$business_code?>";
                    }else{
                        layer.open({
                            content: '验证码错误',
                            skin: 'msg',
                            time: 2 
                        });
                        return false;
                    }
                }
            });
        }
</script>

</html>
