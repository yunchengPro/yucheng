{include file="Pub/header" /}
{include file="Product/Index/editstep" /}


<script type="text/javascript">

// 按规格存储规格值数据
var spec_group_checked = [<?php for ($i=0; $i<$sign_i; $i++){if($i+1 == $sign_i){echo "''";}else{echo "'',";}}?>];

var str = '';
var V = new Array();

<?php for ($i=0; $i<$sign_i; $i++){?>
var spec_group_checked_<?php echo $i;?> = new Array();
<?php }?>


$('div[nctype="spec_group_dl"]').on('click', 'span[nctype="input_checkbox"] > input[type="checkbox"]',function(){
    into_array();
    //alert(11);
    goods_stock_set();
});

var count_sign = <?php echo $sign_i>0?$sign_i:0;?>;
var specarr = new Array();
<?php
$tmp_count = 0;
foreach($spec as $k=>$val){
    echo "specarr[".$tmp_count."]='".$val['specname']."';\n";
    $tmp_count++;
}
?>

// 将选中的规格放入数组
function into_array(){

<?php for ($i=0; $i<$sign_i; $i++){?>
        
    spec_group_checked_<?php echo $i;?> = new Array();
    $('div[nc_type="spec_group_dl_<?php echo $i;?>"]').find('input[type="checkbox"]:checked').each(function(){
        i = $(this).attr('nc_type');
        v = $(this).val();
        c = null;
        if ($(this).parents('div:first').attr('spec_img') == 't') {
            c = 1;
        }
        spec_group_checked_<?php echo $i;?>[spec_group_checked_<?php echo $i;?>.length] = [v,i,c];
    });

    spec_group_checked[<?php echo $i;?>] = spec_group_checked_<?php echo $i;?>;

<?php }?>

}


function getSpecHead(){
    var headstr = '';
    var selectspec = new Array();
    //找出哪些规格是勾选了的
    var count_td = 0;
    headstr+='<th width="3%">序号</th>\n';
    console.log('spec_group_checked');
    console.log(spec_group_checked);
    for(var i=0;i<count_sign;i++){
        //console.log(spec_group_checked[i].length);
        if(spec_group_checked[i].length>0){
            selectspec[count_td] = i;
            count_td++;
        }
    }
    console.log('selectspec');
    console.log(specarr);
    for(var i=0;i<selectspec.length;i++){
        headstr+='<th >'+specarr[selectspec[i]]+'</th>\n';
    }

    headstr+='<th >供货价格</th>\n';
    headstr+='<th >销售价格</th>\n';
    headstr+='<th >折扣</th>\n';
    headstr+='<th >现金价格</th>\n';
    headstr+='<th >牛豆数量</th>\n';
    headstr+='<th >条形码</th>\n';
    headstr+='<th >商品货号</th>\n';
    headstr+='<th >库存</th>\n';

    headstr+='<th >图片</th>\n';
    return headstr;
}

