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
</head>

<body>
    
   <div class="pay-success-container" style="display:none;">
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
       
    </div>

    <div class="am-modal am-modal-loading am-modal-no-btn" tabindex="-1" id="my-modal-loading">
        <div class="am-modal-dialog">
        <div class="am-modal-hd">正在载入...</div>
            <div class="am-modal-bd-loading">
                <span class="am-icon-spinner am-icon-spin"></span>
            </div>
        </div>
    </div>

</body>
<script type="text/javascript">
    $(function(){

        $("#my-modal-loading").modal();
        setTimeout("getStatucs()",1000); 

       
    });

     var flag=true;
     function getStatucs(){
            var orderno = "<?=$orderno?>";
            var url = "/Customer/Index/ajaxPayStatus?&orderno="+orderno;
            
         

            $.get(url, function(data){
               
                data = eval("("+data+")");
               
                if(data.code == '200'){

                   

                    if(data.data.pay_status == 1){
                        
                        $('.pay-success-container').show();
                        $('.payamount').text(data.data.pay_amount);

                        $(".nosuccess").hide();
                        $(".success").show();

                         $("#my-modal-loading").modal('close');

                    }else{

                        $('.pay-success-container').show();
                        $('.payamount').text(data.data.amount);
                     

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
