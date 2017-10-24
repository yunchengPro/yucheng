<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />

<!--[if lt IE 9]>
<script type="text/javascript" src="/newui/static/h-ui.admin/js/html5shiv.min.js"></script>
<script type="text/javascript" src=/newui/static/h-ui.admin/js/respond.min.js"></script>
<![endif]-->
<link rel="Shortcut Icon" href="/newui/static/h-ui.admin/images/nnh/bitbug_favicon.ico"/>
<!-- <link rel="stylesheet" href="http://www.jq22.com/jquery/bootstrap-3.3.4.css">  -->
<link rel="stylesheet" type="text/css" href="/newui/static/h-ui.admin/css/dropdowns.css?v=1.0"/>
<link rel="stylesheet" type="text/css" href="/newui/static/h-ui.admin/css/bootstrap-select.css?v=1.0"/>
<link rel="stylesheet" type="text/css" href="/newui/static/h-ui/css/H-ui.min.css?v=1.0" />
<link rel="stylesheet" type="text/css" href="/newui/static/h-ui.admin/css/H-ui.admin.css?v=1.0" />
<link rel="stylesheet" type="text/css" href="/newui/lib/Hui-iconfont/1.0.7/iconfont.css?v=1.0" />
<link rel="stylesheet" type="text/css" href="/newui/lib/icheck/icheck.css?v=1.0" />
<link rel="stylesheet" type="text/css" href="/newui/static/h-ui.admin/skin/default/skin.css?v=1.0" id="skin" />
<link rel="stylesheet" type="text/css" href="/newui/static/h-ui.admin/css/H-ui.ie.css?v=1.0" />


<link href="/pinture/css/common_crm.css?v=1.0" media="screen" rel="stylesheet" type="text/css" />
<title><?=$title?></title>

<script type="text/javascript" src="/newui/lib/jquery/1.9.1/jquery.min.js"></script>

<script src="/pinture/js/admin.js"></script>
<script>
    var current_user = {"loginname":"","name":""};
    var PAGE_SIZE = 20;
    document.cookie="edcrmlang=";
</script>
<!--
<script type="text/javascript" src="/js/crm_common.js"></script>
-->
<link rel="stylesheet" type="text/css" href="/newui/static/h-ui.admin/css/superstyle.css?v=1.1" />
<link rel="stylesheet" type="text/css" href="/newui/static/h-ui.admin/css/supernewui.css?v=1.0" />
<!--
<link href='//cdn.webfont.youziku.com/webfonts/nomal/89991/46615/57b530a3f629d805cc461b57.css' rel='stylesheet' type='text/css' />
-->
<script type="text/javascript" src="/pinture/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/pinture/js/ajaxfileupload.js"></script>
</head>
<body>


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

<style type="text/css">
    .page-container{padding-top: 0px;}
</style>

<div class="main-div">
    <div class="admin">
        <article class="page-container page-container-add" id="tab-set">
            <form class="form form-horizontal" id="fModi" name="fModi" method="post" action="" class="frm" rel="iframe-form">
                <div class="row cl"  >
                    <label class="form-label col-xs-3 col-sm-4"  >
                        文章标题:
                    </label>
                    <div class="formControls col-xs-9 col-sm-8"  >
                        <?php echo Html::text(array("name"=>"title","value"=>$Article['title'],"other"=>"style='width:800px;'","validator"=>"required:true","placeholder"=>"文章标题"));?>&nbsp;<span class="red">*</span>
                    </div>
                </div>
                <div class="row cl"  >
                    <label class="form-label col-xs-3 col-sm-4"  >
                        副标标题:
                    </label>
                    <div class="formControls col-xs-9 col-sm-8"  >
                        <?php echo Html::text(array("name"=>"shorttitle","value"=>$Article['shorttitle'],"other"=>"style='width:800px;'","validator"=>"required:true","placeholder"=>"副标标题"));?>&nbsp;<span class="red">*</span>
                    </div>
                </div>
                <div class="row cl"  >
                     <label class="form-label col-xs-3 col-sm-4"  >
                        选择分类:
                    </label>
                    <div class="formControls col-xs-9 col-sm-8"  >
                        <?php echo Html::select(array("name"=>"categoryid","value"=>$Article['categoryid'],'option'=>$category_arr,"validator"=>"required:true","messages"=>"请选择分类"));?>&nbsp;<span class="red">*</span>

                      

                    </div>
                      
                </div>
                <?php if($type == 2){ ?>
                <div class="row cl citycode"  >
                     <label class="form-label col-xs-3 col-sm-4"  >
                        所属地区:
                    </label>
                    <div  class="formControls col-xs-9 col-sm-8" >
                        <?php echo Html::selectpicker(array("name"=>"citycode","value"=>$Article['citycode'],'option'=>$city,"validator"=>"required:true","messages"=>"请选择分类"));?>
                    </div>
                </div>
                <?php } ?>
                <div class="row cl"  >
                    <label class="form-label col-xs-3 col-sm-4"  >
                        缩略图:
                    </label>
                    <div class="formControls col-xs-9 col-sm-8"  >
                        <?=Html::imgupload(array("name"=>"thumb","validator"=>"required:true","append_str"=>",注：商品主图",'id'=>'thumb',"options"=>array("maxNumberOfFiles"=>1,'maxFileSize'=>4194304),'value'=>$Article['thumb']))?>&nbsp;<span class="red">*</span>
                    </div>
                </div>
                <div class="row cl"  >
                    <label class="form-label col-xs-3 col-sm-4"  >
                        内容:
                    </label>
                    <div class="formControls col-xs-9 col-sm-8"  >

                            <?=Html::edit(array("name"=>"content","value"=>$Article['content'],"width"=>"100%", "config"=>"autoHeightEnabled:false", "height"=>400))?>&nbsp;<span class="red">*</span>

                    </div>
                </div>
                <div class="row cl"  >
                    <label class="form-label col-xs-3 col-sm-4"  >
                        作者:
                    </label>
                    <div class="formControls col-xs-9 col-sm-8"  >
                        <?php echo Html::text(array("name"=>"author","value"=>$Article['author'],"other"=>"style='width:800px;'","validator"=>"required:true","placeholder"=>"作者"));?>&nbsp;<span class="red">*</span>
                    </div>
                </div>
                <?=$formtoken;?>
                <div class="row cl">
                    <div class="col-xs-8 col-sm-6 col-xs-offset-4 ">
                        <?=Html::hidden(array("name"=>"id","value"=>$Article['id']))?>
                        <?=Html::hidden(array("name"=>"type","value"=>$type))?>
                        <button class="btn btn-gold "  type="button" id="release_now" >立即发布</button>
                        <button class="btn btn-gold "  type="button" id="release_future" >保存不发布</button>
                    </div>
                </div>
            </form>
        </article>
    </div>
