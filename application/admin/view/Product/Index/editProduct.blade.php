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


//生成库存配置
function goods_stock_set_old(){
    //  店铺价格 商品库存改为只读
    //$('input[name="g_price"]').attr('readonly','readonly').css('background','#E7E7E7 none');
    //$('input[name="g_storage"]').attr('readonly','readonly').css('background','#E7E7E7 none');
    
    $('tr[nc_type="spec_dl"]').show();

    str = '<tr>';
    <?php recursionSpec(0,$sign_i,"saveedit");?>
    
    if(str == '<tr>'){
        //  店铺价格 商品库存取消只读
        $('input[name="f_productprice"]').removeAttr('readonly').css('background','');
        $('input[name="f_productstorage"]').removeAttr('readonly').css('background','');
        //
        $('tr[nc_type="spec_dl"]').hide();
    }else{

        $('tbody[nc_type="spec_table"]').empty().html(str)
            .find('input[nc_type]').each(function(){
                s = $(this).attr('nc_type');
                try{$(this).val(V[s]);}catch(ex){$(this).val('');};
            }).end()
            .find('img[nc_type]').each(function(){
                s = $(this).attr('nc_type');
                try{
                    
                    if(typeof(V[s]) != 'undefined' && V[s] !=''){
                      
                        $(this).attr('src',"//nnhtest.oss-cn-shenzhen.aliyuncs.com/"+V[s]);
                    }else{
                       
                        $(this).parent().remove();
                        
                    }
                    //$(this).val(E_SPV[s]);

                }catch(ex){$(this).val('');};
            }).end()
            .find('span[nc_type]').each(function(){
                s = $(this).attr('nc_type');
                try{
                    $(this).text(V[s]);
                    //$(this).val(E_SPV[s]);

                }catch(ex){$(this).val('');};
            })
    }
    
    
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
        //alert(sort_key("i_9_10_20_3|price"));
        
        

        /*$('tbody[nc_type="spec_table"]').find('input[nc_type]').each(function(){
            s = $(this).attr('nc_type');
            s = sort_key(s);
            V[s] = $(this).val();
        });*/


        var tmp_V = new Array();
        var tmpa_V = new Array();
        //console.log(V);
       
        $('tbody[nc_type="spec_table"]').empty().html(str)
            .find('input[nc_type]').each(function(){
                s = $(this).attr('nc_type');
                s = sort_key(s);
                
                //console.log(V);
                //try{$(this).val(V[s]);}catch(ex){$(this).val('');};
                //console.log(s);
                for(k_s in V){
                    var tmp_k_s = k_s.split("|");
                    var tmp_s = s.split("|");
                    tmp_s[0] = tmp_s[0]+"_";
                    //console.log(k_s+"###"+s);

                    if(tmp_k_s[1]==tmp_s[1]){

                        var tmp_k_s_t = tmp_k_s[0].split("_");
                        
                        var flag = true;
                        for(var i=0;i<tmp_k_s_t.length;i++){
                            //console.log(tmp_s[0]+"###"+tmp_k_s_t[i]+"_");
                            if(tmp_s[0].indexOf(tmp_k_s_t[i]+"_")<0){
                                flag = false;
                                break;
                            }
                        }
                        

                        if(flag && (typeof(tmp_V[k_s])=='undefined' || tmp_V[k_s]=='' )){
                            //console.log("==="+tmp_V[k_s]+"===="+V[k_s]+"===");
                            $(this).val(V[k_s]);
                            tmp_V[k_s] = V[k_s];
                            break;
                        }
                    } 
                }

                /*if(typeof V[s]!='undefined')
                    $(this).val(V[s]);
                else
                    $(this).val('');*/

                if($(this).attr('data_type') == 'price' && $(this).val() == ''){
                    $(this).val($('input[name="productprice"]').val());
                    //$(this).attr("class",);
                }
                if($(this).attr('data_type') == 'stock' && $(this).val() == ''){
                    $(this).val('0');
                }

            }).end()
            .find('input[nc_type]').change(function(){
                s = $(this).attr('nc_type');
                s = sort_key(s);
                V[s] = $(this).val();
            }).end()
            .find('img[nc_type]').each(function(){
                s = $(this).attr('nc_type');
                s = sort_key(s);
               
                for(k_s in V){
                    var tmp_k_s = k_s.split("|");
                    var tmp_s = s.split("|");
                    tmp_s[0] = tmp_s[0]+"_";
                    //console.log(k_s+"###"+s);

                    if(tmp_k_s[1]==tmp_s[1]){

                        var tmp_k_s_t = tmp_k_s[0].split("_");
                        
                        var flag = true;
                        for(var i=0;i<tmp_k_s_t.length;i++){
                            //console.log(tmp_s[0]+"###"+tmp_k_s_t[i]+"_");
                            if(tmp_s[0].indexOf(tmp_k_s_t[i]+"_")<0){
                                flag = false;
                                break;
                            }
                        }
                        

                        if(flag && (typeof(tmpa_V[k_s])=='undefined' || tmpa_V[k_s]=='' )){
                            //console.log("==="+tmp_V[k_s]+"===="+V[k_s]+"===");
                            
                           
                            if(typeof(V[k_s]) != 'undefined' && V[k_s] !=''){
                  
                                $(this).attr('src',"//nnhtest.oss-cn-shenzhen.aliyuncs.com/"+V[k_s]);
                            }else{
                               
                                $(this).parent().remove();
                                
                            }
                            tmpa_V[k_s] = V[k_s];
                            break;
                        }
                    } 
                }

           
            });

        //console.log("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
    }
    
    
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


