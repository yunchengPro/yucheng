<?php
    
    $title = '角色列表';
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
        "name"=>array("name"=>"名称","align"=>"left"),
        "remark"=>array("name"=>"描述","align"=>"left"),
        "user"=>array("name"=>"用户"),
        "enable_str"=>array("name"=>"状态"),
        "act"=>array("name"=>"操作","width"=>"200"),
    );

    //print_r($pagelist);

    //$f_enable_arr = Html::color(array("value"=>array('0'=>"停用",'1'=>"启用"),"color"=>array("0"=>"red")));
    foreach($pagelist as $key=>$value){
        //$pagelist[$key]['enable_str'] = $f_enable_arr[$value['enable']];

        $pagelist[$key]['act'][] = array("type"=>"popup","name"=>"button","value"=>"编辑","_width"=>"750","_height"=>"500","_title"=>"编辑","_url"=>"/configure/role/editRole?id=".$value['id']);

        $pagelist[$key]['act'][] = array("type"=>"newpage","name"=>"button","value"=>"设置用户","_width"=>"800","_height"=>"500","_title"=>"设置用户","_url"=>"/configure/role/roleUser?roleid=".Encode($value['id']));
        $pagelist[$key]['act'][] = array("type"=>"newpage","name"=>"button","value"=>"设置权限","_width"=>"750","_height"=>"500","_title"=>"设置权限","_url"=>"/configure/menu/menuRole?roleid=".$value['id']);


        if($value['enable']==1){
            $pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"停用","_width"=>"750","_height"=>"500","_title"=>"是否确定停用该角色","_url"=>"/configure/role/setEnable?enable=0&id=".$value['id']);
            $pagelist[$key]['enable_str'] = '<span class="label label-success radius">启用</span>';


        }else{
            $pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"启用","_width"=>"750","_height"=>"500","_title"=>"是否确定启用该角色","_url"=>"/configure/role/setEnable?enable=1&id=".$value['id']);
            $pagelist[$key]['enable_str'] = '<span class="label label-warning radius">停用</span>';
        }
        // if($value['url']!='/'){
        // $pagelist[$key]['act'][] = array("type"=>"popup","name"=>"button","value"=>"设置权限","_width"=>"800","_height"=>"500","_title"=>"设置权限","_url"=>"/configure/menu/menuAction?menuid=".$value['id']);
        // }
        
        $pagelist[$key]['act'] =Html::Htmlact($pagelist[$key]['act']);

        $pagelist[$key]['user'] = Html::a(array("type"=>"newpage","name"=>"button","value"=>$pagelist[$key]['user'],"_width"=>"800","_height"=>"500","_title"=>"设置用户","_url"=>"/configure/role/roleUser?roleid=".Encode($value['id'])));
    }

    $search = array(
        "name"=>array("type"=>"text","name"=>"角色名称","value"=>""),
    );
    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$exportflag = false; //不导出
    $button = array(

            "bt1"=>array("name"=>"button","value"=>"新增角色","type"=>"popup","_title"=>"新增角色","_url"=>"/configure/role/addRole","_width"=>"600","_height"=>"400"),
        );
   
?> 
<!---这里可以写css和其他-->
<!---这里可以写css和其他-->
<!---头部-->
@include('Pub.list')
<!---尾部-->