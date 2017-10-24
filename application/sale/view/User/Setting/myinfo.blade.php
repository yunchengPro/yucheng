{include file="Pub/header" /}
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/usercenter.css" />
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
<link rel="stylesheet" href="<?=$publicDomain?>/mobile/jQueryFileUpload/css/fileupload.css">
 <!---上传图片的js-->
<script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/vendor/jquery.ui.widget.js"></script>
<script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/load-image.all.min.js"></script>
<script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/canvas-to-blob.min.js"></script>
<script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/jquery.iframe-transport.js"></script>
<script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/jquery.fileupload.js"></script>
<script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/jquery.fileupload-process.js"></script>
<script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/jquery.fileupload-image.js"></script>
<script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/jquery.fileupload-validate.js"></script>
<script src="<?=$publicDomain?>/mobile/jQueryFileUpload/js/fileupload.js"></script>
<!-- content -->

	<header class="page-header">	
		<div class="page-bar">	
			<a href="javascript:history.go(-1)">
				<span class="back-ico"></span>
			</a>
			<span class="bar-title">我的资料</span>
		</div>
	</header>
			
	<section class="config-wrap">
		
		<div class="config-item avatar">
			<a href="#">
				<div class="config-tip">头像</div>
				<div class="config-val"  id="uploadfiles_photos">
					<img id="photos" name="photos" :src="headerpic" style="margin-top:-7px; ">
				</div>
					

	                
                    <div  id="photos_upload" name="photos_upload" >
                        <input  name="file" class="upload" multiple="" type="file">
                        
                    </div>

	                
				<i></i>
			</a>
		</div>
	</section>

	<section class="config-wrap">
		<div class="config-item">
			<a href="/user/setting/updatenickname">
				<div class="config-tip">昵称</div>
				<div class="config-val" v-html="nickname"></div>
				<i></i>
			</a>
		</div>
		<div class="config-item">
			<div class="config-tip">性别</div>
			<div class="config-val" v-html="sexArr[sex]" @click="showSexSelect"></div>
			<i></i>
		</div>
	</section>

	<section class="config-wrap">
		<div class="config-item">
			<a href="/user/setting/authentica">
				<div class="config-tip">实名认证</div>
				<div v-if="isnameauth==''||isnameauth=='未认证'" class="config-val no" v-html="isnameauth"></div>
				<div v-else class="config-val yes" v-html="isnameauth"></div>
				<i></i>
			</a>
		</div>
		<div class="config-item">
			<a href="/user/setting/mybanklist">
				<div class="config-tip">我的银行卡</div>
				<div v-if="banknumber=='未设置'" class="config-val no" v-html="banknumber"></div>
				<div v-else class="config-val" v-html="banknumber"></div>
				<i></i>
			</a>
		</div>
		<a href="/user/userlogistics/logisticslist">
		<div class="config-item">
			<div class="config-tip">收货地址</div>


			<div v-if="logisticsDec=='未设置'|| logisticsDec==''" class="config-val no" v-html="logisticsDec"></div>
			<div v-else class="config-val yes" v-html="logisticsDec"></div>
			<i></i>
		</div>
		</a>
	</section>
	<!--底部选择-->
	<div class="tl-select-mask" @click="hideSexSelect" v-show="isShowSex"></div>
	<div class="tl-bottom-select" v-show="isShowSex">
		<div class="select-wrap">
			<div class="select-item">
				<label><input type="radio"  v-model="chosesex" value="1">男</label>
			</div>
			<div class="select-item">
				<label><input type="radio"  v-model="chosesex" value="2">女</label>
			</div>

		</div>
		<div class="cancel-wrap">
			<div class="select-item" @click="hideSexSelect">取消</div>
		</div>
	</div>
<!-- end content -->
{include file="Pub/tail" /}

 <style>
			
	.avatar .upload{
		position: absolute;
	    top: 7px;
	    right: 25px;
	    width: 56px;
	    height: 56px;
	    opacity: 0;
	}
