<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>支付方式</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}

    <style>  
            
.am-list-news-hd{margin-top: 9px;}      
.am-list-news-hd h2 {
    font-weight: 500;
    padding-left: 12px;
}

.pay-pwd-container {
    margin-top: 50px;
    padding: 0 12px;
}

.forget-pwd-container {
    padding: 10px 12px;
}

.pay-pwd-title {
    font-size: 17px;
    margin-bottom: 25px;
}

.pay-pwd-inpt {
    height: 44px;
    line-height: 44px;
    background: #eee;
    border: none;
    width: 100%;
    color: #666;
    padding: 10px;
}

.pay-type img {
    width: 20px;
    margin-right: 10px;
    vertical-align: bottom;
}

.vertical-middle {
    vertical-align: middle !important;
}

.pay-type .am-list-item-dated{padding: 0 12px;}
.pay-type a {
    color: #333;
}

.pay-for-money {
    margin-top: 6rem;
    padding-left:12px ;
    padding-right: 12px;
    padding-bottom: 20px;
}

.pay-for-money .am-u-sm-6 {
    padding: 0;
    font-size: 15px;
}
.pay-for-money .red{color: #cd9951;}
.pay-for-money .pay-btn {
    margin-top: 10px;
    padding: 0;
}
.pay-for-money .pay-btn a{
    background: #cd9951;
    border-color: #cd9951;
    height: 44px;
}
.pay-type i.icon {
    background-size: 100% auto;
    background-position: center;
    background-repeat: no-repeat;
    display: inline-block;
}
.pay-type i.icon.icon-choose-pay {
    width: 20px;
    height: 20px;
    background-image: url(<?=$publicDomain?>/mobile/img/icon/ic_choose_pay.png);
}


.pay-pwd-wrap{
        position: fixed;
    width: 100%;
    bottom: 0;
    padding: 10px 0;
    background: #fff;
    z-index: 100;
}
.pay-pwd-wrap .text-center{text-align: center;}
            
.pwd-box{  
    
        width:300px;  
        margin: 0px auto;
          
        position: relative;  
        border: 1px solid #9f9fa0;  
        border-radius: 3px;  
        overflow:hidden;
        font-size: 14px;
        box-sizing: border-box;
    } 
  
    .pwd-box input[type="tel"]{  
        width: 100%;  
        height: 45px;  
        color: transparent;  
        position: absolute;  
        top: 0;  
        left: 0;  
        border: none;  
        font-size: 18px;  
        opacity: 0;  
        z-index: 1;  
        letter-spacing: 40px; 
        padding-left: 5px; 
        box-sizing: border-box;  
        
    }  
    .fake-box input{  
        /*width: 49px; */ 
        width: 46px; 
        height: 48px;  
        border: none;  
        border-right: 1px solid #e5e5e5;  
        text-align: center;  
        font-size: 30px;  
        border-radius: 0;
        box-sizing: border-box;
    }  
    .fake-box input:nth-last-child(1){  
        border:none;  
    } 
    body .layui-m-layer .layui-m-layer-msg{
        bottom: 0;
    }
    
    
    </style>
</head>

<body>
    <div id="app">
        
        
        <header class="page-header">
                        
                <div class="page-bar">
                
                <a href="javascript:history.go(-1)">
                    <img src="<?=$publicDomain?>/mobile/img/icon/back@2x.png" class="back-ico">
                </a>
                
                <div class="bar-title">支付方式</div>
                
            </div>
        </header>
        <section  class="">
            <div class="am-list-news-hd am-cf">
                <h2>请选择您的支付方式：</h2>
            </div>
            <div class="am-list-news-bd pay-type">
                <ul class="am-list">
                    <?php if($is_weixin){?>
                    <li class="am-g am-list-item-dated">
                        <a href="##" class="am-list-item-hd ">
                            <img src="<?=$publicDomain?>/mobile/img/icon/weixin.png" /> 微信支付
                        </a>
                        <span class="am-list-date"><i class="icon icon-choose-pay"></i></span>
                    </li>
                    <?php }else{?>
                    <li class="am-g am-list-item-dated">
                        <a href="##" class="am-list-item-hd ">
                            <img src="<?=$publicDomain?>/mobile/img/icon/ali.png" /> 支付宝支付
                        </a>
                        <span class="am-list-date"><i class="icon icon-choose-pay"></i></span>
                    </li>
                    <?php }?>
                </ul>
            </div>
            <div class="pay-for-money">
                <div class="am-g">
                      <div class="am-u-sm-6">订单金额</div>
                        <div class="am-u-sm-6 text-r">总计：<span class="red"><?=$amount?></span> 元</div>
                </div>
              
                <div class="pay-btn">
                    <a href="javascript:void(0)" onclick="topay()" type="button" class="am-radius am-btn am-btn-danger am-btn-block">支付</a>
                </div>
            </div>
        </section>
            
            <!--<div class="tl-mask"></div>
            <div class="pay-pwd-wrap" style="display: none;">
        
                <div class="text-center">
                        <div class="pay-pwd-title">输入6位数支付密码</div>
           
                    </div>
                <div class="pwd-box">
                    <input type="tel" maxlength="6" class="pwd-input" id="pwd-input">
                    <div class="fake-box">
                       <input type="password" readonly="">
                        <input type="password" readonly="">
                        <input type="password" readonly=""> 
                       <input type="password" readonly="">
                        <input type="password" readonly=""> 
                        <input type="password" readonly=""> 
                    </div> 
                    
                </div>
            </div>-->
    </div>
</body>


<script>
    /*$(".pay-type li").on("click",function(){
        var check = '<i class="icon icon-choose-pay"></i>';
        $(this).find(".am-list-date").html(check);
        $(this).siblings().find(".am-list-date").html('');
    });*/
    var orderno = '<?=$orderno?>';
    // 阿里支付
    function alipay(){
        window.location.href="/Sys/Pay/aliwappayorder?orderno="+orderno;
    }

    //调用微信JS api 支付
    var jsApiParameters = '';

    function jsApiCall(){
        
        //console.log(jsApiParameters);
        jsApiParameters = eval("("+jsApiParameters+")");
        WeixinJSBridge.invoke(
          'getBrandWCPayRequest',
          jsApiParameters,
          function(res){
                console.log(res);
                WeixinJSBridge.log(res.err_msg);
                //alert("AAA:"+res.err_code+res.err_desc+res.err_msg);
                if(res.err_msg == "get_brand_wcpay_request:ok" ) {
                  //支付成功
                 // alert('支付成功');
                    layer.open({
                        content: '支付成功',
                        skin: 'msg',
                        time: 2 
                    });
                    //支付结果
                    window.location.href = "/Sys/Pay/payresult?orderno="+orderno;
                }
              
          }
        );
    }

    function callpay(){
        loadtip({
            content:'加载中..'
        });
        var url = "/Sys/Pay/callpay?orderno="+orderno;
        $.get(url, function(data){
            $("#my-modal-loading").modal('close');
            //alert(data);
            console.log(data);
            var data_arr = eval("("+data+")");
            //alert(data_arr.code);
            if(data_arr.code=='200'){

                jsApiParameters = data_arr.data.jsApiParameters;
                //alert(jsApiParameters.appId);
                if (typeof WeixinJSBridge == "undefined"){
                    //alert(3);
                    if( document.addEventListener ){
                        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                    }else if (document.attachEvent){
                        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
                        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                    }
                }else{
                    //alert(111)
                    jsApiCall();
                }
                loadtip({
                    close:true
                });
            }else{

                loadtip({
                    close:true,
                    alert:data_arr.msg
                });

            }
        });

    }
        
                
     //页面层
    var content='<div class=" text-center">'+ 
                    '<div class="pay-pwd-title">输入6位数支付密码</div>'+ 
       
                '</div>'+ 
                '<div class="pwd-box">'+
                    '<input type="tel" maxlength="6" class="pwd-input" id="pwd-input">'+  
                    '<div class="fake-box">'+  
                       '<input type="password" readonly="">'+ 
                        '<input type="password" readonly="">'+  
                        '<input type="password" readonly="">'+  
                       '<input type="password" readonly="">'+  
                        '<input type="password" readonly="">'+  
                        '<input type="password" readonly="">'+  
                    '</div>'+  
                    
                '</div>';
                
    function topay(){
        <?php if($is_weixin){?>
            callpay();
        <?php }else{?>
            //alert("暂时仅支持微信支付");
            alipay();
        <?php }?>
    }
  
</script>
</html>
