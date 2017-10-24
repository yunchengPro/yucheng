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
        "name"=>array("name"=>"角色名称","align"=>"left"),
        "remark"=>array("name"=>"角色描述","align"=>"left"),
        "enable"=>array("name"=>"启用状态",'align'=>"left",'data_arr'=>['1'=>"启用","-1"=>'禁用']),
        "act"=>array("name"=>"操作","width"=>"200"),
    );

    //临时变量
    $temp_list = array();
    //系统三级菜单

   

  
   	//$enable_arr = Html::html("color",array("value"=>Default_Model_DbTable_SysMenu::$enable_arr,"color"=>array("0"=>"red")));   
   	foreach($pagelist as $key=>$row){
      

        $pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"设置用户","_width"=>"500","_height"=>"400","_title"=>"设置用户","_url"=>"/System/Role/roleuser?roleid=".Encode($row['id']));

    
        $pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"设置权限","_width"=>"500","_height"=>"400","_title"=>"设置<font color='green'>".$row['name']."</font>权限","_url"=>"/System/Role/setMenuRole?roleid=".Encode($row['id']));
    

        $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
   	}
    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$exportflag = false; //不导出
    //
    $search = array(
        "name"=>array("type"=>"text","name"=>"角色名称","class"=>"width250"),
    );
 	$button = array(
        "bt1"=>array("type"=>"popup_listpage","name"=>"button","value"=>"添加角色","_width"=>"500","_height"=>"300","_title"=>'添加角色',"_url"=>"/System/Role/addRole"),

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