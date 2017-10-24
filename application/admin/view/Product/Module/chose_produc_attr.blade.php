<?php
    $title = "添加商品";
    /*
    设置列表，表头
    "f_mobile"=>array("name"=>"所属分会"),"sort"=>true,"width"=>"120","head_str"=>"width='30' nowrap","align"=>"left"),
    f_mobile 显示的字段
    name     表头名称
    sort     是否排序，默认false 不排序
    width    列宽度
    head_str 表头的其他信息
    data_str 数据其他信息
    align    设置内容项位置：left,center,right 默认是居中center
    nowrap   设置表格内容过长时是否进行缩进，默认false
    */
    $list_set = array(
        "checkbox"=>array("name"=>"选择","width"=>"30","noencode"=>1),
        "attr_name"=>array("name"=>"属性名称"),
        "act"=>array("name"=>"操作"),
    );

    foreach($pagelist as $key=>$row){

      
        $pagelist[$key]['act'].= Html::button(array("type"=>"button","name"=>"button","value"=>"选择","_width"=>"600","_height"=>"600","_title"=>"选择","class"=>"chose"));


    }

    // $search = array(
    //     // "keyword"=>array("type"=>"text","name"=>"名称","value"=>""),
    // );

    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$export_url = "/demo/pub/export"; //导出数据的action

    //自动生成按钮
    // $button = array(
    //         "bt1"=>array("type"=>"popup","name"=>"button","value"=>"添加产品","_width"=>"500","_height"=>"200","_title"=>"添加产品","_url"=>"/product/index/addproductstp1",)//弹窗按钮
    //     );
?> 

<?php if ($full_page){?>
<!---头部-->
{include file="Pub/header" /}
{include file="Pub/pubHead" /}
<!---头部-->
            <!---搜索条件-->
            {include file="Pub/pubSearch" /}
            <!---搜索条件-->
            
            <!---这里可以写操作按钮等-->
            <button class="button checkaddproduct"  type="button" ><?='批量添加'?></button>
                   
                        
            <!---这里可以写操作按钮等-->
<?php }?>
            
            <!---列表+分页-->       
            {include file="Pub/pubList" /}
            <!---列表+分页-->
<?php if ($full_page){?>
<!---尾部-->
{include file="Pub/pubTail" /}
{include file="Pub/footer" /}
<!---尾部-->
<?php }?>
<script type="text/javascript">
$(function(){


    $(".checkaddproduct").click(function (){
        var ids = get_selected_items();
        if(ids == ''){
            //alert('请选择关联的属性');
            return false;
        }
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        var close = true;
        var  itemHtml = '';
        var add_spec = true;
        $("input[type='checkbox']:checked").each(function (){
            var id = $(this).val();
            //商品名称 
            var product_name = $.trim($(this).parent().parent().find("td").eq(1).text());

            if(id != 'on'){
              
                if(parent.$("input[name='attr_type[]']").length > 0){
                    parent.$("input[name='attr_type[]']").each(function(){
                         var spec_val = $(this).val();
                         var addSpecVal =  id ;

                        if(spec_val == addSpecVal){
                            close = false;
                            add_spec = false;
                            alert('已选的属性：'+product_name);
                            return false;
                        }else{
                            itemHtml += '<div class="type-item"><a>'+ product_name + '</a>' + '<input type="hidden" name="attr_type[]" value="'+id+'" />' + '<img class="deleteItem" src="/newui/static/h-ui.admin/images/ic_close.png"></div>';
                        }
                    });
                }else{
                    itemHtml += '<div class="type-item"><a>'+ product_name + '</a>' + '<input type="hidden" name="attr_type[]" value="'+id+'" />' + '<img class="deleteItem" src="/newui/static/h-ui.admin/images/ic_close.png"></div>';
                }
            }
               
        });
        if(add_spec)
        parent.$('.add_attr').before(itemHtml);
        if(close){
            parent.layer.msg('加入成功', {icon: 1});
            parent.layer.close(index); //再执行关闭
        }
       
    });
    $(".chose").on('click',function(){
        var id = $(this).parent().parent().attr('_rowid');
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引

        var close = true;
        //商品名称 
        var product_name = $.trim($(this).parent().parent().find("td").eq(1).text());
        var itemHtml = '<div class="type-item"><a>'+ product_name + '</a>' + '<input type="hidden" name="attr_type[]" value="'+id+'" />'  + '<img class="deleteItem" src="/newui/static/h-ui.admin/images/ic_close.png"></div>';
       
        if(parent.$("input[name='attr_type[]']").length > 0){
            parent.$("input[name='attr_type[]']").each(function(){
               
                var spec_val = $(this).val();
                var addSpecVal =  id;
               
                if(spec_val == addSpecVal){
                    close = false;
                    alert('已选该属性');
                    return false;
                }
            });
            parent.$('.add_attr').before(itemHtml);
        }else{
            parent.$('.add_attr').before(itemHtml);
        }

        if(close){
            parent.layer.msg('加入成功', {icon: 1});
            parent.layer.close(index); //再执行关闭
        }
    });

});
</script>