{include file="Pub/header" /}
<?php 
    $field_type = "detail";
    $field = array(
        "sto_name" => array("type" => "text", "name" => "商家名称", "data" => $stoExamInfo['sto_name']),
//         "area" => array("type" => "area", "name" => "地区", "validator" => "required:true","value"=>$stoExamInfo['area_code']),
//         "area_code" => array("type" => "hidden", "name" => "地区id", "value" => $stoExamInfo['area_code']),
//         "mobile" => array("type" => "text", "name" => "手机号码", "data" => $stoExamInfo['mobile']),
        "discount" => array("type" => "text", "name" => "折扣", "data" => $stoExamInfo['discount']),
        "sto_hour_begin" => array("type" => "text", "name" => "营业开始时间", "data" => $stoExamInfo['sto_hour_begin']),
        "sto_hour_end" => array("type" => "text", "name" => "营业结束时间", "data" => $stoExamInfo['sto_hour_end']),
        "service_type" => array("type" => "checkbox", "name" => "商家服务", "terms" => array("1" => "免费wifi", "2" => "免费停车", "3" => "送货上门"), "value" => $stoExamInfo['service_type']),
        "delivery" => array("type" => "text", "name" => "起送金额", "data" => $stoExamInfo['delivery']),
        "nearby_village" => array("type" => "text", "name" => "附近楼宇或小区名", "data" => $stoExamInfo['nearby_village']),
        "sto_mobile" => array("type" => "text", "name" => "商圈服务电话", "data" => $stoExamInfo['sto_mobile']),
//         "address" => array("type" => "text", "name" => "详细地址", "data" => $stoExamInfo['address']),
//         "metro_id" => array("type" => "select", "name" => "地铁id"),
//         "user_metro" => array("type"=>"hidden", "name" =>"用户地铁id", "value"=>$stoExamInfo['metro_id']),
//         "district_id" => array("type" => "select", "name" => "商圈id"),
//         "user_district" => array("type"=>"hidden", "name" =>"用户商圈id", "value"=>$stoExamInfo['bus_district']),
//         "idnumber" => array("type" => "text", "name" => "身份证号码", "data" => $stoExamInfo['idnumber']),
        "main_image" => array("type" => "text", "name" => "店铺主图", "data" => "<img src = ".ShowThumb($stoExamInfo['main_image']).">"),
        "sto_image" => array("type" => "text", "name" => "店铺轮播图", "data" => "<img src = ".ShowThumb($stoExamInfo['sto_image'][0]).">"),
        "album_image" => array("type" => "text", "name" => "店铺相册图", "data" => "<img src = ".ShowThumb($stoExamInfo['album_image'][0]).">"),
//         "licence_image" => array("type" => "text", "name" => "营业执照图片", "data" => "<img src = ".ShowThumb($stoExamInfo['licence_image']).">"),
//         "idnumber_image" => array("type" => "text", "name" => "身份证图片", "data" => "<img src = ".ShowThumb($stoExamInfo['idnumber_arr'][0]).">"),
        "description" => array("type" => "textarea", "name" => "描述(购买须知)", "data" => $stoExamInfo['description']),
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