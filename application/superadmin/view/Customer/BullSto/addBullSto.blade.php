{include file="Pub/header" /}

<?php 

    $action = $actionUrl;
    $field = array(
        "instroducerRole" => array("type" => "select", "name" => "分享人角色", "option" => $roleRecoList, "validator"=>"required:true"),
        "instroducerMobile" => array("type" => "text", "name" => "分享人手机号码","validator"=>"required:true,number:true","message"=>"手机号码有问题"),
        "area" => array("type" => "area", "name"=>"地区","validator"=>"required:true"),
        "sto_type_id" => array("type" => "select", "name" => "类型", "option" => $optionCate, "validator"=>"required:true"),
        "sto_name" => array("type" => "text", "name" => "公司名称","validator"=>"required:true"),
        "mobile" => array("type" => "text", "name" => "手机号码", "validator" => "required:true"),
        "discount" => array("type" => "text", "name" => "折扣", "validator" => "required:true"),
        "sto_hour_begin" => array("type" => "text", "name" => "营业开始时间", "validator" => "required:true", "append_str"=>"格式为09:00"),
        "sto_hour_end" => array("type" => "text", "name" => "营业结束时间", "validator" => "required:true", "append_str"=>"格式为09:00"),
        "service_type" => array("type" => "checkbox", "name" => "商家服务", "terms" => array("1" => "免费wifi", "2" => "免费停车", "3" => "送货上门")),
        "delivery" => array("type" => "text", "name" => "配送金额"),
        "nearby_village" => array("type" => "text", "name" => "附近楼宇或小区名", "validator" => "required:true"),
        "sto_mobile" => array("type" => "text", "name" => "商家服务电话", "validator" => "required:true"),
        "address" => array("type" => "text", "name" => "详细地址","validator"=>"required:true"),
        "metro_id" => array("type" => "select", "name" => "地铁id"),
        "district_id" => array("type" => "select", "name" => "商圈id"),
        "idnumber" => array("type" => "text", "name" => "身份证号码", "validator" => "required:true"),
        "main_image" => array("type" => "imgupload", "name"=>"店铺主图","options"=>array("maxNumberOfFiles"=>1),"validator"=>"required:true"),
        "sto_image" => array("type" => "imgupload", "name" => "店铺轮播图", "options"=>array("maxNumberOfFiles"=>5),"validator"=>"required:true"),
        "licence_image" => array("type" => "imgupload", "name" => "营业执照图片", "options"=>array("maxNumberOfFiles"=>1),"validator"=>"required:true"),
        "idnumber_image" => array("type" => "imgupload", "name" => "身份证图片", "options"=>array("maxNumberOfFiles"=>2),"validator"=>"required:true"),
        "description" => array("type" => "textarea", "name" => "描述(购买须知)", "validator" => "required:true"),
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
    $("#area_city").on("change", function(){
        var area_code = $(this).val();
        if(area_code > 0) {
            $.ajax({
                type:'POST',
                data:{area_code:area_code},
                async:false,
                traditional :true,
                dataType:'JSON',
                url:'/Customer/BullSto/getSysInfo',
                success:function(res) {
                    $("#metro_id option").remove();

                    $("#metro_id").append("<option value='0'>==请选择==</option>");

                    var metro = res.metro;
                    // console.log(temp);
                    for(i = 0; i < metro.length; i++) {
                        for(j = 0; j < metro[i].metrolist.length; j++) {
                            var metroInfo = metro[i].metrolist[j];
                            $("#metro_id").append("<option value='"+metroInfo.id+"'>"+metro[i].linename+"--"+metroInfo.metroname+"</option>");
                        }
                    }

                    $("#district_id option").remove();

                    $("#district_id").append("<option value='0'>==请选择==</option>");

                    var district = res.district;
                    for(i = 0; i < district.length; i++) {
                        $("#district_id").append("<option value='"+district[i].id+"'>"+district[i].district_name+"</option>");
                    }
                }
            });
        }
    });  
});

</script>

{include file="Pub/pubAdd" /}
{include file="Pub/footer" /}