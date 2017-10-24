<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{$title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2//mobile/css/amazeui.min.css"> -->
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}
    <style>  
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
        width: 49px;  
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
    </style>
</head>

<body>
 
    <div data-am-widget="list_news" class="am-list-news am-list-news-default">
        <div class="am-list-news-hd am-cf">
            <h2>请选择您的支付方式：</h2>
        </div>
        <div class="am-list-news-bd pay-type">
            <ul class="am-list">
                <li class="am-g am-list-item-dated">
                    <a href="#" class="am-list-item-hd ">
                        <img src="/mobile/img/icon/balance.png" /> 余额支付 <?php if($result < 1){ ?><span class="gray">余额不足</span><?php } ?>
                    </a>
                    <span class="am-list-date"><i class="icon icon-choose-pay"></i></span>
                </li>
                <?php if($is_weixin){?>
                <li class="am-g am-list-item-dated">
                    <a href="##" class="am-list-item-hd ">
                        <img src="/mobile/img/icon/weixin.png" /> 微信支付
                    </a>
                    <span class="am-list-date"></span>
                </li>
                <?php }else{?>
                <li class="am-g am-list-item-dated">
                    <a href="##" class="am-list-item-hd ">
                        <img src="/mobile/img/icon/ali.png" /> 支付宝支付
                    </a>
                    <span class="am-list-date"></span>
                </li>
                <?php }?>
                <?php if(!$is_alipay){?>
                <li class="am-g am-list-item-dated">
                    <a href="##" class="am-list-item-hd ">
                        <img src="/mobile/img/icon/union.png" class="vertical-middle" /> 快捷支付
                    </a>
                    <span class="am-list-date"></span>
                </li>
                <?php }?>
            </ul>
        </div>
        <div class="pay-for-money am-g">
            <div class="am-u-sm-6">订单金额</div>
            <div class="am-u-sm-6 text-right">总计：<span class="red"><?=$amount?></span> 元</div>
            <div class="pay-btn">
                <input type="hidden" name="paytype" value="2" id="paytype" />
                <a href="javascript:;"  id="pay_ac" type="button" class="am-btn am-btn-danger am-btn-block">确定付款</a><!--/StoBusiness/Indextest/paysuccess?storeid=1-->
            </div>
        </div>
    </div>

    <div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="my-modal-loading">
      <div class="am-modal-dialog">
        <div class="am-modal-hd">正在载入...</div>
        <div class="am-modal-bd-loading">
          <span class="am-icon-spinner am-icon-spin"></span>
        </div>
      </div>
    </div>

