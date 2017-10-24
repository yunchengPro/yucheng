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
            .pay-success-wrap>.suc-header{padding-top: 40px;display: -webkit-box;-webkit-box-align: center;-webkit-box-orient: vertical;}
            .pay-success-wrap>.suc-header img{width: 100px;margin-bottom: 10px;}
            .pay-success-wrap>.suc-body>.suc-item{display: -webkit-box;-webkit-box-pack: justify;height: 44px;line-height:44px;padding: 0 12px;font-size: 14px;border-bottom: 0.5px solid #DDDDDD;}
            .pay-success-wrap>.suc-body>.suc-item .c-666{font-size: 13px;}
            .pay-success-wrap>.suc-body>.suc-item .orange{color: #CEA15A;font-size: 16px;}
            .suc-footer{padding: 0 12px;margin-top: 40px;}
            .suc-footer>.suc-btn{background: #CEA15A;height: 44px;line-height: 44px;color: #FFFFFF;display: inline-block;width: 100%;text-align: center;font-size: 16px;border-radius: 4px;}
       </style>
    </head>
    <body>
        <div id="app">
                <header class="page-header">
                        
                        <div class="page-bar">
                        
                        <a href="javascript:history.go(-1)">
                            <img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
                        </a>
                        
                        <div class="bar-title">支付成功</div>
                        
                    </div>
                </header>
                
                
                    <section class="pay-success-wrap">
                        <div class="suc-header">
                        <img src="<?=$publicDomain?>/mobile/img/icon/success.png" />
                            <h2 >支付成功</h2>
                        </div>
                        <div class="suc-body">

                            <div class="suc-item" v-for="val in item">
                                <div v-html="val.key"></div>
                                <div class="c-666" v-html="val.value"></div>
                            </div>
                            
                        </div>
                        
                    
                    </section>
                        <div class="suc-footer">
                            <a href="#" class="suc-btn">完成</a>
                        </div>
                    
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
                                    
                                    _this.item = data.data.item;
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
