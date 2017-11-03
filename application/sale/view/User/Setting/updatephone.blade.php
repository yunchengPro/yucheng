{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
<!-- content -->
	<header class="page-header">	
		<div class="page-bar">	
			<a href="javascript:history.go(-1)">
				<span class="back-ico"></span>
			</a>
			<span class="bar-title">修改手机</span>
		</div>
	</header>
	
	<section class="config-wrap">
		<div class="config-item">
			<input type="tel" maxlength="11" placeholder="请输入您的手机号" v-model.trim="mobile"/>
		</div>
	</section>

	<section class="config-wrap modify-phone">
		<div class="config-item">
			<input type="tel" maxlength="11" placeholder="请输入验证码" v-model.trim="valicode"/>
			<button class="get-vercode" type="button" v-bind:disabled="disvalicode" v-on:click="send">获取验证码</button>
		</div>
	</section>
	<div class="modify-wrap">
		<button type="button" class="sure-btn" v-bind:disabled="dis" v-on:click="updatePhone">确定</button>
	</div>
<!-- end content -->
{include file="Pub/tail" /}
<script>
    var Vm = new Vue({
        el:'#app',
        data:{
        	checktoken:'<?=$checktoken?>',
            // checkcode:"<?=$checkcode?>",
            mobile:"",
            valicode:"",
            disvalicode:true,
            dis:true
        },
        mounted:function(){
            // 初始化方法
        },
        methods:{
        	send:function() {
                var _this = this;
                var sendUrl = '/user/setting/sendvalicode';
                _this.$http.post(sendUrl,{
                    mobile:_this.mobile
                }).then(
                    function(res) {
                        var data = cl(res);
                        if(data.code == "200") {
                            toast("发送短信成功");
                        } else {
                            toast(data.msg);
                        }
                    }, function(res) {
                        toast("操作有异");
                    }
                );
        	},
        	updatePhone:function() {
                var _this = this;
                var updateUrl = '/user/setting/updatephonevalicode';
                loadtip({content:'修改中'});
                _this.$http.post(updateUrl,{
                    mobile:_this.mobile,valicode:_this.valicode,checktoken:_this.checktoken
                }).then(
                    function(res) {
                        var data = cl(res);
                        if(data.code == "200") {
                            loadtip({
                                close:true,
                                alert:'修改成功',
                                urlto:'/user/setting/safeindex'
                            });
                            // toast("修改成功");
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
        	mobile:{
        		handler:function(val,oldVal) {
        			if(this.mobile != "") {
                        if(this.mobile.length >= 11) {
                            this.disvalicode = false;
                        } else {
                            this.disvalicode = true;
                        }
        			} else {
        				this.disvalicode = true;
        			}
        		}
        	},
        	valicode:{
        		handler:function(val,oldVal) {
        			if(this.valicode != "" && this.mobile != "") {
        				this.dis = false;
        			} else {
        				this.dis = true;
        			}
        		}
        	}
        }
    });
</script>