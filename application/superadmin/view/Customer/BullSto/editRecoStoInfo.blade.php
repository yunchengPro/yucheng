{include file="Pub/header" /}
<?php 
    $action = $actionUrl;
    $field = array(
        "category_name" => array("type" => "text", "name" => "店铺类型", "data" => $recoInfo['category_name']),
        "sto_name" => array("type" => "text", "name" => "商家名称", "data" => $recoInfo['sto_name']),
        "area" => array("type" => "area", "name" => "地区", "validator" => "required:true","value"=>$recoInfo['area_code']),
        "area_code" => array("type" => "hidden", "name" => "地区id", "value" => $recoInfo['area_code']),
        "mobile" => array("type" => "text", "name" => "手机号码", "data" => $recoInfo['mobile']),
        "discount" => array("type" => "text", "name" => "折扣", "value" => $recoInfo['discount'], "validator" => "required:true"),
        "sto_hour_begin" => array("type" => "text", "name" => "营业开始时间", "value" => $recoInfo['sto_hour_begin'], "validator"=>"required:true", "append_str"=>"格式为09:00"),
        "sto_hour_end" => array("type" => "text", "name" => "营业结束时间", "value" => $recoInfo['sto_hour_end'], "validator" => "required:true","append_str"=>"格式为09:00"),
        "service_type" => array("type" => "checkbox", "name" => "商家服务", "terms" => array("1" => "免费wifi", "2" => "免费停车", "3" => "送货上门"), "value" => $recoInfo['service_type'], "validator"=>"required:true"),
        "delivery" => array("type" => "text", "name" => "配送金额", "value" => $recoInfo['delivery']),
        "nearby_village" => array("type" => "text", "name" => "附近楼宇或小区名", "value" => $recoInfo['nearby_village'], "validator" => "required:true"),
        "sto_mobile" => array("type" => "text", "name" => "商圈服务电话", "data" => $recoInfo['sto_mobile']),
        "address" => array("type" => "text", "name" => "详细地址", "data" => $recoInfo['address']),
        "metro_id" => array("type" => "select", "name" => "地铁id"),
        "user_metro" => array("type"=>"hidden", "name" =>"用户地铁id", "value"=>$recoInfo['metro_id']),
        "district_id" => array("type" => "select", "name" => "商圈id"),
        "user_district" => array("type"=>"hidden", "name" =>"用户商圈id", "value"=>$recoInfo['bus_district']),
        "idnumber" => array("type" => "text", "name" => "身份证号码", "data" => $recoInfo['idnumber']),
        "sto_image" => array("type" => "thumb", "name" => "店铺主图", "data" => $recoInfo['sto_image']),
        "licence_image" => array("type" => "thumb", "name" => "营业执照图片", "data" => $recoInfo['licence_image']),
        "idnumber_image" => array("type" => "thumb", "name" => "身份证图片", "data" => $recoInfo['idnumber_arr'][0]),
        "description" => array("type" => "textarea", "name" => "描述(购买须知)", "data" => $recoInfo['description']),
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
    var area_code = $("#area_code").val();

    if(area_code > 0) {
        $.ajax({
            type:'POST',
            data:{area_code:area_code},
            async:false,
            traditional:true,
            dataType:'JSON',
            url:'/Customer/BullSto/getSysInfo',
            success:function(res) {

                var metro_id = $("#user_metro").val();

                // 获取商圈 和地铁
                $("#metro_id option").remove();
                $("#metro_id").append("<option value='0'>==请选择==</option>");
                var metro = res.metro;
                for(i = 0; i < metro.length; i++) {
                    for(j = 0; j < metro[i].metrolist.length; j++) {
                        var metroInfo = metro[i].metrolist[j];
                        var metro_html = "<option value ='"+metroInfo.id+"'";

                        if(metroInfo.id == metro_id) {
                            metro_html += " selected = 'selected'";
                        }
                        metro_html += ">"+metro[i].linename+"--"+metroInfo.metroname+"</option>";

                        $("#metro_id").append(metro_html);

                        // $("#metro_id").append("<option value='"+metroInfo.id+"'>"+metro[i].linename+"--"+metroInfo.metroname+"</option>");
                    }
                }

                var district_id = $("#user_district").val();

                $("#district_id option").remove();
                $("#district_id").append("<option value='0'>==请选择==</option>");
                var district = res.district;
                for(i = 0; i < district.length; i++) {

                    var district_html = "<option value ='"+district[i].id+"'";
                    if(district[i].id == district_id) {
                        district_html += " selected = 'selected'";
                    }
                    district_html += ">"+district[i].district_name+"</option>";
                    $('#district_id').append(district_html);
                    // $("#district_id").append("<option value='"+district[i].id+"'>"+district[i].district_name+"</option>");
                }
            }
        });
    }

	

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