function getSpecTable(){
    //count_sign 为几种规格
    var choose = new Array();
    var count = 0;
    var selectspec = new Array();
    //console.log("count_sign:"+count_sign);

    //找出哪些规格是勾选了的
    var count_td = 0;
    for(var i=0;i<count_sign;i++){
        if(spec_group_checked[i].length>0){
            selectspec[count_td] = spec_group_checked[i];
            count_td++;
        }
    }

    var speclist = new Array();

    speclist = getSpecList(0,selectspec,new Array());
    var str = '';
    //var strimg='';
    var supplyprice = $("#supplyprice").val();
    var saleprice   = $("#saleprice").val();
    var prouctprice = $("#prouctprice").val();
    var bullamount  = $("#bullamount").val(); 
    var discount    = $("#discount").val();

    if(speclist.length>0){

        for(var i=0;i<speclist.length;i++){
            str+="";
            var spec_bunch = 'i';
            for(var j=0;j<speclist[i].length;j++){
                spec_bunch += "_"+speclist[i][j][1];
            }
            str += '<input type="hidden" name="spec['+spec_bunch+'][goods_id]" nc_type="'+spec_bunch+'|id" value="" />';
            //序号
            str +='<td>'+(i+1)+'</td>';

            for(var j=0;j<speclist[i].length;j++){
                str+="<td>";
                str+='<input type="hidden" name="spec['+spec_bunch+'][sp_value]['+speclist[i][j][1]+']" value="'+speclist[i][j][0]+'" />';
                str+= '<input type="hidden" name="spec['+spec_bunch+'][color]" value="'+speclist[i][j][1]+'" />';
                str+=speclist[i][j][0];
                str+="</td>";
            }
          
          
            str +='<td><input class="input-text width250 supplyprice" style="width:90px;" type="text" name="spec['+spec_bunch+'][supplyprice]" data_type="supplyprice" nc_type="'+spec_bunch+'|supplyprice" onblur="supplyFormatPrice($(this))" value="'+supplyprice+'" /></td>';

            str +='<td><input class="input-text width250 saleprice" style="width:90px;" type="text" name="spec['+spec_bunch+'][saleprice]" data_type="saleprice" nc_type="'+spec_bunch+'|saleprice" onblur="saleFormatPrice($(this))" value="'+saleprice+'" /></td>';

            str +='<td><input class="input-text width250 discount" style="width:90px;" type="text" name="spec['+spec_bunch+'][discount]" data_type="discount" nc_type="'+spec_bunch+'|discount"  readonly="true" value="'+discount+'" /></td>';

            str +='<td><input class="input-text width250 prouctprice" style="width:90px;" type="text" name="spec['+spec_bunch+'][prouctprice]" data_type="prouctprice" nc_type="'+spec_bunch+'|prouctprice" readonly="true" value="'+prouctprice+'" /></td>';

            str +='<td><input class="input-text width250 bullamount" style="width:90px;" type="text" name="spec['+spec_bunch+'][bullamount]" data_type="bullamount" nc_type="'+spec_bunch+'|bullamount"  readonly="true" value="'+bullamount+'" /></td>';

         

            str +='<td><input class="input-text width250 barcode" style="width:90px;" type="text" name="spec['+spec_bunch+'][barcode]" data_type="barcode" nc_type="'+spec_bunch+'|barcode" value="" /></td>';

            str +='<td><input class="input-text width250 serialnumber" style="width:90px;" type="text" name="spec['+spec_bunch+'][serialnumber]" data_type="serialnumber" nc_type="'+spec_bunch+'|serialnumber" value="" /></td>';
            
            str +='<td><input class="input-text width250 productstorage" style="width:90px;" type="text" name="spec['+spec_bunch+'][productstorage]" data_type="productstorage" nc_type="'+spec_bunch+'|productstorage" value="" /></td>';
            
            str+='<td><div class="formControls col-xs-9 col-sm-8"><span name="spec['+spec_bunch+'][image_upload]" id="spec-'+spec_bunch+'-image_upload" class="btn btn-danger fileinput-button"><span>添加图片</span><input type="file" multiple="" name="file"></span>&nbsp;<font>(文件数：1；文件大小：4M)</font><div style="margin-top:15px;" class="uploadfiles" id="uploadfiles_spec-'+spec_bunch+'-image"><input class="text input image" style="width:90px;" type="hidden" name="spec['+spec_bunch+'][image_upload]" id="spec-'+spec_bunch+'-image" data_type="image" nc_type="'+spec_bunch+'|image" value="" /></div><script type="text/javascript">fileupload(\'spec-'+spec_bunch+'-image\',\'{"url":"/uploadfile/jqueryfileupload.php","domain":"//nnhtest.oss-cn-shenzhen.aliyuncs.com/","maxFileSize":4194304,"maxNumberOfFiles":"1","savefileurl":"/Sys/upload/getfile","getParamUrl":"/Sys/upload/policy","server_type":"NNH/images","formData":{"server_type":"NNH/images"}}\');<\/script>（建议尺寸:200pxX200px）</div></td>';

            str +='</tr>\n';
        }  
    }

    return str;
}

function showobj(obj){
    var str="";
    str+="length:"+obj.length+"\n";
    for(var i=0;i<obj.length;i++){
        str+="["+i+"]="+obj[i]+"\n";
    }
    return str;
}

function getSpecList(index,data,t){
    var ret     = new Array();
    var tttt     = new Array();
    tttt = t.concat();

   
    for(var i=index;i<data.length;i++){
       
        for(var j=0;j<data[i].length;j++){
            tttt[tttt.length] = data[i][j];
            var aa = getSpecList(i+1,data,tttt);
            if(aa.length>0){
                for(var l=0;l<aa.length;l++){
                    ret[ret.length]=aa[l];
                }
                tttt = new Array(); 
                tttt=t.concat();
               
            }else{
               
                if(data.length==tttt.length){

                    var ret_length  = ret.length;
                    ret[ret_length] = new Array();
                    
                    for(var ct=0;ct<tttt.length;ct++){
                        var tmp_len = ret[ret_length].length;
                        
                        ret[ret_length][tmp_len]=tttt[ct];
                    }
                   
                }

                tttt = new Array();  
                tttt =t.concat();
               
            }
           
        }
        
        tttt = new Array();
    }
    return ret;
}

