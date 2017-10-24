{include file="Pub/header" /}
<?php 

    $action = $actionUrl;
    $field = array(
        "instroducerRole" => array("type" => "select", "name" => "分享人角色", "option" => $roleRecoList, "validator"=>"required:true"),
        "instroducerMobile" => array("type" => "text", "name" => "分享人手机号码","validator"=>"required:true,number:true","message"=>"手机号码有问题"),
        "area" => array("type" => "area", "name"=>"地区","validator"=>"required:true"),
        "company_name" => array("type" => "text", "name" => "公司名称","validator"=>"required:true"),
        "person_charge" => array("type" => "text", "name" => "负责人姓名","validator"=>"required:true"),
        "mobile" => array("type" => "text", "name" => "手机号码", "validator" => "required:true"),
        "corporation" => array("type" => "text", "name" => "公司法人","validator"=>"required:true"),
//         "idnumber" => array("type" => "text", "name" => "法人身份证","validator"=>"required:true"),
        "company_area" => array("type" => "text", "name" => "公司详细地址","validator"=>"required:true"),
        "price_type" => array("type" => "checkbox", "name" => "售价方式", "terms" => array("1" => "现金", "2" => "现金+牛豆/牛豆"),"validator"=>"required:true,minlength:1"),
        "licence_image" => array("type" => "imgupload", "name" => "营业执照图片", "options"=>array("maxNumberOfFiles"=>1),"validator"=>"required:true"),
//         "idnumber_image" => array("type" => "imgupload", "name" => "身份证图片", "options"=>array("maxNumberOfFiles"=>2),"validator"=>"required:true"),
        "company_logo" => array("type" => "imgupload", "name" => "公司logo", "options"=>array("maxNumberOfFiles"=>1),"validator"=>"required:true"),
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