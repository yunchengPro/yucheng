<?php
use app\lib\Db;

	$title = $title;
	
	$list_set = array(
	    "num" => array("name"=>"序号"),
	    "productname" => array("name"=>"商品名称"),
	    "businessname" => array("name"=>"归属商家"),
	    "act" => array("name"=>"操作"),
	);
	
	foreach ($pagelist as $key => $row) {
	    $pagelist[$key]['act'][] = array("type" => "button", "name" => "button", "value" => "选择", "_width" => "500", "_height" => "400", "_title" => "选择该商品", "class"=>"test");
	    $pagelist[$key]['act'] = Html::Htmlact($pagelist[$key]['act']);
	}
	
    $search = array(
    	"productname"=>array("type"=>"text", "name"=>"商品名称","value"=>""),
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
<script type="text/javascript">
	$(function(){
		$(".test").click(function(){
			// 获取弹出层
			var index = parent.layer.getFrameIndex(window.name);
			// 获取用户点击的商品id
			var goodsid = $(this).parent().parent().attr('_rowid');

			// 异步请求商品信息
			$.ajax({
				type:'POST',
				data:{goodsid:goodsid},
				url:'/Product/activityGoods/ajaxGoodsInfo',
				async:false,
				dataType:'json',
				success:function(res) {
					data = eval("("+res+")");
					parent.$("#productname").val(data.productname);
					parent.$("#productid").val(goodsid);
					parent.$("#supplyprice").val(data.supplyprice);
					parent.$("#prouctprice").val(data.prouctprice);
					parent.$("#saleprice").val(data.saleprice);
					parent.$("#bullamount").val(data.bullamount);
				}
			});

			// 关闭弹出层
			parent.layer.close(index);
			return false;
		});
	});
</script>
<?php if ($full_page){?>
<!---尾部-->
{include file="Pub/pubTail" /}

{include file="Pub/footer" /}
<!---尾部-->
<?php }?>