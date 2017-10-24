{include file="Pub/header" /}
<?php

    $action = $action;//定义表单提交的路径
    //$field_type ='detail'; //表示以查看详情的方式显示
    $field = array(
            "menuname"=>array("type"=>"text","name"=>"菜单名称","validator"=>"required:true","head_width"=>"150","other"=>" style='width:300px;' ","value"=>$menuData['menuname']),
            "url"=>array("type"=>"text","name"=>"菜单地址","validator"=>"required:true","value"=>"/","append_str"=>"<br>地址方式如：/demo/index/list","other"=>" style='width:300px;' ","value"=>$menuData['url']),
            "parentname"=>array("type"=>"text","name"=>"上级菜单","data"=>$parantname),
            "enable"=>array("type"=>"select","name"=>"状态","option"=>array("0"=>"停用","1"=>"启用"),"value"=>$menuData['enable'],"validator"=>"required:true"),
            "sort"=>array("type"=>"text","name"=>"排序","validator"=>"number:true","value"=>$menuData['sort']),
            "icon"=>array("type"=>"text","value"=>$menuData['icon'],"name"=>"图标"),
            "class"=>array("type"=>"text","value"=>$menuData['class'],"name"=>"class"),
            "parentid"=>array("type"=>"hidden","value"=>$parent_id),
            "id"=>array("type"=>"hidden","value"=>$id),
            //"submit"=>array("type"=>"submit","name"=>"提交"), //加该参数表示独立页面，不加该参数表示弹窗
        );
?>


<!---地区控件的js-->

<!---上传图片的js-->
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
