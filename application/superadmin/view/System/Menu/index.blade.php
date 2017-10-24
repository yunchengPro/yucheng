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
        "sort"=>array("name"=>"排序","width"=>"100","data_sort"=>true,"_url"=>"/System/Menu/sort"),
        "enable"=>array("name"=>"状态","width"=>"60",'data_arr'=>['0'=>'停用','1'=>'启用']),
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
   		
        //$pagelist[$key]['enable_str'] = $enable_arr[$value['enable']];

   		
        if($value['level']<3){
            $pagelist[$key]['act'][] = array("type"=>"popup","name"=>"button","value"=>"添加子菜单","_width"=>"750","_height"=>"500","_title"=>"添加子菜单","_url"=>"/System/Menu/addMenu?parentid_path=".$value['parentid_path']);
   		}
   		if($value['enable']==1){
            $pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"停用","_width"=>"750","_height"=>"500","_title"=>"是否确定停用该菜单","_url"=>"/System/Menu/setEnable?enable=0&id=".Encode($value['id']));

   			$pagelist[$key]['menuname'] ="<strong>".$value['menuname']."</strong>";
   		}else{
            $pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"启用","_width"=>"750","_height"=>"500","_title"=>"是否确定启用该菜单","_url"=>"/System/Menu/setEnable?enable=1&id=".Encode($value['id']));

   			$pagelist[$key]['menuname'] ="<font color='#ccc'>".$value['menuname']."</font>";
   		}
            $pagelist[$key]['act'][] = array("type"=>"popup","name"=>"button","value"=>"编辑","_width"=>"750","_height"=>"500","_title"=>"编辑","_url"=>"/System/Menu/editMenu?id=".Encode($value['id']));

   		if($value['child_menu']!=true){
            $pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"750","_height"=>"500","_title"=>"是否确定删除该菜单","_url"=>"/System/Menu/delMenu?id=".Encode($value['id']));
   		}
   		// if($value['f_url']!='/'){
     //        $pagelist[$key]['act'][] = array("type"=>"popup","name"=>"button","value"=>"设置权限","_width"=>"800","_height"=>"500","_title"=>"设置权限","_url"=>"/System/Menu/menuaction?menuid=".Encode($value['id']));
   		// }

        // if($value['f_url']!='/'){
        //     $pagelist[$key]['act'][] = array("type"=>"popup","name"=>"button","value"=>"设置列表项","_width"=>"800","_height"=>"500","_title"=>"设置列表项","_url"=>"/System/Menu/index?menuid=".Encode($value['id']));
        // }
        
        $pagelist[$key]['act'] = Html::htmlact($pagelist[$key]['act']);
   	}
    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$exportflag = false; //不导出
 	$button = array(
     
 	  "bt1"=>array("name"=>"button","value"=>"新增顶级菜单","type"=>"popup","_title"=>"新增菜单","_url"=>"/System/Menu/addMenu","_width"=>"600","_height"=>"450"),
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