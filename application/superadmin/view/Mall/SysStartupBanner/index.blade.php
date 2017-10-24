<?php
    use app\lib\Model;
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
        "bname"=>array("name"=>"图片标题","align"=>"left"),
        "thumb"=>array("name"=>"图片链接","align"=>"left",'thumb'=>['width'=>250,'height'=>100]),
        "urltype"=>array("name"=>"跳转类型","align"=>"left",'data_arr'=>$skipurl),
        "url"=>array("name"=>"跳转连接",'align'=>"left"),
        "addtime"=>array("name"=>"添加时间",'align'=>"left"),
        "sort"=>array("name"=>"排序",'align'=>"left","data_sort"=>true,"_url"=>"/Mall/SysStartupBanner/sort",'sort'=>true),
        "enable"=>array("name"=>"启用状态",'align'=>"left",'data_arr'=>['-1'=>"禁用","1"=>"启用"]),
        "banner_type"=>array("name"=>"启动类型",'align'=>"left",'data_arr'=>['1'=>"打开就启动","2"=>"只启动一次"]),
        "time"=>array("name"=>"显示时间",'align'=>"left"),
        "act"=>array("name"=>"操作","width"=>"200"),
    );

    //临时变量
    $temp_list = array();
    //系统三级菜单
  
    //$enable_arr = Html::html("color",array("value"=>Default_Model_DbTable_SysMenu::$enable_arr,"color"=>array("0"=>"red")));   
    foreach($pagelist as $key=>$row){
      
      
        if($row['enable'] == -1){
            $enable_int = 1;
            $enable_str = '启用';
        }else{
            $enable_int = -1;
            $enable_str = '禁用';
        }

        $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>$enable_str,"_width"=>"650","_height"=>"500","_title"=>$enable_str,"_url"=>"/Mall/SysStartupBanner/enableBanner?id=".Encode($row['id'])."&enable=".$enable_int);

        $pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"修改","_width"=>"650","_height"=>"500","_title"=>"修改","_url"=>"/Mall/SysStartupBanner/editBanner?id=".Encode($row['id']));

         $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"400","_height"=>"300","_title"=>"删除","_url"=>"/Mall/SysStartupBanner/delBanner?ids=".Encode($row['id']));
    

        $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
    }
    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$exportflag = false; //不导出
    $search = array(
        "bname"=>array("type"=>"text","name"=>"图片标题","class"=>"width250"),
    );
    $button = array(
        "bt1"=>array("type"=>"popup_listpage","name"=>"button","value"=>"添加启动图片","_width"=>"650","_height"=>"500","_title"=>'添加启动图片',"_url"=>"/Mall/SysStartupBanner/addBanner"),

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