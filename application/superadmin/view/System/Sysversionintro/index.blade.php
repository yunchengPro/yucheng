<?php
    use app\lib\Model;
   
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
        "title"=>array("name"=>"版本介绍标题","align"=>"left"),
        "type"=>array("name"=>"类型",'align'=>"left",'data_arr'=>['1'=>"IOS","2"=>'Android']),
        "content"=>array("name"=>"版本介绍内容","align"=>"left"),
        "version"=>array("name"=>"版本号","align"=>"left"),
        "addtime"=>array("name"=>"添加时间","align"=>"left"),
        "sort"=>array("name"=>"排序","data_sort"=>true,"_url"=>"/System/Sysversionintro/sort",'sort'=>true),
        "act"=>array("name"=>"操作","width"=>"200"),
    );

    //临时变量
    $temp_list = array();
    //系统三级菜单

   

  
    //$enable_arr = Html::html("color",array("value"=>Default_Model_DbTable_SysMenu::$enable_arr,"color"=>array("0"=>"red")));   
    foreach($pagelist as $key=>$row){
      
     
      

        $pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"修改","_width"=>"850","_height"=>"680","_title"=>"修改","_url"=>"/System/Sysversionintro/editversion?id=".Encode($row['id']));

        $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"400","_height"=>"300","_title"=>"删除","_url"=>"/System/Sysversionintro/delversion?id=".Encode($row['id']));
    

        $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
    }
    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$exportflag = false; //不导出
    $search = array(
        "title"=>array("type"=>"text","name"=>"标题","class"=>"width250"),
        "type"=>array("type"=>"select","name"=>"类型","class"=>"width250",'option'=>['1'=>"IOS",'2'=>"Android"]),
        "version"=>array("type"=>"text","name"=>"版本号","class"=>"width250"),
    );
    $button = array(
        "bt1"=>array("type"=>"newpage","name"=>"button","value"=>"添加版本介绍","_width"=>"850","_height"=>"680","_title"=>'添加版本介绍',"_url"=>"/System/Sysversionintro/addversion"),

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