</style>
<script>
    var Vm = new Vue({
        el:'#app',
        data:{
            apiUrl:"/user/index/getIndexData",
            headerpic:"/mobile/img/icon/ic_center_head@2x.png",
            role:"<?=$role?>",
            nickname:'',
            sex:'',
            isnameauth:'',
            banknumber:'未设置',
            logisticsDec:'',
            sexArr:["未设置","男","女"],
            isShowSex:false,
            chosesex:1
        },
        mounted:function(){
            // 初始化方法
            this.getIndexData();
            var filepath = fileupload('photos','{"url":"\/uploadfile\<?=$publicDomain?>/mobile/jQueryFileUpload.php","domain":"\/\/nnhtest.oss-cn-shenzhen.aliyuncs.com\/","maxFileSize":4194304,"maxNumberOfFiles":5,"savefileurl":"\/Sys\/upload\/getfile","getParamUrl":"\/Sys\/upload\/policy","server_type":"NNH\/images","formData":{"server_type":"NNH\/images"},returnUrl:true,imgid:"photos",uploadfunc:"Vm.updateheaderpic"}');
            
        },
        methods:{
            getIndexData:function(){
                var _this = this;
                var nameauthArr = ["未认证","已认证"];
                var logisticsArr = ["未设置","已设置"];
                _this.$http.post(_this.apiUrl,{
                    role:_this.role
                }).then(
                    function(res){
                        data = cl(res);
                        if(data.code == "200") {
                        	_this.headerpic = data.data.userinfo.headerpic != '' ? data.data.userinfo.headerpic : _this.headerpic;
                        	_this.nickname = data.data.userinfo.nickname;
                        	_this.sex = data.data.userinfo.sex;
                        	_this.isnameauth = nameauthArr[data.data.userinfo.isnameauth];
                        	_this.banknumber = data.data.userinfo.banknumber;
                        	if(_this.banknumber==0){
                        		_this.banknumber='未设置';
                        	}
                        	_this.logisticsDec = logisticsArr[data.data.userinfo.logisticsDec];
                        } else {
                            toast(data.msg);
                        }
                    },function(res){
                        toast("数据查询有异");
                    }
                );
            },
            updateheaderpic:function(filepath){
            	
            	var _this = this;
		  		var apiUrl = "/user/setting/updateInfo";
		  		var headerpic = filepath;
		  		_this.hideSexSelect();
		  		loadtip({content:'提交中'});
		  		_this.$http.post(apiUrl,{
            		headerpic:headerpic
            	}).then(
            		function(res) {
            			// 处理成功的结果
            			data = cl(res);
            			if(data.code == "200") {
                            loadtip({
                                close:true,
                                alert:'修改成功'
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

            },
            //显示底部选择
		  	showSexSelect:function(){
		  		this.isShowSex=true;
		  	},
		  	// //隐藏底部选择 
		  	hideSexSelect:function(){
		  		this.isShowSex=false;
		  	},

		  	updateSexData:function() {
		  		var _this = this;
		  		var apiUrl = "/user/setting/updateInfo";

		  		_this.hideSexSelect();
		  		loadtip({content:'提交中'});
		  		_this.$http.post(apiUrl,{
            		sex:_this.sex,checkcode:_this.checkcode
            	}).then(
            		function(res) {
            			// 处理成功的结果
            			data = cl(res);
            			if(data.code == "200") {
                            loadtip({
                                close:true,
                                alert:'修改成功'
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
        	chosesex:{
		  	 	handler:function(val,oldVal){
		  	//  		//提交数据
		  	//  		// toast("修改成功");
		  	 		// if(val=="1"){
		  	 		// 	this.sex="男";
		  	 		// }else{
		  	 		// 	this.sex="女";
		  	 		// }
		  	 		this.sex = val;
		  	 		this.updateSexData();
		  	 		// this.hideSexSelect();
		  	 	}
		  	}
        }
    });
</script>