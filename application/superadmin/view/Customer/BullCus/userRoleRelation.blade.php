{include file="Pub/header" /}
<?php 
    $field = array(
        "parent_id" => array("type" => "text", "name" => "上级消费者", "data" => $relation['parent_id']),
        "grandpa_id" => array("type" => "text", "name" => "上上级消费者", "data" => $relation['grandpa_id']),
        "business_id" => array("type" => "text", "name" => "绑定的实体店", "data" => $relation['business_id']),
        "business_recomid" => array("type" => "text", "name" => "推荐实体店", "data" => $relation['business_recomid']),
        "business_role" => array("type" => "text", "name" => "推荐实体店角色", "data" => $relation['business_role']),
        "business_precomid" => array("type" => "text", "name" => "实体店推荐者上级", "data" => $relation['business_precomid']),
        "business_prole" => array("type" => "text", "name" => "推荐者上级角色", "data" => $relation['business_prole']),
        "business_pprecomid" => array("type" => "text", "name" => "推荐者上上级", "data" => $relation['business_pprecomid']),
        "business_pprole" => array("type" => "text", "name" => "推荐者上上级角色", "data" => $relation['business_pprole']),
        "countyagent_id" => array("type" => "text", "name" => "孵化中心", "data" => $relation['countyagent_id']),
        "countyagent_recomid" => array("type" => "text", "name" => "孵化中心引荐人", "data" => $relation['countyagent_recomid']),
        "cityagent_id" => array("type" => "text", "name" => "运营中心", "data" => $relation['cityagent_id']),
        "cityagent_recomid" => array("type" => "text", "name" => "运营中心引荐人", "data" => $relation['cityagent_recomid']),
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