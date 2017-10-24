<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>支付</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css"> -->
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}
    <style>
        .mb-10{margin-bottom: 10px;}
        .gift_bg{background: url(<?=$publicDomain?>/mobile/img/icon/gift_bg.png) repeat;}
        .payInfo-wrap,.new-user-wrap,.pay-wrap{position: relative;padding:0 10px;color: #FFFFFF;}
        .payInfo-wrap input,.new-user-wrap input,.pay-wrap button{width: 100%;height: 40px;border-radius: 4px;outline: 0;}
        .payInfo-wrap .tip{margin: 5px 0;}
        .payInfo-wrap .shop-name{font-size: 25px;text-align: center;margin-top: 20px;}
        .payInfo-wrap .platform-no{font-size: 14px;text-align: center;}
        .payInfo-wrap .pay-tip{font-size: 15px;font-weight: bold;}
        .payInfo-wrap .pay-money{font-size: 25px;font-weight: bold;color: #ffba25;}
        .payInfo-wrap .desc-wrap{font-size: 12px;}
        .desc-wrap .q{background: url(<?=$publicDomain?>/mobile/img/icon/q.png) no-repeat;width: 13px; height: 13px; display: inline-block; background-size: 100%; position: relative; top: 2px;left: 3px;}
        .payInfo-wrap input,
        .new-user-wrap input{padding-left: 10px;color: #333333;}
        .new-user-wrap input{color: #333333;}
        .new-user-wrap{border-top: 2px dashed #9e2520;margin-top: 15px;padding-top: 15px;}
        .new-user-wrap .card-wrap{background: url(<?=$publicDomain?>/mobile/img/icon/card_bg2.png) center center no-repeat;background-size: 100%;padding: 10px 25px ;}
        .card-wrap .item{margin-bottom: 10px;position: relative;}
        .card-wrap .item:last-child{margin-bottom: 0px;}
        .card-wrap .user-tip{color: #F13437;font-size: 16px;text-align: center;font-weight: bold;}
        .card-wrap .getcode{width: 85px;position: absolute;right: 0;top: 0;height: 40px;background: #F13437;border-radius: 0 4px 4px 0;font-size: 12px;padding: 0 10px;}
        .card-wrap .p-1{color: #333;font-size: 15px;font-weight: bold;}
        .card-wrap .p-2{color: #F13437;font-size: 25px;font-weight: bold;}
        .pay-wrap{margin-top: 20px;margin-bottom: 20px;}
        .pay-order{background: #FFBA25;font-size: 17px;font-weight: bold;}
        
        @media only screen and (min-width: 320px) and (max-width: 340px){
            .payInfo-wrap .shop-name { font-size: 22px;margin-top: 10px;}
            .payInfo-wrap input, .new-user-wrap input, .pay-wrap button {height: 35px;}
            .card-wrap .user-tip {font-size: 14px;}
            .card-wrap .getcode{height: 35px;}
            .card-wrap .p-1 {font-size: 12px;}
            .card-wrap .p-2 {font-size: 22px;}
            .pay-order{font-size: 14px;}
        }
        .page-header{
            background: #F9F9F9;
            border-bottom: 1px solid #CCCCCC;
        }
        .page-header .page-bar{
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #333;
            position: relative;
        }
        .page-header .page-bar a{
            
        }

        .page-header .page-bar .back-ico{
            width: 10px;
            height: 17px;
            background: url(<?=$publicDomain?>/mobile/img/icon/back@2x.png) no-repeat;
            background-size:100% ;
            display: block;
            position: absolute;
        }
        .page-header .page-bar .bar-title{
            font-size: 16px;

            width: 100%;
            text-align: center;
        }
    </style>
   
</head>

<body class="gift_bg">
    <header class="page-header">    
        <div class="page-bar">  
            <a href="javascript:history.go(-1)">
                <span class="back-ico"></span>
            </a>
            <span class="bar-title"><?=$title?></span>
        </div>
    </header>
    <!--背景-->
    <div class="gift_bg"></div>
    <!--/end 背景-->
    <!--基本信息-->
    <form id="setpayamountform" action="" method="post">
        <section class="payInfo-wrap">
            <div class="shop-name"><?=$shopDetails['businessname']?></div>
            <div class="platform-no">平台号：<?=$shopDetails['business_code']?></div>
            <div class="tip">消费总金额</div>
            <div>
                <input type="text"  id="amount" <?php if(!empty($amount)){ ?> readonly="true" <?php }?> name="amount" placeholder="询问服务员后输入金额" value="<?=$amount?>"  />
            </div>
            
            <div class="tip">不参与活动金额</div>
            <div>
                <input type="text" placeholder="询问服务员后输入金额" <?php if(!empty($noinvamount)){ ?> readonly="true" <?php }?> value="<?=$noinvamount?>" id="noinvamount" name="noinvamount"  />
            </div>
            <div class="tip">备注</div>
            <div>
                <input type="text" name="remarks" id="remarks" value="" />
            </div>
            <?php if(!empty($customerid)){ ?>
                <div class="tip">牛粮奖励金抵扣</div>
                <div class="input-box">
                    <input type="text" id="maxbonusamount"  placeholder="本次消费最高可抵扣<?=$maxbonusamount?>元" maxlength="20" readonly="readonly"/>
                    <div class="dikou"><span class="red">-</span><span class="bonusamount red" ><?=$bonusamount?></span><span class="red">元</span></div>
                </div>
               
                <?php if($hasbind != 1){ ?>
                <div class="tip bind-login-staff">服务员号（非必填）</div>
                <div class="input-box bind-login-staff">
                    <input type="tel" id="serviceId" name="serviceid" placeholder="请输入服务员号" maxlength="6"/>
                    <button type="button" class="bind" id="bind" onclick="loginbindstaff()">绑定</button>
                </div>
                <?php } ?>
            <?php } ?>
            <div class="text-right"><span class="pay-tip">实付金额：</span><span class="pay-money"><?=$coustamount?></span><span class="pay-tip">元</span></div>
            <input type="hidden" name="trueamount" id="trueamount" value="<?=$amount?>" />
            <div class="desc-wrap">
                <div>不参与活动金额<span class="q"></span></div>
            
                <div>部分消费是不参与优惠活动的（例如套餐、酒水不参与）。请您向服务员确认消费总额，以及是否包含不参与优惠金额。</div>
            </div>
        </section>
    </form>
     <!--/end 基本信息-->
     
    <!--新用户付款-->
    <?php if(empty($customerid)){ ?>
    <section class="new-user-wrap">
        <div class="card-wrap">
            <div class="text-center mb-10"><span class="user-tip"><?php if($hasamount > 0 ){ ?>新用户首次付款验证手机获得立减优惠<?php } ?></span></div>
            <div class="item">
                 <input type="tel" id="mobile" name="mobile"  maxlength="11" placeholder="请输入您的手机号码">
            </div>
            <div class="item" >
                <input type="tel"  id="valicode" name="valicode" maxlength="6" placeholder="请输入验证码">
                <!-- <a href="#" class="get-code" id="ko" onclick="settime()" >获取验证码</a> -->
                <button class="am-btn am-btn-danger get-code getcode" type="button" id="ko" onclick="wait(this)">获取验证码</button>
            </div>

            <div class="item item-bindstaff" >
                <input type="tel" id="service_code" name="service_code" placeholder="请输入服务员号" maxlength="6"/>
                <button class="getcode"  id="ko" onclick="bindstaff(this)" >绑定</button>
            </div>

            <div class="text-right">
                <span class="p-1">优惠后金额：</span><span class="p-2"><?=$amount?></span ><span class="p-1">元</span>
                <br/><div class="show-bonusamount" style="display: none;"><span class="p-1" style="font-size: 8px">牛粮奖励金抵扣：</span><span class='red'>-</span><span class="red bonusamount"><?=$bonusamount?></span ><span class="p-1">元</span></div>
            </div>
        </div>
    </section>

     <?php } ?>
    <!--/end 新用户付款-->
    
    <!--确认买单-->
    <section class="pay-wrap">
        <input type="hidden" name="business_code" id="business_code" value="<?=$shopDetails['business_code']?>" />
        <input type="hidden" name="managerid" id="managerid" value="<?=$managerid?>" />
        <input type="hidden" name="customerid" id="customerid" value="<?=$customerid?>" />
        <input type="hidden" name="bonusamount" id="bonusamount_cost" value="<?=$bonusamount?>" />
        <button class="pay-order pay" id="pay">确认买单</button>
    </section>
    <!--确认买单-->
</body>

<!-- <script src="http://cdn.amazeui.org/amazeui/2.7.2/js/amazeui.min.js"></script> -->
<script type="text/javascript">
    $(function(){

        $("#amount").bind("input propertychange", function () {
            clearNoNum(this);
            $(".pay-money").text($(this).val());
            $(".p-2").text($(this).val());
            $("#trueamount").val($(this).val());
            // var stop=false;
            //  // alert(1);
            // var amount =  $("#amount").val();
            // var mobile = $("#mobile").val();
            // // alert(amount);
            // // alert(mobile);
            // if(stop==false){
            //     stop=true; 
            //     if(amount > 0 && mobile != '' && mobile.length == 11){
            //         // alert(amount);
            //         // alert(mobile);
            //         $.ajax({
            //             type:'GET',
            //             dataType:'json',
            //             url:"/StoBusiness/Index/returngetbounty?mobile=" + mobile + "&amount=" + amount,
            //             success: function(result){
            //                 // alert(result);
            //                 result = eval("("+result+")");
            //                 //alert(result);
            //                 //alert(result.code);
            //                 if(result.code == '200'){
                                
                               
            //                     // alert(result.data.data.amount);
            //                     var disamount = result.data.data.amount;
            //                     //var newamount = amount - disamount
            //                     // alert(amount);
            //                     // alert(disamount);
            //                     // alert(newamount);
            //                     $(".pay-money").text(disamount);
            //                     $("#trueamount").val(disamount);
            //                     $(".p-2").text(disamount);
            //                 }else{
                                
            //                     layer.open({
            //                         content: result.msg,
            //                         skin: 'msg',
            //                         time: 2 
            //                     });
            //                     return false;
            //                 }
                            
                           
            //             }
            //         });
            //     }
            // }
        });

        $("#amount").bind("input blur", function () {
            clearNoNum(this);
            $(".pay-money").text($(this).val());
            $(".p-2").text($(this).val());
            $("#trueamount").val($(this).val());
           
             // alert(1);
            var amount =  $("#amount").val();
            var mobile = $("#mobile").val();
            var noinvamount = $("#noinvamount").val();
            var continuestaus = false;
            // alert(amount);
            // alert(mobile);
            // 
            if (typeof(mobile) == "undefined" && continuestaus == false && amount > 0){
                var customerid = $("#customerid").val();
                continuestaus = true;
                $.ajax({
                        type:'GET',
                        dataType:'json',
                        url:"/StoBusiness/Index/returnbonusamount?storeid=<?=$storeid?>&customerid=" + customerid + "&amount=" + amount + "&noinvamount="+noinvamount,
                        success: function(result){
                            // alert(result);
                            result = eval("("+result+")");
                            

                            if(result.code == '200'){
                                
                                var payamout = amount - result.data.bonusamount;
                                 payamout = Math.round(payamout*100)/100;
                                $("#maxbonusamount").attr("placeholder","本次消费最高可抵扣"+result.data.maxbonusamount+"元");
                                $(".bonusamount").text(result.data.bonusamount);

                                $("#trueamount").val(payamout);
                                $(".pay-money").text(payamout);
                                $(".p-2").text(payamout);
                                $("#bonusamount_cost").val(result.data.bonusamount);

                                if(result.data.bonusamount > 0){
                                    $('.show-bonusamount').show();
                                }

                            }else{
                                
                                layer.open({
                                    content: result.msg,
                                    skin: 'msg',
                                    time: 2 
                                });
                                return false;
                            }
                            
                           
                        }
                    });
                
            }else{

                if(amount > 0 && mobile != '' && mobile.length == 11){
                    // alert(amount);
                    // alert(mobile);
                    $.ajax({
                        type:'GET',
                        dataType:'json',
                        url:"/StoBusiness/Index/returngetbounty?mobile=" + mobile + "&amount=" + amount,
                        success: function(result){
                            // alert(result);
                            result = eval("("+result+")");
                            //alert(result);
                            //alert(result.code);
                            if(result.code == '200'){
                                
                               
                                // alert(result.data.data.amount);
                                var disamount = result.data.data.amount;

                               // var newamount = amount - disamount
                                // alert(amount);
                                // alert(disamount);
                                // alert(newamount);
                                $(".pay-money").text(disamount);
                                $("#trueamount").val(disamount);
                                $(".p-2").text(disamount);
                            }else{
                                
                                layer.open({
                                    content: result.msg,
                                    skin: 'msg',
                                    time: 2 
                                });
                                return false;
                            }
                            
                           
                        }
                    });

                    $.ajax({
                            type:'GET',
                            dataType:'json',
                            url:"/StoBusiness/Index/returnmobilebonusamount?storeid=<?=$storeid?>&mobile=" + mobile + "&amount=" + amount + "&noinvamount="+noinvamount,
                            success: function(result){
                                // alert(result);
                                result = eval("("+result+")");
                                

                                if(result.code == '200'){
                                    
                                    var payamout = amount - result.data.bonusamount;
                                     payamout = Math.round(payamout*100)/100;
                                    $("#maxbonusamount").attr("placeholder","本次消费最高可抵扣"+result.data.maxbonusamount+"元");
                                    $(".bonusamount").text(result.data.bonusamount);

                                    $("#trueamount").val(payamout);
                                    $(".pay-money").text(payamout);
                                    $(".p-2").text(payamout);
                                    $("#bonusamount_cost").val(result.data.bonusamount);

                                    if(result.data.bonusamount > 0){
                                        $('.show-bonusamount').show();
                                    }

                                }else{
                                    
                                    layer.open({
                                        content: result.msg,
                                        skin: 'msg',
                                        time: 2 
                                    });
                                    return false;
                                }
                                
                               
                            }
                        });
                }
            }
        });
        

        $("#noinvamount").bind("input propertychange", function () {
            clearNoNum(this);

            var customerid = $("#customerid").val();
              // alert(1);
            var amount =  $("#amount").val();
        
            var noinvamount = $("#noinvamount").val();

            var mobile = $("#mobile").val();

            if(customerid != ''){
                $.ajax({
                        type:'GET',
                        dataType:'json',
                        url:"/StoBusiness/Index/returnbonusamount?storeid=<?=$storeid?>&customerid=" + customerid + "&amount=" + amount + "&noinvamount="+noinvamount,
                        success: function(result){
                            // alert(result);
                            result = eval("("+result+")");
                            

                            if(result.code == '200'){
                                
                                var payamout = amount - result.data.bonusamount;
                                payamout = Math.round(payamout*100)/100;
                                $("#maxbonusamount").attr("placeholder","本次消费最高可抵扣"+result.data.maxbonusamount+"元");
                                $(".bonusamount").text(result.data.bonusamount);

                                $("#trueamount").val(payamout);
                                $(".pay-money").text(payamout);
                                $(".p-2").text(payamout);
                                $("#bonusamount_cost").val(result.data.bonusamount);

                                if(result.data.bonusamount > 0){
                                    $('.show-bonusamount').show();
                                }

                            }else{
                                
                                layer.open({
                                    content: result.msg,
                                    skin: 'msg',
                                    time: 2 
                                });
                                return false;
                            }
                            
                           
                        }
                    });
            }else{
                $.ajax({
                    type:'GET',
                    dataType:'json',
                    url:"/StoBusiness/Index/returnmobilebonusamount?storeid=<?=$storeid?>&mobile=" + mobile + "&amount=" + amount + "&noinvamount="+noinvamount,
                    success: function(result){
                        // alert(result);
                        result = eval("("+result+")");
                        

                        if(result.code == '200'){
                            
                            var payamout = amount - result.data.bonusamount;
                             payamout = Math.round(payamout*100)/100;
                            $("#maxbonusamount").attr("placeholder","本次消费最高可抵扣"+result.data.maxbonusamount+"元");
                            $(".bonusamount").text(result.data.bonusamount);

                            $("#trueamount").val(payamout);
                            $(".pay-money").text(payamout);
                            $("#bonusamount_cost").val(result.data.bonusamount);
                            $(".p-2").text(payamout);

                            if(result.data.bonusamount > 0){
                                $('.show-bonusamount').show();
                            }

                        }else{
                            
                            layer.open({
                                content: result.msg,
                                skin: 'msg',
                                time: 2 
                            });
                            return false;
                        }
                        
                       
                    }
                });
            }

        });

        $("#mobile").bind("input blur", function () {
            // alert(1);
            var amount =  $("#amount").val();
            var mobile = $("#mobile").val();
            var noinvamount = $("#noinvamount").val();
            // alert(amount);
            // alert(mobile);
            if(amount > 0 && mobile != '' && mobile.length == 11){
                // alert(amount);
                // alert(mobile);
                $.ajax({
                    type:'GET',
                    dataType:'json',
                    url:"/StoBusiness/Index/returngetbounty?mobile=" + mobile + "&amount=" + amount,
                    success: function(result){
                        // alert(result);
                        result = eval("("+result+")");
                        //alert(result);
                        //alert(result.code);
                        if(result.code == '200'){
                            
                           
                            // alert(result.data.data.amount);
                            var disamount = result.data.data.amount;
                            //var newamount = amount - disamount
                            // alert(amount);
                            // alert(disamount);
                            // alert(newamount);
                            $(".pay-money").text(disamount);
                            $("#trueamount").val(disamount);
                            $(".p-2").text(disamount);

                        }else{
                            
                            layer.open({
                                content: result.msg,
                                skin: 'msg',
                                time: 2 
                            });
                            return false;
                        }
                        
                       
                    }
                });

                $.ajax({
                    type:'GET',
                    dataType:'json',
                    url:"/StoBusiness/Index/returnmobilebonusamount?storeid=<?=$storeid?>&mobile=" + mobile + "&amount=" + amount + "&noinvamount="+noinvamount,
                    success: function(result){
                        // alert(result);
                        result = eval("("+result+")");
                        

                        if(result.code == '200'){
                            
                            var payamout = amount - result.data.bonusamount;
                             payamout = Math.round(payamout*100)/100;
                            $("#maxbonusamount").attr("placeholder","本次消费最高可抵扣"+result.data.maxbonusamount+"元");
                            $(".bonusamount").text(result.data.bonusamount);

                            $("#trueamount").val(payamout);
                            $(".pay-money").text(payamout);
                            $(".p-2").text(payamout);
                           
                            $("#bonusamount_cost").val(result.data.bonusamount);

                            if(result.data.bonusamount > 0){
                                $('.show-bonusamount').show();
                            }
                        }else{
                            
                            layer.open({
                                content: result.msg,
                                skin: 'msg',
                                time: 2 
                            });
                            return false;
                        }
                        
                       
                    }
                });
            }
        });

        // $("#mobile").bind("input propertychange", function () {
        //     // alert(1);
        //     var amount =  $("#amount").val();
        //     var mobile = $("#mobile").val();
        //     // alert(amount);
        //     // alert(mobile);
        //     if(amount > 0 && mobile != '' && mobile.length == 11){
        //         // alert(amount);
        //         // alert(mobile);
        //         $.ajax({
        //             type:'GET',
        //             dataType:'json',
        //             url:"/StoBusiness/Index/returngetbounty?mobile=" + mobile + "&amount=" + amount,
        //             success: function(result){
        //                 // alert(result);
        //                 result = eval("("+result+")");
        //                 //alert(result);
        //                 //alert(result.code);
        //                 if(result.code == '200'){
                            
                           
        //                     // alert(result.data.data.amount);
        //                     var disamount = result.data.data.amount;
        //                     //var newamount = amount - disamount
        //                     // alert(amount);
        //                     // alert(disamount);
        //                     // alert(newamount);
        //                     $(".pay-money").text(disamount);
        //                     $("#trueamount").val(disamount);
        //                     $(".p-2").text(disamount);
        //                 }else{
                            
        //                     layer.open({
        //                         content: result.msg,
        //                         skin: 'msg',
        //                         time: 2 
        //                     });
        //                     return false;
        //                 }
                        
                       
        //             }
        //         });
        //     }
        // });

        $("#pay").click(function(){
           
            //$("#form").submit();
            var amount =  $("#amount").val();
            var noinvamount = $("#noinvamount").val();
            var managerid = $("#managerid").val();
            var business_code = $("#business_code").val();
            var remarks = $("#remarks").val();
            var customerid = $("#customerid").val();

            var mobile = $("#mobile").val();
            var valicode = $("#valicode").val();
            var continuestaus = false;
            var bonusamount = $("#bonusamount_cost").val();
            var service_code = $("#serviceId").val();
            var service_code_2 = $("#service_code").val();

            if(service_code_2 != '' && typeof service_code_2 !='undefined'){
                service_code = service_code_2;
            }

            if(typeof amount =='undefined' || amount=='' || amount=='0'){
                //alert("请填写消费金额");
                 layer.open({
                            content: '请填写消费金额',
                            skin: 'msg',
                            time: 2 
                        });
                return false;
            }

            if(parseFloat(amount) <= 0){
                 //alert("请填写消费金额");
                 layer.open({
                            content: '请填写消费金额',
                            skin: 'msg',
                            time: 2 
                        });
                return false;
            }

            if(amount * 0.5 < noinvamount){
                //alert("不参与活动金额不得大于总金额的50%");
                 layer.open({
                            content: '不参与活动金额不得大于总金额的50%',
                            skin: 'msg',
                            time: 2 
                        });
                return false;
            }
            
          
           

            if(customerid == ''){

                if(mobile == ''){
                    layer.open({
                            content: '请填写手机号码',
                            skin: 'msg',
                            time: 2 
                        });
                    return false;
                }

                if(valicode == ''){
                    layer.open({
                            content: '请填写验证码',
                            skin: 'msg',
                            time: 2 
                        });
                    return false;
                }

                $.ajax({
                    type:'GET',
                    dataType:'json',
                    url:"/StoBusiness/Index/ajaxlogin?mobile=" + mobile + "&valicode=" + valicode +"&storeid=<?=$storeid?>",
                    success: function(result){
                        //alert(result);
                        result = eval("("+result+")");
                        //alert(result);
                        //alert(result.code);
                        if(result.code == '200'){
                            
                           
                           customerid = result.data.data.customerid
                            var param = "amount="+amount+"&noinvamount="+noinvamount+"&managerid="+managerid+"&business_code="+business_code+"&remarks="+remarks + "&customerid="+customerid+'&storeid=<?=$storeid?>&openid=<?=$openid?>&bonusamount='+bonusamount+"&service_code="+service_code;
                           getUrl(param);

                        }else{
                            
                            layer.open({
                                content: result.msg,
                                skin: 'msg',
                                time: 2 
                            });
                            return false;
                        }
                        
                       
                    }
                });

            }else{

                var param = "amount="+amount+"&noinvamount="+noinvamount+"&managerid="+managerid+"&business_code="+business_code+"&remarks="+remarks + "&customerid="+customerid+'&storeid=<?=$storeid?>&openid=<?=$openid?>&bonusamount='+bonusamount+"&service_code="+service_code;
                
                getUrl(param);
            }

           
            
        });

    });
    
    function bindstaff(){
        
        var mobile = $("#mobile").val();
        var valicode = $("#valicode").val();
        var service_code = $("#service_code").val();
        if(mobile == ''){
            layer.open({
                    content: '请填写手机号码',
                    skin: 'msg',
                    time: 2 
                });
            return false;
        }

        if(valicode == ''){
            layer.open({
                    content: '请填写验证码',
                    skin: 'msg',
                    time: 2 
                });
            return false;
        }

        if(service_code == ''){
            layer.open({
                    content: '请填写服务员号',
                    skin: 'msg',
                    time: 2 
                });
            return false;
        }

        $.ajax({
            type:'GET',
            dataType:'json',
            url:"/StoBusiness/Index/ajaxbindstaff?service_code="+service_code+"&mobile=" + mobile + "&valicode=" + valicode +"&storeid=<?=$storeid?>",
            success: function(result){
                //alert(result);
                result = eval("("+result+")");
                // alert(result);
                // alert(result.code);
                if(result.code == '200'){
                    
                    layer.open({
                        content: '成功绑定',
                        skin: 'msg',
                        time: 2 
                    });
                    
                    $('.item-bindstaff').remove();
                }else{
                    
                    layer.open({
                        content: result.msg,
                        skin: 'msg',
                        time: 2 
                    });
                    return false;
                }
                
               
            }
        });

    }

    function loginbindstaff(){
        
        
        var customerid = $("#customerid").val();
        var service_code = $("#serviceId").val();

        if(service_code == ''){

            layer.open({
                content: '请填写服务员号',
                skin: 'msg',
                time: 2 
            });
            return false;
        }
         $.ajax({
            type:'GET',
            dataType:'json',
            url:"/StoBusiness/Index/ajaxloginbindstaff?service_code="+service_code+"&customerid=" + customerid +"&storeid=<?=$storeid?>",
            success: function(result){
                //alert(result);
                result = eval("("+result+")");
                // alert(result);
                // alert(result.code);
                if(result.code == '200'){
                    
                    layer.open({
                        content: '成功绑定',
                        skin: 'msg',
                        time: 2 
                    });
                    
                    $('.bind-login-staff').remove();
                }else{
                    
                    layer.open({
                        content: result.msg,
                        skin: 'msg',
                        time: 2 
                    });
                    return false;
                }
                
               
            }
        });
    }
    
    function getUrl(param){
      
        var url = "/StoBusiness/Index/getUrl?"+param;

        $.get(url, function(data){

            data = eval("("+data+")");
          
            if(data.code=='200'){
                if(typeof data.data.url!='undefined' && data.data.url!=''){
                    $("#setpayamountform").attr("action",data.data.url);
                    $("#setpayamountform").submit();
                }
                
            }
        });
    }

      function clearNoNum(obj){    
      obj.value = obj.value.replace(/[^\d.]/g,"");  //清除“数字”和“.”以外的字符     
      obj.value = obj.value.replace(/\.{2,}/g,"."); //只保留第一个. 清除多余的     
      obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");    
      obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');//只能输入两个小数
      //以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额        
      if(obj.value.indexOf(".")< 0 && obj.value !=""){ 
       obj.value= parseFloat(obj.value);    
      }    
    }  

    var countdown=60; 
    var timeout;
    function wait(dom){
        var mobile = $("#mobile").val();
            if(mobile ==''){
               // alert('请填写手机号码');
               
                  layer.open({
                    content: '请填写手机号码',
                    skin: 'msg',
                    time: 2 
                  });
                return false
            }
            settime();
    }
        function settime() { 

             var mobile = $("#mobile").val();
            if (countdown == 0) { 
                $("#ko").attr("disabled",false);    
                $("#ko").html("获取验证码"); 
                countdown = 60; 
                clearTimeout(timeout);
               // return;
            }else{ 

                if(countdown == 60){

                    $.ajax({
                        type:'GET',
                        dataType:'json',
                        url:"/Index/Index/Send?mobile=" + mobile,
                        success: function(result){
                            if(result.code == 200){
                                //alert('发送成功');
                                layer.open({
                                    content: '发送成功',
                                    skin: 'msg',
                                    time: 2 
                                });
                            }else{
                                layer.open({
                                    content: result.msg,
                                    skin: 'msg',
                                    time: 2 
                                });
                            }
                        }
                    });
                }
               
                $("#ko").attr("disabled", true); 
                $("#ko").html("重新发送(" + countdown + ")"); 
                countdown--;

                timeout= setTimeout(function() { 
                    settime() 
                },1000); 
            } 

           
        } 
</script>
</html>