function getSpecHead(){
    var headstr = '';
    var selectspec = new Array();
    //找出哪些规格是勾选了的
    var count_td = 0;
    headstr+='<th width="3%">序号</th>\n';
    //console.log('spec_group_checked');
    //console.log(spec_group_checked);
    for(var i=0;i<count_sign;i++){
        //console.log(spec_group_checked[i].length);
        if(spec_group_checked[i].length>0){
            selectspec[count_td] = i;
            count_td++;
        }
    }
    //console.log('selectspec');
    //console.log(specarr);
    for(var i=0;i<selectspec.length;i++){
        headstr+='<th >'+specarr[selectspec[i]]+'</th>\n';
    }

    headstr+='<th >供货价格(<font color=red>必填</font>)</th>\n';
    headstr+='<th >销售价格(<font color=red>必填</font>)</th>\n';
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
    
    //找出哪些规格是勾选了的
    var count_td = 0;
    for(var i=0;i<count_sign;i++){
        if(spec_group_checked[i].length>0){
            selectspec[count_td] = spec_group_checked[i];
            count_td++;
        }
    }
    //console.log("selectspec:"+selectspec);

    var speclist = new Array();

    speclist = getSpecList(0,selectspec,new Array());

    //console.log("speclist:");
    console.log(speclist);

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
            //str+='<td><div class="formControls col-xs-9 col-sm-8"><span name="spec['+spec_bunch+'][image_upload]" id="spec-'+spec_bunch+'-image_upload" class="btn btn-danger fileinput-button"><span>添加图片</span><input type="file" multiple="" name="file"></span>&nbsp;<font>(文件数：1；文件大小：4M)</font><div style="margin-top:15px;" class="uploadfiles" id="uploadfiles_spec-'+spec_bunch+'-image"><input class="text input image" style="width:90px;" type="hidden" name="spec['+spec_bunch+'][image_upload]" id="spec-'+spec_bunch+'-image" data_type="image" nc_type="'+spec_bunch+'|image" value="" /></div><script type="text/javascript">fileupload(\'spec-'+spec_bunch+'-image\',\'{"url":"/uploadfile/jqueryfileupload.php","domain":"//nnhtest.oss-cn-shenzhen.aliyuncs.com/","maxFileSize":4194304,"maxNumberOfFiles":"1","savefileurl":"/Sys/upload/getfile","getParamUrl":"/Sys/upload/policy","server_type":"NNH/images","formData":{"server_type":"NNH/images"}}\');<\/script>（建议尺寸:200pxX200px）</div></td>';

            str +='<td><div class=\"formControls col-xs-9 col-sm-8\"><span class=\"btn btn-danger fileinput-button\" id=\"spec-'+spec_bunch+'-image_upload\" name=\"spec['+spec_bunch+'][image_upload]\"><span>添加图片</span><input type=\"file\" name=\"file\" multiple=\"\"></span>&nbsp;<font>(文件数：1,上传多张只会保存最新的；文件大小：4M)</font><div id=\"uploadfiles_spec-'+spec_bunch+'-image\" class=\"uploadfiles\" style=\"margin-top:15px;\"><input type=\"hidden\" id=\"spec-'+spec_bunch+'-image\" name=\"spec['+spec_bunch+'][image]\" nc_type=\"'+spec_bunch+'|image\" value=\"\"><div class=\"filediv_spec-'+spec_bunch+'-image\" id=\"file_spec-'+spec_bunch+'-image_1327\" style=\"float: left; width: 100px; margin-right: 10px;\"><div><img id=\"spec-'+spec_bunch+'-image_show\" nc_type=\"'+spec_bunch+'|image\" src=\"\" width=\"100px\"></div><div style=\"text-align:center;\"></div><span id=\"url\" nc_type=\"'+spec_bunch+'|image\" style=\"display:none\"></span></div></div><script type=\"text/javascript\">fileupload(\'spec-'+spec_bunch+'-image\',\'{\"url\":\"/uploadfile/jqueryfileupload.php\",\"domain\":\"//nnhtest.oss-cn-shenzhen.aliyuncs.com/\",\"maxFileSize\":4194304,\"maxNumberOfFiles\":\"4\",\"savefileurl\":\"/Sys/upload/getfile\",\"getParamUrl\":\"/Sys/upload/policy\",\"server_type\":\"NNH/images\",\"formData\":{\"server_type\":\"NNH/images\"}}\');<\/script>（建议尺寸:200pxX200px）</div></td></tr>';

            str +='</tr>\n';
        }  
    }

    return str;
}






