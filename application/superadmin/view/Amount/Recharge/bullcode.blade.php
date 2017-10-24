<?php
    $list_set = array(
        "checkbox" => array("name" => "选择"),
        "id" => array("name" => "序号"),
        "show_code" => array("name" => "卡号"),
        "addtime" => array("name" => "兑换时间"),
        "status" => array("name" => "状态", "data_arr" => array("0" => "未使用", "1" => "已使用")),
    );
    
    $search = array(
        "show_code" => array("type" => "text", "name" => "卡号"),
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