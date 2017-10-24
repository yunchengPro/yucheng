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
        "linename"=>array("name"=>"线路名称","align"=>"left"),
        "metroname"=>array("name"=>"地铁名称","align"=>"left"),
        "pinyin" => array("name"=>"地铁名称拼音","align"=>"left"),
        "areaid"=>array("name"=>"所属地区","align"=>"left"),
        "sort"=>array("name"=>"排序",'align'=>"left"),
        "act"=>array("name"=>"操作","width"=>"200"),
    );

    //临时变量
    $temp_list = array();
    //系统三级菜单

   

  
    //$enable_arr = Html::html("color",array("value"=>Default_Model_DbTable_SysMenu::$enable_arr,"color"=>array("0"=>"red")));   
    foreach($pagelist as $key=>$row){
        
        $pagelist[$key]['areaid'] = $city[$row['areaid']];
        // $pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"设置密码","_width"=>"500","_height"=>"400","_title"=>"移除","_url"=>"#".Encode($row['id']));

        $pagelist[$key]['roleid'] = Model::ins('SysRole')->getRow(['id'=>$row['roleid']],'name')['name'];
        if($row['enable'] == 1){
            $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"禁用","_width"=>"500","_height"=>"400","_title"=>"禁用","_url"=>"/System/Sysmetro/setenable?enable=-1&id=".Encode($row['id']));
        }else{
             $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"启用","_width"=>"500","_height"=>"400","_title"=>"启用","_url"=>"/System/Sysmetro/setenable?enable=1&id=".Encode($row['id']));
        }

      

       
        $pagelist[$key]['act'][]= array("type"=>"popup","name"=>"button","value"=>"编辑","_width"=>"500","_height"=>"400","_title"=>"编辑","_url"=>"/System/Sysmetro/editMetro?id=".Encode($row['id']));
       
        $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"500","_height"=>"400","_title"=>"删除","_url"=>"/System/Sysmetro/delMetro?ids=".Encode($row['id']));
     
    

        $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
    }
    //帮助说明内容
    //$helpstr = "暂无帮助内容";
    //$exportflag = false; //不导出
    $search = array(
        "linename"=>array("type"=>"text","name"=>"线路名称","class"=>"width250"),
        "areaid"=>array("type"=>"select", "name"=>"所属区域", 'option' => $city, "value"=>$Metro['areaid']),
    );
    $button = array(
        "bt1"=>array("type"=>"popup_listpage","name"=>"button","value"=>"添加地铁","_width"=>"650","_height"=>"500","_title"=>'添加地铁',"_url"=>"/System/Sysmetro/addMetro"),

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