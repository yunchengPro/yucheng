<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>支付密码</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta name="format-detection" content="telephone=no" />
    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css"> -->
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}
    	
    	<style>  
.pwd-box{  
	
        width:310px;  
        margin: 0 auto;
          
        position: relative;  
        border: 1px solid #9f9fa0;  
        border-radius: 3px;  
		overflow:hidden;
		font-size: 14px;
    }  
    .pwd-box input[type="tel"]{  
        width: 100%;  
        height: 45px;  
        color: transparent;  
        position: absolute;  
        top: 0;  
        left: 0;  
        border: none;  
        font-size: 18px;  
        opacity: 0;  
        z-index: 1;  
        letter-spacing: 40px; 
        padding-left: 5px;   
        
    }  
    .fake-box input{  
        width: 48px;  
        height: 48px;  
        border: none;  
        border-right: 1px solid #e5e5e5;  
        text-align: center;  
        font-size: 30px;  
        border-radius: 0;
    }  
    .fake-box input:nth-last-child(1){  
        border:none;  
    }  
    
    .forget-pwd-box{
    	width: 310px;
    	margin: 10px auto;
    	padding: 0;
    	text-align: right;
    }
</style>  
</head>

<body>
   
    <div class="pay-pwd-container text-center">
        <div class="pay-pwd-title">输入6位数支付密码</div>
        <!-- onclick="javascript:window.location.href='pay-success.html'" -->
        <!--<input type="password" maxlength="6" placeholder="请输入6位支付密码" class="pay-pwd-inpt"/>-->
    </div>
    
    
   
    
    <div class="pwd-box">  
	    <input type="tel" maxlength="6" class="pwd-input" id="pwd-input">  
	    <div class="fake-box">  
	        <input type="password" readonly="">  
	        <input type="password" readonly="">  
	        <input type="password" readonly="">  
	        <input type="password" readonly="">  
	        <input type="password" readonly="">  
	        <input type="password" readonly="">  
	    </div>  
	    
	</div>
	

<script src="js/jquery.min.js"></script>
<script src="js/amazeui.min.js"></script>
<script>  
			var $input = $(".fake-box input");  
            var j = 0;

            $("#pwd-input").on("input", function() {  
                var pwd = $(this).val().trim();  
                for (var i = 0, len = pwd.length; i < len; i++) {  
                    $input.eq("" + i + "").val(pwd[i]);  
                }  
                $input.each(function() {  
                    var index = $(this).index();  
                    if (index >= len) {  
                        $(this).val("");  
                    }  
                });  
                if (len == 6) {  
                    var pwd = $("#pwd-input").val();
                    var customerid = "<?=$customerid?>";
                   
                    if(j >= 3){
                        //alert('密码错误次数过多');
                        layer.open({
                            content: '密码错误次数过多',
                            skin: 'msg',
                            time: 2 
                        });
                        return false;
                    }
                    $.ajax({
                        type:"post",
                        url:"/StoBusiness/Index/checkpassword",
                        data:{'pwd':pwd,'customerid':customerid},
                        async:true,
                        success:function(data){
                        
                            var _data=JSON.parse(data); 
                            if(_data.code == 200){
                                balancepay();
                            }else if(_data.code == 404){
                                //alert('参数错误');
                                layer.open({
                                    content: '参数错误',
                                    skin: 'msg',
                                    time: 2 
                                });
                                return false
                            }else{
                                
                                layer.open({
                                    content: '密码错误',
                                    skin: 'msg',
                                    time: 2 
                                });
                                j++;
                                return false
                               
                            }
                            
                        }
                    });
                   
                }  
            });  

        

            function balancepay(){
                var amount = "<?=$amount?>";
                var customerid = "<?=$customerid?>";
                var orderno = "<?=$orderno?>";
                var storeid = "<?=$storeid?>";
                var pwd = $("#pwd-input").val();
                $.ajax({
                        type:"post",
                        url:"/StoBusiness/Index/balancepay",
                        data:{'orderno':orderno,'amount':amount,'customerid':customerid,'pwd':pwd},
                        async:true,
                        success:function(data){
                           
                            $("#my-modal-loading").modal('close');
                           
                            if(data == '支付成功'){
                                //alert('支付成功');
                                layer.open({
                                    content: '支付成功',
                                    skin: 'msg',
                                    time: 2 
                                });
                                window.location.href = "/StoBusiness/Index/paysuccess?orderno=<?=$orderno?>";
                            }else{
                                // layer.open({
                                //     content: data,
                                //     skin: 'msg',
                                //     time: 2 
                                // });
                                alert(data); 
                                window.location.href = "/index/index/storeindex?storeid=138";
                            }
                        }
                });
            }
</script>  
</body>


</html>