<?php if (!empty($productinfo) && !empty($sp_value) && !empty($spec_checked)){?>
//  编辑商品时处理JS
$(function(){
    var E_SP = new Array();
    var E_SPV = new Array();
    <?php
    $strings = '';
    foreach ($spec_checked as $v) {
        $strings .= "E_SP[".$v['id']."] = '".$v['name']."';";
    }
    //把js输出的 php 格式化代码替换2为空 以达到输出原生j效果
    $strings =  str_replace('<?php', '', $strings);
    $strings = str_replace(' echo ', '', $strings);
    echo   str_replace('; ?>','',$strings);
    echo "\n";
    $string = '';
    foreach ($sp_value as $k=>$v) {
        
        //重组键名
        $tmp = explode("|",$k);
        //$tmp[0] = "i_4_10_20_9";
        $tmp1 = explode("_",$tmp[0]);
        $profit = $tmp1[0];
        unset($tmp1[0]);

        //排序
        sort($tmp1);
       
        $tmp2 = $profit."_";
        foreach($tmp1 as $tmpv){
            $tmp2.=$tmpv."_";
        }
        $tmp2 = substr($tmp2,0,-1);
        $k = $tmp2."|".$tmp[1];

        $string .= "E_SPV['{$k}'] = '{$v}';";
    }
     //把js输出的 php 格式化代码替换2为空 以达到输出原生j效果
    $string =  str_replace('<?php', '', $string);
    $string =  str_replace(' echo ', '', $string);
    echo   str_replace('; ?>','',$string);
    ?>
 
    V = E_SPV;
    $('tr[nc_type="spec_dl"]').show();
    $('div[nctype="spec_group_dl"]').find('input[type="checkbox"]').each(function(){

        //  店铺价格 商品库存改为只读
        $('input[name="f_productprice"]').attr('readonly','readonly').css('background','#E7E7E7 none');
        $('input[name="f_productstorage"]').attr('readonly','readonly').css('background','#E7E7E7 none');
        s = $(this).attr('nc_type');
        if (!(typeof(E_SP[s]) == 'undefined')){
            $(this).attr('checked',true);
            v = $(this).parents('li').find('span[nctype="pv_name"]');
            if(E_SP[s] != ''){
                $(this).val(E_SP[s]);
                //v.html('<input type="text" maxlength="20" value="'+E_SP[s]+'" />');
            }else{
                v.html('<input type="text" maxlength="20" value="'+v.html()+'" />');
            }
            //change_img_name($(this));           // 修改相关的颜色名称
        }
    });

    into_array();   // 将选中的规格放入数组
    str = '<tr>';
    //console.log(str);
    //alert(spec_group_checked[0].length);
    <?php //recursionSpec(0,$sign_i,"saveedit");?>
    /*
    //console.log(str);
    if(str == '<tr>'){
        //console.log("========11");
        $('tr[nc_type="spec_dl"]').hide();
        $('input[name="f_productprice"]').removeAttr('readonly').css('background','');
        $('input[name="f_productstorage"]').removeAttr('readonly').css('background','');
    }else{
        $('tbody[nc_type="spec_table"]').empty().html(str)
            .find('input[nc_type]').each(function(){
                s = $(this).attr('nc_type');
                try{$(this).val(E_SPV[s]);}catch(ex){$(this).val('');};
            }).end()
            .find('img[nc_type]').each(function(){
                s = $(this).attr('nc_type');
                try{
                     
                   if(typeof(V[s]) != 'undefined' && V[s] !=''){
                       
                        $(this).attr('src',"//nnhtest.oss-cn-shenzhen.aliyuncs.com/"+V[s]);
                   }else{
                        
                        $(this).parent().remove();
                   }
                    //$(this).val(E_SPV[s]);

                }catch(ex){$(this).val('');};
            }).end()
            .find('span[nc_type]').each(function(){
                s = $(this).attr('nc_type');
                try{
                    $(this).text(V[s]);
                    //$(this).val(E_SPV[s]);

                }catch(ex){$(this).val('');};
            });
    }*/
});
<?php }?>



</script>



<?php


