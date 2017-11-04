<!DOCTYPE html>
<html>
	<head>
		  <meta charset="UTF-8">
		    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		    <title>邀请注册</title>
		    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		    <meta name="renderer" content="webkit">
		    <meta name="format-detection" content="telephone=no" />
		    <meta http-equiv="Cache-Control" content="no-siteapp" />
		    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css"> -->
		    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/amazeui.min.css">
		    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
		    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/invite.css">
		    <script src="<?=$publicDomain?>/mobile/js/jquery.min.js"></script>
			<script src="<?=$publicDomain?>/mobile/js/amazeui.min.js"></script>
			<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/jquery.lazyload.js" ></script>
			<script src="<?=$publicDomain?>/mobile/js/layer.js"></script>
	</head>
	<body>
		<style>
			
		</style>
		<div class="invite-niufen-wrap">
			<div class="process-box pro-1">
				<img src="<?=$publicDomain?>/mobile/img/icon/invite_4.png" />
				<div class="receive-box">
					<input type="tel" maxlength="11" placeholder="请输入手机号码接受邀请" id="receive-phone"/>
					<button type="button" class="receive-btn">立领100牛粮奖励金+300牛豆</button>
					
				</div>
				<div class="tip">注：转发邀请好友注册，可获得30牛粮奖励金+100牛豆</div>
				<span class="ic_arrow"></span>
			</div>
			<div class="process-box pro-2">
				<img src="<?=$publicDomain?>/mobile/img/icon/default2.png" data-original="<?=$publicDomain?>/mobile/img/icon/invite_5.png"  class="lazy"/>
				
				<div class="pro-desc-np">
					<div>用牛票（现金）</div>
					<div>在牛票专区买商品，</div>
					<div>得<span>牛粮</span>，划算！</div>
				</div>
				
			</div>
			<div class="process-box pro-3">
				<img src="<?=$publicDomain?>/mobile/img/icon/default2.png" data-original="<?=$publicDomain?>/mobile/img/icon/invite_6.png" class="lazy"/>
				<div class="pro-desc-nl">
					<div>用牛粮</div>
					<div>在实体店消费，</div>
					<div>得<span>牛豆</span>，靠谱！</div>
				</div>
				<div class="pro-desc-nd">
					<div>用牛票+牛豆</div>
					<div>在牛票+牛豆专区兑换商品，</div>
					<div>超值!</div>
				</div>
			</div>
			<div class="process-box pro-4">
				<img src="<?=$publicDomain?>/mobile/img/icon/default2.png" data-original="<?=$publicDomain?>/mobile/img/icon/invite_7.png" class="lazy"/>
				<!-- <div class="download-box">
					<a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt" class="download-link">
						下载  一分钱花三次
					</a>
				</div> -->
			</div>
		</div>
		<div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="my-modal-loading">
	      	<div class="am-modal-dialog">
	        <div class="am-modal-hd">正在载入...</div>
	        <div class="am-modal-bd-loading">
	          	<span class="am-icon-spinner am-icon-spin"></span>
	        </div>
	      	</div>
	    </div>
		<script>
		$(function(){
			//图片懒加载
			$(".lazy").lazyload({
				// placeholder : "img/default.png",
          		 failurelimit : 10,  
                 effect: "fadeIn"
			});
			
			//立领 按钮
			$(".receive-btn").click(function(){
				$("#my-modal-loading").modal();
				var phone=$("#receive-phone").val();
				var phoneReg = /^1[34578]\d{9}$/;
				var type = "<?=$type?>";
				var userid = "<?=$userids?>";
				var stocode = "<?=$stocode?>";
				var checkcode = "<?=$checkcode?>";
					
				if(phone==""){
					 layer.open({
	                    content: '请填写手机号码',
	                    skin: 'msg',
	                    time: 2 
	                  });
					$("#my-modal-loading").modal('close');
	                return false;
				}else if(phone.length<11){
					layer.open({
	                    content: '请填写11位数手机号码',
	                    skin: 'msg',
	                    time: 2 
	                 });
					$("#my-modal-loading").modal('close');
	                 return false;
				}else{
					//手机号格式验证
					 if (!(phoneReg.test(phone))) {
					 	layer.open({
		                    content: '手机号码有误',
		                    skin: 'msg',
		                    time: 2 
		                 });
					 	$("#my-modal-loading").modal('close');
		                return false;
					 }
					
					$("#my-modal-loading").modal();
					 $.ajax({
			            type:'POST',
			            data:{mobile:phone,userid:userid,stocode:stocode,checkcode:checkcode,type:type},
			            async:false,
			            traditional:true,
			            dataType:'json',
			            url:'/Customer/Index/doinviteniufen',
			            success:function(result){
			            	$("#my-modal-loading").modal('close');
			            	result = eval("("+result+")");
			                if(result.code != "200") {
			                	
			                    layer.open({
			                        content:result.msg,
			                        skin:'msg',
			                        time:2,
			                    });
			                    
			                    if(result.code =='4000'){
			                    	 $('html, body').animate({  
					                    scrollTop: $(".download-box").offset().top  
					                }, 1000); 
			                    }

			                }else if(result.code == "200"){
			                	
			                	layer.open({
			                        content:'成功领取奖励',
			                        skin:'msg',
			                        time:2,
			                    });
			                	window.location.href ='/Index/Index/register?loginType=register';
			                }
			            },
			            beforeSend:function(){

			            	
			            }
			        });
		
	                //$(this).attr("disabled",true);
				}
			});
			
			//下一屏
			 $(".ic_arrow").on("touchend",function(){
				 	
			     $('html, body').animate({  
                    scrollTop: $(".pro-2").offset().top  
                }, 1000); 
			 });  
//			$(document).on("pagecreate",".invite-niufen-wrap",function(){
//				
//				 $(".ic_arrow").on("tap",function(){
//				 	alert(11)
//			     $('html, body').animate({  
//                  scrollTop: $(".pro-2").offset().top  
//              }, 1000); 
//			 });  
//			});
			
		});
		
		/*$(window).bind("load", function() {    
			var timeout = setTimeout(function() {$(".lazy").trigger("sporty")}, 3000);    
		}); */
			
		</script>
	</body>
</html>
