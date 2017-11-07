<!DOCTYPE html>
<html style="height: 100%;">


{include file="Pub/header" /}
	
	<div class="mall-good-caetes">
		<div class="left-cates">

		<div class="cate " :id="'cate-'+category.cateId" :data-key="category.cateId" v-for="category in categorylist">
			<span class="tl-ellipsis" v-html="category.cateName"></span>
		</div>
		<!-- 	<div class="cate active" id="cate-1" data-key="1">
				<span class="tl-ellipsis">家居家纺</span>
			</div>
			<div class="cate " id="cate-2" data-key="2">
				<span class="tl-ellipsis">手机数码</span>
			</div>
			<div class="cate" id="cate-3" data-key="3">
				<span class="tl-ellipsis">家用电器</span>
			</div>
			<div class="cate" id="cate-4" data-key="4">
				<span class="tl-ellipsis">运动户外</span>
			</div>
			<div class="cate"id="cate-5" data-key="5">
				<span class="tl-ellipsis">鞋靴箱包</span>
			</div>
			<div class="cate" id="cate-6" data-key="6">
				<span class="tl-ellipsis">个护美妆</span>
			</div>
			<div class="cate" id="cate-7" data-key="7">
				<span class="tl-ellipsis">服装配饰</span>
			</div>
			<div class="cate" id="cate-8" data-key="8">
				<span class="tl-ellipsis">食品饮料</span>
			</div>
			<div class="cate" id="cate-9" data-key="9">
				<span class="tl-ellipsis">医药保健</span>
			</div>
			<div class="cate" id="cate-10" data-key="10">
				<span class="tl-ellipsis">三农产品</span>
			</div>
			
			<div class="cate" id="cate-11" data-key="11">
				<span class="tl-ellipsis">服装配饰</span>
			</div>
			<div class="cate" id="cate-12" data-key="12">
				<span class="tl-ellipsis">食品饮料</span>
			</div>
			<div class="cate" id="cate-13" data-key="13">
				<span class="tl-ellipsis">医药保健</span>
			</div>
			<div class="cate" id="cate-14" data-key="14">
				<span class="tl-ellipsis">三农产品</span>
			</div>
			<div class="cate" id="cate-15" data-key="15">
				<span class="tl-ellipsis">汽车用品</span>
			</div>
				 -->
		</div>
		<div class="right-content">
			<div id="mainwrap">
				<div class="foodlistwrap" style="transform: translate(0px, 0px) translateZ(0px);"  >
					<!--分类商品-->
					<div class="one-class" :id="'list-'+category.cateId" :data-tagid="category.cateId" v-for="category in categorylist">
						<div class="cate-name">
							<h4 v-html="category.cateName"></h4>
						</div>
						<div class="cate-list-wrap">
							<div class="tl-grid">
								<div class="tl-grid-1-3" v-for="child in category.childs">
									<a href="#">
										<img :src="child.category_icon" />
										<p v-html="child.cname"></p>
									</a>
								</div>
								
								
								
								
							</div>
							
							
						</div>
					</div>
					<!--/end分类商品-->
					
					<!--分类商品-->

					<!--/end分类商品-->
					<!--分类商品-->
					
					<!--/end分类商品-->
					
					<!--分类商品-->
					
					<!--/end分类商品-->
					
					<!--分类商品-->
					
					<!--/end分类商品-->
					
					<!--分类商品-->
					
					<!--/end分类商品-->
					
					<!--分类商品-->
				
					<!--/end分类商品-->
					
					<!--分类商品-->
					
					<!--/end分类商品-->
					
					<!--分类商品-->
				
					<!--/end分类商品-->
					
					<!--分类商品-->
					
					<!--/end分类商品-->
					
					
				
				
				
				</div>
			</div>
		</div>
	</div>
{include file="Pub/tail" /}
<style>

	body{overflow: hidden;}
	.mall-good-caetes{position: absolute;top: 0rem;width: 100%;bottom: 0;right: 0;    overflow: hidden;}
	.mall-good-caetes .left-cates{width:95px;position: absolute;bottom: 0;right: 0;left: 0;top: 0;overflow: auto;height: 100%;background: #FFFFFF;}
	.left-cates .cate{background: #FFFFFF;height:4.2rem;line-height: 4.2rem;padding-left: 1.4rem;border-left: 2px solid transparent;}
	.left-cates .cate.active{background: #EEEEEE;color: #F13437;}
	.left-cates .cate a{color: #333333;width: 100%;display: inline-block;}
	.left-cates .cate.active a{color: #F13437;}
	.mall-good-caetes .right-content{-ms-touch-action:none;position: absolute;bottom: 0;right: 0;left: 95px;top: 0;overflow: hidden;background: #EEEEEE;}
	
	.one-class .cate-name{height: 4.4rem;line-height: 4.4rem;}
	.one-class .cate-name h4{position: relative;font-weight: normal;text-align: center;color: #999999;}
	.one-class .cate-list-wrap .tl-grid-1-3{padding: 10px;}
	.one-class .cate-list-wrap p{text-align: center;font-size: 12px;margin-top: 5px;}
	
	.pos-a{}
	.one-c-good{position: relative;margin: 9px 12px 0 9px;border-bottom: 1px solid #EEEEEE;padding-bottom: 9px;height: 7.4rem;}
	.one-c-good a{color: #333333;}
	.one-c-good:last-child{border-bottom: 0;}
	.one-c-good img{width: 9.5rem;height: 6.3rem;}
	.one-c-good .c-good-name{position: absolute;left: 10.4rem;top: 0;}
	.one-c-good .c-good-price{position: absolute;left: 10.4rem;bottom: 9px;}
	
	.foodlistwrap{
		    transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1);
	    transition-duration: 0ms;
	    
	}
</style>
	<script type="text/javascript">
		$(function(){
/*			$(".cate").click(function(){
				$(this).addClass("active").siblings().removeClass("active");
			});*/
			
			$(".cate").click(function(){
				var _key=$(this).data("key");
				//对应右侧列表距离顶部位置的高度
				var slideY= $("#list-"+_key).offset().top.toFixed(2);
				
				var translates = $(".foodlistwrap").css("transform");
				//var translateX=parseFloat(translates.substring(7).split(',')[4]);
				var translateY=parseFloat(translates.substring(7).split(',')[5]);
				
				translateY=translateY-slideY;
				 $(".foodlistwrap").css({
                 	"transition-timing-function": "cubic-bezier(0.1, 0.57, 0.1, 1)",
                 	 "transition-duration": "600ms",
                 	 "transform":  "translate(0px,"+translateY+"px)"
                 });
                 
                 $(this).addClass("active").siblings().removeClass("active");
			});
			
			
			$(".one-c-good a").on("touchend",function(){
				window.location.href=$(this).attr('href');
			});
		});
	</script>
	
	<script>
		   //全局变量，触摸开始位置  
            var startX = 0, startY = 0;  
              
            //touchstart事件  
            function touchSatrtFunc(evt) {  
                 
                	evt.stopPropagation();
                    evt.preventDefault(); //阻止触摸时浏览器的缩放、滚动条滚动等  
  
                    var touch = evt.touches[0]; //获取第一个触点  
                    var x = Number(touch.pageX); //页面触点X坐标  
                    var y = Number(touch.pageY); //页面触点Y坐标  
                    //记录触点初始位置  
                    startX = x;  
                    startY = y;  
  
            }  
  
            //touchmove事件，这个事件无法获取坐标  
            function touchMoveFunc(evt) {  
                 
	            	evt.stopPropagation();
	                evt.preventDefault(); //阻止触摸时浏览器的缩放、滚动条滚动等  
	                var touch = evt.touches[0]; //获取第一个触点  
	                var x = Number(touch.pageX); //页面触点X坐标  
	                var y = Number(touch.pageY); //页面触点Y坐标  
	  
	                //判断滑动方向  
	                // x - startX != 0 左右滑动
					//y - startY != 0 上下滑动
	                      
	               
	                var translates = $(".foodlistwrap").css("transform");
					var translateX=parseFloat(translates.substring(7).split(',')[4]);
					var translateY=parseFloat(translates.substring(7).split(',')[5]);
					
					/*if(-translateY+$(".right-content").height()>$(".foodlistwrap").height()){
						translateY=-($(".foodlistwrap").height()-$(".right-content").height());
					}*/
					
					var tY=translateY+(y-startY);
					if(tY>0){
						tY=0;
					}
					if(-tY+$(".right-content").height()>$(".foodlistwrap").height()){
						tY=-($(".foodlistwrap").height()-$(".right-content").height());
					}
					
					//滚动
	                 $(".foodlistwrap").css({
	                 	"transition-timing-function": "cubic-bezier(0.1, 0.57, 0.1, 1)",
	                 	 "transition-duration": "600ms",
	                 	 "transform":  "translate(0px,"+tY+"px)"
	                 });
                    
            }  
  
            //touchend事件  
            function touchEndFunc(evt) {  
                
	            	//evt.stopPropagation();
	                //evt.preventDefault(); //阻止触摸时浏览器的缩放、滚动条滚动等  
	  
	                $(".foodlistwrap").css({
	                 	"transition-timing-function": "cubic-bezier(0.1, 0.57, 0.1, 1)",
	                 	 "transition-duration": "0ms"
	                 	 
	                });
		                 
	                var translates = $(".foodlistwrap").css("transform");
					//var translateX=parseFloat(translates.substring(7).split(',')[4]);
					var translateY=parseFloat(translates.substring(7).split(',')[5]);
					
		             var hMin=0;
	                 var hMax=0;
	                 $(".one-class").each(function(idx,ele){
		                 	//累计tag 高度
		                 	hMax+=$(ele).height();
		                 	//滑动结束，translateY 的值在当前tag 区域内
		                 	if(-translateY>=hMin&&-translateY<hMax){
		                 		//获取左侧菜单对应id
		                 		var tagid=$(ele).data("tagid");
		                 		//左侧菜单对应选中
		                 		$("#cate-"+tagid).addClass("active").siblings().removeClass("active");
		                 		//循环终止
		                 		return false;	
		                 		/*return false：将停止循环 (就像在普通的循环中使用 'break')。
		                 		 * return true：跳至下一个循环(就像在普通的循环中使用'continue')。*/
		                 		
		                 	}else{
		                 		//不在区域，最小区域值变大
		                 		hMin=hMax;
		                 	}
	                 });
                
               
            }  
  
            //绑定事件  
            function bindEvent() {
            	var rightWrap=document.getElementById("mainwrap");
                rightWrap.addEventListener('touchstart', touchSatrtFunc, false);  
                rightWrap.addEventListener('touchmove', touchMoveFunc, false);  
                rightWrap.addEventListener('touchend', touchEndFunc, false);  
            }  
            
            bindEvent();
	</script>
<script>
   new Vue({
    el:'#app',
    data:{
        categorylist:[],
    },

    methods:{
        getcategorylist:function(){
            
            var _this=this;

            _this.$http.post('/product/category/getcategory',{
               
            }).then(
                function (res) {
                    var _this=this;
                    data = eval("("+res.body+")");
                    //data = cl(res);
                    if(data.code=='200'){
                       
                        _this.categorylist = data.data;
                        
                    }else{

                        layer.open({
                            content: data.msg,
                            skin: 'msg',
                            time: 2 
                        });
                        return false;

                    }
                    //$("#city").val('');
                },function (res) {
                    // 处理失败的结果
                    //console.log(res);
                    layer.open({
                        content: '加载数据错误！请重新请求',
                        skin: 'msg',
                        time: 2 
                    });
                }
            );
        },
    },
    mounted:function(){
     	var _this = this;
        _this.getcategorylist();
    }
  });
</script>
