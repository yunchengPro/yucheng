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

    <title><?php echo isset($title)?$title:(isset($_ENV['title'])?$_ENV['title']:'');?></title>
    <style type="text/css">
        label.error{
            position: absolute;
        }
        .row .formControls{
            position: relative;
        }

        .loginnameCotainer label.error{
            top: 8px;
            right: 24px;
        }

        .passwordCotainer label.error{
            top: 8px;
            right: 24px;
        }

        .loginCodeCotainer label.error{
            top: 36px;
            left: 0px;
        }
        .closeImg{
            cursor: pointer;
        }
        #kanbuq{
             cursor: pointer;
        }
        .header{
            position: relative;
        }
        .nav-tit {
            position: absolute;
            left: 160px;
            top: 15px;
        }
    </style>
</head>
<body style="overflow-x: hidden;">

<div class="row cl">
    <div class="col-xs-2"></div>
    <div class="col-xs-5">
        <div class=""></div>
    </div>

</div>
<div class="loginWraper">
    <div id="loginform" class="loginBox">
    	 <div class="row-cl">
            <div class="text-c">
            	<img src="/newui/static/h-ui.admin/images/nnh/logo.png" />
            </div>
        </div>
        <div class="row-cl">
            <div class="text-c loginTtl">牛商理系统</div>
        </div>
        <form class="form form-horizontal" action="//Login/Index/doforgetPwd" method="post" id="logform" novalidate="novalidate">
            <div class="row cl">
                <div class="formControls loginnameCotainer">
                     <input type="tel" id="mobile" name="mobile"  maxlength="11" placeholder="请输入您的手机号码">
                  <!--  oninput="checkValue($(this))" onblur="checkValue($(this))" onkeyup="checkValue($(this))"  <img src="./newui/static/h-ui.admin/images/close.png" style="display:none;" class="closeImg closeloginname"> -->
                </div>
            </div>
            <div class="row cl">
                <div class="formControls passwordCotainer">
                    <input type="tel"  name="valicode" maxlength="6" placeholder="请输入验证码">
                    <a href="#" class="get-code" id="ko" onclick="settime()" >获取验证码</a>
                </div>
            </div>
             <div class="am-form-group login-btn-container">
                <a href="#" type="button" class="am-btn am-btn-danger am-btn-block" id="login">提交申请</a>
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
<script type="text/javascript">

        var countdown=60; 

        function settime() { 

            var mobile = $("#mobile").val();
            
            if (countdown == 0) { 
                $("#ko").attr("disabled",false);    
                $("#ko").html("免费获取验证码"); 
                countdown = 60; 
            }else{ 

                if(countdown == 60){

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
               
                $("#ko").attr("disabled", true); 
                $("#ko").html("重新发送(" + countdown + ")"); 
                countdown--; 
            } 

            setTimeout(function() { 
                settime() 
            },1000);
        } 

        $("#login").click(function(){
            $("#form").submit();
        })

</script>

</body>
</html>