function recursionSpec_new($len,$sign,$action) {

    for($len=0;$len<$sign;$len++){
        echo "td_".(intval($len)+1)." = spec_group_checked[".$len."][i_".$len."];\n";
    }

    for($len=0;$len<$sign;$len++){
        
        echo "for (var i_".$len."=0; i_".$len."<spec_group_checked[".$len."].length; i_".$len."++){\n";
        
        echo "var tmp_spec_td = new Array();\n";
        for($i=0; $i< $len; $i++){ 
            echo "if(typeof td_".($i+1)."!='undefined')";
            echo "tmp_spec_td[".($i)."] = td_".($i+1)."[1];\n";
        }
        
        echo "tmp_spec_td.sort(function(a,b){return a-b});\n";
        echo "var spec_bunch = 'i_';\n";
        for($i=0; $i< $len; $i++){
            if($i == ($len - 1)){
                echo "spec_bunch += tmp_spec_td[".($i)."];\n";
            }else{
                echo "spec_bunch += tmp_spec_td[".($i)."] + '_';\n";
            }
            
        }

        echo "str += '<input type=\"hidden\" id=\"spec['+spec_bunch+'][goods_id]\" name=\"spec['+spec_bunch+'][goods_id]\" nc_type=\"'+spec_bunch+'|id\" value=\"\" />';";

        for($i=0; $i< $len; $i++){
            echo "if(typeof td_".($i+1)."!='undefined'){";
            echo "if (td_".($i+1)."[2] != null) { str += '<input type=\"hidden\" name=\"spec['+spec_bunch+'][color]\" value=\"'+td_".($i+1)."[1]+'\" />';}";
            echo "str +='<td ><input type=\"hidden\" name=\"spec['+spec_bunch+'][sp_value]['+td_".($i+1)."[1]+']\" value=\"'+td_".($i+1)."[0]+'\" /><label style=\"width:90px;\">'+td_".($i+1)."[0]+'</label></td>';\n";
            echo "}";
        }
        

        echo "str +='<td><input class=\"input-text width250 supplyprice\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][supplyprice]\" data_type=\"supplyprice\" nc_type=\"'+spec_bunch+'|supplyprice\" value=\"\"  onblur=\"supplyFormatPrice($(this))\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 saleprice\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][saleprice]\" data_type=\"saleprice\" nc_type=\"'+spec_bunch+'|saleprice\" onblur=\"saleFormatPrice($(this))\" value=\"\" /></td>';\n";
        
        echo "str +='<td><input class=\"input-text width250 discount\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][discount]\" data_type=\"discount\" nc_type=\"'+spec_bunch+'|discount\" value=\"\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 prouctprice\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][prouctprice]\" data_type=\"prouctprice\" nc_type=\"'+spec_bunch+'|prouctprice\" readonly=\"true\" value=\"\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 bullamount\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][bullamount]\" data_type=\"bullamount\" nc_type=\"'+spec_bunch+'|bullamount\" readonly=\"true\" value=\"\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 productstorage\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][productstorage]\" data_type=\"productstorage\" nc_type=\"'+spec_bunch+'|productstorage\" value=\"\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 barcode\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][barcode]\" data_type=\"barcode\" nc_type=\"'+spec_bunch+'|barcode\" value=\"\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 serialnumber\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][serialnumber]\" data_type=\"serialnumber\" nc_type=\"'+spec_bunch+'|serialnumber\" value=\"\" /></td>';\n";

        //图片
        echo "str +='<td><div class=\"formControls col-xs-9 col-sm-8\"><span class=\"btn btn-danger fileinput-button\" id=\"spec-'+spec_bunch+'-image_upload\" name=\"spec['+spec_bunch+'][image_upload]\"><span>添加图片</span><input type=\"file\" name=\"file\" multiple=\"\"></span>&nbsp;<font>(文件数：1,上传多张只会保存最新的；文件大小：4M)</font><div id=\"uploadfiles_spec-'+spec_bunch+'-image\" class=\"uploadfiles\" style=\"margin-top:15px;\"><input type=\"hidden\" id=\"spec-'+spec_bunch+'-image\" name=\"spec['+spec_bunch+'][image]\" nc_type=\"'+spec_bunch+'|image\" value=\"\"><div class=\"filediv_spec-'+spec_bunch+'-image\" id=\"file_spec-'+spec_bunch+'-image_1327\" style=\"float: left; width: 100px; margin-right: 10px;\"><div><img id=\"spec-'+spec_bunch+'-image_show\" nc_type=\"'+spec_bunch+'|image\" src=\"\" width=\"100px\"></div><div style=\"text-align:center;\"></div><span id=\"url\" nc_type=\"'+spec_bunch+'|image\" style=\"display:none\"></span></div></div><script type=\"text/javascript\">fileupload(\'spec-'+spec_bunch+'-image\',\'{\"url\":\"/uploadfile/jqueryfileupload.php\",\"domain\":\"//nnhtest.oss-cn-shenzhen.aliyuncs.com/\",\"maxFileSize\":4194304,\"maxNumberOfFiles\":\"4\",\"savefileurl\":\"/Sys/upload/getfile\",\"getParamUrl\":\"/Sys/upload/policy\",\"server_type\":\"NNH/images\",\"formData\":{\"server_type\":\"NNH/images\"}}\');<\/script>（建议尺寸:200pxX200px）</div></td></tr>';\n";

        echo "}";
    }



    /*

    if($len < $sign){
        echo "for (var i_".$len."=0; i_".$len."<spec_group_checked[".$len."].length; i_".$len."++){td_".(intval($len)+1)." = spec_group_checked[".$len."][i_".$len."];\n";
        $len++;
        recursionSpec($len,$sign,$action);
    }else{
        //echo "str+='333'";
        echo "var tmp_spec_td = new Array();\n";
        for($i=0; $i< $len; $i++){ 
            echo "tmp_spec_td[".($i)."] = td_".($i+1)."[1];\n";
        }
        echo "tmp_spec_td.sort(function(a,b){return a-b});\n";
        echo "var spec_bunch = 'i_';\n";
        for($i=0; $i< $len; $i++){
            if($i == ($len - 1)){
                echo "spec_bunch += tmp_spec_td[".($i)."];\n";
            }else{
                echo "spec_bunch += tmp_spec_td[".($i)."] + '_';\n";
            }
            
        }

        echo "str += '<input type=\"hidden\" id=\"spec['+spec_bunch+'][goods_id]\" name=\"spec['+spec_bunch+'][goods_id]\" nc_type=\"'+spec_bunch+'|id\" value=\"\" />';";
        
        for($i=0; $i< $len; $i++){
            echo "if (td_".($i+1)."[2] != null) { str += '<input type=\"hidden\" name=\"spec['+spec_bunch+'][color]\" value=\"'+td_".($i+1)."[1]+'\" />';}";
            echo "str +='<td ><input type=\"hidden\" name=\"spec['+spec_bunch+'][sp_value]['+td_".($i+1)."[1]+']\" value=\"'+td_".($i+1)."[0]+'\" /><label style=\"width:90px;\">'+td_".($i+1)."[0]+'</label></td>';\n";
        }
        

        echo "str +='<td><input class=\"input-text width250 supplyprice\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][supplyprice]\" data_type=\"supplyprice\" nc_type=\"'+spec_bunch+'|supplyprice\" value=\"\"  onblur=\"supplyFormatPrice($(this))\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 saleprice\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][saleprice]\" data_type=\"saleprice\" nc_type=\"'+spec_bunch+'|saleprice\" onblur=\"saleFormatPrice($(this))\" value=\"\" /></td>';\n";
        
        echo "str +='<td><input class=\"input-text width250 discount\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][discount]\" data_type=\"discount\" nc_type=\"'+spec_bunch+'|discount\" value=\"\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 prouctprice\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][prouctprice]\" data_type=\"prouctprice\" nc_type=\"'+spec_bunch+'|prouctprice\" readonly=\"true\" value=\"\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 bullamount\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][bullamount]\" data_type=\"bullamount\" nc_type=\"'+spec_bunch+'|bullamount\" readonly=\"true\" value=\"\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 productstorage\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][productstorage]\" data_type=\"productstorage\" nc_type=\"'+spec_bunch+'|productstorage\" value=\"\" /></td>';\n";

        //图片
        echo "str +='<td><div class=\"formControls col-xs-9 col-sm-8\"><span class=\"btn btn-danger fileinput-button\" id=\"spec-'+spec_bunch+'-image_upload\" name=\"spec['+spec_bunch+'][image_upload]\"><span>添加图片</span><input type=\"file\" name=\"file\" multiple=\"\"></span>&nbsp;<font>(文件数：1,上传多张只会保存最新的；文件大小：4M)</font><div id=\"uploadfiles_spec-'+spec_bunch+'-image\" class=\"uploadfiles\" style=\"margin-top:15px;\"><input type=\"hidden\" id=\"spec-'+spec_bunch+'-image\" name=\"spec['+spec_bunch+'][image]\" nc_type=\"'+spec_bunch+'|image\" value=\"\"><div class=\"filediv_spec-'+spec_bunch+'-image\" id=\"file_spec-'+spec_bunch+'-image_1327\" style=\"float: left; width: 100px; margin-right: 10px;\"><div><img id=\"spec-'+spec_bunch+'-image_show\" nc_type=\"'+spec_bunch+'|image\" src=\"\" width=\"100px\"></div><div style=\"text-align:center;\"></div><span id=\"url\" nc_type=\"'+spec_bunch+'|image\" style=\"display:none\"></span></div></div><script type=\"text/javascript\">fileupload(\'spec-'+spec_bunch+'-image\',\'{\"url\":\"/uploadfile/jqueryfileupload.php\",\"domain\":\"//nnhtest.oss-cn-shenzhen.aliyuncs.com/\",\"maxFileSize\":4194304,\"maxNumberOfFiles\":\"4\",\"savefileurl\":\"/Sys/upload/getfile\",\"getParamUrl\":\"/Sys/upload/policy\",\"server_type\":\"NNH/images\",\"formData\":{\"server_type\":\"NNH/images\"}}\');<\/script>（建议尺寸:200pxX200px）</div></td></tr>';\n";
       
        
        for($i=0; $i< $len; $i++){
            echo "}\n";
        }

        
    }*/

}


