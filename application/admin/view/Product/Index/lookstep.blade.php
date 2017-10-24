<style>
#fModi{
    margin-top: 30px;
}
body, th, td, button, input, select, textarea { font-family: "dongqingheiti", serif }
@font-face {
  font-family: "dongqingheiti";
  src: url("/newui/static/h-ui.admin/css/dongqingheiti.otf");
}  
.tab_style_base tr td  .label{
    display: block;
}


.tabBar{
    border:none;
}

.table  tr{
    height: 50px;
}
.table  tr td{
  border:none;
}
.table_view tr td{
  border:none;
}

.tab_style_base tr td  .label{
    display: block;
}
.tab_style_base tr td  .label{
    margin-top: 2px;
    text-align: right;
    color: #555;
    display: block;
    font-weight: normal;
    width: 250px;
    background-color:#FFF;
}

.table  tr td{
    border:none;
    box-sizing: border-box;
    margin-left: -15px;
    margin-right: -15px;
    margin-top: 15px;
}
.spec-bg { background-color: #FCFCFC;}
.spec li { vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; *display: inline/*IE6,7*/; margin-left: 10px; margin-bottom: 6px; zoom: 1;}
.ncsc-form-radio-list li{float: left;margin-right: 10px;}
.active{display: inline-block;}
.button-group  select{
    margin-bottom: 10px;
}
.table tr:nth-child(2n){
    background-color: #FFF;
}
.button-group-small{
    width: 100%;
}
.tab_style_base tr td  .label label{
    margin-top: 2px;
    text-align: right;
    color: #555;
    display: block;
    font-weight: normal;
    border:  1px solid #CCC;

    
}
.tab_style_base_new tr td {
    border:  1px solid #CCC;
}
.table_header tr th:last-child {
    border-right: 1px solid #ccc;
}

.ncsc-form-radio-list li {
    float: left;
    margin-right: 10px;
}
#settle_cycle label{
    float: right;
    margin-right: 83%;
}
#businesscategoryid label{
    float: right;
    margin-right: 77%;
}
</style>
<link rel="stylesheet" type="text/css" href="/pinture/css/jquery.editable-select.min.css" />


<script src="/js/store_goods_add.step2.js"></script>

<script charset="utf-8" src="/Ueditor/ueditor.config.js"></script>
<script charset="utf-8" src="/Ueditor/ueditor.all.min.js"> </script>
<script charset="utf-8" src="/Ueditor/lang/zh-cn/zh-cn.js"></script>


<!--图片插件-->
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


<script type="text/javascript">

jQuery.validator.addMethod("commas", function(value, element){
    //return this.optional(element) || /[\u4e00-\u9fa5]+/.test(value);

    var angelweb = value;
    var re=/[，]/g;
    if(re.test(angelweb)){
        return false;  
    }else{
        return true;
    }



}, "关键字中不能有中文逗号");

$(function(){



    $("#special input[type=radio]").click(function(){
        //inline-block
        if($(this).val() != 0){
            $("#specialpic").css("display", "");
            $("#f_specialpic").attr("class", "required");
        }else{
            console.log(validator.rules);
            $("#specialpic").css("display", "none");
        }
    });


});

</script>

