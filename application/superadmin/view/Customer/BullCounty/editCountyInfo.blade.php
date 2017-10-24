{include file="Pub/header" /}
<?php
    $action = $actionUrl;
    $typeArr = array("1"=>"个人","2"=>"公司");
    
    $field = array(
        "type" => array("type"=>"text", "name"=>"所属类型", "data"=>$typeArr[$agentInfo['type']]),
        "typeNum" => array("type"=>"hidden", "name"=>"类型值", "value"=>$agentInfo['type']),
        
        "instoducerMobile" => array("type"=>"text", "name"=>"分享人手机号码", "data"=>$cus['mobile']),
        
        "company_name" => array("type"=>"text", "name"=>"公司名称", "value"=>$agentInfo['company_name'], "append_str" => " *"),
        "charge_idnumber" => array("type"=>"text", "name"=>"负责人身份证", "value"=>$agentInfo['charge_idnumber'], "append_str" => " *"),
        "charge_name" => array("type"=>"text", "name"=>"负责人姓名", "value"=>$agentInfo['charge_name'], "append_str" => " *"),
        "charge_mobile" => array("type"=>"text","name"=>"负责人手机号码", "value"=>$agentInfo['charge_mobile'], "append_str" => " *"),
        
        "realname" => array("type" => "text", "name" => "真实姓名", "value" => $agentInfo['realname'], "append_str" => " *"),
        "mobile" => array("type" => "text", "name" => "手机号码", "value" => $agentInfo['mobile'], "append_str" => " *"),
        "area" => array("type" => "area", "name" => "所属省市区", "value"=>$agentInfo['area_code']),
        
        "corporation_name" => array("type" => "text", "name" => "法人姓名", "value"=>$agentInfo['corporation_name'], "append_str" => " *"),
        "corporation_idnumber" => array("type" => "text", "name" => "法人身份证", "value" => $agentInfo['corporation_idnumber'], "append_str" => " *"),
        "old_licence_image" => array("type" => "text", "name" => "原营业执照图片", "data" => "<img id='old_licence_image' src = ".ShowThumb($agentInfo['licence_image']).">"),
        "licence_image" => array("type" => "imgupload", "name" => "营业执照图片", "options" => array("maxNumberOfFiles"=>1)),
        "old_corporation_image" => array("type" => "text", "name" => "原法人身份证图片", "data" => "<img id='old_corporation_image' src = ".ShowThumb($corporation_image[0]).">  <img src = ".ShowThumb($corporation_image[1]).">"),
        "corporation_image" => array("type" => "imgupload", "name" => "法人身份证图片", "options" => array("maxNumberOfFiles"=>2)),
        "address" => array("type" => "text", "name" => "详细地址", "value"=>$agentInfo['address'], "append_str" => " *"),
        "join_area" => array("type" => "area", "name" => "加盟区县", "value"=>$agentInfo['join_code']),
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

<script type="text/javascript">
    $(function() {
        var type = $("#typeNum").val();
        if(type == 1) {
            $("#company_name").parent().parent().hide();
            $("#charge_idnumber").parent().parent().hide();
            $("#charge_name").parent().parent().hide();
            $("#charge_mobile").parent().parent().hide();
            $("#corporation_name").parent().parent().hide();
            $("#corporation_idnumber").parent().parent().hide();
            $("#old_licence_image").parent().parent().hide();
            $("#uploadfiles_licence_image").parent().parent().hide();
            $("#old_corporation_image").parent().parent().hide();
            $("#uploadfiles_corporation_image").parent().parent().hide();
        } else {
            $("#realname").parent().parent().hide();
            $("#mobile").parent().parent().hide();
        }
    });
</script>

{include file="Pub/pubAdd" /}
{include file="Pub/footer" /}