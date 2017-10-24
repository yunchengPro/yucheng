{include file="Pub/header" /}
<?php 
    $action = $actionUrl;
    $field = array(
        "type"    =>  array("type" => "select", "name" => "所属类型", "option"=>array("1" => "个人", "2" => "公司"), "validator" => "required:true","class"=>"type"),
        
        "instroducerRole" => array("type" => "select", "name" => "分享人角色", "option" => $roleRecoList, "validator"=>"required:true"),
        "instroducerMobile" => array("type" => "text", "name" => "分享人手机号码", "validator" => "required:true, number:true"),
        "company_name" => array("type" => "text", "name" => "公司名称", "append_str"=>" *"),
        "charge_idnumber" => array("type" => "text", "name" => "负责人身份证", "append_str"=>" *"),
        "charge_name" => array("type" => "text", "name" => "负责人姓名", "append_str" => " *"),
        "charge_mobile" => array("type" => "text", "name" => "负责人手机号码", "append_str" => " *"),
        
        "realname" => array("type" => "text", "name" => "真实姓名", "append_str" => " *"),
        "mobile" => array("type" => "text", "name" => "手机号码", "append_str" => " *"),
        "area" => array("type" => "area", "name" => "所属省市区", "append_str" => " *"),
        
        
        "corporation_name" => array("type" => "text", "name" => "法人姓名", "append_str" => " *"),
        "corporation_idnumber" => array("type" => "text", "name" => "法人身份证", "append_str" => " *"),
        "licence_image" => array("type" => "imgupload", "name" => "营业执照图片", "options"=>array("maxNumberOfFiles"=>1), "append_str" => " *"),
        "corporation_image" => array("type" => "imgupload", "name" => "法人身份证图片", "options"=>array("maxNumberOfFiles"=>2), "append_str" => " *"),
        
        "address" => array("type" => "text", "name" => "详细地址", "append_str" => " *"),
//         "join_area" => array("type" => "area", "name" => "加盟区县", "append_str" => " * <input type='button' id='addJoin' value='   +   '>"),
        "join_area" => array("type" => "area", "name" => "加盟区县"),
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
	$(function(){
		$("#fModi .row").hide();
		$("#fModi .row:first-child").show();
		
		$("#type").change(function(){
			if($(this).val() == 2){
				$("#realname").parent().parent().hide();
				$("#mobile").parent().parent().hide();
				
				$("#address").parent().parent().show();
				$("#company_name").parent().parent().show();
				$("#charge_idnumber").parent().parent().show();
				$("#charge_name").parent().parent().show();
				$("#charge_mobile").parent().parent().show();
				$("#corporation_name").parent().parent().show();
				$("#corporation_idnumber").parent().parent().show();
				$("#uploadfiles_licence_image").parent().parent().show();
				$("#uploadfiles_corporation_image").parent().parent().show();
				$("#area_county").parent().parent().show();
				$("#join_area_county").parent().parent().show();
				$("#instroducerMobile").parent().parent().show();
				$("#instroducerRole").parent().parent().show();
			}
			if($(this).val() == 1){
				$("#company_name").parent().parent().hide();
				$("#charge_idnumber").parent().parent().hide();
				$("#charge_name").parent().parent().hide();
				$("#charge_mobile").parent().parent().hide();
				$("#corporation_name").parent().parent().hide();
				$("#corporation_idnumber").parent().parent().hide();
				$("#uploadfiles_licence_image").parent().parent().hide();
				$("#uploadfiles_corporation_image").parent().parent().hide();
				
				$("#area_county").parent().parent().show();
				$("#join_area_county").parent().parent().show();
				$("#realname").parent().parent().show();
				$("#mobile").parent().parent().show();
				$("#address").parent().parent().show();
				$("#instroducerMobile").parent().parent().show();
				$("#instroducerRole").parent().parent().show();
			}
		});
	});
</script>

{include file="Pub/pubAdd" /}
{include file="Pub/footer" /}