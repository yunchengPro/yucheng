{include file="Pub/header" /}
		<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
        
		<header class="page-header">	
			<div class="page-bar">	
				<a href="/user/index/index">
					<span class="back-ico"></span>
				</a>
				<span class="bar-title">管理收货地址</span>
			</div>
		</header>
		
		
		
		
		<div class="address-list">
		
			<section class="one-address" v-for="address in logisticsList">
				
				<div class="base">
					<div class="name" v-html="address.realname"></div>
					<div class="phone" v-html="address.mobile"></div>
				</div>
				<div class="address">
                    <span v-html="address.city"></span>
                    <span v-html="address.address"></span>
				</div>
				
				<div class="operator">
					<label class="active">
						<input type="radio" checked="checked" name="default" v-if="address.isdefault == 1">
						<input type="radio"  name="default" v-else  v-on:click="setDefaultlogistic(address.id)" />默认地址
					</label>
					<a   class="remove" v-on:click="delCustomerLogistic(address.id)"  ><span>删除</span></a>
					<a   v-bind:href="'/user/userlogistics/editlogistics?address_id='+address.id" class="edit"><span>编辑</span></a>
					
					<div class="clear"></div>
				</div>
				
			</section>

		</div>
		<div style="height: 100px;"></div>
		<footer class="add-address">
			<a href="/user/userlogistics/addlogistics">添加新地址</a>
		</footer>
		
{include file="Pub/tail" /}
        <style type="text/css">
            body{background: #eee;}
        </style>
		<script>
			$(function(){
				$(".operator label").click(function(){
					$(this).addClass("active");
					$(this).parents(".one-address").siblings().find(".operator").find("label").removeClass("active");
				});
			});
		</script>
		<script>
            
            var Vm = new Vue({
                el: '#app',
                data: {
                    // 页面约定的参数
                    apiUrl:"/User/Userlogistics/getlogisticsList",
                    apiDefaultlogistic:"/User/Userlogistics/setDefaultlogistic",
                    apidelCustomerLogistic:"/User/Userlogistics/apidelCustomerLogistic",
                  	logisticsList:[],
                    checkcode:"<?=$checkcode?>"
                },
                mounted: function() {
                    var _this=this;
                    // 页面初始化时执行的方法
                    _this.getLogisticsList();

                },
                methods: {
                   	setDefaultlogistic:function(address_id){
                   		var _this=this;

                        _this.$http.post(_this.apiDefaultlogistic,{
                        	logisticid:address_id,
                            checkcode:_this.checkcode
                        }).then(
                            function (res) {
                               	var _this=this;
                                //data = eval("("+res.body+")");
                                data = cl(res);
                                if(data.code=='200'){
                                	
                                	layer.open({
                                        content: '设置成功',
                                        skin: 'msg',
                                        time: 2 
                                    });
                                    return false;
                               		
                               	}else{

                               		layer.open({
                                        content: data.msg,
                                        skin: 'msg',
                                        time: 2 
                                    });
                                    return false;

                               	}
                                //$("#city").val('');
                            },function (res) {
                                // 处理失败的结果
                                //console.log(res);
                                layer.open({
                                    content: '加载数据错误！请重新请求',
                                    skin: 'msg',
                                    time: 2 
                                });
                            }
                        );
                   	},
                    delCustomerLogistic:function(address_id){
                        var _this=this;
						if(confirm("确定删除收货地址?")) {
                            _this.$http.post(_this.apidelCustomerLogistic,{
                                logisticid:address_id,
                                checkcode:_this.checkcode
                            }).then(
                                function (res) {
                                    var _this=this;
                                    //data = eval("("+res.body+")");
                                    data = cl(res);
                                    if(data.code=='200'){
                                        toast("删除成功");
//                                         layer.open({
//                                             content: '删除成功',
//                                             skin: 'msg',
//                                             time: 2 
//                                         });
                                        _this.getLogisticsList();
                                        return false;
                                        
                                    }else{
                                    	toast(data.msg);
//                                         layer.open({
//                                             content: data.msg,
//                                             skin: 'msg',
//                                             time: 2 
//                                         });
                                        return false;
    
                                    }
                                    //$("#city").val('');
                                },function (res) {
                                    // 处理失败的结果
                                    //console.log(res);
//                                     layer.open({
//                                         content: '加载数据错误！请重新请求',
//                                         skin: 'msg',
//                                         time: 2 
//                                     });
                                	toast('加载数据错误！请重新请求');
                                	return false;
                                }
                            );
						}
                    },
                    getLogisticsList:function(){
                        var _this=this;

                        _this.$http.post(_this.apiUrl,{
                        }).then(
                            function (res) {
                               	var _this=this;
                                data = eval("("+res.body+")");
                                //data = cl(res);
                                if(data.code=='200'){
                                	

                               		_this.logisticsList = data.data;
                               		//console.log(_this.logisticsList);
                               	}else{

                               		layer.open({
                                        content: data.msg,
                                        skin: 'msg',
                                        time: 2 
                                    });
                                    return false;

                               	}
                                //$("#city").val('');
                            },function (res) {
                                // 处理失败的结果
                                //console.log(res);
                                layer.open({
                                    content: '加载数据错误！请重新请求',
                                    skin: 'msg',
                                    time: 2 
                                });
                            }
                        );
                    }
                    
                }
            });
        
        </script>

