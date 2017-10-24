{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
<!-- content -->
	<header class="page-header">	
		<div class="page-bar">	
			<a href="javascript:history.go(-1)">
				<span class="back-ico"></span>
			</a>
			<span class="bar-title">修改昵称</span>
		</div>
	</header>
	
	<section class="config-wrap modify-nick-name">
		<div class="config-item">
			<input type="tel" maxlength="16" v-bind:placeholder="holderName" v-model.trim="nickName"/>
		</div>
	</section>
	<div class="modify-tip">由中英文、数字以及下划线组成且不超过8个汉字或16个字符</div>
	<div class="modify-wrap">
		<button type="button" class="sure-btn" v-bind:disabled="dis" v-on:click="updateNickName">确定</button>
	</div>
<!-- end content -->
{include file="Pub/tail" /}
<script>
    var Vm = new Vue({
        el:'#app',
        data:{
            checkcode:"<?=$checkcode?>",
        	nickName:"",
            holderName:"",
        	dis:true,
        },
        mounted:function(){
        	this.getIndexData();
        },
        methods:{
        	getIndexData:function() {
        		var _this = this;
        		var apiUrl = "/user/index/getIndexData";
        		_this.$http.post(apiUrl,{
        			role:_this.role
        		}).then(
        			function(res) {
        				// 处理成功的结果
        				data = cl(res);
        				if(data.code == "200") {
                            _this.holderName = data.data.userinfo.nickname;
        				} else {
        					toast(data.msg);
                        }
        			}, function(res) {
        				toast("操作有异");
        			}
        		);
        	},
            updateNickName:function() {
            	var _this = this;
            	var apiUrl = "/user/setting/updateInfo";

                loadtip({content:'提交中'});
            	_this.$http.post(apiUrl,{
            		nickname:_this.nickName,checkcode:_this.checkcode
            	}).then(
            		function(res) {
            			// 处理成功的结果
            			data = cl(res);
            			if(data.code == "200") {
            				// toast("修改成功");
                            // LinkTo("/user/setting/myinfo");

                            // toast("修改成功");
                            loadtip({
                                close:true,
                                alert:'修改成功',
                                urlto:'/user/setting/myinfo'
                            });
            			} else {
            				// toast(data.msg);
                            loadtip({
                                close:true,
                                alert:data.msg
                            });
            			}
            		}, function (res) {
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
        	// 监听
        	nickName:{
        		handler:function(val,oldVal) {
        			if(this.nickName != "") {
        				this.dis = false;
        			} else {
        				this.dis = true;
        			}
        		}
        	}
        }
    });
</script>