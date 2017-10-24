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
    position: absolute;
    left: 50%;
    top: 50%;
    margin-left: -300px;
    margin-top: -300px;
    /*border: 1px solid #ddd;*/
    border-radius: 6px;
    padding: 25px 20px;
    box-sizing: border-box;
        }
        
        .getPasswordBox input{width: 400px;height: 60px; padding: 5px 12px; border: 1px solid #cccccc;  border-radius: 4px;  font-size: 18px;  color: #999999;}
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
        .psw-row button:disabled{background: #999;}
    </style>
</head>
<body style="overflow-x: hidden;">


<!-- <div class="loginWraper">
    <div id="loginform" class="loginBox">
    	
        <div class="row-cl">
            <div class="text-c loginTtl">设置新密码</div>
        </div>
        <form class="form form-horizontal" action="/Login/Index/doSetpwd" method="post" id="logform" novalidate="novalidate">
         
            <div class="row cl">
                <div class="formControls passwordCotainer">
                    <input id="password" name="newpwd" placeholder="新密码" class="size-L input-text password" type="password">
                 
                </div>
                 <div class="formControls passwordCotainer">
                    <input id="password" name="newpwd2" placeholder="确认新密码" class="size-L input-text password" type="password">
                 
                </div>
            </div>
           

            </form>
           
             
            <div class="row cl safeLogin imdLogin">
                <div class="formControls">
                    <button  class="btn  login-btn" id="formsubmit">登陆</button>
                </div>
            </div>
            <div class="row cl safeLogin">
                <div class="formControls">
                   
                </div>
            </div>
        
    </div>
</div> -->

<div class="getPasswordWrap">
    <div class="getPasswordBox">
        <form  action="/Login/Index/doSetpwd" method="post" id="setPassword" novalidate="novalidate">
             <div class="psw-row">
                <div class="text-c">
                    <img src="/newui/static/h-ui.admin/images/nnh/logo.png" />
                </div>
            </div>
            <div class="psw-row">
                <div class="text-c pswTl">重设密码</div>
            </div>
            <div class="psw-row">
                <span class="tip">新密码：</span>
                <input class="size-L" id="password" name="newpwd" placeholder="新密码"  type="password"/>
            </div>
            <div class="psw-row">
                <span class="tip">确认密码：</span>
                <input  id="surepassword" name="newpwd2" class="size-L" placeholder="确认新密码" type="password"/>
            
            </div>
            <div class="psw-row">
                <button type="button" class="sub-btn" id="formsubmit">确定</button>
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
        var validate = $("#setPassword").validate({
                onsubmit:true,// 是否在提交是验证 
                onfocusout:false,// 是否在获取焦点时验证 
                onkeyup :false,// 是否在敲击键盘时验证
                ignore: [],  
                rules:{
                    loginname:{
                        required:true
                    },
                    password:{
                        required:true,
                        remote:{
                            type:"POST",
                            url:"/login/index/checkLogin?t=" + Math.random(),             
                            dataType:"json",
                            data:{  
                                loginname:function(){return $("#loginname").val();},
                                password:function(){return $("#password").val();}
                            },
                            dataFilter: function (data) {　　　　//判断控制器返回的内容
                                if (data == "true") {
                                    return true;
                                }
                                else {
                                    return false;
                                }
                            }
                        }
                    },
                    captcha:{
                        required:true,
                        remote:{
                            type:"POST",
                            url:"/login/index/checkCaptcha?t=" + Math.random(),             
                            dataType:"json",
                            data:{  
                                captcha:function(){return $("#captcha").val();}

                            },
                            dataFilter: function (data) {　　　　//判断控制器返回的内容
                                if (data == "true") {
                                    return true;
                                }
                                else {
                                    return false;
                                }
                            }
                        }
                    }
                },
                messages:{
                    loginname:{
                        required:"用户名不能为空"
                    },
                    password:{
                        required:"密码不能为空",
                        remote:$.format("用户名或密码错误"),
                    }
                                
                }
                          
        });   
    });

    /**
     * messages:{
                    loginname:{
                        required:"用户名不能为空"
                    },
                    password:{
                        required:"密码不能为空",
                        remote:$.format("用户名或密码错误"),
                    },
                    captcha:{
                        required:"验证码码不能为空",
                        remote:$.format("验证码错误"),
                    }
                                
                }
     */

    $(function(){
        $("#kanbuq").bind("click",reloadSecImg);
        $("#img_captcha").bind("click",reloadSecImg);
        $("#formsubmit").bind("click",function(){
            $("#setPassword").submit();
        });
    });

    function reloadSecImg(){
        $("#img_captcha").attr("src","{:captcha_src()}?"+Math.random());
    }

    $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });
    
</script>
</body>
</html>