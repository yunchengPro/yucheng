<?php 
    $list_set = array(
        "checkbox" => array("name" => "选择"),
        "id" => array("name" => "序号"),
        "orderno" => array("name" => "订单号"),
        "cust_name" => array("name" => "姓名"),
        "thumb" => array("name" => "商品图片", "thumb"=>["width"=>"50","height"=>"50"]),
        "productname" => array("name" => "商品名称"),
        "mobile" => array("name" => "手机号码"),
        "logisticsRealname" => array("name" => "收货人"),
        "logisticsMobile" => array("name" => "收货人电话"),
        "logisticsAddress" => array("name" => "收货地址"),
        "role" => array("name" => "角色身份", "data_arr" => ["2" => "牛人", "3" => "牛创客"]),
        "addtime" => array("name" => "提交时间"),
        "orderstatus" => array("name" => "状态", "data_arr" => ["1" => "已付款待发货", "2" => "已发货", "3" => "确认收货", "4" => "订单完结", "5" => "取消"]),
        "act" => array("name" => "操作"),
    );
    
    foreach ($pagelist as $key => $row) {
        if($row['orderstatus'] == 1) 
            $pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value" => "发货", "_width" => "450", "_height" => "400", "_title" => "发货", "_url" => "/Order/Index/setGiftExpress?id=".Encode($row['id']));
        
        if($row['orderstatus'] == 2)
            $pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value" => "修改物流信息", "_width" => "650", "_height" => "550", "_title" => "修改物流信息", "_url" => "/Order/Index/setGiftExpress?id=".Encode($row['id']));
        
        if($row['orderstatus'] != 0 && $row['orderstatus'] != 1)
            $pagelist[$key]['act'][] = array("type" => "popup_listpage", "name" => "button", "value" => "查看物流信息", "_width" => "1000", "_height" => "600", "_title" => "查看物流信息", "_url" => "/Order/Index/lookGiftLogistics?id=".Encode($row['id']));
        $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
    }
    
    $search = array(
        "role" => array("type" => "select", "name" => "角色类型", "option" => array("2" => "牛人", "3" => "牛创客")),
        "orderstatus" => array("type" => "select", "name" => "订单状态", "option" => array("1" => "待发货", "2" => "已发货")),
        "orderno" => array("type" => "text", "name" => "订单号"),
        "addtime" => array("type" => "times", "name" => "提交时间"),
        "productname" => array("type" => "text", "name" => "商品名称"),
        "cust_name" => array("type" => "text", "name" => "姓名"),
        "mobile" => array("type" => "text", "name" => "手机号码"),
    );
    $export_url = "/order/index/exportGift"; //导出数据的action
//     $button = array(
//         "bt1" => array("type" => "popup_listpage","name"=>"button", "value" =>"导出excel", "_width"=>"650","_height"=>"550","_title"=>"导出excel","_url"=>"/Order/Index/reportGiftData"),
//     );
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