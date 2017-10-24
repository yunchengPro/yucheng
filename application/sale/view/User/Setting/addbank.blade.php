{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
<!-- content -->
	<header class="page-header">	
		<div class="page-bar">	
			<a href="javascript:history.go(-1)">
				<span class="back-ico"></span>
			</a>
			<span class="bar-title">添加银行卡</span>
		</div>
	</header>
	
	<section class="add-bank-card">
        <!--
		<div class="account-type">
			<div class="card-item ">
				<span class="item-tip">选择账户类型</span>
				<select class="tl-fr" v-model="selected">
                    <option v-for="option in typeOptions" v-bind:value="option.num" v-html="option.text"></option>
                    -->
                    <!--
					<option value="1">个人</option>
					<option value="2">企业</option>
                    -->
                    <!--
				</select>
			</div>
		</div>
        -->
		<div class="account-info">
			<div class="card-item ">
				<span class="item-tip">银行开户名</span>
				<input type="text" placeholder="请输入用户名" v-model.trim="account_name"/>
			</div>
			<div class="card-item ">
				<span class="item-tip">银行卡号</span>
				<input type="tel" maxlength="30" placeholder="请输入银行卡号" v-model.trim="account_number"/>
			</div>
			<div class="card-item ">
				<span class="item-tip">开户银行</span>
				<input type="text" v-model.trim="bank_type_name"/>
			</div>
			<div class="card-item ">
				<span class="item-tip">支行名称</span>
				<input type="text" placeholder="开户行支行" v-model.trim="branch"/>
			</div>
			<div class="card-item ">
				<span class="item-tip">手机号</span>
				<input type="tel" placeholder="银行预留手机号" v-model.trim="mobile"/>
			</div>
		</div>
	</section>
	
	<div class="add-card-oper">
		<button type="button" v-bind:disabled="dis" v-on:click="addBank">确定</button>
	</div>
<!-- end content -->
{include file="Pub/tail" /}
<script>
    var Vm = new Vue({
        el:'#app',
        data:{
            checkcode:"<?=$checkcode?>",
            account_name:"",
        	account_number:"",
            bank_type_name:"",
            branch:"",
            mobile:"",
        	dis:false,
        	msg:1,
            typeOptions:[
                {num:1,text:'个人'},
                {num:2,text:'企业'}
            ],
            selected:1
        },
        mounted:function(){
        },
        methods:{
        	addBank:function() {

                var _this = this;
                if(_this.account_name == "") {
                    toast("银行开户名不能为空");
                    return false;
                }
                if(_this.account_number == "") {
                    toast("银行卡号不能为空");
                    return false;
                }
                if(_this.bank_type_name == "") {
                    toast("开户银行不能为空");
                    return false;
                }
                if(_this.branch == "") {
                    toast("支行名称不能为空");
                    return false;
                }
                if(_this.mobile == "") {
                    toast("手机号不能为空");
                    return false;
                }

                loadtip({content:'提交中'});

                var addUrl = "/user/setting/addBankNumber";
                _this.$http.post(addUrl,{
                    account_type:_this.selected,account_name:_this.account_name,account_number:_this.account_number,
                    bank_type_name:_this.bank_type_name,branch:_this.branch,mobile:_this.mobile,checkcode:_this.checkcode
                }).then(
                    function(res) {
                        data = cl(res);
                        if(data.code == "200") {
                            loadtip({
                                close:true,
                                alert:'添加成功',
                                urlto:'/user/setting/mybanklist'
                            });
                            // LinkTo("/user/setting/mybanklist");
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
        	},
            checkBankNumber:function() {
                var _this = this;
                var checkUrl = "/user/setting/checkBankNumber";
                _this.$http.post(checkUrl,{
                    account_number:_this.account_number
                }).then(
                    function(res) {
                        data = cl(res);
                        if(data.code = "200") {
                            _this.bank_type_name = data.data;
                        } else {
                            toast(data.msg);
                        }
                    }, function(res) {
                        toast("数据查询有异");
                    }
                );
            }
        },
        watch:{
            account_number:{
                handler:function(val,oldVal) {
                    if(this.account_number.length >= 6) {
                        this.checkBankNumber();
                    }
                }
            }
        }
    });
</script>