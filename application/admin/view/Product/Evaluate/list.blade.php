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
        "orderno"=>array("name"=>"订单编号",),
        "productname"=>array("name"=>"商品名称"),
        "frommembername"=>array("name"=>"买家"),
        "ordertime"=>array("name"=>"下单时间"),
        "addtime"=>array("name"=>"评论时间"),
        "content"=>array("name"=>"评价内容"),
        "complaints"=>array("name"=>"申述内容"),
        "scores"=>array("name"=>"评分"),
        "act"=>array("name"=>"操作"),
    );

    /*
    前端显示数据处理
    也可以在controller中处理
    */
    foreach($pagelist as $key=>$row){
        $pagelist[$key]['ordertime'] = Db::Model("OrdOrder")->getRow(['orderno'=>$row['orderno']],'addtime')['addtime'];
        
        $pagelist[$key]['content'] = cubstr($row['content'], 0,100);
        
        $pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"回复","_width"=>"550","_height"=>"450","_title"=>"回复","_url"=>"/Product/Evaluate/reply?id=".Encode($row['id']));


        if($row['evaluateauto'] == 2)
            $pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"申述","_width"=>"550","_height"=>"450","_title"=>"申述","_url"=>"/Product/Evaluate/complaints?id=".Encode($row['id']));

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
        "orderno"=>array("type"=>"text","name"=>"订单编号","value"=>""),
        "productname"=>array("type"=>"text","name"=>"商品名称","value"=>""),
        "frommembername"=>array("type"=>"text","name"=>"买家","value"=>""),
        "addtime"=>array("type"=>"times","name"=>"评论时间","value"=>"")
    );

    // //自动生成按钮
    // $button = array(
    //         "bt1"=>array("type"=>"popup_listpage","name"=>"button","value"=>"添加品牌","_width"=>"550","_height"=>"450","_title"=>'添加品牌',"_url"=>"/Product/Brand/addBrand"),

    //         "bt2"=>array("type"=>"confirm_all","name"=>"button","value"=>"批量删除","_width"=>"500","_height"=>"200","_title"=>'删除选中项?',"_url"=>"/Product/Brand/delBrand?rel=delete")
        // );

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