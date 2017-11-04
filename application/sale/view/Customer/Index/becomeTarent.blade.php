<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{$title}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta name="format-detection" content="telephone=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
     <!-- <link rel="stylesheet" href="http://cdn.amazeui.org/amazeui/2.7.2/css/amazeui.min.css"> -->
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/amazeui.min.css">
    <link rel="stylesheet" type="text/css" href="<?=$publicDomain?>/mobile/css/dialog.css"/>
    <link rel="stylesheet" type="text/css" href="<?=$publicDomain?>/mobile/css/mobile-select-area.css"/>
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/my-app.css">
    <link rel="stylesheet" href="<?=$publicDomain?>/mobile/css/invite.css?v20170821">
    
    <script src="<?=$publicDomain?>/mobile/js/jquery.min.js"></script>
    <script src="<?=$publicDomain?>/mobile/js/amazeui.min.js"></script>
    <!-- <script src="http://cdn.amazeui.org/amazeui/2.7.2/js/amazeui.min.js"></script> -->
    <script type="text/javascript" src="<?=$publicDomain?>/mobile/js/layer.js" ></script>

    <script type="text/javascript" src="<?=$publicDomain?>/mobile/js/dialog.js" ></script>
    <script src="<?=$publicDomain?>/mobile/js/mobile-select-area.js"></script>
    <style>
        .layui-m-layercont{padding: 0;}
       
        
      
    </style>
</head>

