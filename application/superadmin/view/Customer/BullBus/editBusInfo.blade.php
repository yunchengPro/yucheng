{include file="Pub/header" /}
<?php 

    $field = array(
        "businessname" => array("type" => "text", "name" => "公司名称", "data" => $busInfo['businessname']),
        "businesslogo" => array("type" => "text", "name" => "公司logo", "data" => "<img src = ".ShowThumb($busInfo['businesslogo']).">"),
        "realname" => array("type" => "text", "name" => "负责人姓名", "data" => $busInfo['realname']),
        "idnumber" => array("type" => "text", "name" => "身份证号码", "data" => $busInfo['idnumber']),
        "mobile" => array("type" => "text", "name" => "手机号码", "data" => $busInfo['mobile']),
        "area" => array("type" => "text", "name" => "所属省市区", "data" => $busInfo['area']),
        "address" => array("type" => "text", "name" => "详细地址", "data" => $busInfo['address']),
        "idnumber" => array("type" => "text", "name" => "身份证号码", "data" => $busInfo['idnumber']),
        "servicetel" => array("type" => "text", "name" => "服务电话", "data" => $busInfo['servicetel']),
        "description" => array("type" => "text", "name" => "商家详细介绍", "data" => $busInfo['description']),
        "businessintro" => array("type" => "text", "name" => "简单描述", "data" => $busInfo['businessintro']),
        "scores" => array("type" => "text", "name" => "店铺分值", "data" => $busInfo['scores']),
        "addtime" => array("type" => "text", "name" => "添加时间", "data" => $busInfo['addtime']),
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