//生成库存配置
function goods_stock_set(){
    //  店铺价格 商品库存改为只读
    //$('input[name="g_price"]').attr('readonly','readonly').css('background','#E7E7E7 none');
    //$('input[name="g_storage"]').attr('readonly','readonly').css('background','#E7E7E7 none');
    
    $('tr[nc_type="spec_dl"]').show();
    
    //console.log(1111111111);
    //alert(12333);
    str = '<tr>';
    var spectablestr = getSpecTable();
    str+=spectablestr;
    
    if(str == '<tr>'){
        //  店铺价格 商品库存取消只读
        $('input[name="f_productprice"]').removeAttr('readonly').css('background','');
        $('input[name="f_productstorage"]').removeAttr('readonly').css('background','');
        $('tr[nc_type="spec_dl"]').hide();
    }else{
        //获取头部
        $('thead[nc_type="spec_thead"]').empty().html(getSpecHead());
        $('tbody[nc_type="spec_table"]').empty().html(str)
            .find('input[nc_type]').each(function(){
                s = $(this).attr('nc_type');
               // try{$(this).val(V[s]);}catch(ex){$(this).val('');};
                if($(this).attr('data_type') == 'price' && $(this).val() == ''){
                    $(this).val($('input[name="f_productprice"]').val());
                    //$(this).attr("class",);
                }
                if($(this).attr('data_type') == 'stock' && $(this).val() == ''){
                    $(this).val('0');
                }
            }).end()
            .find('input[nc_type]').change(function(){
                s = $(this).attr('nc_type');
                V[s] = $(this).val();
            });
    }
    
    
}









//  编辑商品时处理JS
$(function(){
    var E_SP = new Array();
    var E_SPV = new Array();

  

    $('div[nctype="spec_group_dl"]').find('input[type="checkbox"]').each(function(){
        s = $(this).attr('nc_type');
        if (!(typeof(E_SP[s]) == 'undefined')){
            $(this).attr('checked',true);
            v = $(this).parents('li').find('span[nctype="pv_name"]');
            if(E_SP[s] != ''){
                $(this).val(E_SP[s]);
            }else{
                v.html('<input type="text" maxlength="20" value="'+v.html()+'" />');
            }
            
        }

    });

    into_array();   // 将选中的规格放入数组

    goods_stock_set();

    str = '<tr>';
    var spectablestr = getSpecTable();
    str+=spectablestr;

    //console.log("1111"+str);

    $('tbody[nc_type="spec_table"]').empty().html(str)
        .find('input[nc_type]').each(function(){
            s = $(this).attr('nc_type');
            try{$(this).val(E_SPV[s]);}catch(ex){$(this).val('');};
        }).end()
        .find('input[data_type="stock"]').change(function(){
            computeStock();    // 库存计算
        }).end()
        .find('input[data_type="price"]').change(function(){
            computePrice();     // 价格计算
        }).end()
        .find('input[data_type="marketprice"]').change(function(){
            computeMarketPrice();     // 市场价格计算
        }).end()
        .find('input[data_type="costprice"]').change(function(){
            computeCostpricePrice();     // 成本价格计算
        }).end()
        .find('input[type="text"]').change(function(){
            s = $(this).attr('nc_type');
            V[s] = $(this).val();
        });




});





</script>

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



{include file="Pub/footer" /}


