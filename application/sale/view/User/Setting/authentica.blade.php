{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
<!-- content -->
    <header class="page-header">    
        <div class="page-bar">  
            <a href="javascript:history.go(-1)">
                <span class="back-ico"></span>
            </a>
            <span class="bar-title" v-html="title"></span>
        </div>
    </header>
    <section class="config-wrap">
        <div class="config-item">
            <input type="tel" maxlength="11" v-bind:placeholder="holderrealname" v-model.trim="realname"/>
        </div>
    </section>
    <section class="config-wrap modify-phone">
        <div class="config-item">
            <input type="tel" maxlength="20" v-bind:placeholder="holderidnumber" v-model.trim="idnumber"/>
        </div>
    </section>
    <div class="modify-wrap" v-if="isnameauth!=1">
        <button type="button" class="sure-btn" v-bind:disabled="dis" v-on:click="authUser">确定</button>
    </div>
<!-- end content -->
{include file="Pub/tail" /}
<script>
    var Vm = new Vue({
        el:'#app',
        data:{
            // checkcode:"<?=$checkcode?>",
            checktoken:'<?=$checktoken?>',
            title:"<?=$title?>",
            holderrealname:"",
            holderidnumber:"",
            realname:"",
            idnumber:"",
            isnameauth:"",
            dis:true
        },
        mounted:function(){
            this.getIndexData();
        },
        methods:{
            getIndexData:function() {
                var _this = this;
                var apiUrl = "/user/index/getIndexData";
                _this.$http.post(apiUrl,{
                	// customerid:_this.customerid
                    // role:_this.role
                }).then(
                    function(res) {
                        // 处理成功的结果
                        data = cl(res);
                        if(data.code == "200") {
                            _this.holderrealname = data.data.userInfo.realname;
                            _this.holderidnumber = data.data.userInfo.idnumber;
                            _this.isnameauth = data.data.userInfo.isnameauth;
                        } else {
                            toast(data.msg);
                        }
                    }, function(res) {
                        toast("操作有异");
                    }
                );
            },
            authUser:function() {
                var _this = this;
                var updateUrl = "/user/setting/updateauthdata";

                loadtip({content:'提交中'});
                _this.$http.post(updateUrl,{
                    realname:_this.realname,idnumber:_this.idnumber,checktoken:_this.checktoken
                }).then(
                    function(res) {
                        // 处理成功的结果
                        data = cl(res);
                        if(data.code == "200") {
                            loadtip({
                                close:true,
                                alert:'修改成功',
                                urlto:'/user/setting/myinfo'
                            });
                            // LinkTo("/user/setting/myinfo");
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
            realname:{
                handler:function(val,oldval) {
                    if(this.realname != "" && this.idnumber != "") {
                        this.dis = false;
                    } else {
                        this.dis = true;
                    }
                }
            },
            idnumber:{
                handler:function(val,oldval) {
                    if(this.realname != "" && this.idnumber != "") {
                        this.dis = false;
                    } else {
                        this.dis = true;
                    }
                }
            }
        }
    });
</script>