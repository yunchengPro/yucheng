{include file="Pub/header" /}
		
		<header class="page-header">	
			<div class="page-bar">	
				<a href="/User/Userlogistics/logisticslist">
					<span class="back-ico"></span>
				</a>
				<span class="bar-title"><?=$title?></span>
			</div>
		</header>
		
		<section class="new-address">
			<div class="address-item">
				<span class="item-tip">收货人</span>
				<input type="text" maxlength="30" value="" placeholder="" v-model.trim="realname"/>
			</div>
			<div class="address-item">
				<span class="item-tip">联系电话</span>
				<input type="text" maxlength="30" value="" v-model.trim="mobile"/>
			</div>
			<div class="address-item">
				<span class="item-tip">所在地区</span>
				<input type="text" placeholder="请选择" class="area" id="txt_area" value="<?=$hd_area_value?>" />
                <input type="hidden" id="hd_area"   value="<?=$hd_area?>" >
				<i></i>
			</div>
		<!-- 	<div class="address-item">
				<span class="item-tip">街道</span>
				<i></i>
			</div> -->
			<div class="address-item">
				<textarea placeholder="请填写详细地址，不少于5个字" value="" v-model.trim="address"></textarea>
			</div>
		</section>
		
		<section class="set-default">
			<span>设为默认地址</span>
			<span class="tl-toggle">
					<input   type="checkbox" v-model.trim="isdefault" value="" />
			</span>
		
		</section>
		
		<div class="oper-address">
			<button type="button" v-on:click="editLogisticsList()" class="save-btn">保存</button>
		</div>
		

{include file="Pub/tail" /}
		<link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/ucenter_set.css" />
		<link rel="stylesheet" type="text/css" href="<?=$publicDomain?>/mobile/css/dialog.css"/>
		<link rel="stylesheet" type="text/css" href="<?=$publicDomain?>/mobile/css/mobile-select-area.css"/>

		<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/dialog.js" ></script>
    	<script type="text/javascript" src="<?=$publicDomain?>/mobile/js/mobile-select-area.js"></script>
		
		<script>
            
            var Vm = new Vue({
                el: '#app',
                data: {
                    // 页面约定的参数
                    apiUrl:"<?=$apiUrl?>",
                  	logisticid:"<?=$logisticid?>",
                  	realname:"<?=$logistic['realname']?>",
                  	mobile:"<?=$logistic['mobile']?>",
                  	city_id:"<?=$logistic['city_id']?>",
                  	city:"<?=$logistic['city']?>",
                    address:"<?=$logistic['address']?>",
                    isdefault:'<?=$isdefault?>',
                    checkcode:"<?=$checkcode?>"
                },
              	 mounted: function() {
						var selectArea = new MobileSelectArea();
					        selectArea.init({
					            trigger:'#txt_area',
					            data:'<?=$publicDomain?>/mobile/data/getAreaJson.json',
					            default:0,
					            value:$('#hd_area').val(),
					           // value:[110000, 110100, 110101],
					            text:['北京', '北京市', '东城区'],
					            position:"bottom"
					        });
              	},
                methods: {
                   
                    editLogisticsList:function(){

                        var _this=this;
                        loadtip({
                            content:'加载中..'
                        });
                        _this.$http.post(_this.apiUrl,{
                        	logisticid:_this.logisticid,
                        	realname:_this.realname,
                        	mobile:_this.mobile,
                        	city_id:_this.city_id,
                        	address:_this.address,
                        	isdefault:_this.isdefault,
                        	hd_area:$('#hd_area').val(),
                        	checkcode:_this.checkcode
                        }).then(
                            function (res) {
                                loadtip({
                                    close:true
                                });
                               	var _this=this;
                                data = eval("("+res.body+")");
                               	//data = cl(res);
                                if(data.code=='200'){
                                	layer.open({
                                        content: '<?=$success?>',
                                        skin: 'msg',
                                        time: 2 
                                    });
                                    window.location.href='/user/userlogistics/logisticslist';
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
                    }
                    
                }
            });
        
        </script>
