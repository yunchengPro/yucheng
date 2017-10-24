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
        "numid"=>array("name"=>"序号"),
        "productname"=>array("name"=>"商品名称",),
        "sort" => array("name" => "排序"),
        "businessname"=>array("name"=>"商家名称"),
        "supplyprice"=>array("name"=>"供货价"),
        "type" => array("name" => "角色类型商品"),
        "enable"=>array("name"=>"上下架状态", 'data_arr' => ['-1' => '下架', '1' => '上架', '2' => '违规下架']),
//         "checksatus"=>array("name"=>"审核状态", 'data_arr' => ['-1' => '未通过', '0'=>'待审核','1' => '通过']),
        "addtime"=>array("name"=>"添加时间"),
        "act"=>array("name"=>"操作"),
    );

    /*
    前端显示数据处理
    也可以在controller中处理
    */
    $search = array(
        "productname"=>array("type"=>"text","name"=>"商品名称","value"=>""),
        "businessname"=>array("type"=>"text","name"=>"商家名称","value"=>""),
        "role" => array("type"=>"select","name" => "角色类型商品","value"=>"", 'option' => ['2' => '牛人', '3' => '牛创客']),
//         "enable"=>array("type"=>"select","name"=>"上下架状态","value"=>"", 'option' => ['-1' => '下架', '1' => '上架', '2' => '违规下架']),
        "enable"=>array("type"=>"select","name"=>"上下架状态","value"=>"", 'option' => ['-1' => '下架', '1' => '上架']),
//         "checksatus"=>array("type"=>"select","name"=>"审核状态","value"=>"", 'option' => ['-1' => '未通过', '0'=>'待审核','1' => '通过']),
        "addtime"=>array("type"=>"times","name"=>"提交时间","value"=>""),
    );
    
    foreach ($pagelist as $key => $row) {
        if($row['enable'] != 1) {
            $pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"修改","_width"=>"650","_height"=>"550","_title"=>"修改礼品信息", "_url"=>"/Product/Index/editGiftInfo?id=".Encode($row['id']));         
            $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"上架", "_width"=>"500","_height"=>"200","_title"=>"是否上架该礼品","_url"=>"/Product/Index/enableGift?id=".Encode($row['id'])."&status=1");
        }
        if($row['enable'] == 1)
            $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"下架", "_width"=>"500","_height"=>"200","_title"=>"是否下架该礼品","_url"=>"/Product/Index/enableGift?id=".Encode($row['id'])."&status=-1");
        $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"删除", "_width"=>"500","_height"=>"200","_title"=>"删除","_url"=>"/Product/Index/enableGift?id=".Encode($row['id'])."&status=2");
        $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
    }

    //自动生成按钮
    $button = array(
//             "bt1"=>array("type"=>"popup_listpage","name"=>"button","value"=>"添加商品","_width"=>"500","_height"=>"300","_title"=>'添加商品',"_url"=>"/Product/Index/addProductStepone"),

        "bt1"=>array("type"=>"popup_listpage","name"=>"button","value"=>"添加礼品","_width"=>"650","_height"=>"600","_title"=>'添加礼品',"_url"=>"/Product/Index/addRoleProduct"),

//             "bt2"=>array("type"=>"confirm_all","name"=>"button","value"=>"批量删除","_width"=>"500","_height"=>"200","_title"=>'删除选中项?',"_url"=>"/Product/Index/delProduct?rel=delete")
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
            <button _rel="newpage" id="goto2" style="display: none;" name="button" class="button " _title="添加产品" _width="500" _height="300" type="newpage" _url="">添加产品</button>

           
                  
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
<script type="text/javascript">
function goto2(url)
{
    //alert(url);
    $("#goto2").attr("_url", url);
    $("#goto2").click();
}
</script>
