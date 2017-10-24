<?php
    $list_set = array(
        "checkbox" => array("name" => "选择"),
        "id" => array("name" => "序号"),
        "mobile" => array("name" => "用户"),
        "realname" => array("name" => "姓名"),
        "cashamount" => array("name" => "现金余额"),
        "profitamount" => array("name" => "收益现金余额"),
        "bullamount" => array("name" => "牛豆余额"),
        "comamount" => array("name" => "企业账户余额"),
        "act" => array("name" => "操作"),
    );
    
    foreach ($pagelist as $key => $row) {
        $pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value"=>"查看交易明细", "_width"=>"1000", "_height"=>"600", "_title"=>"查看交易明细", "_url"=>"/Amount/Index/flowLayout?id=".Encode($row['id']));
        $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
    }
    
    $search = array(
        "mobile" => array("type" => "text", "name" => "用户手机"),
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