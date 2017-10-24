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
        //"checkbox"=>array("name"=>"选择"),
        "id"=>array("name"=>"序号","width"=>"50"),
        //"username"=>array("name"=>"用户名"),
        "mobile"=>array("name"=>"手机","width"=>"50"),
        "realname" => array("name"=>"实名","width"=>"50"),
        "nickname" => array("name"=>"昵称","width"=>"50"),
        "busname" => array("name"=>"商家名称","width"=>"50"),
        "stoname" => array("name"=>"店铺名称","width"=>"50"),
        "cusrole" => array("name"=>"开通角色","width"=>"100"),
        "enable"=>array("name"=>"状态", 'data_arr' => ['1' => '启用', '-1'=>'禁用'],"width"=>"50"),
        "usercount"=>array("name"=>"粉丝数","width"=>"50"),
        "amount"=>array("name"=>"分享奖励","width"=>"50"),
       // "lastlogintime"=>array("name"=>"上次登录时间"),
        //"createtime"=>array("name"=>"创建时间","width"=>"50"),
        "act"=>array("name"=>"操作","width"=>"50"),
    );

    /*
    前端显示数据处理
    也可以在controller中处理
    */
    foreach($pagelist as $key=>$row){
//         $pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"查看", "_width"=>"1100","_height"=>"650","_title"=>"查看","_url"=>"/Amount/User/showusercount?userid=".$row['id']);
        $pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"查看", "_width"=>"1100","_height"=>"650","_title"=>"查看","_url"=>"/Amount/User/showusercount?userid=".$row['id']);
        
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
        "mobile"=>array("type"=>"text", "name"=>"手机号码","value"=>""),
 	    "busname"=>array("type"=>"text","name"=>"商家名称"),
 	    "stoname"=>array("type"=>"text","name"=>"店铺名称"),
        "userid"=>array("type"=>"hidden","value"=>$userid),
        "roletype"=>array("type"=>"hidden","value"=>$roletype),
    );

    //自动生成按钮
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