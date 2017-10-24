{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
<!-- content -->
	<header class="page-header">
			<div class="page-bar">
				<a href="javascript:history.go(-1)">
					<img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
				</a>
			<div class="bar-title">账户安全</div>
		</div>
	</header>
	
	<section class="config-wrap">
		<a href="/user/setting/updatephone">
			<div class="config-item">
				<div class="config-tip">注册手机</div>
				<div class="config-val" v-html="mobile"></div>
				<i></i>
			</div>
		</a>
		<a :href="setPayUrl">
			<div class="config-item">
				<div class="config-tip">支付密码</div>
				<div :class="['config-val',{'no':payDec==0,'yes':payDec==1}]" v-html="payType[payDec]"></div>

				<i></i>
			</div>
		</a>
	</section>
<!-- end content -->
{include file="Pub/tail" /}
<script>
    var Vm = new Vue({
        el:'#app',
        data:{
            apiUrl:"/user/index/getIndexData",
            role:"<?=$role?>",
            mobile:'',
            payDec:0,
            payType:["未设置","已设置"],
            setPayUrl:'/user/setting/setpay'
        },
        mounted:function(){
            // 初始化方法
            this.getIndexData();
        },
        methods:{
            getIndexData:function(){
                var _this = this;
                _this.$http.post(_this.apiUrl,{
                    role:_this.role
                }).then(
                    function(res){
                        data = cl(res);
                        if(data.code == "200") {
                        	_this.mobile = data.data.userinfo.mobile;
                        	_this.payDec = data.data.userinfo.payDec;
                            if(_this.payDec == 1) {
                                _this.setPayUrl = '/user/setting/updatepay';
                            }
                        } else {
                            toast(data.msg);
                        }
                    },function(res){
                        toast("数据查询有异");
                    }
                );
            }
        },
        watch:{}
    });
</script>