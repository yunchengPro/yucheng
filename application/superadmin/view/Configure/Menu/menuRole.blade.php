<?php
    
    $title = '角色权限';
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
      "menuname"=>array("name"=>"菜单名称","align"=>"left","width"=>"340"),
      "sq"=>array("name"=>"授权","align"=>"left")
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
                    $temp_list[$v1['id']]['child_menu']=true;//设置父节点有子菜单

                    $v2['level']=2;//菜单级数
                    $v2['menuname'] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|____ ".$v2['menuname'];
                    $v2['parentid_path'] = $v1['id']."_".$v2['id'];
                    $temp_list[$v2['id']]=$v2;
                    //获取三级菜单
                    foreach($pagelist as $v3){
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

    foreach($pagelist as $key=>$value){
           
      $menutype = $menutype; 

      $typearr = array( $value['id']=>"显示");
       
      $pagelist[$key]['sq'].= Html::checkbox(array("name"=>"menu","terms"=>$typearr,"value"=>$menutype));
      
      $pagelist[$key]['sq'].= "&nbsp;&nbsp;&nbsp;&nbsp;";

      // foreach ($value['menuActionData'] as $k => $v) {

      //     $actiontype = $this->actiontype;

      //     $actiontypearr = array( $v['id']=>$v['f_actionname']);

      //     $this->list[$key]['sq'].=$this->html("checkbox",array("name"=>"menuaction","terms"=>$actiontypearr,"value"=>$actiontype));

      //     $this->list[$key]['sq'].= "&nbsp;&nbsp;&nbsp;&nbsp;";
      // }
      
      // if(!empty($listitemarr[$value['id']])){
      //   $this->list[$key]['listitem']=$this->html("checkbox",array("name"=>"listitem","terms"=>$listitemarr[$value['id']],"value"=>$listrolearr[$value['id']]));
      // }
    }
    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$exportflag = false; //不导出
    $button = array(
            "bt"=>array("name"=>"button","id"=>"submit","value"=>"保存","type"=>"submit","_title"=>"保存","_url"=>"#")
        );
   
?> 
<!---这里可以写css和其他-->
<!---这里可以写css和其他-->
<!---头部-->
@include('Pub.list')
<!---尾部-->
<!---这里可以写js-->
<script type="text/javascript">
 

  $('#submit').click(function(){
        //获取全选之外的checkbox
        var $check_boxes = $("input[name='menu[]']:checked");
        
        var array = new Array();
        $check_boxes.each(function(){
            
            array.push($(this).val());
        });

        console.log(array);

        //alert(array);
        $.post("/Configure/Menu/saveMenuRole/",{menuid:array,roleid:<?=$roleid?>},function(result){
            alert(result);
        });
       
        return false;
    });
  
</script>
<!--这里可以写js-->