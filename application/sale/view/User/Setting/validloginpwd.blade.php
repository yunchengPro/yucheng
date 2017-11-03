{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
<!-- content -->
	<header class="page-header">	
		<div class="page-bar">	
			<a href="/user/setting/safeindex">
				<span class="back-ico"></span>
			</a>
			<span class="bar-title" v-html="title"></span>
		</div>
	</header>

	<section class="config-wrap modify-phone" style="margin-top: 0;">
		<div class="config-item verify">
			<span>手机号码：</span>
			<span class="phone" v-html="mobile"></span>
		</div>
		<div class="config-item">
			<input type="tel" maxlength="11" placeholder="请输入验证码" v-model.trim="valicode"/>
			<button class="get-vercode" type="button" v-bind:disabled="dis" @click="send">获取验证码</button>
		</div>
	</section>
	<div class="modify-wrap">
		<button type="button" class="sure-btn" v-bind:disabled="nextDis" @click="next">下一步</button>
	</div>
<!-- end content -->
{include file="Pub/tail" /}
<script>
	var Vm = new Vue({
        el:'#app',
        data:{
            title:"<?=$title?>",
            payDec:"<?=$payDec?>",
            mobile:"<?=$mobile?>",
            completemobile:"<?=$completemobile?>",
            checktoken:"<?=$checktoken?>",
            valicode:"",
            dis:false,
            nextDis:true
        },
        mounted:function(){
            // 初始化方法
        },
        methods:{
        	send:function() {
        		var _this = this;
        		var sendUrl = "/user/setting/sendvalicodeloginpwd";
        		_this.$http.post(sendUrl,{
        			mobile:_this.completemobile
        		}).then(
        			function(res) {
        				var data = cl(res);
        				if(data.code == "200") {
        					toast("发送成功");
        				} else {
        					toast(data.msg);
        				}
        			}, function(res) {
        				toast("操作有异");
        			}
        		);
        	},
        	next:function() {
        		var _this = this;
        		var nextUrl = "/user/setting/validateloginpwd";

                loadtip({content:'校验中'});
        		_this.$http.post(nextUrl,{
        			valicode:_this.valicode,mobile:_this.completemobile
        		}).then(
        			function(res) {
        				var data = cl(res);
        				if(data.code == "200") {
                            loadtip({
                                close:true,
                                alert:'校验通过',
                                urlto:'/user/setting/updatepwdnumber'
                            });
                            // LinkTo("/user/setting/updatepaynumber");
        				} else {
                            loadtip({
                                close:true,
                                alert:data.msg
                            });
        					// toast(data.msg);
        				}
        			}, function(res) {
                        loadtip({
                            close:true,
                            alert:'操作有异'
                        });
        				// toast("操作有异");
        			}
        		);
        	}
        },
        watch:{
        	valicode:{
        		handler:function(val,oldVal) {
        			if(this.valicode != "") {
        				this.nextDis = false;
        			} else {
        				this.nextDis = true;
        			}
        		}
        	}
        }
    });
</script>