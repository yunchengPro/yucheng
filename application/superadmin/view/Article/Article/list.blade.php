<?php
use app\lib\Db;

	$title = $title;
	/*
    设置列表，表头
    "mobile"=>array("name"=>"所属分会"),"sort"=>true,"width"=>"120","head_str"=>"width='30' nowrap","align"=>"left"),
    mobile 显示的字段
    name     表头名称
    sort     是否排序，默认false 不排序
    width    列宽度
    head_str 表头的其他信息
    data_str 数据其他信息
    align    设置内容项位置：left,center,right 默认是居中center
    nowrap   设置表格内容过长时是否进行缩进，默认false
    */
    $list_set = array(
        "checkbox"=>array("name"=>"选择"),
        "title"=>array("name"=>"标题"),
        "categoryid" =>array('name'=>'分类','data_arr'=>$category_arr),
        "citycode"=>array('name'=>'所属地区'),
        "thumb"=>array('name'=>'缩略图','thumb'=>['width'=>100,'height'=>50]),
        'addtime'=>array('name'=>'添加时间'),
        'isrelease'=>array('name'=>'是否发布','data_arr'=>$release_arr),
        'newstype'=>array('name'=>'文章类型','data_arr'=>$newstype),
        "sort" => array('name'=>'排序',"data_sort"=>true,"_url"=>"/Article/Category/sort",'sort'=>true),
        "act"=>array("name"=>"操作"),
    );

    /*
    前端显示数据处理
    也可以在controller中处理
    */
    foreach($pagelist as $key=>$row){
        
        if($row['istop'] ==1){
            $top = -1;
            $str = '取消置顶';
        }else{
            $top = 1;
            $str = '置顶';
        }
        if($row['isrelease'] == 0 || $row['isrelease'] ==2){
            $release = 1;
            $releasestr = '发布';
        }
        if($row['isrelease'] == 1){
            $release = 2;
            $releasestr = '下线';

        }

        $pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"查看","_width"=>"550","_height"=>"400","_title"=>"编辑<font color='green'>".$row['title']."</font>","_url"=>"/Article/Article/look?aid=".Encode($row['id']));

        $pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"编辑","_width"=>"550","_height"=>"400","_title"=>"编辑<font color='green'>".$row['title']."</font>","_url"=>"/Article/Article/editarticle?id=".Encode($row['id'])."&type=".$type);

        $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>$releasestr,"_width"=>"500","_height"=>"400","_title"=>"确定".$releasestr."?","_url"=>"/Article/Article/setarticlerelease?ids=".Encode($row['id'])."&release=".$release);

         $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>$str,"_width"=>"500","_height"=>"400","_title"=>"确定".$str."?","_url"=>"/Article/Article/setarticletop?ids=".Encode($row['id'])."&top=".$top);

        $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"500","_height"=>"200","_title"=>'是否确定删除该记录?',"_url"=>"/Article/Article/delarticle?ids=".Encode($row['id'])."&type=".$type);
        
         

        $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
        
	}

    /*
    设置搜索项
    "keyword"=>array("type"=>"text","name"=>"商品名称/关键字","value"=>$this->_request->getParam("keyword")),
    "keyword" 表示搜索项的名称
    "type"    类型：text | select | time | times 
    "value"   初始化值，之后的值自动获取到，但是controller层需要把数据提交过来
    "name"    中文说明
    "option"  设置select类型的下拉选项
    "dateFmt" 1-年月日（默认） 2-年月日时分秒 3-年月 4-年  用于时间控件
	
    说明：times类型下，表单的命名自动在前面加"start_"和"end_"   
          显示的值  自动获取 
    */
 	$search = array(
       "title"=>array("type"=>"text","name"=>"文章标题/关键字","value"=>""),
       "categoryid"=>array("type"=>"select","name"=>"分类","value"=>"", 'option' =>$category_arr),
       "isrelease"=>array("type"=>"select","name"=>"发布状态","value"=>"", 'option' =>$release_arr),
       'addtime'=>array("type"=>"times","name"=>"添加时间","value"=>""),
       'type'=>array("type"=>"hidden","name"=>"类型","value"=>$type),
    );

    //自动生成按钮
    $button = array(
            "bt1"=>array("type"=>"newpage","name"=>"button","value"=>"添加资讯","_width"=>"550","_height"=>"400","_title"=>'添加资讯',"_url"=>"/Article/Article/addarticle?type=".$type),

            "bt2"=>array("type"=>"confirm_all","name"=>"button","value"=>"批量下线","_width"=>"500","_height"=>"200","_title"=>'批量下线?',"_url"=>"/Article/Article/setarticlerelease?release=2"),
            
            "bt3"=>array("type"=>"confirm_all","name"=>"button","value"=>"批量发布","_width"=>"500","_height"=>"200","_title"=>'批量发布?',"_url"=>"/Article/Article/setarticlerelease?release=1")
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