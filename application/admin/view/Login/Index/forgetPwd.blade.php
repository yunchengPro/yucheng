<html><head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <link href="/newui/static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="/newui/static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
    <link href="/newui/static/h-ui.admin/css/style.css" rel="stylesheet" type="text/css" />
    <link href="/newui/lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />

    <title>供应商后台管理系统</title>
    
    <style>
        .getPasswordWrap{
            position: absolute;
            width: 100%;
            left: 0;
            top: 0;
            bottom: 0;
            right: 0;
            z-index: 1;
            background:#F2F2F2 url(/newui//static/h-ui.admin/images/nnh/login_bg.png) no-repeat center;
        }
        .getPasswordBox{
            width: 600px;
            height: 600px;
            position: relative;
            left: 50%;
            top: 50%;
            margin-left: -300px;
            margin-top: -300px;
            /*border: 1px solid #ddd;*/
            border-radius: 6px;
            padding: 25px 20px;
            box-sizing: border-box;
        }
        
        .getPasswordBox input{box-sizing: border-box;width: 400px;height: 60px; padding: 15px 12px; border: 1px solid #cccccc;  border-radius: 4px;  font-size: 18px;  color: #999999;}
        .psw-row{margin-bottom: 30px;}
        .psw-row span{ font-size: 18px;width: 95px;display: inline-block;   text-align: right; }
        .getvercode{
            position: absolute;
            right: 64px;
            top: 1px;
            height: 58px;
            width: 120px;
            font-size: 16px;
            border: 0;
            outline: 0;
            background: #F13437;
            color: #FFFFFF;
            border-radius: 0 6px 6px 0;
        }
        .pswTl{ margin-top: 20px; margin-bottom: 40px; font-size: 32px; color: #333333; font-weight: bold;}
        .sub-btn{width: 400px;  margin-left: 95px; height: 60px; font-size: 18px; border: 0; outline: 0;background: #F13437;color: #FFFFFF;border-radius:6px ;}
        .psw-row button:disabled{background: #999999;color: #FFFFFF}
        .psw-row button[disabled]{background: #999999;color: #FFFFFF}
    </style>
</head>
<body style="overflow-x: hidden;">

<div class="getPasswordWrap">
    <div class="getPasswordBox">
        <form id="getPasswordForm"  rel="iframe-form"   class="form-x"  action="/Login/Index/doforgetPwd"  method="post">
             <div class="psw-row">
                <div class="text-c">
                    <img src="/newui//static/h-ui.admin/images/nnh/logo.png" />
                </div>
            </div>
            <div class="psw-row">
                <div class="text-c pswTl">忘记密码</div>
            </div>
            <div class="psw-row">
                <span class="tip">手机号码：</span><input class="size-L" name="mobile" id="phone" placeholder="请输入手机号" maxlength="11" type="tel"/>
            </div>
            <div class="psw-row pos-r">
                <span class="tip">验证码：</span><input type="tel" id="vercode" name="valicode" maxlength="6" class="size-L" placeholder="请输入验证码"/>
                <button type="button" disabled="true" id="getvercode" onclick="timer(this)" class="getvercode">获取验证码</button>
            </div>
            <div class="psw-row">
                <button type="submit" disabled="true" class="sub-btn">下一步</button>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript" src="/newui/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/newui/static/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/newui/lib/icheck/jquery.icheck.min.js"></script>
<script type="text/javascript" src="/newui/lib/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="/newui/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="/newui/lib/jquery.validation/1.14.0/messages_zh.min.js"></script>
<script>
   
    $(function(){
        $("#phone").on("keyup",function(){
            if(!isNaN($(this).val())&&$(this).val().length==11){
                $("#getvercode").prop("disabled",false);
            }
        });
        
        $("#vercode").on("keyup",function(){
            if(!isNaN($(this).val())&&$(this).val().length==6){
                $(".sub-btn").prop("disabled",false);
            }
        });

    });
    
    /*发送验证码
    *talon 
    *2017-4-14 17:06:57
    */
    var wait=60;
    var countdown;
    function timer(dom){
        var mobile = $("#phone").val();
        if (wait == 0) {
            $(dom).removeAttr("disabled");
            //$(dom).removeClass("am-btn-default").addClass("am-btn-success");
            $(dom).html("获取验证码");
            wait = 60;
            clearTimeout(countdown);
        } else {

            if(wait == 60){

                    $.ajax({
                        type:'GET',
                        dataType:'json',
                        url:"/Login/Index/Send?mobile=" + mobile,
                        success: function(result){
                            if(result.code == 200){
                                alert('发送成功');
                            }
                        }
                    });
                }
             $(dom).attr("disabled", true);
           
             wait--;
             $(dom).html(""+wait+"s重新获取");    
        }

        countdown=setTimeout(function() {
                timer(dom);
        },1000);
    
    }

</script>

</body></html>