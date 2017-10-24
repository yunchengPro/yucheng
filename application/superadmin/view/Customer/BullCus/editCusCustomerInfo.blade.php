{include file="Pub/header" /}
<?php
    $sex_arr = array("0" => "未设置", "1" => "男", "2" => "女");
    $auth_arr = array("0" => "否", "1" => "是");

    $action = $actionUrl;
    $field = array(
        "id" => array("type" => "text", "name" => "序号", "data" => $cusInfo['id']),
        "username" => array("type" => "text", "name" => "用户名", "data" => $cusInfo['username']),
        "nickname" => array("type" => "text", "name" => "*昵称", "value" => $cusInfo['nickname']),
        "realname" => array("type" => "text", "name" => "*真实姓名", "value" => $cusInfo['realname']),
        "mobile" => array("type" => "text", "name" => "手机号码", "data" => $cusInfo['mobile']),
        "instroducerMobile" => array("type" => "text", "name" => "分享人", "data" => $cusInfo['instroducerMobile']),
        "headerpic" => array("type" => "text", "name" => "头像", "data" => "<img src = ".ShowThumb($cusInfo['headerpic']).">"),
        "roleStr" => array("type" => "text", "name" => "已有角色", "data" => $cusInfo['roleStr']),
        "sex" => array("type" => "text", "name" => "性别", "data" => $sex_arr[$cusInfo['sex']]),
        "isnameauth" => array("type" => "text", "name" => "是否实名认证", "data" => $auth_arr[$cusInfo['isnameauth']]),
        "idnumber" => array("type" => "text", "name" => "身份证号", "data" => $cusInfo['idnumber']),
//         "area" => array("type" => "text", "name" => "所在地区", "data" => $cusInfo['area']),
//         "address" => array("type" => "text", "name" => "地址", "data" => $cusInfo['address']),
        "lastlogintime" => array("type" => "text", "name" => "最近一次登陆时间", "data" => $cusInfo['lastlogintime']),
        "createtime" => array("type" => "text", "name" => "注册时间", "data" => $cusInfo['createtime']),
    );
?>
<link rel="stylesheet" href="/jQueryFileUpload/css/fileupload.css">
<script src="/jQueryFileUpload/js/vendor/jquery.ui.widget.js"></script>
<script src="/jQueryFileUpload/js/load-image.all.min.js"></script>
<script src="/jQueryFileUpload/js/canvas-to-blob.min.js"></script>
<script src="/jQueryFileUpload/js/jquery.iframe-transport.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload-process.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload-image.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload-validate.js"></script>
<script src="/jQueryFileUpload/js/fileupload.js"></script>


<!---文本编辑框的js-->
<script type="text/javascript" charset="utf-8" src="/Ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Ueditor/ueditor.all.min.js"> </script>

{include file="Pub/pubAdd" /}
{include file="Pub/footer" /}