</div>
<script type="text/javascript">
$(function(){

    //扩展方法
    $.validator.addMethod("img_upload",function(value, element, param){
        var name=$(element).attr("name");
        if($("#"+name).val()!=''){
            return true;
        }else{
            return false;
        }
    },"图片不能为空");

    $.validator.addMethod("ueditor",function(value, element, param){
        var name=$(element).attr("name");
        //alert($(element).attr("name"));
        eval("var content=ue_"+name+".getContent();");
        //alert("|"+content+"|");
        if(content!='' && content!=' '){
            return true;
        }else{
            //alert("#"+name);
            $("#"+name).after('<label class="error" for="">必填字段</label>');
            return false;
        }
        return false;
    },"内容不能为空");
    var validator = $("#fModi").validate({
            ignore: [],
            rules: {
                categoryid:{required:true},
                title:{required:true},
                shorttitle:{required:true},
                content:{required:true},
                thumb:{required:true},        
            },
            messages: {
            }
        });

    $("#release_now").click(function(){
        var url = '/Article/Article/doaddoreditarticle?isrelease=1';
        $("#fModi").attr("action",url);
        $("#fModi").submit();
    });

    $("#release_future").click(function(){
        var url = '/Article/Article/doaddoreditarticle?isrelease=0';
        $("#fModi").attr("action",url);
        $("#fModi").submit();
    });
    // $("#categoryid").change(function(){
       
    //     var categoryid = $(this).val();
    //     var categoryname = $(this).find("option:selected").text();
    //     if(categoryname == '地区'){
    //         $('.citycode').show();
    //     }else{
    //         $('.citycode').hide();
    //     }
       
    // });
});

</script>

<script>
 $("#closewin").click(function(){
    parent.layer.close(parent.layer.getFrameIndex(window.name));
});

</script>



<script type="text/javascript" src="/newui/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="/newui/static/h-ui/js/H-ui.js?v=1.0"></script>
<script type="text/javascript" src="/newui/static/h-ui.admin/js/H-ui.admin.js?v=1.0"></script>
<script type="text/javascript" src="/newui/static/h-ui.admin/js/posfixed.js?v=1.0"></script>
<script type="text/javascript" src="/newui/lib/icheck/jquery.icheck.min.js?v=1.0"></script>
<script type="text/javascript" src="/newui/lib/jquery.validation/1.14.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="/newui/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="/newui/lib/jquery.validation/1.14.0/messages_zh.min.js"></script>

<script type="text/javascript" src="/newui/static/h-ui.admin/js/bootstrap-select.js"></script>
<script type="text/javascript" src="/newui/static/h-ui.admin/js/defaults-zh_CN.js"></script>

<!--<script type="text/javascript" src="/js/crm_common.js"></script>-->
<script type="text/javascript" src="/js/ysl.js?v=1.0"></script>
<script type="text/javascript" src="/js/crm_list.js?v=1.0"></script>
<script type="text/javascript" src="/js/init.js?v=1.0"></script>

<script type="text/javascript" src="/js/listtable.js?v=1.0"></script>
<script type="text/javascript" src="/js/linkage_address.js?v=1.0"></script>
<script type="text/javascript" src="/js/linkage_category.js?v=1.0"></script>
</body>
</html>
