<?php

    $list_set = array(
        "checkbox" => array("name" => "选择"),
        "id" => array("name" => "序号"),
        "nickname" => array("name" => "名称"),
        "mobile" => array("name" => "手机号码"),
//         "total_amount" => array("name" => "分润总额"),
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
                  
           <input type="button" value="返回粉丝列表页" onclick="javascript:history.back()">
                  
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