<body class="<?php if($role == 2){ ?>invite-nr-bg<?php } ?><?php if($role == 3){ ?>invite-nck-bg<?php } ?><?php if($role == 8){ ?>invite-ndr-bg<?php } ?>">
    <div class=""></div>
        <div class="invite-wrap">
            <div class="invite-desc">
            <?php if($role == 2){ ?>
                <div>您受邀成为创业牛人，体验创业；</div>
                <div>您可以拓展实体店和工厂入驻平台，</div>
                <div>购买平台技术服务，</div>
                <div>即可以让您拓展的实体店和工厂</div>
                <div>免费使用平台技术服务，</div>
                <div>您除了可以获赠拓店奖励以外，</div>
                <div>还可以获赠自己拓展的实体店和工厂的交易分润收益。</div>
            <?php } ?>  
            <?php if($role == 3){ ?>  
                <div>您受邀成为创业牛创客，开始创业；</div>
                <div>您可以拓展实体店和工厂入驻平台</div>
                <div >购买平台技术服务，</div>
                <div>即可以让您拓展的实体店和工厂</div>
                <div>免费使用平台技术服务，</div>
                <div>您除了可以获赠拓店奖励以外，</div>
                <div>还可以获赠自己拓展的实体店和工厂的交易分润收益。</div>
            <?php } ?>  
            <?php if($role == 8){ ?>  
                <div>您受邀成为创业牛达人，开始创业；</div>
                <div>您可以拓展实体店和工厂入驻平台</div>
                <div >购买平台技术服务，</div>
                <div>即可以让您拓展的实体店和工厂</div>
                <div>免费使用平台技术服务，</div>
                <div>您除了可以获赠拓店奖励以外，</div>
                <div>还可以获赠自己拓展的实体店和工厂的交易分润收益。</div>
            <?php } ?>  
            </div>
            
            <div class="" style="position: relative;">
                <div class="right-pen">
                        <img src="<?=$publicDomain?>/mobile/img/icon/pen.png" />
                    </div>
                    <div class="invite-form">
                        
                        <div class="tips">联系电话</div>
                        <div class="m-item ">
                            <input type="tel" value="" placeholder="请输入联系电话" id="mobile" maxlength="11"  name="mobile">
                        </div>
                        
                        <div class="tips">短信验证码</div>
                        <div class="m-item ">
                             <input type="tel" value="" maxlength="6" placeholder="请输入验证码" name="valicode">
                            <button class="vercode-btn" id="getcode" onclick="wait(this)">获取验证码</button>
                        </div>
                    
                           
                        <div class="tips">姓名</div>
                         <div class="m-item ">    
                            <input type="text" placeholder="请输入姓名" value="" name="realname" maxlength="20"/>
                        </div>
                          
                        <div class="tips">身份证号</div>
                        <div class="m-item ">
                            <input type="text" value="" placeholder="请输入身份证号"  name="idnumber" maxlength="18"/>
                        </div>
                        
                       
                        <div class="tips">所属省市区</div>
                         <div class="m-item ">
                            <input type="text" placeholder="请选择" class="area" id="txt_area" value="" name="area_code"/>
                            <input type="hidden" id="hd_area" value="110000, 110100, 110101">
                         <i class="ico-right"></i> 
                        </div>
                       
                        <div class="tips">详细地址</div>
                        <div class="m-item ">
                            <input type="text" placeholder="请输入详细地址" value="{$otherData['address']}" name="address"/>
                        </div>
                      
                       
                        <div class="tips">分享人(非必填) <span class="roleerror">{$otherData['errormsg']}</span></div>
                        <div class="m-item ">
                            <input type="tel" value="{$otherData['parentMobile']}" name="parentMobile" disabled="disabled"/>
                        </div>
                        

                        <div class="tips">客服电话</div>
                        <div class="m-item">
                            <input type="text" placeholder="" value="{$otherData['companyMobile'][0]}" readonly="readonly"/>
                            <a href="tel:{$otherData['companyMobile'][0]}" class="call"><img src="<?=$publicDomain?>/mobile/img/icon/phone.png"></a>
                        </div>

                           
                     
                        <!-- <div class="p-item">
                            <input type="checkbox" checked="checked" class="checkbox"/>
                            <span>同意购买</span>
                            <a href="<?=$editionUrl?>"><?=$editionStr?></a>
                            <span class="money">{$otherData['roleMoney']}</span><span class="p-red"> 元</span>
                        </div> -->
                        <div class="p-item">
                            
                            <span>平台技术服务费</span>
                           
                            <span class="money">{$otherData['roleMoney']}</span><span class="red"> 元</span>
                        </div>
                        <div class="p-item">
                            <input type="checkbox" checked="checked" class="checkbox" id="pactCheck"/>
                            <span>我已阅读并同意</span>
                            <a href="{$serviceUrl}">{$serviceStr}</a>
                        </div>
                        <input type="hidden" value="" id="orderno">
                        <input type="hidden" value="1" id="issetpwd">
                        <input type="hidden" value="" id="customerid">
                        
                        <div class="oper-bar invite-oper">
                            <button href="#none" class="oper-btn go-invtite <?php if($role == 8){ ?> ndr-btn <?php }else{ ?> nr-btn <?php } ?>">马上接受邀请</button>
                        </div>
                    </div>
                    </div>
            <div class="invite-rule">
            <div class="t-r"><strong>活动规则：</strong></div>
            <?php if($role == 2){ ?>
                <div>1、获赠的牛票在平台可提现可消费；</div>
                <div>2、获赠的牛粮奖励金可在联盟实体店直接抵扣；</div>
                <div>3、享受邀请的创业牛人拓展实体店粉丝在全国联盟商家的消费分润；</div>
                <div>4、每位牛人最多可以拓展30家实体店和30家工厂入驻平台；</div>
                <div>5、活动最终解释权归平台所有。</div>
            <?php } ?>
            <?php if($role == 3){ ?>
                <div>1、获赠的牛票在平台可提现可消费；</div>
                <div>2、获赠的牛粮奖励金可在联盟实体店直接抵扣；</div>
                <div>3、享受邀请的创业牛创客拓展实体店粉丝在全国联盟商家的消费分润；</div>
                <div>4、每位牛创客人可不受限拓展实体店和工厂入驻平台；</div>
                <div>5、活动最终解释权归平台所有。</div>
            <?php } ?>
              <?php if($role == 8){ ?>
                <div>1、获赠的牛票在平台可提现可消费；</div>
                <div>2、获赠的牛粮奖励金可在联盟实体店直接抵扣；</div>
                <div>3、享受邀请的创业牛达人拓展实体店粉丝在全国联盟商家的消费分润；</div>
                <div>4、每位牛达人最多可以拓展60家实体店和60家工厂入驻平台；</div>
                <div>5、活动最终解释权归平台所有。</div>
            <?php } ?>
        </div>
    </div>
   



    <!--蒙版-->
   <!-- <section class="tl-mask"></section>-->
    
    <!--验证码弹框-->
   <!--  <div style="display: none;" id="verify-html">
        <section class="verify-dialog">
            <p>请输入验证码</p>
            <a href="#none" class="close"></a>
            <div class="verify-box">
                <input type="text" maxlength="1"/>
                <input type="text" maxlength="1"/>
                <input type="text" maxlength="1"/>
                <input type="text" maxlength="1"/>
            </div>
            <div class="code-box">
                <img src="<?=$publicDomain?>/mobile/img/banner-sjsm.png" />
                <a href="#">看不清，换一张</a>
            </div>
            
            <div class="oper-box">
                <a href="#" class="yes-btn">确定</a>
                <a href="#" class="no-btn">取消</a>
            </div>
        </section>
    </div> -->
    
    <!--支付方式-->
    <div style="display: none;"  id="pay-html">
        <div data-am-widget="list_news" class="am-list-news am-list-news-default ph-wrap">
            <div class="am-list-news-hd am-cf">
                <div class="ph-title">付款详情  <a href="#none" class="ph-close"></a></div>
            </div>
            <div class="am-list-news-bd pay-type">
                <ul class="am-list">
                    <!-- 
                    <li class="am-g am-list-item-dated">
                        <a href="##" class="am-list-item-hd ">
                            <img src="<?=$publicDomain?>/mobile/img/icon/balance.png" /> 余额支付 <span class="gray"></span>
                        </a>
                        <span class="am-list-date"></span> <!-- <i class="icon icon-choose-pay"></i> -->
                    </li>
                    
                    <?php if($is_weixin){ ?>
                    <li class="am-g am-list-item-dated">
                        <a href="##" class="am-list-item-hd ">
                            <img src="<?=$publicDomain?>/mobile/img/icon/weixin.png" /> 微信支付
                        </a>
                        <span class="am-list-date"><i class="icon icon-choose-pay"></i></span>
                    </li>
                    <?php }else{ ?>
                    <li class="am-g am-list-item-dated">
                        <a href="##" class="am-list-item-hd ">
                            <img src="<?=$publicDomain?>/mobile/img/icon/ali.png" /> 支付宝支付
                        </a>
                        <span class="am-list-date"><i class="icon icon-choose-pay"></i></span>
                    </li>
                    <?php } ?>
                    <?php if(!$is_alipay){?>
                        <li class="am-g am-list-item-dated">
                            <a href="##" class="am-list-item-hd">
                                <img src="<?=$publicDomain?>/mobile/img/icon/union.png"> 快捷支付
                            </a>
                            <span class="am-list-date"></span>
                        </li>
                    <?php }?>
                    <!--<li class="am-g am-list-item-dated">
                        <a href="##" class="am-list-item-hd ">
                            <img src="img/icon/union.png" class="vertical-middle" /> 银联支付
                        </a>
                        <span class="am-list-date"></span>
                    </li>-->
                </ul>
            </div>
            <div class="pay-for-money am-g">
                <div class="am-u-sm-6 c-333">支付金额</div>
                <div class="am-u-sm-6 text-right"><span class="red">{$otherData['roleMoney']}</span> <span class="red">元</span></div>
                <div class="pay-btn">
                    <a href="javascript:void(0)" onclick="topay()" type="button" class="am-btn am-btn-danger am-btn-block">确定付款</a>
                </div>
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
</body>

