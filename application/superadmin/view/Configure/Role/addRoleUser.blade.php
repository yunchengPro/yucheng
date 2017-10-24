<?php
    
    $title = '添加角色用户';
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
        "checkbox"=>array("name"=>"选择","width"=>"60","noencode"=>1),
        "loginname"=>array("name"=>"用户名"),
        "name"=>array("name"=>"用户名称"),
        "role"=>array("name"=>"角色"),
        "act"=>array("name"=>"操作"),
    );

    foreach($pagelist as $key=>$value){
        $pagelist[$key]['act'][]=array("type"=>"confirm","value"=>"选择","_url"=>"/configure/role/addRoleUserAct?roleid=".$roleid."&id=".$value['id'],"_title"=>"是否确认替换该用户的角色");
        
      $pagelist[$key]['act'] =Html::Htmlact($pagelist[$key]['act']);
    }

    $search = array(
        "loginname"=>array("type"=>"text","name"=>"用户名"),
        "name"=>array("type"=>"text","name"=>"用户名称"),
    );
    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$exportflag = false; //不导出

    $button_str .= Html::button(array("type"=>"confirm_all","name"=>"button","value"=>"批量选择","_width"=>"500","_height"=>"200","_title"=>'批量选择选中项?',"_url"=>"/configure/role/addRoleUserAct?roleid=".$roleid));
   
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