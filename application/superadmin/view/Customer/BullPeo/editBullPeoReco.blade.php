{include file="Pub/header" /}
<?php 

    $action = $actionUrl;
    $field = array(
        "id" => array("type" => "text", "name" => "序号", "data" => $recoInfo['id']),
        "realname" => array("type" => "text", "name" => "真实姓名", "value" => $recoInfo['realname']),
        "mobile" => array("type" => "text", "name" => "手机号码", "data" => $recoInfo['mobile']),
        "area" => array("type" => "text", "name" => "所在地区", "data" => $recoInfo['area']),
        "address" => array("type" => "text", "name" => "详细地址", "value" => $recoInfo['address']),
        "instroducerMobile" => array("type" => "text", "name" => "分享人手机号码", "value" => $recoInfo['instroducerMobile']),
        "addtime" => array("type" => "text", "name" => "推荐时间", "data" => $recoInfo['addtime']),
//         "productid" => array("type" => "radio", "name" => "礼品类型", "terms" => $goodslist, "value" => $giftInfo['item']['productid']),
//         "logisticsName" => array("type" => "text", "name" => "收货人", "value" => $giftInfo['logistics']['realname']),
//         "logisticsMobile" => array("type" => "text", "name" => "收货人电话", "value" => $giftInfo['logistics']['mobile']),
//         "logisticsArea" => array("type" => "area", "name" => "收货地区", "value" => $giftInfo['logistics']['city_id']),
//         "logisticsAddress" => array("type" => "text", "name" => "收货地址", "value" => $giftInfo['logistics']['address']),
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