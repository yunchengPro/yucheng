@include('Pub.header')
<?php

    $action = $action;//定义表单提交的路径
    //$field_type ='detail'; //表示以查看详情的方式显示
    $field = array(
            "name"=>array("type"=>"text","name"=>"名称","validator"=>"required:true","head_width"=>"150","other"=>" style='width:300px;' ","value"=>$roleData['name']),
            "remark"=>array("type"=>"textarea","name"=>"描述","validator"=>"required:true","value"=>"","other"=>"style='width:300px;' ","value"=>$roleData['remark']),
            "enable"=>array("type"=>"select","name"=>"状态","option"=>array("0"=>"停用","1"=>"启用"),"value"=>$roleData['enable'],"validator"=>"required:true"),
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

@include('Pub.pubAdd')
@include('Pub.footer')