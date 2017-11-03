{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
<!-- content -->
	<header class="page-header">	
		<div class="page-bar">	
			<a href="/user/setting/myinfo">
				<span class="back-ico"></span>
			</a>
			<span class="bar-title">我的银行卡</span>
            <span class="mgr" v-show="isShow" @click="tabEdit(0)">管理</span>
            <span class="mgr" v-show="!isShow"  @click="tabEdit(1)">取消</span>
		</div>
	</header>

    <div v-if="menus != ''">
    	<section class="bank-cards-wrap">
    		<div class="bank-card-item zhangshang" v-for="(menu,index) in menus">
    			<div class="one-card" @click="checkbank(index)" >
                    <div v-show="false" ref="menuid" v-html="menu.id"></div>

                    <input type="radio" v-model="cardCheck" v-show="!isShow" :value="menu.id">
    				<p class="bank-name" v-html="menu.bank_name"></p>
    				<p class="card-type" v-html="accountType[menu.account_type]"></p>
    				<div class="card-num">
    					<span class="star">****</span>
    					<span class="star">****</span>
    					<span class="star">****</span>
    					<span class="star num" v-html="menu.account_number.substring(menu.account_number.length-4)"></span>
    				</div>
    			</div>
    		</div>
    	</section>
    </div>

	<footer class="add-card" v-show="isShow">
		<a href="/user/setting/addbank">添加银行卡</a>
	</footer>
    <footer class="add-card" v-show="!isShow">
        <a href="javascript:void(0)" @click="unbind">解绑</a>
    </footer>
<!-- end content -->
{include file="Pub/tail" /}
<script>
    var Vm = new Vue({
        el:'#app',
        data:{
            apiUrl:"/user/setting/getBankListData",
            unbindUrl:"/user/setting/unbindBank",
            checkUrl:"/user/withdrawals/withdrawalsIndex",
            menus:'',
            accountType:["","个人账户","企业账户"],
            isShow:true,
            cardCheck:''
        },
        mounted:function(){
            // 初始化方法
            this.getBankListData();
        },
        methods:{
            getBankListData:function(){
                var _this = this;
                _this.$http.post(_this.apiUrl,{
                }).then(
                    function(res){
                        data = cl(res);
                        if(data.code == "200") {
                        	_this.menus = data.data.list;
                        } else {
                            toast(data.msg);
                        }
                    },function(res){
                        toast("数据查询有异");
                    }
                );
            },
            tabEdit:function(idx){
                this.isShow = !this.isShow;
                if(idx==1){
                    this.cardCheck = '';
                }
            },
            unbind:function(){
                if(this.cardCheck == "") {
                    toast("请选择解绑银行卡");
                    return false;
                }
                var _this = this;
                _this.$http.post(_this.unbindUrl,{
                    bank_id:_this.cardCheck
                }).then(
                    function(res){
                        data = cl(res);
                        if(data.code == "200") {
                            toast("解绑成功");
                            _this.getBankListData();
                        } else {
                            toast(data.msg);
                        }
                    }, function(res) {
                        toast("操作有异");
                    }
                );
            },
            checkbank:function(idx){
                // 假如是管理模式，就不执行后续操作
                var _this = this;
                if(_this.isShow) {
                    var bankid = _this.$refs.menuid[idx].innerText;

                    var url = _this.checkUrl+"?bankid="+bankid;

                    LinkTo(url);
                }
            }
        },
        watch:{}
    });
</script>