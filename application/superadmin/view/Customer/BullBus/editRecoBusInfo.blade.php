{include file="Pub/header" /}
<?php 
    $action = $actionUrl;
    $field = array(
        "company_name" => array("type" => "text", "name" => "公司名称", "data" => $recoInfo['company_name']),
        "person_charge" => array("type" => "text", "name" => "负责人姓名", "data" => $recoInfo['person_charge']),
        "mobile" => array("type" => "text", "name" => "手机号码", "data" => $recoInfo['mobile']),
        "corporation" => array("type" => "text", "name" => "公司法人", "data" => $recoInfo['corporation']),
        "area" => array("type" => "text", "name" => "所属省市区", "data" => $recoInfo['area']),
        "company_area" => array("type" => "text", "name" => "详细地址", "data" => $recoInfo['company_area']),
        "instroducerRole" => array("type" => "select", "name" => "归属", "option" => array("2" => "牛人", "3" => "牛创客"), "value" => $recoInfo['instroducerRole'], "validator" => "required:true"),
        "instroducerMobile" => array("type" => "text", "name" => "分享人手机号码", "value" => $recoInfo['instroducerMobile'], "validator" => "required:true"),
        "price_type" => array("type" => "checkbox", "name" => "售价方式", "terms" => array("1" => "现金", "2" => "现金+牛豆/牛豆"), "value" => $recoInfo['price_type']),
//         "idnumber" => array("type" => "text", "name" => "身份证号码", "data" => $recoInfo['idnumber']),
//         "licence_image" => array("type" => "text", "name" => "营业执照图片", "data" => "<img src = ".ShowThumb($recoInfo['licence_image']).">"),
        "licence_image" => array("type" => "thumb", "name" => "营业执照图片", "data" => $recoInfo['licence_image']),
//         "idnumber_image" => array("type" => "text", "name" => "身份证图片", "data" => "<img src = ".ShowThumb($recoInfo['idnumber_arr'][0]).">"),
//         "company_logo" => array("type" => "text", "name" => "公司logo", "data" => "<img src = ".ShowThumb($recoInfo['company_logo']).">"),
        "company_logo" => array("type" => "thumb", "name" => "公司logo", "data" => $recoInfo['company_logo']),
        "addtime" => array("type" => "text", "name" => "添加时间", "data" => $recoInfo['addtime']),
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