<script>
    

</script>

 <script language="JavaScript" type="text/javascript">    

     function reloadSecImg(){
        $("#img_captcha").attr("src","/Customer/Index/captcha?"+Math.random());
     }

     function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");  
        var r = window.location.search.substr(1).match(reg);  
        if (r != null) return unescape(r[2]);  
        return null;  
     }

     function validate_form() {
        // 校验联系电话
         var mobile = $("[name='mobile']").val();
         if(mobile == '') {
             layer.open({
                 content:'联系电话不能为空',
                 skin:'msg',
                 time:2,
             });
             return false;
         }
         // 检验短信验证码
         var valicode = $("[name='valicode']").val();
         if(valicode == '') {
             layer.open({
                 content:'验证码不能为空',
                 skin:'msg',
                 time:2,
             });
             return false;
         }
        // 校验姓名
        var realname = $("[name='realname']").val();
        if(realname == '') {
            layer.open({
                content:'姓名不能为空',
                skin:'msg',
                time:2,
            });
            return false;
        }
        // 校验身份证号
        var idnumber = $("[name='idnumber']").val();
        if(idnumber == '') {
            layer.open({
                content:'身份证号不能为空',
                skin:'msg',
                time:2,
            });
            return false;
        }
        // 校验省市区
        var area_code = $("[name='area_code']").val();
        if(area_code == '') {
            layer.open({
                content:'所属省市区不能为空',
                skin:'msg',
                time:2,
            });
            return false;
        }
        // 校验详细地址
        var address = $("[name='address']").val();
        if(address == '') {
            layer.open({
                content:'详细地址不能为空',
                skin:'msg',
                time:2,
            });
            return false;
        }
        // 检验分享人
        var parentMobile = $("[name='parentMobile']").val();
        if(parentMobile == '') {
            layer.open({
                content:'分享人不能为空',
                skin:'msg',
                time:2,
            });
            return false;
        }

         /*2017-7-26 17:19:34 talon
            * 提交先勾选协议
            */
        if(!$("#pactCheck").is(':checked')){
             layer.open({
                        content: '请先勾选协议',
                        skin: 'msg',
                        time: 2 
              });
              return false;
        }
        return true;
     }
    
    $(function(){

        var roleupdateauth = "<?=$otherData['roleupdateauth']?>";
        if(roleupdateauth == 1) {
            $("input[name='parentMobile']").attr("disabled", false);
        }

        $("input[name='parentMobile']").on("input", function(){
            var mobile = $(this).val();
            var mobileLength = mobile.length;

            if(mobileLength >= 11) {
            	var role = "<?=$role?>";
            	$.ajax({
                    type:'POST',
                    data: {role:role,mobile:mobile},
                    async:false, 
                    traditional :true,
                    dataType:'json',
                    url:'/Customer/Index/checkrecorole',
                    success:function(result){
                        console.log(result);
                        if(result.code != "200") {
                            layer.open({
                                content:result.data,
                                skin:'msg',
                                time:2,
                            });
                        } else {
                        	$(".roleerror").html(result.data.errormsg);
                        }
                    }
                });
            }
        });
         //微信&支付宝浏览器下处理
        if(isWeiXin()||isAlipay()){
            $("header").remove();
            $("body").removeClass("am-with-fixed-header");
        }
        
        //区域选择
        //var data={}
        var selectArea = new MobileSelectArea();
        selectArea.init({
            trigger:'#txt_area',
            data:'<?=$publicDomain?>/mobile/data/getAreaJson.json',
            default:0,
            value:$('#hd_area').val(),
           // value:[110000, 110100, 110101],
            text:['北京', '北京市', '东城区'],
            position:"bottom"
        });
        
        
        //去支付
        $(".oper-btn").on("click",function(){

            var result = validate_form();

            if(!result){
                return false;
            }

//             //$(".tl-mask,.verify-dialog").show();
//             var html='<section class="verify-dialog" style="display:block">'+
//                         '<p>请输入验证码</p>'+
//                         '<a href="#none" class="close"></a>'+
//                         '<div class="verify-box">'+
//                            '<input type="text" maxlength="5"/>'+
//                         '</div>'+
//                         '<div class="code-box">'+
//                             '<img src="/Customer/Index/captcha" id="img_captcha"/>'+
//                             '<a href="javascript:void();" id="reloadImg">看不清，换一张</a>'+
//                         '</div>'+
                        
//                         '<div class="oper-box">'+
//                             '<a href="#" class="yes-btn">确定</a>'+
//                             '<a href="#" class="no-btn">取消</a>'+
//                         '</div>'+
//                     '</section>';   
                    
//                     layer.open({
//                             title: ''
//                             ,content:html
//                           });
//         });
//         //验证码蒙版关闭
//         $(document).on("click",".close,.no-btn",function(){
//             var index=$(this).parents(".layui-m-layer").attr("index");
//             index=parseInt(index);
//             layer.close(index);
            
//         });
        
//         //验证码确定
        
//         $(document).on("click",".yes-btn",function(){

            // 数据完整
//             var captcha=$(".verify-box input").val();
//             /*$(".verify-box input").each(function(idx,ele){
//                 captcha+=$(ele).val();
//             });*/
//             if(captcha.length<4){
//                 layer.open({
//                     content:'请输入4位数验证码',
//                     skin:'msg',
//                     time:2,
//                 });
//                 return;
//             }

//             var index=$(this).parents(".layui-m-layer").attr("index");

//             var customercode = getQueryString("customercode");
//             var customerid = getQueryString("customerid");
            var role_type = getQueryString('role');

            var realname = $("[name='realname']").val();
            var idnumber = $("[name='idnumber']").val();
            var mobile = $("[name='mobile']").val();
            var area = $("[name='area_code']").val();
            var area_code = $('#hd_area').val();
            var address = $("[name='address']").val();
            var parentMobile = $("[name='parentMobile']").val();
            var valicode = $("[name='valicode']").val();
            var introducerrole = "<?=$otherData['introducerrole']?>";

            $.ajax({
                type:'POST',
                data: {realname:realname,idnumber:idnumber,mobile:mobile,area:area,area_code:area_code,address:address,parentMobile:parentMobile,introducerrole:introducerrole,
                    role_type:role_type,valicode:valicode},
                async:false, 
                traditional :true,
                dataType:'json',
                url:'/Customer/Index/submitData',
                success:function(result){
                    if(result.code != "200") {
                        layer.open({
                            content:result.data,
                            skin:'msg',
                            time:2,
                        });
                    } else {
                        // 说明验证都通过了，返回订单号和需要支付的金额数
//                         index = parseInt(index);
//                         layer.close(index);

                        $("#orderno").val(result.data.orderno);
                        $("#customerid").val(result.data.customerid);

                        // result.data.issetpaypwd 判断是否已经设置支付密码
                        if(result.data.issetpaypwd == 0) {
                            $("#issetpwd").val(0);
                        } else {
                            $("#issetpwd").val(1);
                        }
                        
                        if(result.data.balance == 0) {
                            $("#pay-html .gray").html("余额不足");
                        } else {
                            $("#pay-html .gray").html("");
                        }

                        var html=$("#pay-html").html();
                        layer.open({
                            type:1,
                            content:html,
                            shadeClose:false,
                            anim:'up',
                            style:'position:absolute; bottom:0; left:0; width: 100%; padding:10px 0; border:none;'
                        });
                    }
                }
            });

        });

     
        
        //4个验证码
       /* $(document).on("keyup",".verify-box input",function(){
            if($(this).index()!=4&&$(this).val()!=""){
                $(this).next().focus();
            }
        });*/
    });
        /*function name wait
         * param dom
         * desc 验证码按钮倒计时 （点击触发 wait(this)）
         * talon 2016-10-25 16:57:53
         * */
      var countdown=60; 
        var timeout;
        function wait(dom){
            var mobile = $("#mobile").val();
            if(mobile ==''){
               // alert('请填写手机号码');
               
                  layer.open({
                    content: '请填写手机号码',
                    skin: 'msg',
                    position:"bottom",
                    time: 2 
                  });
                  $("#mobile").focus();
                   $("#mobile").val(mobile);
                return ;
            }
            if(mobile.length<11){
                 layer.open({
                    content: '请填写手机11位号码',
                    skin: 'msg',
                    position:"bottom",
                    time: 2 
                  });
                   $("#mobile").focus();
                   $("#mobile").val(mobile);
                return ;
            }

            $.ajax({
                type:'GET',
                dataType:'json',
                url:"/Index/Index/Send?mobile=" + mobile,
                success: function(result){
                    if(result.code == 200){
                        //alert('发送成功');
//                         layer.open({
//                             content: '发送成功',
//                             skin: 'msg',
//                             time: 2 
//                         });
                        timer(dom);
                    } else {
                        layer.open({
                            content: result.data,
                            skin: 'msg',
                            time: 2 
                        });
                        return false;
                    }
                }
            });
        }
        function timer(dom) { 

            var mobile = $("#mobile").val();
           
            if (countdown == 0) { 
                $("#getcode").attr("disabled",false);    
                $("#getcode").html("获取验证码"); 
                countdown = 60; 
                clearTimeout(timeout);
               // return;
            }else{ 

                if(countdown == 60){
                    
                     layer.open({
                            content: '发送成功',
                            skin: 'msg',
                            time: 2 
                        });
                }
               
                $("#getcode").attr("disabled", true); 
                $("#getcode").html(countdown + "s"); 
                countdown--;

                timeout= setTimeout(function() { 
                    timer(dom) 
                },1000); 
            } 

           
        }

        // 检验手机号码
    function checkPhone(){
        var mobile = $("#mobile").val();
        if(mobile ==''){
              layer.open({
                content: '请填写手机号码',
                skin: 'msg',
                time: 2 
              });
              $("#mobile").focus();
               $("#mobile").val(mobile);
            return false;
        }
        if(mobile.length<11){
             layer.open({
                content: '请填写手机11位号码',
                skin: 'msg',
                time: 2 
              });
               $("#mobile").focus();
               $("#mobile").val(mobile);
            return false;
        }
        var role = getQueryString('role');
        // 根据手机电话 异步判断是否是正确的 还有是否有对应的用户角色
        $.ajax({
            type:'POST',
            data:{mobile:mobile,role:role},
            async:false,
            traditional:true,
            dataType:'json',
            url:'/Customer/Index/checkPhone',
            success:function(result){
                if(result.code != "200") {
                    layer.open({
                        content:result.data,
                        skin:'msg',
                        time:2,
                    });
                }
            }
        });
    }
   //方法 判断是否微信
    function isWeiXin(){
        var ua = window.navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i) == 'micromessenger'){
            return true;
        }else{
            return false;
        }
    }

    //判断是支付宝app的浏览器
    function isAlipay(){

        var userAgent = navigator.userAgent.toLowerCase();
        if(userAgent.match(/Alipay/i)=="alipay"){
            return true;
        }else{
            return false;
        }
    }
     
