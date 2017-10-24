<!DOCTYPE html>
<html class="js cssanimations" lang="en">

	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>{$title}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="renderer" content="webkit">
		<meta http-equiv="Cache-Control" content="no-siteapp">
		<!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2//mobile/css/amazeui.min.css"> -->
		{include file="Pub/assetcss" /}
    	{include file="Pub/assetjs" /}
		<style>
			
			.build-body{background: url(/mobile/img/icon/BG1.png) #F13437 no-repeat;background-size:100% ;height: 100%;}
			/*.build-body{position: static;}
			.build-bg{background: url(img/icon/BG.png) no-repeat;background-size:cover ;height: 100%;position: absolute;
    width: 100%;
    left: 0;
    top: 0;
    bottom: 0;
    right: 0;
   }*/
			.build-wrap{padding-top: 160px;display: -webkit-box;-webkit-box-align: center;-webkit-box-orient: vertical;}
			.build-wrap .build-item{width: 280px;margin-bottom: 25px;position: relative;text-align: center;}
			
			@media only screen and (min-width:350px ) {
				.build-wrap .build-item{
					width: 300px;
				}
			}
			@media only screen and (min-width:400px ) {
				.build-wrap .build-item{
					width: 320px;
				}
			}
			.build-item input,
			.build-item button{
				width: 100%;
				height: 44px;
			    padding: 0 10px;
				outline: 0;
				border-radius: 4px;
                border: 0;
			}
			.build-item .get-vcode{
				width: 68px;
				height: 26px;
				position: absolute;
				right: 9px;
				top:9px;
				font-size: 10px;
				padding: 0;
				background: #F13437;
				color: #FFFFFF;
			}
			.build-item .get-vcode:disabled { background: #c1c1c1;}
			.build-item .build-btn{background: #faa248;color: #FFFFFF;font-size: 18px;}
			
		</style>
	</head>

	<body class="build-body">
		<div class="build-bg"></div>
		<div class="build-wrap">
			<div>
				<div class="build-item">
					<input type="tel" id="mobile" maxlength="11" placeholder="请输入您的手机号"/>
				</div>
				<div class="build-item">
					<input type="tel" id="valicode" maxlength="6" placeholder="请输入验证码"/>
					<button class="get-vcode" id="getcode" onclick="wait(this)">获取验证码</button>
				</div>
				
				<div class="build-item">
					<button class="build-btn" id="build" >确认建立关系</button>
				</div>
			</div>
		</div>


<script>
 /*function name wait
		 * param dom
		 * desc 验证码按钮倒计时 （点击触发 wait(this)）
		 * talon 2016-10-25 16:57:53
		 * */
	  var countdown=60; 
        var timeout;
        function wait(dom) { 

            var mobile = $("#mobile").val();
            if(mobile ==''){
               // alert('请填写手机号码');
               
                  layer.open({
                    content: '请填写手机号码',
                    skin: 'msg',
                    time: 2 
                  });
                return false
            }
            if(mobile.length<11){
                 layer.open({
                    content: '请填写手机11位号码',
                    skin: 'msg',
                    time: 2 
                  });
                return false
            }
            if (countdown == 0) { 
                $("#getcode").attr("disabled",false);    
                $("#getcode").html("获取验证码"); 
                countdown = 60; 
                clearTimeout(timeout);
               // return;
            }else{ 

                if(countdown == 60){

                    $.ajax({
                        type:'GET',
                        dataType:'json',
                        url:"/Index/Index/Send?mobile=" + mobile,
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
                    
                     // layer.open({
                     //        content: '发送成功',
                     //        skin: 'msg',
                     //        time: 2 
                     //    });
                }
               
                $("#getcode").attr("disabled", true); 
                $("#getcode").html(countdown + "s"); 
                countdown--;

                timeout= setTimeout(function() { 
                    wait(dom) 
                },1000); 
            } 

           
        }

        $(function() {

        	$("#build").click(function(){
        		
        		if($("#mobile").val() == ''){
                    //alert('请填写手机号码');
                    layer.open({
                        content: '请填写手机号码',
                        skin: 'msg',
                        time: 2 
                    });
                    return false;
                }

                if($("#mobile").val().length<11){
                    layer.open({
                        content: '请填写11位手机号码',
                        skin: 'msg',
                        time: 2 
                    });
                    return false;
                }

                if($("#valicode").val() == ''){
                    //alert('验证码不能为空');
                     layer.open({
                        content: '验证码不能为空',
                        skin: 'msg',
                        time: 2 
                    });
                    return false
                }

                $.ajax({
                        type:'post',
                       
                        data:{
                            "mobile":$("#mobile").val(),
                            "valicode": $("#valicode").val(),
                            "userid":"<?=$userid?>",
                            "stocode":"<?=$stocode?>",
                            'checkcode':"<?=$checkcode?>",
                            'roleid':"<?=$role?>"
                        },
                        url:"/Customer/Relation/dobuild",
                        success: function(result){
                           //console.log(result);
                          
                           result = eval("("+result+")");
                            if(result.code == 200){
                           //  alert(result.data.data);
                           // return false;
                              window.location.href = result.data.data;
                            }else{
                                layer.open({
                                    content: result.msg,
                                    skin: 'msg',
                                    time: 2 
                                });
                                return false;
                            }
                            // alert($("#ischeck").val());
                        }
                    });
        	});

        });
  </script>
	</body>

</html>