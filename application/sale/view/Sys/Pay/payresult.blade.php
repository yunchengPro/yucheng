<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>支付成功</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="renderer" content="webkit">
        <meta name="author" content="talon">
        <meta name="application-name" content="niuniuhui-wap">
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta name="format-detection" content="telephone=no" />
        
        {include file="Pub/assetcss" /}
        {include file="Pub/assetjs" /}
          
        <style>
           .pay-result{padding-top: 45px;}
            .pay-result .result-item{
                display: -webkit-box;
                -webkit-box-pack: center;
                
            }
            
            .pay-result .result-item .txt{font-size: 17px;margin-bottom: 5px;}
            .pay-result .result-item img{width: 90px;margin-bottom: 20px;}
            .pay-result .result-item .goon-btn{
                height: 30px;
                line-height: 30px;
                text-align: center;
                width: 80px;
                font-size: 13px;
                background: #CEA15A;
                color: #fff;
                border: 1px solid #CEA15A;
                display: inline-block;
                border-radius: 4px;
                margin-bottom: 30px;
                margin-top: 10px;

                margin-right: 5px;
                
            }
            .pay-result .result-item .back-btn{
                height: 30px;
                line-height: 30px;
                text-align: center;
                width: 80px;
                font-size: 13px;
                border: 1px solid #CEA15A;
                color: #CEA15A;
                display: inline-block;
                border-radius: 4px;
                margin-bottom: 30px;
                margin-top: 10px;

                
            }
            .pay-result .deal-order {padding: 0 15px;}
            .pay-result .deal-order .order-item:first-child{border-top:  0.5px solid #E5E5E5;}
            .pay-result .deal-order .order-item {
                display: -webkit-box;
                -webkit-box-pack: justify;
                height: 44px;
                -webkit-box-align: center;
                font-size: 14px;
                border-bottom:  0.5px solid #E5E5E5;
            }
            
       </style>
    </head>
    <body>
        <div id="app">
                <header class="page-header">
                        
                        <div class="page-bar">
                        
                        <a href="/conn/Conn/buycon">
                            <img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
                        </a>
                        
                        <div class="bar-title">支付成功</div>
                        
                    </div>
                </header>
                
                
            <section class="pay-result">
                <div>
                    <div class="result-item"><img src="/mobile/img/icon/success.png" /></div>
                    <div class="result-item"><div class="txt">支付成功</div></div>
                    <div class="result-item"><div class="txt" v-html="item.payamount"></div></div>
                    <div class="result-item">
                        <a href="/conn/Conn/buycon" class="goon-btn">继续充值</a>
                        <a href="/index/index/index" class="back-btn">返回首页</a>
                    </div>
                    <div class="deal-order">
                        <div class="order-item">
                        <div>订单编号</div>
                        <div v-html="item.orderno"></div>
                    </div>
                    <div class="order-item">
                        <div>交易时间</div>
                        <div v-html="item.addtime">2017-10-24 16:31:19</div>
                    </div>
                    </div>
                </div>
            </section>

                    
        </div>
        <script type="text/javascript">
                
            var vm= new Vue({
                el: '#app',
                data: {
                    apiUrl:"/Sys/Pay/getpayresult",
                    orderno:'<?=$orderno?>',
                    item:[]
                },
             
                methods:{
                    getpayresult:function(){
                        var _this=this;

                        _this.$http.post(_this.apiUrl,{
                            orderno:_this.orderno
                        }).then(
                            function (res) {
                                var _this=this;
                                data = eval("("+res.body+")");
                                //data = cl(res);
                                if(data.code=='200'){
                                   
                                    _this.item = data.data;
                                }else{
                                    toast(data.msg);
                                }
                                
                            },function (res) {
                                // 处理失败的结果
                                //console.log(res);
                                toast('加载数据错误！请重新请求');
                                
                            }
                        );
                    }
                },
                mounted:function(){
                    this.getpayresult();
                }
               
            });

            $(".suc-btn").click(function(){
                // 回到首页
                window.location.href='/';
            });
            </script>
        </script>
    </body>
</html>
