<?php
    
    $title = '角色用户列表';
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
        "agent"=>array("name"=>"代理商","width"=>"200"),
        "loginname"=>array("name"=>"用户名","width"=>"200"),
        "name"=>array("name"=>"用户名称","align"=>"left","width"=>"260"),
        "act"=>array("name"=>"操作","width"=>"160",),
    );

    foreach($pagelist as $key=>$value){

        $pagelist[$key]['act'][] = array("type"=>"confirm","name"=>"button","value"=>"移除","_width"=>"750","_height"=>"500","_title"=>"是否确定移除该用户","_url"=>"/configure/role/delRoleUserAct?id=".$value['id']);
        
      $pagelist[$key]['act'] =Html::Htmlact($pagelist[$key]['act']);
    }

    $search = array(
        "loginname"=>array("type"=>"text","name"=>"用户名"),
        "name"=>array("type"=>"text","name"=>"用户名称"),
        "roleid"=>array("type"=>"hidden","value"=>Encode($roleid)),
    );
    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$exportflag = false; //不导出
    $button = array(
            "bt1"=>array("name"=>"button","value"=>"添加用户","type"=>"popup","_title"=>"添加用户","_url"=>"/configure/role/addRoleUser?roleid=".$roleid,"_width"=>"850","_height"=>"400"),
            
        );
    
?> 
<!---这里可以写css和其他-->
<!---这里可以写css和其他-->
<!---头部-->
@include('Pub.list')
<!---尾部-->
<!---这里可以写js-->
<script type="text/javascript">

</script>
<!--这里可以写js-->