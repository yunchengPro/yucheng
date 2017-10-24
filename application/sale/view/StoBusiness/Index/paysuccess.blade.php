<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{$title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/<?=$publicDomain?>/mobile/css/amazeui.min.css"> -->
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/amazeui.min.css">
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
    {include file="Pub/assetcss" /}
    {include file="Pub/assetjs" /}
    <style>
    .suc-item{
        display: -moz-box;
        display: -webkit-box;
              padding: 15px 10px;
     border-bottom: 1px solid #efefef;
    }
    .suc-item .left{
        -webkit-box-flex: 1;
        -webkit-box-pack: center;/*垂直居中*/
        -webkit-box-align:start;/* 水平居左 */
        text-align: left;
    }
    .suc-item .right{
        -webkit-box-flex: 1;
        -webkit-box-pack: center;/*垂直居中*/
        -webkit-box-align:end;/* 水平居右 */
        text-align: right;
    }
    </style>
    <style>
        .c-333{color: #333333}
        .fav {background: url(<?=$publicDomain?>/mobile/img/icon/tag.png) 50% no-repeat; background-size: 100%;
               color: #fff;display: inline-block;padding-left: 5px;padding-right: 15px;
                position: relative;left: -5px;
        }
        .payed-money{ font-size: 40px; margin-top: 0;font-weight: normal;color: #333333;}
        .icon-right { background-size: 120%;position: relative;top: .5rem;}
        .payed-money small{font-size: 15px;}
        .suc-item {
            position: relative;
            display: -webkit-box;
            -webkit-box-align: center;
            height: 44px;
            padding: 0px 15px 0 0;
            border-bottom: 1px solid #efefef;
            margin-left: 15px;
        }
        .suc-item .left{
            -webkit-box-flex: 1;
            -webkit-box-pack: center;/*垂直居中*/
            -webkit-box-align:start;/* 水平居左 */
            text-align: left;
             color:#666666;
        }
        .suc-item .right{
            -webkit-box-flex: 1;
            -webkit-box-pack: center;/*垂直居中*/
            -webkit-box-align:end;/* 水平居右 */
            text-align: right;
             color:#333333;
        }
        .suc-tip{ padding: 15px; font-size: 12px; text-align: left; color: #666;}
        .suc-oper{margin-top: 30px;}
        .suc-oper .oper-btn{
            height: 40px;
            line-height: 40px;
            color: #FFFFFF;
            background: #F13437;
            font-size: 17px;
            width: 130px;
            display: inline-block;
            border-radius: 4px;
        }
        .suc-oper .oper-btn:first-child{margin-right: 25px;}
    </style>    
</head>

<body>
    
    <div class="pay-success-container" style="display:none;">
        <a href="#" class="c-333"><b class="businessname"></b></a>
        <div class="payed-money"><span class="payamount"></span><small>元</small></div>
        <div class="success" style="display: none;">
            <div class="text-center c-333">支付成功</div>
            <div class="suc-item suc-amount" >
                <div class="left">订单金额</div>
                <div class="right"><span class="red amount"></span>元</div>
            </div>
            <div class="suc-item suc-cost" style="display: none;">
                <div class="left"><span class="fav">立减优惠</span><span class="red">奖励金</span> </div>
                <div class="right"><span class="red cost"></span>元</div>
            </div>
            <div class="suc-item suc-bonusamount" style="display: none;">
                <div class="left">牛粮奖励金</div>
                <div class="right"><span class="red bonusamount"></span>元</div>
            </div>
        </div>
        <div class="nosuccess" style="display: none;">
            <div class="text-center c-333">支付失败</div>
             <div class="suc-item suc-amount" >
                <div class="left">订单金额</div>
                <div class="right"><span class="red amount"></span>元</div>
            </div>
            <div class="suc-item suc-cost" style="display: none;">
                <div class="left"><span class="fav">立减优惠</span><span class="red">奖励金</span> </div>
                <div class="right"><span class="red cost"></span>元</div>
            </div>
            <div class="suc-item suc-bonusamount" style="display: none;">
                <div class="left">牛粮奖励金</div>
                <div class="right"><span class="red bonusamount"></span>元</div>
            </div>
        </div>
        
        <div class="suc-item">
            <div class="left">赠送牛豆</div>
            <div class="right"><span class="red returnBullamont"></span>牛豆</div>
        </div>
       
        <div class="suc-item">
            <div class="left">收款人</div>
            <div class="right"><span class="businessname"></span></div>
        </div>
        <div class="suc-item">
            <div class="left">交易时间</div>
            <div class="right"><span class="addtime"></span></div>
        </div>
       <!--  <div class="suc-tip">
            【】温馨提示：牛粉邀请好友注册成功即获赠10牛豆并获得好友每一笔消费产生的奖励金。
        </div> -->
        <!--<div class="pay-btn">
            <a href="index.html" type="button" class="am-btn am-btn-danger am-btn-block">完 成</a>
        </div>-->
        
        <div class="suc-oper">
            <a href="/StoBusiness/Index/shareactivity" class="oper-btn">分享</a>
            <a href="/StoBusiness/Index/comment?orderno=<?=$orderno?>" class="oper-btn">评价</a>
        </div>
    </div>

<!--    <div class="pay-success-container" style="display:none;">
        <div class="success" style="display: none;">
            <img src="<?=$publicDomain?>/mobile/img/icon/success.png" />
            <div class="pay-success-title"></div>
            <h2 class="red paymoney"><span class="payamount"></span><small>元</small></h2>
        </div>  
        <div class="nosuccess" style="display: none;">
            <img src="<?=$publicDomain?>/mobile/img/icon/error.png" />
            <div class="pay-success-title">支付失败</div>
            <h2 class="red paymoney"><span class="payamount"></span><small>元</small></h2>
        </div>
        <div class="suc-item">
            <div class="left">赠送牛豆</div>
            <div class="right red"><span class="returnBullamont"></span>牛豆</div>
        </div>
        <div class="suc-item">
            <div class="left">收款人</div>
            <div class="right"><span class="businessname"></span></div>
        </div>
        <div class="suc-item">
            <div class="left">交易时间</div>
            <div class="right"><span class="addtime"></span></div>
        </div>
        <div class="pay-btn">
            <a href="/StoBusiness/Index/comment?orderno=<?=$orderno?>" type="button" class="am-btn am-btn-danger am-btn-block">完 成</a><!--/index/index/index-->
       <!--  </div>
    </div> -->

    <div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="my-modal-loading">
        <div class="am-modal-dialog">
        <div class="am-modal-hd">正在载入...</div>
            <div class="am-modal-bd-loading">
                <span class="am-icon-spinner am-icon-spin"></span>
            </div>
        </div>
    </div>
    {include file="Pub/down" /}
</body>
<script type="text/javascript">
    $(function(){

        $("#my-modal-loading").modal();
        setTimeout("getStatucs()",1000); 

       
    });

     var flag=true;
     function getStatucs(){
            var orderno = "<?=$orderno?>";
            var url = "/StoBusiness/Index/ajaxPayStatus?&orderno="+orderno;
            
         

            $.get(url, function(data){
               
                data = eval("("+data+")");
               
                if(data.code == '200'){

                   

                    if(data.data.status == 1){
                        
                        $('.pay-success-container').show();
                        $('.payamount').text(data.data.payamount);
                        $('.returnBullamont').text(data.data.returnBullamont);
                        $('.businessname').text(data.data.businessname);
                        $('.addtime').text(data.data.addtime);
                        $(".bonusamount").text(data.data.bonusamount);
                        $(".amount").text(data.data.amount);
                        if(data.data.bonusamount >0){
                            $(".suc-bonusamount").show();
                        }
                        if(data.data.hascost == 1){
                            $(".cost").text(data.data.cost);
                            //$('.payamount').text(data.data.morepay);
                            $(".suc-cost").show();
                        }else{
                            $(".suc-cost").hide();
                            $(".cost").text(data.data.cost);
                            //$('.payamount').text(data.data.morepay);
                        }

                        $(".nosuccess").hide();
                        $(".success").show();

                        $("#my-modal-loading").modal('close');

                    }else{

                        $('.pay-success-container').show();
                        $('.payamount').text(data.data.payamount);
                        $('.returnBullamont').text(data.data.returnBullamont);
                        $('.businessname').text(data.data.businessname);
                        $('.addtime').text(data.data.addtime);
                        $(".bonusamount").text(data.data.bonusamount);
                        $(".amount").text(data.data.amount);
                        if(data.data.bonusamount >0){
                            $(".suc-bonusamount").show();
                        }
                        if(data.data.hascost == 1){
                            $(".cost").text(data.data.cost);
                            //$('.payamount').text(data.data.morepay);
                            $(".suc-cost").show();
                        }else{
                            $(".suc-cost").hide();
                            $(".cost").text(data.data.cost);
                            //$('.payamount').text(data.data.morepay);
                        }

                        $(".success").hide();
                        $(".nosuccess").show();
                        if(flag){
                            setTimeout("getStatucs()",500); 
                            //getStatucs();
                            flag=false;
                        }else{
                             $("#my-modal-loading").modal('close');
                        }

                    }

                  

                }else{
                    alert('获取结果错误！');
                }
            });

            return false;
        }
</script>
</html>
