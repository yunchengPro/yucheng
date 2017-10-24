{include file="Pub/header" /}
<?php 

$title = $title;

    $action = $actionUrl;
    $field = array(
        "realname" => array("type" => "text", "name" => "真实姓名", "value" => "", "validator"=>"required:true"),
        "mobile" => array("type" => "text", "name" => "手机号码", "value" => "", "validator"=>"required:true"),
        "area" => array("type" => "area", "name" => "地区", "validator"=>"required:true"),
        "address" => array("type" => "text", "name" => "详细地址", "value" => "", "validator"=>"required:true"),
        "instroducerMobile" => array("type" => "text", "name" => "分享人手机号码", "value" => "", "validator"=>"required:true"),
        "role_money" => array("type" => "text", "name" => "应付金额", "data" => $role_money, "append_str" => "赠送身份不能获得牛豆或牛粮"),
//         "role_status" => array("type" => "radio", "name" => "角色状态", "terms" => ["1"=>"新开","2"=>"赠送"], "validator"=>"required:true", "append_str" => "赠送身份不能获得牛豆或牛粮"),
//         "amount" => array("type" => "text", "name" => "实付金额", "value" => "", "append_str" => "*"),
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

<script type="text/javascript">
//     $(function(){
//         $("#amount").parent().parent().hide();

//         $("input[name='role_status']").change(function(){
//             if($(this).val() == 1) {
//                 $("#amount").parent().parent().show();
//             } else {
//                 $("#amount").parent().parent().hide();
//             }
//         });
//     });
</script>

{include file="Pub/pubAdd" /}
{include file="Pub/footer" /}