<div class="admin">
    <form id="fModi" method="post"  rel="iframe-form" ><!--    -->
  

        <table class="table  table_header tab_style_base table_view" >

            <tr>
                <td align="right" style="width: 5%">

                    <div class="label"><span class="red">*</span>商品属性:</div>
                </td>
                <td style="width: 95%">
                    <div class="field">
                        <div class="button-group button-group-small radio">
                            <input type="text" value="<?=$module['modulename']?>" class="input-text " readonly="true">
                            
                        </div>
                    </div>
                </td>
            </tr>
           
            <tr>
                <td align="right" style="width: 5%">

                    <div class="label">条形码:</div>
                </td>
                <td style="width: 95%">
                    <div class="field">
                        <div class="button-group button-group-small radio">
                            <input type="text" value="<?=$productinfo['barcode']?>" class="input-text " name="barcode" >
                            
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td align="right" style="width: 5%">

                    <div class="label">货号:</div>
                </td>
                <td style="width: 95%">
                    <div class="field">
                        <div class="button-group button-group-small radio">
                            <input type="text" value="<?=$productinfo['serialnumber']?>" class="input-text " name="serialnumber" >
                            
                        </div>
                    </div>
                </td>
            </tr>
            
            <tr>
                <td align="right">

                    <div class="label"><span class="red">*</span>商品商家分类:</div>

                </td>
                <td id="businesscategoryid">
                    <div class="field">
                        <div class="button-group button-group-small radio">
                            <!-- <input type="text" value="<?=$module['categoryname']?>" class="input-text width250" disabled="disabled"> -->
                            <?php echo html::selectpicker(array("name"=>"businesscategoryid","value"=>$productinfo['businesscategoryid'],'option'=>$optionCate,"validator"=>"required:true","messages"=>"请选择分类"));?>
                            
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">

                    <div class="label"><span class="red">*</span>商品分类:</div>

                </td>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">
                            <!-- <input type="text" value="<?=$module['categoryname']?>" class="input-text width250" disabled="disabled"> -->
                            <?php echo html::category(array("name"=>"categoryid","pvalue"=>$parent_cate_id,"svalue"=>$productinfo['categoryid'],"validator"=>"required:true","messages"=>"请选择分类","hasbull"=>$hasbull));?>
                            
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">

                    <div class="label"><span class="red">*</span>产品名称:</div>

                </td>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">
                            <input type="text" name="productname" id="productname" value="<?=$productinfo['productname']?>" class="input-text  required" style="width:750px;">
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td align="right">

                    <div class="label">商品品牌:</div>

                </td>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">
                            <?php echo html::selectpicker(array("name"=>"brandid","value"=>$productinfo['brandid'],'option'=>$brand,"validator"=>"required:true","messages"=>"请选择品牌"));?>
                        </div>
                    </div>
                </td>
            </tr>
        
            <tr>
                <td>
                    <div class="label">商品价格:</div>
                </td>
                <td id="settle_cycle">
               
                    <?php if(in_array(1, $type_arr)){ ?><input id="radio-1" type="radio" value="1" <?php if($productinfo['saletype'] == 1){ ?> checked="checked" <?php } ?> class="settle_cycle" id="settle_cycle1" name="saletype">牛票
                    <?php } ?>
                    <?php if(in_array(2, $type_arr)){ ?><input  id="radio-2" type="radio" value="2" <?php if($productinfo['saletype'] == 2){ ?> checked="checked" <?php } ?>  class="settle_cycle" id="settle_cycle2" name="saletype">牛票+牛豆
                    <?php if($hasbull == 1){ ?>
                    <input  id="radio-3" type="radio" value="3" <?php if($productinfo['saletype'] == 3){ ?> checked="checked" <?php } ?>  class="settle_cycle" id="settle_cycle3" name="saletype">牛豆
                    <?php } ?>
                    <?php } ?>
                    <?php if(!in_array(1, $type_arr) && !in_array(2, $type_arr)){ ?>
                        <input id="radio-1" type="radio" value="1" <?php if($productinfo['saletype'] == 1){ ?> checked="checked" <?php } ?> class="settle_cycle" id="settle_cycle1" name="saletype">牛票
                        <input  id="radio-2" type="radio" value="2" <?php if($productinfo['saletype'] == 2){ ?> checked="checked" <?php } ?>  class="settle_cycle" id="settle_cycle2" name="saletype">牛票+牛豆
                        <?php if($hasbull == 1){ ?>
                        <input  id="radio-3" type="radio" value="3" <?php if($productinfo['saletype'] == 3){ ?> checked="checked" <?php } ?>  class="settle_cycle" id="settle_cycle3" name="saletype">牛豆
                        <?php } ?>
                    <?php } ?>    
                  
                </td>
            </tr>
             <tr id="price_input">
                <td>
                </td>
                <td>
                    供货价<input type="text" onfocus="" onblur=""  onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"  id="supplyprice"   name="supplyprice" value="<?php echo DePrice($productinfo['supplyprice']);?>" class="input-scanNo input-text radius" >
                    销售价
                    <input type="text" onfocus="" onblur="" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" id="saleprice"  name="saleprice" value="<?php echo DePrice($productinfo['saleprice']); ?>" value="" class="input-scanNo input-text radius" >
                    <span style="color:red" id="msg"></span>
                </td>
            </tr>
            <tr id="cash_input" >
                <td>
                </td>
                <td>
                    折扣<input type="text" onfocus="" onblur="" onkeyup="" readonly="true" id="discount"  name="discount" value="<?php echo  $productinfo['discount']; ?>" class="input-scanNo input-text radius" >
                    现金
                    <input type="text" onfocus="" onblur="" onkeyup="" readonly="true"  id="prouctprice" name="prouctprice" value="<?php echo  DePrice($productinfo['prouctprice']); ?>" class="input-scanNo input-text radius" >
                    牛豆
                    <input type="text" onfocus="" onblur="" onkeyup="" readonly="true"  id="bullamount" name="bullamount" value="<?php echo  DePrice($productinfo['bullamount']); ?>" class="input-scanNo input-text radius" >
                </td>
            </tr>
         
            <tr>
                <td align="right">

                    <div class="label">商品图片:</div>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">    
                            <?=Html::imgupload(array("name"=>"photos","validator"=>"required:true" ,"append_str"=>",注：商品主图",'id'=>'photos',"options"=>array("maxNumberOfFiles"=>5,'maxFileSize'=>4194304),'value'=>$photos))?>（建议尺寸:750pxX750px）
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td align="right">
                    <div class="label">商品缩略图片:</div>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">    
                            <?=Html::imgupload(array("name"=>"thumb","validator"=>"required:true" ,"append_str"=>",注：商品主图",'id'=>'images',"value"=>$productinfo['thumb'],"options"=>array("maxNumberOfFiles"=>1,'maxFileSize'=>4194304)))?>（建议尺寸:370pxX370px）
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td align="right">
                        <div class="label">商品描述:</div>
                </td>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio" >
                        
                            <?=Html::edit(array("name"=>"description","value"=>$productinfo['description'],"width"=>"100%", "config"=>"autoHeightEnabled:false", "height"=>400))?>
                            
                        </div>
                    </div>
                </td>
            </tr>
            
         
           
            <?php if(is_array($spec) && !empty($spec)){ ?>
            <?php $i = 0; ?>
            <?php foreach($spec as $sk => $sv){ ?>
            <tr>
                <td align="right">
                    <div class='label'><?=$sv['specname']?>:</div>
                    <input type="hidden" name="sp_name[<?=$i?>]" value="<?=$sv['specname']?>" />
                </td>

                <td>
                    <div class="field">
                        <div nctype="spec_group_dl" nc_type="spec_group_dl_<?=$i?>" class="button-group button-group-small radio" <?php if($i==0){ ?> spec_img="t" <?php } ?>  >
                            <ul class="spec">
                                
                                <?php if(is_array($sv['itemValue'])){ ?>
                                    <?php foreach($sv['itemValue'] as $v){  ?>
                                    <li>
                                        <span nctype="input_checkbox">
                                            <input style="30px;display:inline" type="checkbox" name="sp_val[<?php echo $sk; ?>][<?php echo $v['id']; ?>]" nc_type="<?php echo $v['id']; ?>" value="<?php echo $v['spec_value_name'];?>" >
                                        </span>
                                        <span nctype="pv_name"><?php echo $v['spec_value_name'];?></span>
                                    </li>
                                    <?php } ?>
                                <?php } ?>
                                <?php if($sv['_id'] > 0){ ?>
                                <li data-speid="<?php echo $sv['_id']; ?>" data-spename="<?php echo $sv['specname']; ?>" data-url="/Product/index/ajaxaddspec">
                                    <div nctype="specAdd1">
                                        <a href="javascript:void();" class="ncsc-btn" nctype="specAdd">
                                            <i class="icon-plus"></i>添加规格值
                                        </a>
                                    </div>
                                    <div nctype="specAdd2" style="display:none;">
                                        <input class="input-text width250" style="width:100px;display:inline" type="text" placeholder="规格值名称" maxlength="20">
                                        <a href="javascript:void();" nctype="specAddSubmit">确认</a>
                                        <a href="javascript:void();" nctype="specAddCancel">取消</a>
                                    </div>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </td>

            </tr>  
            <?php $i++; ?>  
            <?php } ?>
            <?php } ?>

            <tr nc_type="spec_dl" style="<?php echo empty($spec_arr_td)?"display:none;":""?>">
                <td align="right">
                        <div class="label">库存配置:</div>
                </td>
                <td>
                 
                    <div class="button-group button-group-small radio" style="width:100%">
                            <span id="notice" style="color: #0a6699;">不填写价格的记录不生成规格数据</span>
                            <table class="table table-hover table_header tab_style_base_new tab_style_base table_view">
                                <thead nc_type="spec_thead">
                                    <th>序号</th>
                                    <?php foreach($spec_arr_td as $key=>$value){ ?>
                                        <th nctype='spec_name_<?=$key?>'><?=$value?></th>
                                    <?php } ?>
                                    <th >供货价格</th>;
                                    <th >销售价格</th>
                                    <th >折扣</th>
                                    <th >现金价格</th>
                                    <th >牛豆数量</th>
                                    <th >条形码</th>
                                    <th >商品货号</th>
                                    <th >库存</th>
                                    <th >图片</th>
                                </thead>
                                <tbody nc_type="spec_table">

                                    
                                </tbody>
                            </table>

                        </div>
                </td>
            </tr>
        

            <tr>
                <td align="right">
                    <div class="label">净重量:</div>
                </td>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">    
                            <input type="text" name="weight" value="<?=$productinfo['weight']?>" class="input-text width250" size="20">kg
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <div class="label">毛重量:</div>
                </td>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">    
                            <input type="text" name="weight_gross" value="<?=$productinfo['weight_gross']?>" class="input-text width250" size="20">kg
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <div class="label">体积:</div>
                </td>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">    
                            <input type="text" name="volume" value="<?=$productinfo['volume']?>" class="input-text width250" size="20">m³
                        </div>
                    </div>
                </td>
            </tr>
         <!--    <tr>
                <td align="right">
                        <div class="label">商品包邮</div>
                </td>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">    
                            <input id="freight_type" nctype="freight_type" name="freight_type" class="radio" type="radio" <?php if($freight['freight_type'] ==1){ ?> checked="checked"<?php } ?> value="1" >满
                            <input type="text" name="freight_1" <?php if($freight['freight_type'] == 1){ ?> value="<?=$freight['freight']?>" <?php } ?> class="input-text width250" size="20">件包邮
                        </div>
                        <div class="button-group button-group-small radio" style="margin-top:5px; ">    
                            <input id="freight_type" nctype="freight_type" name="freight_type" <?php if($freight['freight_type'] == 2){ ?> checked="checked"<?php } ?>  class="radio" type="radio" value="2">满
                            <input type="text" name="freight_2" <?php if($freight['freight_type'] == 2){ ?> value="<?=$freight['freight']?>" <?php } ?> class="input-text width250" size="20">金额包邮
                        </div>
                        <div class="button-group button-group-small radio" style="margin-top:5px; ">    
                            <input id="freight_type" nctype="freight_type" <?php if($freight['freight_type'] != 2 && $freight['freight_type'] != 1){ ?> checked="checked"<?php } ?> name="freight_type" class="radio" type="radio" value="0">都不参与
                        </div>
                    </div>
                </td>
            </tr>-->
            <tr>
                <td align="right">
                        <div class="label">运费模板</div>
                </td>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">
                           
                                   
                                    <div nctype="div_freight" >
                                  
                               
                                       
                                             
                                        <?php if(!empty($transport_list)){ ?>                                            
                                        <?php echo Html::select(["name"=>"transportid","class"=>"select","option"=>$transport_list,"value"=>$productinfo['transportid']]); ?>
                                        <?php }else{  echo "没有查到可用运费模板,请配置运费模板"; } ?>
                                        
                                    </div>
                              </ul>
                        </div>
                    </div>
                </td>
            </tr> 
            <!-- <tr>
                <td align="right">
                    <div class="label">库存:</div>
                </td>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">    
                            <input type="text" name="productstorage" value="<?=$productinfo['productstorage']?>" class="input-text width250" size="20">
                        </div>
                    </div>
                </td>
            </tr> -->
           <!--  <?php if(is_array($attribute) && !empty($attribute)){ ?>
            <?php $i = 1; ?>
            <?php foreach($attribute as $sk=>$sv){  ?>
                 <tr>
                <td align="right">
                        <label>扩展属性<?=$i?>:</label>
                </td>
                <td>
                    <div class="field">
                        <font style="float:left;margin-right: 40px;margin-left: 20px;" size="4"><?=$sv['attr_name']?></font>
                        <select name="attributevalue_<?=$sv['id']?>" style="float:left; width: 200px;">
                             <option selected value="">--请选择--</option>
                                <?php foreach($sv['itemValue'] as $k=>$v){ ?>
                                    <option value="<?=$v['id']?>"><?=$v['attr_value_name']?></option>
                                <?php } ?>
                        </select>
                    </div>
                </td>
            </tr>
          
            <?php $i++; ?>
            <?php } ?>
            <?php } ?> -->
            <tr>
                <td align="right">

                    <div class="label">是否为海外商品:</div>

                </td>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">

                        <?php echo Html::select(['name'=>'isabroad','value'=>$productinfo['isabroad'],'option'=>["0"=>"不是","1"=>"是"],"validator"=>"required:true","messages"=>"请选择"]);?>
                          
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">

                    <div class="label">是否上架:</div>

                </td>
                <td>
                    <div class="field">
                        <div class="button-group button-group-small radio">

                        <?php echo Html::select(['name'=>'enable','value'=>$productinfo['enable'],'option'=>["1"=>"上架","-1"=>"下架"],"validator"=>"required:true","messages"=>"请选择"]);?>
                          
                    </div>
                </td>
            </tr>
            
           



          


        </table>
    </form>
</div>

<!--选择框快捷输入过滤-->
<script src="/js/jquery.editable-select.min.js"></script>
<script type="text/javascript">

$(function(){
     var validator = $("#fModi").validate({
            ignore:[],
            rules: {
                saletype:{
                    required:true
                },
                discount:{
                    required:true
                },
                prouctprice:{
                    required:true
                },
                bullamount:{
                    required:true
                },
                productname:{
                    required:true
                },
                businesscategoryid:{
                    required:true
                },
                supplyprice: {
                    number:true,
                    required:true
                },
                saleprice: {
                    number:true,
                    required:true
                }, 
                images: {
                    required: true
                },
                thumb:{
                    required: true
                },
                photos:{
                    required:true
                },
                description:{
                    required:true
                }
            },
            messages: {

                saletype:{
                    required:'请选择商品类型'
                },
                discount:{
                    required:'折扣不能为空'
                },
               // prouctprice:{
                 //   required:'商品价格不能为空'
               // },
               // bullamount:{
                //    required:'牛豆不能为空'
               // },
                productname:{
                    required:'商品名称不能为空'
                },
                businesscategoryid:{
                    required:'请选择商家分类'
                },
               // supplyprice: {
                 //   number: "供货价格只能是数字",
                  //  required:'供货价格不能为空'
               // },
               // saleprice: {
                 //   number: "销售价格只能是数字",
                  //  required:'销售价格不能为空'
               // },  
                images: {
                    required: "商品缩略图必须上传"
                },
                thumb:{
                    required: "商品图片不能为空"
                },
                photos:{
                    required:'商品图片必须上传'
                },
                description:{
                    required:'商品描述不能为空'
                }
                
            }
            
        });


    $("#special input[type=radio]").click(function(){
        //inline-block
        if($(this).val() != 0){
            $("#specialpic").css("display", "");
            $("#f_specialpic").attr("class", "required");
        }else{
            console.log(validator.rules);
            $("#specialpic").css("display", "none");
        }
    });


    //年度结算周期
    var settle_cycle = $('input:radio[name="saletype"]:checked').val();

    // if(settle_cycle == 2){
    //     $("#cash_input").show();
    // }else{
    //     $("#cash_input").hide();
    // }


    $(".settle_cycle").click(function(){
         //供货价
        var supplyprice = $('input:text[name="supplyprice"]').val();
        //销售价
        var saleprice = $('input:text[name="saleprice"]').val();
        //销售方式 1现金 2现金+牛豆
        var settle_cycle = $('input:radio[name="saletype"]:checked').val();


        if(supplyprice > 0 && saleprice >0 && settle_cycle>0){
            $.ajax({
                type:'POST',
                data: {supplyprice:supplyprice,saleprice:saleprice,settle_cycle:settle_cycle},
                async:false, 
                traditional :true,
                dataType:'json',
                url:"/Product/Index/formatPrice",
                success:function(res){
                    console.log(res);
                    $('input:text[name="prouctprice"]').val(res.prouctprice);
                    $('input:text[name="discount"]').val(res.discount);
                    $('input:text[name="bullamount"]').val(res.bullamount);
                    $("#msg").html(res.msg);
                }
            });
        }
       
        // if(settle_cycle == 2){
        //     $("#cash_input").show();
        // }else{
        //     $("#cash_input").hide();
        // }
    });

    $('input:text[name="supplyprice"]').blur(function(){
        //供货价
        var supplyprice = $('input:text[name="supplyprice"]').val();
        //销售价
        var saleprice = $('input:text[name="saleprice"]').val();
        //销售方式 1现金 2现金+牛豆
        var settle_cycle = $('input:radio[name="saletype"]:checked').val();


        if(supplyprice > 0 && saleprice >0 && settle_cycle>0){
            $.ajax({
                type:'POST',
                data: {supplyprice:supplyprice,saleprice:saleprice,settle_cycle:settle_cycle},
                async:false, 
                traditional :true,
                dataType:'json',
                url:"/Product/Index/formatPrice",
                success:function(res){
                    console.log(res);
                    $('input:text[name="prouctprice"]').val(res.prouctprice);
                    $('input:text[name="discount"]').val(res.discount);
                    $('input:text[name="bullamount"]').val(res.bullamount);
                    $("#msg").html(res.msg);
                }
            });
        }

    });

    $('input:text[name="saleprice"]').blur(function(){
        //供货价
        var supplyprice = $('input:text[name="supplyprice"]').val();
        //销售价
        var saleprice = $('input:text[name="saleprice"]').val();
         //销售方式 1现金 2现金+牛豆
        var settle_cycle = $('input:radio[name="saletype"]:checked').val();
        if(supplyprice > 0 && saleprice >0 && settle_cycle>0){
            $.ajax({
                type:'POST',
                data: {supplyprice:supplyprice,saleprice:saleprice,settle_cycle:settle_cycle},
                async:false, 
                traditional :true,
                dataType:'json',
                url:"/Product/Index/formatPrice",
                success:function(res){
                    console.log(res);
                    $('input:text[name="prouctprice"]').val(res.prouctprice);
                    $('input:text[name="discount"]').val(res.discount);
                    $('input:text[name="bullamount"]').val(res.bullamount);
                    $("#msg").html(res.msg);
                }
            });
        }
    });
});



//$j = jQuery.noConflict();
$('#f_brandname').editableSelect({
    effects: 'slide'
},"f_brandid");




</script>