</script> 


<script>
    <?php if($is_weixin){ ?>
    var paytype = 4;
    <?php }else{ ?>
    var paytype = 1;
    <?php } ?>
    //选择支付方式切换
    $(document).on("click",".pay-type li",function(){
        var check = '<i class="icon icon-choose-pay"></i>';
        $(this).find(".am-list-date").html(check);
        $(this).siblings().find(".am-list-date").html('');
        var text =$(this).find("a").text();
        var test = $.trim(text);
      
        if(test.indexOf('支付宝支付')!=-1){
           // alert(1);
            paytype=1;
        }else if(test.indexOf('余额支付')!=-1){
            //alert(2);
             paytype=2;
        }else if(test.indexOf('快捷支付')!=-1){
             paytype=3;
             
        }else if(test.indexOf('微信支付')!=-1){
            //alert(2);
            paytype=4;
        }

    });


    $(document).on("click","#img_captcha",function(){
        reloadSecImg();
    });

    $(document).on("click","#reloadImg",function(){
        reloadSecImg();
    });
    
    //支付方式关闭按钮事件和输入密码 关闭
    $(document).on("click",".ph-close,.pwd-colse",function(){
            var html='<section class="msg-box" id="waive">'+
                    '<div class="msg-body">您确定要放弃付款吗？</div>'+
                    '<div class="msg-footer">'+
                        '<span class="msg-btn no">取消</span>'+
                        '<span class="split"></span>'+
                        '<span class="msg-btn yes">确定</span>'+
                    '</div>'+
                '</section>';
            layer.open({
                content:html
            });
            
            
        });
  
  
      //放弃付款no
      $(document).on("click",".msg-box .msg-btn.no",function(){
            var index=$(this).parents(".layui-m-layer").attr("index");
                index=parseInt(index);
                layer.close(index);
      })
      //放弃付款 yes
      $(document).on("click",".msg-box .msg-btn.yes",function(){
            
                layer.closeAll();
      })

     //6位数密码页面层
     var content='<div class=" text-center" style="position:relative">'+ 
                    '<div class="pay-pwd-title">输入支付密码</div>'+ 
                    '<div class="pwd-colse"></div>'+
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
    
    
    // function callpay(){
    //     var text =$(this).find("a").text();
    // }   

    var order_no = '';
    function topay(){
        
        if(paytype==1){
          
            var orderno = $("#orderno").val();
            //alert('支付宝支付');
            $("#my-modal-loading").modal();
            alipay(orderno)
            return false;
        }else if(paytype==4){
           
            var orderno = $("#orderno").val();
            order_no = orderno;
            //alert('微信支付');
            $("#my-modal-loading").modal();
            callpay(orderno)
            return false;
        }else if(paytype==3){
         
            var orderno = $("#orderno").val();
            quickpay(orderno);
            return false;
        }else{
            // var issetpwd = $("#issetpwd").val();
            // if(issetpwd == 0) {
            //     layer.open({
            //         content:'您还未设置支付密码，请下载app去设置',
            //         btn:['去下载','取消'],
            //         yes:function(index){
            //             window.location.href='http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt';
            //             layer.close(index);
            //         }
            //     });
            //     return;
            // }
            // layer.closeAll();
            // layer.open({
            //         type: 1,
            //         content: content,
            //         anim: 'up',
            //         style: 'position:fixed; bottom:0; left:0; width: 100%; height: 120px; padding:20px 0; border:none;',
            //         success: function(elem){
            //             var $input = $(".fake-box input");  
            //             $("#pwd-input").on("input", function() { 
                           
            //                 var pwd = $(this).val().trim();  
            //                 for (var i = 0, len = pwd.length; i < len; i++) {  
            //                     $input.eq("" + i + "").val(pwd[i]);  
            //                 }  
            //                 $input.each(function() {  
            //                     var index = $(this).index();  
            //                     if (index >= len) {  
            //                         $(this).val("");  
            //                     }  
            //                 }); 
    
            //                 if (len == 6) {  
    
            //                     //执行检验支付密码正确性
            //                     var orderno = $("#orderno").val();
            //                     var customerid = $("#customerid").val();
            //                     // var customerid = getQueryString("customerid");
            //                     // var customercode = getQueryString("customercode");
            //                     $.ajax({
            //                         type:'POST',
            //                         data: {paypwd:pwd,orderno:orderno,customerid:customerid,customercode:customercode},
            //                         async:false, 
            //                         traditional :true,
            //                         dataType:'json',
            //                         url:'/Customer/Index/balancepay',
            //                         success:function(result){
    
            //                             if(result.code == "200") {
                                           
            //                                 // 支付成功 跳转到提示页
            //                                 //alert("支付成功");
            //                                 layer.open({
            //                                     content:"支付成功",
            //                                     skin:'msg',
            //                                     time:2,
            //                                 });
            //                                 // window.location.href = '/Customer/Index/paysuccess?orderno='+orderno;
            //                                 window.location.href = '/Customer/Index/confirmSuccess?orderno='+orderno+'&customerid='+customerid;
            //                                 return ;
    
            //                             }else if(result.code == '50000' ){
            //                              layer.open({
            //                                  content:'密码输入错误三次请重新设置！请下载app去设置',
            //                                  btn:['去下载','取消'],
            //                                  yes:function(index){
            //                                      window.location.href='http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt';
            //                                      layer.close(index);
            //                                  }
            //                              });
            //                              return;
    
            //                             } else {
            //                                 //alert(result.data);
            //                                 layer.open({
            //                                     content:result.data,
            //                                     skin:'msg',
            //                                     time:2,
            //                                 });
    
            //                                return ;
            //                             }
            //                         }
            //                     });
            //                 }  
            //             });  
            //         }
            //     });
                     
        }
    }
    
    // 阿里支付
    function alipay(orderno){
        $("#my-modal-loading").modal('close');
        // var customerid = getQueryString("customerid");
        var customerid = $("#customerid").val();
        window.location.href="/Customer/Index/aliwappayorder?amount=<?=$otherData['roleMoney']?>&customerid="+customerid+"&orderno="+orderno;
    }

    function quickpay(orderno){
        $("#my-modal-loading").modal('close');
        var customerid = $("#customerid").val();
        // var customerid = getQueryString("customerid");
        // var customercode = getQueryString("customercode");
        window.location.href="/Customer/Index/quickpay?customerid="+customerid+"&orderno="+orderno;
    }


      //调用微信JS api 支付
    var jsApiParameters = '';

    function jsApiCall(data){
        
        // var customerid = getQueryString("customerid");
        var customerid = $("#customerid").val();

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
                    //window.location.href = "/StoBusiness/Index/paysuccess?orderno="+order_no;
                    window.location.href = '/Customer/Index/confirmSuccess?orderno='+order_no+'&customerid='+customerid;
                }
              
          }
        );
    }

    function callpay(orderno){
        var index=$(".layui-m-layer").attr("index");
        index=parseInt(index);
        layer.close(index);

//         var customerid = getQueryString("customerid");
        var customerid = $("#customerid").val();
        //alert(2);
        var openid = '<?=$openid?>';
        //alert(openid);
        //$("#my-modal-loading").modal();
        var url = "/Customer/Index/callpay?amount=<?=$otherData['roleMoney']?>&customerid="+customerid+"&openid="+openid+"&orderno="+orderno;
        $.get(url, function(data){
           // alert(data);
            $("#my-modal-loading").modal('close');
            jsApiParameters = data;
            //alert(jsApiParameters);
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


  
</script>
</html>
