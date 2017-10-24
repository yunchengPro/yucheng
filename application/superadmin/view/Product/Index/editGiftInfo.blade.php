{include file="Pub/header" /}
<?php 
    $action = $actionUrl;
    
    $role_arr = array("2" => "牛人", "3" => "牛创客");

    $field = array(
//         "role" => array("type" => "select", "name" => "礼品角色类型", "option" => array("2" => "牛人", "3" => "牛创客"), "validator"=>"required:true", "data" => $productInfo['role']),
        "role" => array("type" => "text", "name" => "礼品角色类型","data" => $role_arr[$productInfo['role']]),
        "bus" => array("type" => "select", "name" => "vip牛商", "option" => $busList, "validator" => "required:true", "value" => $productInfo['businessid']),
        "productname" => array("type" => "text", "name" => "商品名称", "validator"=>"required:true", "value" => $productInfo['productname']),
        "productImage" => array("type" => "text", "name" => "商品图片", "data" => "<img src = ".ShowThumb($productInfo['thumb']).">"),
        "thumb" => array("type" => "imgupload", "name" => "新商品主图", "options"=>array("maxNumberOfFiles"=>1),"append_str"=>"图片规格 260*260"),
        "supplyprice" => array("type" => "text", "name" => "供货价", "validator"=>"required:true,number:true", "value" => DePrice($productInfo['supplyprice'])),
        "enable" => array("type" => "select", "name" => "上下架状态", "option" => array("-1" => "下架", "1" => "上架"), "validator"=>"required:true", "value" => $productInfo['enable']),
        "sort" => array("type" => "text", "name" => "排序", "validator" => "required:true,number:true", "append_str" => "数字越小越靠前(非0)", "value" => $productInfo['sort']),
//         "freight" => array("type" => "text", "name" => "运费", "value" => $productInfo['freight']),
        "productunit" => array("type" => "text", "name" => "单位", "value" => $productInfo['productunit']),
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