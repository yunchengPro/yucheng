{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
<!-- content -->
	<header class="page-header">	
		<div class="page-bar">	
			<a href="javascript:history.go(-1)">
				<span class="back-ico"></span>
			</a>
			<span class="bar-title" v-html="title"></span>
		</div>
	</header>
	<div class="goods-order">
	    <div class="order-body">
			<div class="order-list">
				<!--已付款-->
				<div class="one-order" style="margin-bottom: 0;border-bottom: 0;">
					<div class="shop-stuas">
						<a href="" class="pos-r"><span v-html="businessname"></span><i></i></a>
					</div>
					<a href="goodsOrderDetail-1.html" v-for="list in lists">
						<div class="order-info-box">
							<div class="order-img">
								<img :src="list.thumb!=''?list.thumb:defaultsrc" />
							</div>
							<div class="order-info">
								<div class="name tl-ellipsis-2" v-html="list.productname"></div>
								<div class="text-r">X<span v-html="list.productnum"></span></div>
								<div class="order-time"><span v-html="list.prouctprice"></span>元</div>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<section class="comment-container">
        <div class="comment-title">我要评价</div>
        <div class="comment-star">
            <i class="icon icon-star"></i>
            <i class="icon icon-star"></i>
            <i class="icon icon-star"></i>
            <i class="icon icon-star"></i>
            <i class="icon icon-star"></i><span class="red">0.0</span>&nbsp;分
        </div>
        <textarea rows="8" style="width: 100%;" placeholder="限200字内" v-model="content"></textarea>
        <a href="javascript:void(0);" @click="add" type="button" class="am-btn am-btn-danger am-btn-block">发表评价</a>
    </section>
<!-- end content -->
{include file="Pub/tail" /}
<script>
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

            Vm.scores = num;
        })
    });

    var Vm = new Vue({
		el:'#app',
		data:{
			evaluateOrderInfoUrl:"/order/evaluateorder/evaluateorderinfo",
			addEvaluateOrderUrl:"/order/evaluateorder/addevaluateorder",
			title:'<?=$title?>',
			orderno:'<?=$orderno?>',
			customerid:'<?=$customerid?>',
			lists:[],
			businessname:'',
			defaultsrc:'<?=$publicDomain?>/mobile/img/icon/default.png',
			checktoken:'<?=$checktoken?>',
			scores:0,
			content:'',
			productid:'',
			skuid:'',
		},
		mounted:function(){
			this.getEvaluateOrderInfo();
		},
		methods:{
			getEvaluateOrderInfo:function() {
				var _this = this;
				_this.$http.post(_this.evaluateOrderInfoUrl,{
					orderno:_this.orderno
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							_this.lists = data.data;
							_this.businessname = data.data[0].businessname!='' ? data.data[0].businessname:'&nbsp';
							_this.productid = data.data[0].productid;
							_this.skuid = data.data[0].skuid;
							console.log(data);
						} else {
							toast(data.msg);
						}
					}, function(res) {
						toast("操作有异")
					}
				);
			},
			add:function() {
				var _this = this;
				if(_this.scores <= 0) {
					toast("请选择评分");
					return false;
				}
				if(_this.content == '') {
					toast("请填写评价内容");
					return false;
				}

				// 拼接字符串
				var evaluateinfo = '{customerid:'+_this.customerid+',isanonymous:1,productid:'+_this.productid+',scores:'+_this.scores+',orderno:'+_this.orderno+
									',content:'+_this.content+',skuid:'+_this.skuid+',evaluate_images:""';
				loadtip({content:'提交中'});
				_this.$http.post(_this.addEvaluateOrderUrl,{
					orderno:_this.orderno,checktoken:_this.checktoken,EvaluateArr:evaluateinfo
				}).then(
					function(res) {
						data = cl(res);
						if(data.code == "200") {
							// 返回到订单列表页
							loadtip({
                                close:true,
                                alert:'提交成功',
                                urlto:'/order/index/orderlist'
                            });
						} else {
							loadtip({
                                close:true,
                                alert:data.msg
                            });
						}
					}, function(res) {
						loadtip({
                            close:true,
                            alert:'操作有异'
                        });
					}
				);
			}
		},
		watch:{},
	});
</script>