function recursionSpec($len,$sign,$action) {

    if($len < $sign){
        echo "for (var i_".$len."=0; i_".$len."<spec_group_checked[".$len."].length; i_".$len."++){td_".(intval($len)+1)." = spec_group_checked[".$len."][i_".$len."];\n";
        $len++;
        recursionSpec($len,$sign,$action);
    }else{
        //echo "str+='333'";
        echo "var tmp_spec_td = new Array();\n";
        for($i=0; $i< $len; $i++){ 
            echo "tmp_spec_td[".($i)."] = td_".($i+1)."[1];\n";
        }
        echo "tmp_spec_td.sort(function(a,b){return a-b});\n";
        echo "var spec_bunch = 'i_';\n";
        for($i=0; $i< $len; $i++){
            if($i == ($len - 1)){
                echo "spec_bunch += tmp_spec_td[".($i)."];\n";
            }else{
                echo "spec_bunch += tmp_spec_td[".($i)."] + '_';\n";
            }
            
        }

        echo "str += '<input type=\"hidden\" id=\"spec['+spec_bunch+'][goods_id]\" name=\"spec['+spec_bunch+'][goods_id]\" nc_type=\"'+spec_bunch+'|id\" value=\"\" />';";
        
        for($i=0; $i< $len; $i++){
            echo "if (td_".($i+1)."[2] != null) { str += '<input type=\"hidden\" name=\"spec['+spec_bunch+'][color]\" value=\"'+td_".($i+1)."[1]+'\" />';}";
            echo "str +='<td ><input type=\"hidden\" name=\"spec['+spec_bunch+'][sp_value]['+td_".($i+1)."[1]+']\" value=\"'+td_".($i+1)."[0]+'\" /><label style=\"width:90px;\">'+td_".($i+1)."[0]+'</label></td>';\n";
        }
        

        echo "str +='<td><input class=\"input-text width250 supplyprice\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][supplyprice]\" data_type=\"supplyprice\" nc_type=\"'+spec_bunch+'|supplyprice\" value=\"\"  onblur=\"supplyFormatPrice($(this))\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 saleprice\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][saleprice]\" data_type=\"saleprice\" nc_type=\"'+spec_bunch+'|saleprice\" onblur=\"saleFormatPrice($(this))\" value=\"\" /></td>';\n";
        
        echo "str +='<td><input class=\"input-text width250 discount\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][discount]\" data_type=\"discount\" nc_type=\"'+spec_bunch+'|discount\" value=\"\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 prouctprice\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][prouctprice]\" data_type=\"prouctprice\" nc_type=\"'+spec_bunch+'|prouctprice\" readonly=\"true\" value=\"\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 bullamount\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][bullamount]\" data_type=\"bullamount\" nc_type=\"'+spec_bunch+'|bullamount\" readonly=\"true\" value=\"\" /></td>';\n";

        echo "str +='<td><input class=\"input-text width250 productstorage\" style=\"width:90px;\" type=\"test\" name=\"spec['+spec_bunch+'][productstorage]\" data_type=\"productstorage\" nc_type=\"'+spec_bunch+'|productstorage\" value=\"\" /></td>';\n";

        //图片
        echo "str +='<td><div class=\"formControls col-xs-9 col-sm-8\"><span class=\"btn btn-danger fileinput-button\" id=\"spec-'+spec_bunch+'-image_upload\" name=\"spec['+spec_bunch+'][image_upload]\"><span>添加图片</span><input type=\"file\" name=\"file\" multiple=\"\"></span>&nbsp;<font>(文件数：1,上传多张只会保存最新的；文件大小：4M)</font><div id=\"uploadfiles_spec-'+spec_bunch+'-image\" class=\"uploadfiles\" style=\"margin-top:15px;\"><input type=\"hidden\" id=\"spec-'+spec_bunch+'-image\" name=\"spec['+spec_bunch+'][image]\" nc_type=\"'+spec_bunch+'|image\" value=\"\"><div class=\"filediv_spec-'+spec_bunch+'-image\" id=\"file_spec-'+spec_bunch+'-image_1327\" style=\"float: left; width: 100px; margin-right: 10px;\"><div><img id=\"spec-'+spec_bunch+'-image_show\" nc_type=\"'+spec_bunch+'|image\" src=\"\" width=\"100px\"></div><div style=\"text-align:center;\"></div><span id=\"url\" nc_type=\"'+spec_bunch+'|image\" style=\"display:none\"></span></div></div><script type=\"text/javascript\">fileupload(\'spec-'+spec_bunch+'-image\',\'{\"url\":\"/uploadfile/jqueryfileupload.php\",\"domain\":\"//nnhtest.oss-cn-shenzhen.aliyuncs.com/\",\"maxFileSize\":4194304,\"maxNumberOfFiles\":\"4\",\"savefileurl\":\"/Sys/upload/getfile\",\"getParamUrl\":\"/Sys/upload/policy\",\"server_type\":\"NNH/images\",\"formData\":{\"server_type\":\"NNH/images\"}}\');<\/script>（建议尺寸:200pxX200px）</div></td></tr>';\n";
       
        
        for($i=0; $i< $len; $i++){
            echo "}\n";
        }

        
    }

}

