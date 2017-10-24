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
        "id"=>array("name"=>"序号",),
        "customer_mobile"=>array("name"=>"用户手机"),
        //"bankid"=>array("name"=>"银行卡ID"),
        "bank_type_name"=>array("name"=>"银行名称"),
        "account_name"=>array("name"=>"银行开户名"),
        "account_number"=>array("name"=>"银行账号"),
        "branch"=>array("name"=>"支行名称"),
        "mobile"=>array("name"=>"银行卡手机号"),
        "addtime"=>array("name"=>"提交时间"),
        "status"=>array("name"=>"提现状态", 'data_arr' => ['0' => '处理中', '1'=>'到帐成功', '2'=>'提现失败']),
        "remark"=>array("name"=>"未通过原因"),
        "amount"=>array("name"=>"提现总金额"),
        "cashamount"=>array("name"=>"现金金额"),
        "comamount"=>array("name"=>"企业金额"),
        "pay_money"=>array("name"=>"实际支付金额"),
        "pay_time"=>array("name"=>"支付时间"),
        "handle_user"=>array("name"=>"处理人"),
        "query_sn"=>array("name"=>"交易流水号"),
        "act"=>array("name"=>"操作"),
    );

    /*
    前端显示数据处理
    也可以在controller中处理
    */
    foreach($pagelist as $key=>$row){
        
        //$pagelist[$key]['businessid'] = Db::Model("BusBusiness")->getRow(['id'=>$row['businessid']],'businessname')['businessname'];

        if($pagelist[$key]['status'] == 0){
            $pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"通过","_width"=>"500","_height"=>"200","_title"=>'实际支付金额',"_url"=>"/Amount/Withdrawals/pass?id=".Encode($row['id']));
            $pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"不通过","_width"=>"650","_height"=>"550","_title"=>'不通过原因',"_url"=>"/Amount/Withdrawals/noPass?id=".Encode($row['id']));
        }

        //$pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"编辑","_width"=>"550","_height"=>"450","_title"=>"编辑","_url"=>"/Mall/Banner/editBanner?id=".Encode($row['id']));

        //$pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"500","_height"=>"200","_title"=>'是否确定删除该记录?',"_url"=>"/Mall/Banner/delBanner?ids=".Encode($row['id']));

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
        //"status"=>array("type"=>"select","name"=>"状态", 'option' => ['0' => '待审核', '1'=>'通过', '2'=>'未通过'])
        "customer_mobile" => array("type" => "text", "name" => "用户手机号", "value" => ""),
        "account_name" => array("type" => "text", "name" => "银行开户名", "value" => ""),
        "account_number" => array("type" => "text", "name" => "银行账号", "value" => ""),
        "branch" => array("type" => "text", "name" => "支行名称", "value" => ""),
        "mobile" => array("type" => "text", "name" => "银行卡手机号", "value" => ""),
        "orderno" => array("type" => "text", "name" => "订单号", "value" => ""),
        "addtime" => array("type" => "times", "name" => "提交时间", "value" => ""),
        "query_sn"=> array("type" => "text", "name" => "交易流水号", "value" => ""),

    );

//    //自动生成按钮
//    $button = array(
//            "bt1"=>array("type"=>"popup_listpage","name"=>"button","value"=>"添加广告","_width"=>"550","_height"=>"450","_title"=>'添加广告',"_url"=>"/Mall/Banner/addBanner"),
//
//            "bt2"=>array("type"=>"confirm_all","name"=>"button","value"=>"批量删除","_width"=>"500","_height"=>"200","_title"=>'删除选中项?',"_url"=>"/Mall/Banner/delBanner?rel=delete")
//        );
    $export_url = "/Amount/Withdrawals/exportlist"; //导出数据的action
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
			提现总额：<?=$total_amount?>&nbsp;&nbsp;&nbsp;&nbsp;
            当前列表总额：<?=$search_amount?>
			<!---列表+分页-->		
			{include file="Pub/pubList" /}
			<!---列表+分页-->
<?php if ($full_page){?>
<!---尾部-->
{include file="Pub/pubTail" /}
{include file="Pub/footer" /}
<!---尾部-->
<?php }?>