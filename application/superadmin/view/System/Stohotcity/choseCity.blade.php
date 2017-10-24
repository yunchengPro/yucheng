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
        "areaname"=>array("name"=>"城市名"),
        "id"=>array("name"=>"城市编码"),
        "act"=>array("name"=>"操作"),
    );

    foreach($pagelist as $key=>$row){

      
        $pagelist[$key]['act'].= Html::button(array("type"=>"button","name"=>"button","value"=>"选择","_width"=>"600","_height"=>"600","_title"=>"选择","class"=>"chose"));


    }

    $search = array(
        "areaname"=>array("type"=>"text","name"=>"城市名","value"=>""),
        "id"=>array("type"=>"text","name"=>"城市编码","value"=>"")
       
    );
   
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
    var roleid = '<?=$roleid?>';

    $(".checkaddproduct").click(function (){
        var ids = get_selected_items();
        if(ids == ''){
            //alert('请选择关联的规格');
            return false;
        }
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        var close = false;
        var i = 0;

        var len = $("input:checkbox:checked").length;
        var ids = '';
        $("input[type='checkbox']:checked").each(function (){
            var id = $(this).val();
          

            if(id != 'on'){
               
                 ids=ids+","+id;
               
            }
           
            i ++;
        });
      

        $.ajax({
                type:'GET',
                dataType:'json',
                url:"/System/Stohotcity/addHotcity?ids="+ids,
                success: function(data){
                    data = eval("("+data+")");
                    
                    if(data.code=='200'){
                        parent.layer.msg('加入成功', {icon: 1});
                        parent.window.location.reload();
                      }
                      else{
                         parent.layer.msg(data.msg+'已存在', {icon: 2});
                      }
                }
        });

      

        // if(len == i){
        //     parent.layer.msg('加入成功', {icon: 1});
        //     //parent.layer.close(index); //再执行关闭
        //     parent.window.location.reload();

        // }
        
    });

    $(".chose").on('click',function(){
        var id = $(this).parent().parent().attr('_rowid');
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引

        var close = true;
       
        
        $.ajax({
                    type:'GET',
                    dataType:'json',
                    url:"/System/Stohotcity/addHotcity?ids="+id,
                    success: function(data){
                        data = eval("("+data+")");
                        
                        if(data.code=='200'){
                            parent.layer.msg('加入成功', {icon: 1});
                            parent.window.location.reload();
                          }
                          else{
                             parent.layer.msg(data.msg+'已存在', {icon: 2});
                          }
                    }
            });
        // parent.layer.msg('加入成功', {icon: 1});
        // parent.window.location.reload();
        // if(close){
        //     parent.layer.msg('加入成功', {icon: 1});
        //     parent.layer.close(index); //再执行关闭
        // }
       
    });

});
</script>