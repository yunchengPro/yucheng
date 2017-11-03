{include file="Pub/header" /}

	<header class="page-header">
			
			<div class="page-bar">
			
			<a href="javascript:history.go(-1)">
				<img src="/mobile/img/icon/back@2x.png" class="back-ico">
			</a>
			
			<div class="bar-title">购买记录</div>
			
		</div>
	</header>
	
	<div class="deal-taurus-records"  v-scroll="getMore">

		<section class="one-record" v-for="buycon in buyconlist">
			<!-- <div class="record-item">
				<div>商家手机号</div>
				<div class="c-666" v-html="buycon.mobile"></div>
			</div> -->
			<div class="record-item">
				<div>消费券金额</div>
				<div class="c-666" v-html="buycon.concount" ></div>
			</div>
			<div class="record-item">
				<div>购买总面值</div>
				<div class="c-666" v-html="buycon.count" ></div>
			</div>
			
			<div class="record-item">
				<div>支付金额</div>
				<div class="c-666" ><span v-html="buycon.totalamount"></span>元</div>
			</div>
			<div class="record-item">
				<div>订单编号 </div>
				<div class="c-666" v-html="buycon.orderno"></div>
			</div>
			<div class="record-item">
				<div>购买时间</div>
				<div class="c-666" v-html="buycon.addtime"></div>
			</div>
		</section>
		
	
		
	</div>
				
				
				
{include file="Pub/tail" /}

<link rel="stylesheet" href="/mobile/css/usercenter.css" />
<style>
	.deal-taurus-records .one-record{margin-bottom: 9px;padding-bottom: 5px;border-bottom: 1px solid #DDDDDD;}
	.one-record .record-item{display: -webkit-box;-webkit-box-pack: justify;height: 30px;line-height:30px;padding: 0 12px;font-size: 14px;}
	.one-record .record-item .c-666{font-size: 13px;}
</style>
<script type="text/javascript">
				
				
			
	var vm=	new Vue({
		el: '#app',
		data: {
		   	payDefault:true,
		   	money:'',
		   	selected:'1',
		   	taurus:0,
		   	buyconlist:[],
		   	page:1,
		   	switchShow:false, 
		},
		mounted:function(){
			var _this = this;
  			_this.getbuyconlist();
  		},
		methods:{
		  	getbuyconlist:function(){
		  		
		  		var _this=this;

                _this.$http.post('/Conn/Conn/getbuyconlist',{
                	page:_this.page,
                }).then(
                    function (res) {
                       	var _this=this;
                       	//data = eval("("+res.body+")");
                        data = cl(res);
                        if(data.code=='200'){
                        		
                    		if(_this.page > 1){
                            	_this.buyconlist = _this.buyconlist.concat(data.data.list);
                        	}else{
                        		_this.buyconlist = data.data.list;
                        	}
                            _this.maxPage = data.data.maxPage;
                            _this.switchShow = !_this.switchShow; 


                       		
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
		  	 // 自定义方法
            getMore: function() {
                var _this = this;
              
                _this.switchShow = !_this.switchShow;
              
                _this.page++;
                if(_this.page<=_this.maxPage){
                    _this.getbuyconlist();

                }else{
                    _this.switchShow = false;
                    $(".loading").hide();
                    return ;
                }
                // _this.getStoList(_this.page);
               
            }
		},
		watch:{
	  		selected:{
	  			handler:function(val,oldVal){
					if(val=="1"){
						this.payDefault=true;
					}else{
						this.payDefault=false;
					}
				}
	  		},
	  		
	  		money:{
	  			handler:function(val,oldVal){
	  				var num=parseInt(val);
	  				if(num){
	  					this.taurus=num*25;
	  				}else{
	  					this.taurus=0;
	  				}
					
				}
	  		}
	  	
	    },
	     directives: { // 自定义指令
                scroll: {
                    bind: function(el, binding) {
                        window.addEventListener('scroll', function() {
                            if(document.body.scrollTop + window.innerHeight+20 >= el.clientHeight) {
                                var fnc = binding.value;
                                fnc();
                            }
                        })
                    }
                }
            }
	});
</script>

