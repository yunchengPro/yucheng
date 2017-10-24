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
        "id"=>array("name"=>"ID"),
        "thumb"=>array("name"=>"商品图片",'thumb'=>['width'=>120,'height'=>120]),
        "productname"=>array("name"=>"商品名称",),
        "businessname"=>array("name"=>"商家名称",),
        "mobile"=>array("name"=>"商家电话",),
        "categoryname"=>array("name"=>"商品分类"),
        "businesscategoryname"=>array("name"=>"店铺分类"),
        "supplyprice"=>array("name"=>"供货价"),
        "prouctprice"=>array("name"=>"商品价格"),
        "bullamount"=>array("name"=>"牛豆"),
        "enable"=>array("name"=>"状态", 'data_arr' => ['-1' => '下架', '1' => '上架', '2' => '违规下架','0'=>'下架']),
        "checksatus"=>array("name"=>"审核状态", 'data_arr' => ['-1' => '未通过', '0'=>'待审核','1' => '通过']),
        "addtime"=>array("name"=>"添加时间"),
        "act"=>array("name"=>"操作"),
    );

    /*
    前端显示数据处理
    也可以在controller中处理
    */
    foreach($pagelist as $key=>$row){
        
        $pagelist[$key]['supplyprice'] = "￥" . DePrice($row['supplyprice']);
        $pagelist[$key]['prouctprice'] = "￥" . DePrice($row['prouctprice']);
        $pagelist[$key]['bullamount'] = "￥" . DePrice($row['bullamount']);


        $pagelist[$key]['businessid'] = Db::Model("BusBusiness")->getRow(['id'=>$row['businessid']],'businessname')['businessname'];

        $pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"预览","_width"=>"500","_height"=>"400","_title"=>"预览<font color='green'>".$row['productname']."</font>","_url"=>"/Product/Index/look?goodsid=".Encode($row['id']));

        $pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"查看","_width"=>"500","_height"=>"400","_title"=>"查看<font color='green'>".$row['productname']."</font>","_url"=>"/Product/Index/lookProduct?goodsid=".Encode($row['id']));

        if($row['enable'] == 0 || $row['enable'] == 2 || $row['enable']<0)
            $pagelist[$key]['act'][]= array("type"=>"newpage","name"=>"button","value"=>"编辑","_width"=>"500","_height"=>"400","_title"=>"编辑<font color='green'>".$row['productname']."</font>","_url"=>"/Product/Index/editProduct?goodsid=".Encode($row['id']));

        if($row['enable'] == 0 || $row['enable'] == 2  || $row['enable']<0)
            $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"上架","_width"=>"500","_height"=>"400","_title"=>'是否确定上架该商品?',"_url"=>"/Product/Index/setEable?ids=".Encode($row['id'])."&enable=1".$paramString);

        if($row['enable'] == 1 )
                $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"下架","_width"=>"500","_height"=>"400","_title"=>'是否确定下架该商品?',"_url"=>"/Product/Index/setEable?ids=".Encode($row['id'])."&enable=-1".$paramString);

        if($row['enable'] == 2)
                $pagelist[$key]['act'][]= array("type"=>"popup_listpage","name"=>"button","value"=>"查看违规理由","_width"=>"500","_height"=>"400","_title"=>"查看<font color='green'>".$row['productname']."</font>违规理由","_url"=>"/Product/Index/lookDisenbaleReason?goodsid=".Encode($row['id']));
            
        if($row['enable'] != 1)
            $pagelist[$key]['act'][]= array("type"=>"confirm","name"=>"button","value"=>"删除","_width"=>"500","_height"=>"200","_title"=>'是否确定删除该记录?',"_url"=>"/Product/Index/delgoods?ids=".Encode($row['id']).$paramString);

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
        "productname"=>array("type"=>"text","name"=>"商品名称/关键字","value"=>""),
        "category"=>array("type"=>"category","name"=>"商品分类","class"=>"width250"),
        "businesscategoryid"=>array("type"=>"select","name"=>"店铺分类名称","value"=>"", 'option' => $businessCategoryArr),
        "enable"=>array("type"=>"select","name"=>"商品状态","value"=>"", 'option' => ['-1' => '下架', '1' => '上架', '2' => '违规下架']),
        "checksatus"=>array("type"=>"select","name"=>"审核状态","value"=>"",'option'=>['-1' => '未通过', '0'=>'待审核','1' => '通过']),
        "addtime"=>array("type"=>"times","name"=>"发布时间","value"=>""),
    );

    //自动生成按钮
    $button = array(
            "bt1"=>array("type"=>"popup_listpage","name"=>"button","value"=>"添加商品","_width"=>"500","_height"=>"300","_title"=>'添加商品',"_url"=>"/Product/Index/addProductStepone"),

            "bt2"=>array("type"=>"confirm_all","name"=>"button","value"=>"批量删除","_width"=>"500","_height"=>"200","_title"=>'删除选中项?',"_url"=>"/Product/Index/delgoods?rel=delete".$paramString),

            "bt3"=>array("type"=>"confirm_all","name"=>"button","value"=>"批量上架","_width"=>"500","_height"=>"200","_title"=>'批量上架选中项?',"_url"=>"/Product/Index/setEable?enable=1".$paramString),
            "bt4"=>array("type"=>"confirm_all","name"=>"button","value"=>"批量下架","_width"=>"500","_height"=>"200","_title"=>'批量下架选中项?',"_url"=>"/Product/Index/setEable?enable=-1".$paramString)
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
