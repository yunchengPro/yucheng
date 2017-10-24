{include file="Pub/header" /}
<?php 
    $action = $actionUrl;
    $field = array(
        "recharge_type" => array("type" => "select", "name" => "充值类型", "option" => array("1" => "现金", "2" => "绑定现金", "3" => "牛豆", "4"=>"牛粮奖励金"), "validator" => "required:true"),
        "amount" => array("type" => "text", "name" => "充值金额(元)", "validator" => "required:true"),
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