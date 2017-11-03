{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
<!-- content -->
	<header class="page-header">
			<div class="page-bar">
				<a href="/user/index/config">
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
        <a :href="setLoginPwdUrl">
            <div class="config-item">
                <div class="config-tip">登录密码</div>
                <div :class="['config-val',{'no':isloginpwd==0,'yes':isloginpwd==1}]" v-html="payType[isloginpwd]"></div>
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
            isloginpwd:0,
            payType:["未设置","已设置"],
            loginPwdType:["未设置","已设置"],
            setPayUrl:'/user/setting/setpay',
            setLoginPwdUrl:'/user/setting/loginpwd',
        },
        mounted:function(){
            // 初始化方法
            this.getIndexData();
        },
        methods:{
            getIndexData:function(){
                var _this = this;
                _this.$http.post(_this.apiUrl,{
                }).then(
                    function(res){
                        data = cl(res);
                        if(data.code == "200") {
                        	_this.mobile = data.data.userInfo.mobile;
                        	_this.payDec = data.data.userInfo.payDec;
                            _this.isloginpwd = data.data.userInfo.isloginpwd;
                            if(_this.payDec == 1) {
                                _this.setPayUrl = '/user/setting/updatepay';
                            }
                            if(_this.isloginpwd == 1) {
                                _this.setLoginPwdUrl = '/user/setting/validloginpwd';
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