<script type='text/javascript'>

    function supplyFormatPrice(o){
        
        var  tmp_supplyprice = $("input[name='supplyprice']").val();//供货价
        var  tmp_saleprice = $("input[name='saleprice']").val();//销售价
        var  tmp_productstorage = $("input[name='productstorage']").val();//商品库存

        var supplyprice = o.val();
        //alert(supplyprice);
        var saleprice =  o.parent().parent().find($(".saleprice")).val();
        //alert(saleprice);
        //销售方式 1现金 2现金+牛豆
        var settle_cycle = $('input:radio[name="saletype"]:checked').val();
        //alert(settle_cycle);
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
                    o.parent().parent().find($(".prouctprice")).val(res.prouctprice);
                    o.parent().parent().find($(".discount")).val(res.discount);
                    o.parent().parent().find($(".prouctprice")).val(res.prouctprice);
                    o.parent().parent().find($(".bullamount")).val(res.bullamount);
                    // $('input:text[name="prouctprice"]').val(res.prouctprice);
                    // $('input:text[name="discount"]').val(res.discount);
                    // $('input:text[name="bullamount"]').val(res.bullamount);
                    // $("#msg").html(res.msg);
                }
            });
        }

         $(".supplyprice").each(function(){
            if($(this).val() < parseFloat(tmp_supplyprice)){
                tmp_supplyprice = $(this).val();

                tmp_saleprice = $(this).parent().parent().find("td input[data_type='saleprice']").val();
                tmp_productstorage = $(this).parent().parent().find("td input[data_type='saleprice']").val();
              
            }
        });
        
     

        $("input[name='supplyprice']").val(tmp_supplyprice);//供货价
        $("input[name='saleprice']").val(tmp_saleprice);//销售价
        $("input[name='productstorage']").val(tmp_saleprice);//库存

         //alert(settle_cycle);
        if(tmp_supplyprice > 0 && tmp_saleprice >0 && settle_cycle>0){
            $.ajax({
                type:'POST',
                data: {supplyprice:tmp_supplyprice,saleprice:tmp_saleprice,settle_cycle:settle_cycle},
                async:false, 
                traditional :true,
                dataType:'json',
                url:"/Product/Index/formatPrice",
                success:function(res){
                    console.log(res);
                   
                 
                    $("input[name='discount']").val(res.discount);
                    $("input[name='prouctprice']").val(res.prouctprice);
                    $("input[name='bullamount']").val(res.bullamount);
                    // $('input:text[name="prouctprice"]').val(res.prouctprice);
                    // $('input:text[name="discount"]').val(res.discount);
                    // $('input:text[name="bullamount"]').val(res.bullamount);
                    // $("#msg").html(res.msg);
                }
            });
        }
    }

    function saleFormatPrice(o){
            //alert(1);
            var  tmp_supplyprice = $("input[name='supplyprice']").val();//供货价
            var  tmp_saleprice = $("input[name='saleprice']").val();//销售价
            var  tmp_productstorage = $("input[name='productstorage']").val();//商品库存

            var saleprice = o.val();
            //alert(saleprice);
            var supplyprice =  o.parent().parent().find($(".supplyprice")).val();
            //alert(supplyprice);
            //销售方式 1现金 2现金+牛豆
            var settle_cycle = $('input:radio[name="saletype"]:checked').val();
            //alert(settle_cycle);

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
                        o.parent().parent().find($(".prouctprice")).val(res.prouctprice);
                        o.parent().parent().find($(".discount")).val(res.discount);
                        o.parent().parent().find($(".prouctprice")).val(res.prouctprice);
                        o.parent().parent().find($(".bullamount")).val(res.bullamount);
                        // $('input:text[name="prouctprice"]').val(res.prouctprice);
                        // $('input:text[name="discount"]').val(res.discount);
                        // $('input:text[name="bullamount"]').val(res.bullamount);
                        // $("#msg").html(res.msg);
                    }
                });
            }

            $(".supplyprice").each(function(){
            if($(this).val() < parseFloat(tmp_supplyprice)){
                tmp_supplyprice = $(this).val();

                tmp_saleprice = $(this).parent().parent().find("td input[data_type='saleprice']").val()
               
                tmp_productstorage = $(this).parent().parent().find("td input[data_type='saleprice']").val();
            }
        });
        
     
       
        $("input[name='supplyprice']").val(tmp_supplyprice);//供货价
        $("input[name='saleprice']").val(tmp_saleprice);//销售价
        $("input[name='productstorage']").val(tmp_saleprice);//库存
         
         //alert(settle_cycle);
        if(tmp_supplyprice > 0 && tmp_saleprice >0 && settle_cycle>0){
         
            $.ajax({
                type:'POST',
                data: {supplyprice:tmp_supplyprice,saleprice:tmp_saleprice,settle_cycle:settle_cycle},
                async:false, 
                traditional :true,
                dataType:'json',
                url:"/Product/Index/formatPrice",
                success:function(res){
                  
                    $("input[name='discount']").val(res.discount);
                    $("input[name='prouctprice']").val(res.prouctprice);
                    $("input[name='bullamount']").val(res.bullamount);
                    // $('input:text[name="prouctprice"]').val(res.prouctprice);
                    // $('input:text[name="discount"]').val(res.discount);
                    // $('input:text[name="bullamount"]').val(res.bullamount);
                    // $("#msg").html(res.msg);
                }
            });
        }
    }

  

</script>


