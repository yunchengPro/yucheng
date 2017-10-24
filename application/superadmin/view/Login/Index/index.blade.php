<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link href="/newui/static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="/newui/static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
    <link href="/newui/static/h-ui.admin/css/superstyle.css" rel="stylesheet" type="text/css" />
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
        <!--  <div class="row-cl">
            <div class="text-c">
                <img src="/newui/static/h-ui.admin/images/nnh/logo.png" />
            </div>
        </div> -->
        <div class="row-cl">
            <div class="text-c loginTtl">超管管理系统</div>
        </div>
        <form class="form form-horizontal" action="/Login/Index/doLogin" method="post" id="logform" novalidate="novalidate">
            <div class="row cl">
                <div class="formControls loginnameCotainer">
                    <input  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);" id="loginname" name="loginname" placeholder="请输入手机号" class="size-L input-text loginname" type="text">
                  <!--  oninput="checkValue($(this))" onblur="checkValue($(this))" onkeyup="checkValue($(this))"  <img src="./newui/static/h-ui.admin/images/close.png" style="display:none;" class="closeImg closeloginname"> -->
                </div>
            </div>
            <div class="row cl">
                <div class="formControls passwordCotainer">
                    <input  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);" id="password" name="password" placeholder="请输入登录密码" class="size-L input-text password" type="password">
                  <!--   <img src="./newui/static/h-ui.admin/images/close.png" style="display:none;"  class="closeImg closepassword"> -->
                </div>
            </div>
            <div class="row cl">
                <div class="formControls loginCodeCotainer cl">
                    <input  onkeydown="KeyDownLogin(event);" onKeyup="return inputSapceTrim(event,this);" id="captcha" name="captcha" class="size-L input-text vercode" style="width: 40%" placeholder="请输入验证码" onblur="" onclick="if(this.value=='验证码:'){this.value='';}" type="text">
                    <img src="/captcha.html" id="img_captcha" class="loginCode">
                    <img src="/newui/static/h-ui.admin/images/refresh.png" class="refresh" id="kanbuq">
                </div>
            </div>

            </form>
            <div class="row cl safeLogin forPassword">
                <div class="text-l register">
                    <div class="forgetPsd">
                       <label><input type="checkbox"></label>
                        <span>记住密码</span>
                    </div>
                  <!--  <a class="" href="/Login/Index/forgetPwd">忘记密码？</a>  -->
                </div>
            </div>
             
            <div class="row cl safeLogin imdLogin">
                <div class="formControls">
                    <button  class="btn  login-btn" id="formsubmit">登录</button>
                </div>
            </div>
            <div class="row cl safeLogin">
                <div class="formControls">
                   
                </div>
            </div>
        
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
       // var mainObj = $("#loginform") ;
       //  mainObj.on("click",".closeImg",function(){
       //      $(this).siblings("input").val("");
       //  });

    });
    // function checkValue(o){
    //     if(o.val() != ''){
    //         $('.closeloginname').show();
    //     }else{
    //         $('.closeloginname').hide();
    //     }
    // }
    //  function checkPass(o){
    //     if(o.val() != ''){
    //         $('.closepassword').show();
    //     }else{
    //         $('.closepassword').hide();
    //     }
    // }
    $(function(){
        var validate = $("#logform").validate({
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
                                    $("#captcha").val("");
                                    $("#img_captcha").click();
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
                                    $("#captcha").val("");
                                    $("#img_captcha").click();
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
        	$("#logform").submit();
        });

        $(".forgetPsd label").on("click",function(){
            if($(this).hasClass("label-on")){
                $(this).removeClass("label-on");
            }else{
                $(this).addClass("label-on");
            }
        });
    });

    function reloadSecImg(){
        $("#img_captcha").attr("src","{:captcha_src()}?"+Math.random());
    }

       /** 
         * 是否去除所有空格 
         * @param str 
         * @param is_global 如果为g或者G去除所有的 
         * @returns 
         */  
        function Trim(str,is_global){  
            var result;  
            result = str.replace(/(^\s+)|(\s+$)/g,"");  
            if(is_global.toLowerCase()=="g"){  
                result = result.replace(/\s/g,"");  
            }  
            return result;  
        }  
        
        /** 
         * 空格输入去除 
         * @param e 
         * @returns {Boolean} 
         */  
        function inputSapceTrim(e,this_temp){  
            this_temp.value = Trim(this_temp.value,"g");  
            var keynum;
             // IE 
            if(window.event){   
                keynum = e.keyCode   
            }
            // Netscape/Firefox/Opera   
            else if(e.which){   
                keynum = e.which   
            }  
            if(keynum == 32){  
                return false;  
            }  
            return true;  
        }  
        
        /** 
         * 禁止空格输入 
         * @param e 
         * @returns {Boolean} 
         */  
        function banInputSapce(e){  
            var keynum; 
            // IE  
            if(window.event){   
                keynum = e.keyCode   
            }
            // Netscape/Firefox/Opera
            else if(e.which){   
                keynum = e.which   
            }  
            if(keynum == 32){  
                return false;  
            }  
            return true;  
        } 

        function KeyDownLogin(e){
             var keynum; 
            // IE  
            if(window.event){   
                keynum = e.keyCode   
            }
            // Netscape/Firefox/Opera
            else if(e.which){   
                keynum = e.which   
            }  
            if(keynum == 32){  
                return false;  
            } 

          if (e.keyCode == 13)
          {
            e.returnValue=false;
            e.cancel = true;
            document.getElementById("formsubmit").click();
          }
        }

    $('.skin-minimal input').iCheck({
			checkboxClass: 'icheckbox-blue',
			radioClass: 'iradio-blue',
			increaseArea: '20%'
		});
    
</script>
</body>
</html>