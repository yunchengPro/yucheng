<?php
  foreach($_GET as $key=>$value){
    $$key = $value;
  }
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link rel="stylesheet" type="text/css" href="../newui/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="../newui/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="../newui/lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="../newui/lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="../newui/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="../newui/static/h-ui.admin/css/style.css" />
<title>授权证书</title>

</head>
<body>
<style type="text/css">
* {
  font-family: "Microsoft YaHei" ! important;
}
/**
 * 授权证书
 */
.letter_auth{
  height: 900px;
  background:url(../newui/static/h-ui.admin/images/bg.jpg) no-repeat;
  background-size: contain;
  position: relative;
}
.letter-container{
  position: absolute;
  top: 35%;
  left: 6%;
  color: #000;
  font-size: 18px;
}
.letter-container div{
  padding: 10px 0;
}
.letter-line{
  border: none;
  border-bottom: 1px dashed #000;
  background-color: transparent;
  color:#976932;
  font-size: 18px;
  padding-left: 12px;
}
.letter-line .letter-underline{
  width: 200px;
}

.letter-underline{
  width: 200px;
}

.letter-desc{
  width: 50%;
  margin:2px 0;
}
.letter-container .letter-desc:last-child{
  margin: 15px 0;
  text-align: center;
}
.letter-container {
  margin-left: 30px;
}
.font-gold1{
  color:#976932;
}

.letter-container div{
  padding: 8px 0;
}
/**
 * 授权证书 end
 *
 */
</style>
<div class="letter_auth">
     <div class="letter-container">
        <div>兹授权：<input type="text" class="letter-line" disabled style="width: 100px;" value="<?php echo $name;?>">先生/女士</div>
        <div>手机号：<input type="tel" class="letter-line" disabled value="<?php echo $mobile;?>"></div>
        <div>微信号：<input type="text" class="letter-line" disabled value="<?php echo $wechat;?>"></div>
        <div>身份证：<input type="text" class="letter-line" disabled value="<?php echo $idnumber;?>"></div>

        <div class="letter-desc">
            &nbsp;&nbsp;&nbsp;&nbsp;为十八己微营销渠道<input type="text" class="letter-line " disabled style="text-align: center;width:140px;" value="<?php echo $agent_type_name;?>">分销商。所有产品均为《SPAKEYS 十八己》正品，由仙宜岱股份有限公司提供授权销售。未获得授权书的个人或公司售卖本公司产品，均属侵权行为，公司将追究其法律责任。
        </div>

        <div class="letter-desc">
            授权证书号：<span class="letter-line" style="border-bottom: none;"><?php echo $auth_code;?></span>
        </div>
        <div class="letter-desc">
            授权证期限：<span class="letter-line" style="border-bottom: none;"><?php echo $auth_time;?></span>
        </div>

        <div class="letter-desc">
            <i class="Hui-iconfont">&#xe6ff;</i>&nbsp;本授权书以正本为有效文本，不得影印，涂改，转让。
        </div>
    </div>
</div>
</body>
</html>