?>


<!-- <link rel="stylesheet" href="/jQueryFileUpload/css/fileupload.css">
<script src="/jQueryFileUpload/js/vendor/jquery.ui.widget.js"></script>
<script src="/jQueryFileUpload/js/load-image.all.min.js"></script>
<script src="/jQueryFileUpload/js/canvas-to-blob.min.js"></script>
<script src="/jQueryFileUpload/js/jquery.iframe-transport.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload-process.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload-image.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload-validate.js"></script>
<script src="/jQueryFileUpload/js/fileupload.js"></script> -->
<script type='text/javascript'>

 function supplyFormatPrice(o){
        
        var  tmp_supplyprice = $("input[name='supplyprice']").val();//供货价
        var  tmp_saleprice = $("input[name='saleprice']").val();//销售价



        var supplyprice = o.val();
        //alert(supplyprice);
        var saleprice =  o.parent().parent().find($(".saleprice")).val();
        //alert(saleprice);
        //销售方式 1现金 2现金+牛豆
        var settle_cycle = $('input:radio[name="saletype"]:checked').val();
        //alert(settle_cycle);
        if(supplyprice > 0 && saleprice >0 && settle_cycle>0){
            o.parent().parent().find("td input[data_type='discount']").attr("readonly","true");
            o.parent().parent().find("td input[data_type='prouctprice']").attr("readonly","true");
            o.parent().parent().find("td input[data_type='bullamount']").attr("readonly","true");
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
                    o.parent().parent().find($(".bullamount")).val(res.bullamount);
                    // $('input:text[name="prouctprice"]').val(res.prouctprice);
                    // $('input:text[name="discount"]').val(res.discount);
                    // $('input:text[name="bullamount"]').val(res.bullamount);
                    // $("#msg").html(res.msg);
                    // 
                    o.parent().parent().find('input[nc_type]').each(function(){
                            s = $(this).attr('nc_type');
                            s = sort_key(s);
                            V[s] = $(this).val();
                        });
                }
            });
        }else{
            o.parent().parent().find("td input[data_type='discount']").removeAttr("readonly");
            o.parent().parent().find("td input[data_type='prouctprice']").removeAttr("readonly");
            o.parent().parent().find("td input[data_type='bullamount']").removeAttr("readonly");
        }
      
        $(".supplyprice").each(function(){
            if($(this).val() <= parseFloat(tmp_supplyprice)){
                tmp_supplyprice = $(this).val();

                tmp_saleprice = $(this).parent().parent().find("td input[data_type='saleprice']").val()
              
            }
        });
        
     

        $("input[name='supplyprice']").val(tmp_supplyprice);//供货价
        $("input[name='saleprice']").val(tmp_saleprice);//销售价

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
            
            var  tmp_supplyprice = $("input[name='supplyprice']").val();//供货价
            var  tmp_saleprice = $("input[name='saleprice']").val();//销售价

            var saleprice = o.val();
            //alert(saleprice);
            var supplyprice =  o.parent().parent().find($(".supplyprice")).val();
            //alert(supplyprice);
            //销售方式 1现金 2现金+牛豆
            var settle_cycle = $('input:radio[name="saletype"]:checked').val();
            //alert(settle_cycle);
            //alert(saleprice);
            if(supplyprice > 0 && saleprice >0 && settle_cycle>0){
     
                o.parent().parent().find("td input[data_type='discount']").attr("readonly","true");
                o.parent().parent().find("td input[data_type='prouctprice']").attr("readonly","true");
                o.parent().parent().find("td input[data_type='bullamount']").attr("readonly","true");
                $.ajax({
                    type:'POST',
                    data: {supplyprice:supplyprice,saleprice:saleprice,settle_cycle:settle_cycle},
                    async:false, 
                    traditional :true,
                    dataType:'json',
                    url:"/Product/Index/formatPrice",
                    success:function(res){
                        console.log(res);
                        o.parent().parent().find($(".discount")).val(res.discount);
                        o.parent().parent().find($(".prouctprice")).val(res.prouctprice);
                        o.parent().parent().find($(".bullamount")).val(res.bullamount);
                        // $('input:text[name="prouctprice"]').val(res.prouctprice);
                        // $('input:text[name="discount"]').val(res.discount);
                        // $('input:text[name="bullamount"]').val(res.bullamount);
                        // $("#msg").html(res.msg);
                        
                        o.parent().parent().find('input[nc_type]').each(function(){
                            s = $(this).attr('nc_type');
                            s = sort_key(s);
                            V[s] = $(this).val();
                        });

                    }
                });
            }else{
                o.parent().parent().find("td input[data_type='discount']").removeAttr("readonly");
                o.parent().parent().find("td input[data_type='prouctprice']").removeAttr("readonly");
                o.parent().parent().find("td input[data_type='bullamount']").removeAttr("readonly");
            }


        $(".supplyprice").each(function(){
            
            if($(this).val() < parseFloat(tmp_supplyprice)){
              
                tmp_supplyprice = $(this).val();
                tmp_saleprice = $(this).parent().parent().find("td input[data_type='saleprice']").val()
               
            }
        });
        
       

        $("input[name='supplyprice']").val(tmp_supplyprice);//供货价
        $("input[name='saleprice']").val(tmp_saleprice);//销售价

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

    function sort_key(str){

        return str;

        var tt = str.split("|");
        var tmp_arr = tt[0].split("_");
        var pif = tmp_arr[0];
        var new_arr = tmp_arr.concat();
        new_arr.shift();
        new_arr.sort(function(a,b){
            return a-b;
        });
        var newstr = '';
        newstr+=pif+"_";
        for(var i=0;i<new_arr.length;i++){
           newstr+=new_arr[i]+"_";
        }
        newstr = newstr.substr(0,newstr.length-1);
        return newstr+"|"+tt[1];
    }


    function ini_spec_select(){
        into_array();

        goods_stock_set();
    }

    $(function(){
        
        ini_spec_select();
    });

    // $(function(){
    //     $('.supplyprice').on('blur',function(){
            
    //         alert(1);
    //         var supplyprice = $(this).val();
    //         //alert(supplyprice);
    //         var saleprice =  $(this).parent().parent().find($(".saleprice")).val();
    //         //alert(saleprice);
    //         //销售方式 1现金 2现金+牛豆
    //         var settle_cycle = $('input:radio[name="saletype"]:checked').val();
    //        // alert(settle_cycle);
    //         if(supplyprice > 0 && saleprice >0 && settle_cycle>0){
    //             $.ajax({
    //                 type:'POST',
    //                 data: {supplyprice:supplyprice,saleprice:saleprice,settle_cycle:settle_cycle},
    //                 async:false, 
    //                 traditional :true,
    //                 dataType:'json',
    //                 url:"/Product/Index/formatPrice",
    //                 success:function(res){
    //                     console.log(res);
    //                     $(this).parent().find($(".prouctprice")).val(res.prouctprice);
    //                     $(this).parent().find($(".discount")).val(res.discount);
    //                     $(this).parent().find($(".prouctprice")).val(res.prouctprice);
    //                     $(this).parent().find($(".bullamount")).val(res.bullamount);
    //                     // $('input:text[name="prouctprice"]').val(res.prouctprice);
    //                     // $('input:text[name="discount"]').val(res.discount);
    //                     // $('input:text[name="bullamount"]').val(res.bullamount);
    //                     // $("#msg").html(res.msg);
    //                 }
    //             });
    //         }
    //     })

    //     $('.saleprice').on('blur',function(){
    //         alert(2);
    //         var o = $(this);
    //         var saleprice = $(this).val();
    //         //alert(saleprice);
    //         var supplyprice =  $(this).parent().parent().find($(".supplyprice")).val();
    //         //alert(supplyprice);
    //         //销售方式 1现金 2现金+牛豆
    //         var settle_cycle = $('input:radio[name="saletype"]:checked').val();
    //         //alert(settle_cycle);
    //         if(supplyprice > 0 && saleprice >0 && settle_cycle>0){
    //             $.ajax({
    //                 type:'POST',
    //                 data: {supplyprice:supplyprice,saleprice:saleprice,settle_cycle:settle_cycle},
    //                 async:false, 
    //                 traditional :true,
    //                 dataType:'json',
    //                 url:"/Product/Index/formatPrice",
    //                 success:function(res){
    //                     console.log(res);
    //                     o.parent().parent().find($(".prouctprice")).val(res.prouctprice);
    //                     o.parent().parent().find($(".discount")).val(res.discount);
    //                     o.parent().parent().find($(".prouctprice")).val(res.prouctprice);
    //                     o.parent().parent().find($(".bullamount")).val(res.bullamount);
    //                     // $('input:text[name="prouctprice"]').val(res.prouctprice);
    //                     // $('input:text[name="discount"]').val(res.discount);
    //                     // $('input:text[name="bullamount"]').val(res.bullamount);
    //                     // $("#msg").html(res.msg);
    //                 }
    //             });
    //         }
    //     })
    // });
</script>
{include file="Pub/footer" /}


