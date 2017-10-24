<?php
	
	$title = _('菜单列表');
	/*
    设置列表，表头
    "f_mobile"=>array("name"=>_("所属分会"),"sort"=>true,"width"=>"120","head_str"=>"width='30' nowrap","align"=>"left"),
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
        "menuname"=>array("name"=>"菜单名称","align"=>"left"),
        "url"=>array("name"=>"菜单链接地址","align"=>"left"),
        "enable"=>array("name"=>"状态","width"=>"60"),
        "act"=>array("name"=>"操作","width"=>"200"),
    );

    //临时变量
    $temp_list = array();
    //系统三级菜单

   	foreach($list as $v1){

   		//顶级菜单
   		if($v1['parentid']==0){
   			$v1['level']=1;//菜单级数
   			$v1['menuname'] = "| ".$v1['menuname'];
   			$v1['parentid_path'] = $v1['id'];
   			$temp_list[$v1['id']]=$v1;
   			//获取二级菜单
   			foreach($list as $v2){
   				if($v2['parentid']==$v1['id']){
   					$temp_list[$v1['id']]['child_menu']=true;//设置父节点有子菜单

   					$v2['level']=2;//菜单级数
   					$v2['menuname'] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|____ ".$v2['menuname'];
   					$v2['parentid_path'] = $v1['id']."_".$v2['id'];
   					$temp_list[$v2['id']]=$v2;
   					//获取三级菜单
   					foreach($list as $v3){
   						if($v3['parentid']==$v2['id']){
   							$temp_list[$v2['id']]['child_menu']=true;//设置父节点有子菜单

   							$v3['level']=3;//菜单级数
   							$v3['menuname'] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|____ ".$v3['menuname'];
   							$v3['parentid_path'] = $v1['id']."_".$v2['id']."_".$v3['id'];
							$temp_list[$v3['id']]=$v3;
   						}
   					}
   				}
   			}
   		}
   	}
   	$pagelist = $temp_list;
  
   	//$enable_arr = Html::html("color",array("value"=>Default_Model_DbTable_SysMenu::$enable_arr,"color"=>array("0"=>"red")));   
   	foreach($pagelist as $key=>$value){
   		
            if(in_array($value['id'], $role_arr)){
               
                $pagelist[$key]['act'] = '<input type="checkbox" checked="checked"   value="'.$value['id'].'" name="menuid" />';
            }else {
                $pagelist[$key]['act'] = '<input type="checkbox"   value="'.$value['id'].'" name="menuid" />';
            }
      
        
        
   	}
    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$exportflag = false; //不导出
 	$button = array(
      "bt"=>array("name"=>"button","value"=>"保存","_title"=>"保存","class"=>"saverole"),
 		
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
<!--这里可以写js-->
<script type="text/javascript">
    $(function(){
        $(".saverole").click(function(){
            
            // $.get("/System/Role/delMenuRole?roleid=<?=$roleid?>", function(data){
            //         });
            var menuid ='';
            $(".table").find("input[type=checkbox]").each(function(){

               if($(this).is(':checked')){
                    menuid=menuid+","+$(this).val();
                }
            });

            $.get("/System/Role/saveMenuRole?roleid=<?=$roleid?>&menuid="+menuid, function(data){
              data = eval("("+data+")");
              if(data.code=='200'){
                alert('保存成功');
                window.location.reload();
              }
              else{
                alert('保存失败');
              }
            });

            
            
        });

    });

</script>