<script type="text/javascript">
    $(function(){
        var order_no = '';

        var chosetext = $(".icon-choose-pay").parent().parent().find("a").text();

        if($.trim(chosetext) == '支付宝支付'){
           // alert(1);
            $("#paytype").val(1);
        }else if($.trim(chosetext) == '快捷支付'){
        	$("#paytype").val(3);
        }else if($.trim(chosetext) == '余额支付'){
            //alert(2);
            $("#paytype").val(2);
        }

        $("#pay_ac").click(function(){
            //alert('aa');
            //callpay();
            $("#my-modal-loading").modal();

            var paytype = $("#paytype").val();

            //提交订单
            $.get("/StoBusiness/Indextest/addorder?amount=<?=$trueamount?>&business_code=<?=$business_code?>&customerid=<?=$customerid?>&managerid=<?=$managerid?>&noinvamount=<?=$noinvamount?>&remarks=<?=$remarks?>&sign=<?=$sign?>&hascoust=<?=$hascoust?>", function(data){
                data = eval("("+data+")");

                if(data.code=='200'){
                    var pay_code = data.data.pay_code;
                    if(typeof pay_code!='undefined' && pay_code!=''){
                        
                        order_no = pay_code;

                        <?php if($is_weixin){?>
                            callpay(pay_code);
                        <?php }else{ //else{?>
                            //alipay(pay_code);
                      

                        if(paytype == 1){
                            alipay(pay_code);
                        }

                        if(paytype ==2){
                            $("#my-modal-loading").modal('close');
                            <?php if($result < 1){ ?>

                                    layer.open({
                                        content: '余额不足',
                                        skin: 'msg',
                                        time: 2 
                                    });
                                    return false;
                                    
                            <?php } ?>
                            topay(pay_code);
                            //window.location.href="/StoBusiness/Indextest/paypassword?amount=<?=$amount?>&customerid=<?=$customerid?>&storeid=<?=$storeid?>&orderno="+pay_code;
                            //balancepay(pay_code);
                        }

                        if(paytype == 3) {
                           // alert(pay_code);
                            quickpay(pay_code);
                        }

                        <?php }?>
                        //$("#my-modal-loading").modal('close');
                    }
                }else if(data.code=='104'){
                    window.location.href="/Index/Index/login"; // 跳转登录页面
                }
            });


        });

        $(".pay-type li").on("click",function(){
            var check = '<i class="icon icon-choose-pay"></i>';
            $(this).find(".am-list-date").html(check);
            var text =$(this).find("a").text();
           
            if($.trim(text) == '支付宝支付'){
               // alert(1);
                $("#paytype").val(1);
            }else if($.trim(text) == '余额支付'){
                //alert(2);
                $("#paytype").val(2);
            }else if($.trim(text) == '快捷支付'){
                $("#paytype").val(3);
            }

            $(this).siblings().find(".am-list-date").html('');
        });

        // 阿里支付
        function alipay(orderno){
            window.location.href="/StoBusiness/Indextest/aliwappayorder?amount=<?=$trueamount?>&coustamount=<?=$coustamount?>&customerid=<?=$customerid?>&orderno="+orderno;
        }
        
		function quickpay(orderno){
			window.location.href="/StoBusiness/Indextest/quickpay?customerid=<?=$customerid?>&orderno="+orderno+"&coustamount=<?=$coustamount?>";
		}
    

        function getopenid(){
              var opid = "<?=$openid?>";
              return opid;
              // if(opid==''){
              //   opid =  $.cookie("cookie_openid");
              //   if(opid==null)
              //     opid='';
              //   return opid;
              // }else{
              //   var cookie_openid = $.cookie("cookie_openid");
              //   if(typeof cookie_openid=='undefined' || cookie_openid=='' || cookie_openid==null){
              //     $.cookie('cookie_openid', opid, { expires: 30 });
              //   }
              //   return opid;
              // }
        }

       
        // alert(openid);
        // if(typeof openid!='undefined' || openid!=''){

            //调用微信JS api 支付
            var jsApiParameters = '';

            function jsApiCall(data){
                //alert(5);
                //alert(data);
                //alert("jsApiParameters:"+data);
                jsApiParameters = eval("("+data+")");
                WeixinJSBridge.invoke(
                  'getBrandWCPayRequest',
                  jsApiParameters,
                  function(res){
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
                            window.location.href = "/StoBusiness/Indextest/paysuccess?orderno="+order_no;
                        }
                      
                  }
                );
            }

            function callpay(orderno){
                
                var chosetext = $(".icon-choose-pay").parent().parent().find("a").text();
                if($.trim(chosetext) == '余额支付'){
                    $("#my-modal-loading").modal('close');
                            <?php if($result < 1){ ?>

                                    layer.open({
                                        content: '余额不足',
                                        skin: 'msg',
                                        time: 2 
                                    });
                                    return false;
                                    
                            <?php } ?>
                            topay(orderno);
                }else{
                    //alert(2);
                    var openid = '<?=$openid?>';
                    //alert(openid);
                    //$("#my-modal-loading").modal();
                    var url = "/StoBusiness/Indextest/callpay?amount=<?=$trueamount?>&coustamount=<?=$coustamount?>&customerid=<?=$customerid?>&openid="+openid+"&orderno="+orderno;
                    $.get(url, function(data){
                        $("#my-modal-loading").modal('close');
                        jsApiParameters = data;

                        if (typeof WeixinJSBridge == "undefined"){
                            if( document.addEventListener ){
                                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                            }else if (document.attachEvent){
                                document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
                                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                            }
                        }else{
                            //alert(3);
                            //alert(data);
                            jsApiCall(data);
                        }
                    });
                }
            }
        // }
    });

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
                
    function topay(pay_code){
        layer.open({
            type: 1,
            content: content,
            anim: 'up',
            style: 'position:fixed; bottom:0; left:0; width: 100%; height: 120px; padding:20px 0; border:none;',
            success: function(elem){
                  var $input = $(".fake-box input");  
                 
                $("#pwd-input").on("input", function() {  
                    var pwd = $(this).val().trim();  
                    for (var i = 0, len = pwd.length; i < len; i++) {  
                        $input.eq("" + i + "").val(pwd[i]);  
                    }  
                    $input.each(function() {  
                        var index = $(this).index();  
                        if (index >= len) {  
                            $(this).val("");  
                        }  
                    });  
                    if (len == 6) {  
                        
                        var pwd = $("#pwd-input").val();
                        var customerid = "<?=$customerid?>";
                       
                       
                       
                        $.ajax({
                            type:"post",
                            url:"/StoBusiness/Indextest/checkpassword",
                            data:{'pwd':pwd,'customerid':customerid},
                            async:true,
                            success:function(data){
                               

                                var _data=JSON.parse(data); 
                                if(_data.code == 200){
                                  
                                    balancepay(pay_code);

                                }else if(_data.code == 404){
                                    //alert('参数错误');
                                    layer.open({
                                        content: '参数错误',
                                        skin: 'msg',
                                        time: 2 
                                    });
                                    return false
                                }else if(_data.code== 50002){
                                    layer.open({
                                        content: _data.msg,
                                        skin: 'msg',
                                        time: 2 
                                    });
                                  
                                    return false
                                }else if(_data.code == 50000){
                                    layer.open({
                                        content: "密码错误",
                                        skin: 'msg',
                                        time: 2 
                                    });

                                    var mymessage=confirm("密码输入错误三次请重新设置！");
                                    if(mymessage==true){  
                                        layer.open({
                                            content: "请设置密码",
                                            skin: 'msg',
                                            time: 2 
                                        });
                                        window.location.href = "/StoBusiness/Indextest/valicode?customerid="+customerid+"&business_code=<?=$business_code?>";
                                        return false;
                                    }else{ 
                                        layer.open({
                                            content: "已取消",
                                            skin: 'msg',
                                            time: 2 
                                        });
                                        return false;
                                    }
                                   
                                }else{
                                    
                                    layer.open({
                                        content: '密码错误',
                                        skin: 'msg',
                                        time: 2 
                                    });
                                   
                                    return false
                                   
                                }
                                
                            }
                        });
                    }  
                });  
            }
          });
    }

    function balancepay(pay_code){
           
            var amount = "<?=$trueamount?>";
            var customerid = "<?=$customerid?>";
            var orderno = pay_code;
            var storeid = "<?=$storeid?>";
            var pwd = $("#pwd-input").val();
            var coustamount ="<?=$coustamount?>";
            var business_code = "<?=$business_code?>";
            $.ajax({
                    type:"post",
                    url:"/StoBusiness/Indextest/balancepay",
                    data:{'orderno':orderno,'amount':amount,'coustamount':coustamount,'customerid':customerid,'pwd':pwd},
                    async:true,
                    success:function(data){
                       
                        $("#my-modal-loading").modal('close');
                        var _data=JSON.parse(data); 
                        if(_data.code == 200){
                            //alert('支付成功');
                            layer.open({
                                content: '支付成功',
                                skin: 'msg',
                                time: 2 
                            });
                            window.location.href = "/StoBusiness/Indextest/paysuccess?orderno="+pay_code;
                        }else{
                            // layer.open({
                            //     content: data,
                            //     skin: 'msg',
                            //     time: 2 
                            // });
                            alert(_data.msg); 
                            window.location.href = "/StoBusiness/Indextest/setpayamount?business_code=" + business_code; //"/index/index/storeindex?storeid="+storeid;
                        }
                    }
            });
        }

</script>
</body>

</html>