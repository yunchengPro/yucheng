<?php
    
    $title = '菜单列表';
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
        //"id"=>array("name"=>_("编号")),
        "sort"=>array("name"=>"排序","width"=>"100","data_sort"=>true,"_url"=>"/Configure/Menu/sort"),
        "enable_str"=>array("name"=>"状态","width"=>"60"),
        "act"=>array("name"=>"操作","width"=>"200"),
    );

    //临时变量
    $temp_list = array();
    //系统三级菜单

    foreach($pagelist as $v1){
        //顶级菜单
        if($v1['parentid']==0){
            $v1['level']=1;//菜单级数
            $v1['menuname'] = "| ".$v1['menuname'];
            $v1['parentid_path'] = $v1['id'];
            $temp_list[$v1['id']]=$v1;
            //获取二级菜单
            foreach($pagelist as $v2){
                if($v2['parentid']==$v1['id']){
                    $temp_list[$v1['id']]['child_Menu']=true;//设置父节点有子菜单

                    $v2['level']=2;//菜单级数
                    $v2['menuname'] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|____ ".$v2['menuname'];
                    $v2['parentid_path'] = $v1['id']."_".$v2['id'];
                    $temp_list[$v2['id']]=$v2;
                    //获取三级菜单
                    foreach($pagelist as $v3){
                        if($v3['parentid']==$v2['id']){
                            $temp_list[$v2['id']]['child_Menu']=true;//设置父节点有子菜单

                            $v3['level']=3;//菜单级数
                            $v3['Menuname'] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|____ ".$v3['menuname'];
                            $v3['parentid_path'] = $v1['id']."_".$v2['id']."_".$v3['id'];
                            $temp_list[$v3['id']]=$v3;
                        }
                    }
                }
            }
        }
    }
    $pagelist = $temp_list;

    //print_r($pagelist);

    //$f_enable_arr = Html::color(array("value"=>array('0'=>"停用",'1'=>"启用"),"color"=>array("0"=>"red")));
    foreach($pagelist as $key=>$value){
        //$pagelist[$key]['enable_str'] = $f_enable_arr[$value['enable']];
        if($value['level']<2){
            $pagelist[$key]['act'][] = array("type"=>"popup","name"=>"button","value"=>"添加子菜单","_width"=>"750","_height"=>"500","_title"=>"添加子菜单","_url"=>"/Configure/Menu/addMenu?parentid_path=".$value['parentid_path']);
        }
        if($value['enable']==1){
            $pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"停用","_width"=>"750","_height"=>"500","_title"=>"是否确定停用该菜单","_url"=>"/Configure/Menu/setEnable?enable=0&id=".$value['id']);
            $pagelist[$key]['enable_str'] = '<span class="label label-success radius">启用</span>';

            $pagelist[$key]['Menuname'] ="<strong>".$value['Menuname']."</strong>";
        }else{
            $pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"启用","_width"=>"750","_height"=>"500","_title"=>"是否确定启用该菜单","_url"=>"/Configure/Menu/setEnable?enable=1&id=".$value['id']);
            $pagelist[$key]['enable_str'] = '<span class="label label-danger radius">停用</span>';

            $pagelist[$key]['Menuname'] ="<font color='#ccc'>".$value['Menuname']."</font>";
        }
        $pagelist[$key]['act'][] = array("type"=>"popup","name"=>"button","value"=>"编辑","_width"=>"750","_height"=>"500","_title"=>"编辑","_url"=>"/Configure/Menu/editMenu?id=".$value['id']);

        if($value['child_Menu']!=true){
        $pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"750","_height"=>"500","_title"=>"是否确定删除该菜单","_url"=>"/Configure/Menu/delMenu?id=".$value['id']);
        }
        // if($value['url']!='/'){
        // $pagelist[$key]['act'][] = array("type"=>"popup","name"=>"button","value"=>"设置权限","_width"=>"800","_height"=>"500","_title"=>"设置权限","_url"=>"/Configure/Menu/MenuAction?Menuid=".$value['id']);
        // }
        
      $pagelist[$key]['act'] =Html::Htmlact($pagelist[$key]['act']);
    }
    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$exportflag = false; //不导出
    $button = array(

            "bt1"=>array("name"=>"button","value"=>"新增顶级菜单","type"=>"popup","_title"=>"新增菜单","_url"=>"/Configure/Menu/addMenu","_width"=>"600","_height"=>"400"),
        );
   
?> 
<!---这里可以写css和其他-->
<!---这里可以写css和其他-->
<!---头部-->
{include file="Pub/list" /}
<!---尾部-->
<!---这里可以写js-->
<script type="text/javascript">

</script>
<!--这里可以写js-->