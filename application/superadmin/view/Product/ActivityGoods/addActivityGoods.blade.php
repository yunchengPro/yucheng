{include file="Pub/header" /}
<style>
	#fModi{
	    margin-top: 30px;
	}
	.tab_style_base tr td  .label{
	    display: block;
	}

	.tabBar{
	    border:none;
	}

	.table  tr{
	    height: 50px;
	}
	.table  tr td{
	  border:none;
	}
	.table_view tr td{
	  border:none;
	}

	.tab_style_base tr td  .label{
	    display: block;
	}
	.tab_style_base tr td  .label{
	    margin-top: 2px;
	    text-align: right;
	    color: #555;
	    display: block;
	    font-weight: normal;
	    width: 250px;
	    background-color:#FFF;
	}

	.table  tr td{
	    border:none;
	    box-sizing: border-box;
	    margin-left: -15px;
	    margin-right: -15px;
	    margin-top: 15px;
	}
	.lefttd {
		width: 20%;
		text-align: right;
	}
	.righttd {
		width: 80%;
		text-align: left;
	}
	.spec-bg { background-color: #FCFCFC;}
	.spec li { vertical-align: top; letter-spacing: normal; word-spacing: normal; display: inline-block; *display: inline/*IE6,7*/; margin-left: 10px; margin-bottom: 6px; zoom: 1;}
	.ncsc-form-radio-list li{float: left;margin-right: 10px;}
	.active{display: inline-block;}
	.button-group  select{
	    margin-bottom: 10px;
	}
	.table tr:nth-child(2n){
	    background-color: #FFF;
	}
	.button-group-small{
	    width: 100%;
	}
	.tab_style_base tr td  .label label{
	    margin-top: 2px;
	    text-align: right;
	    color: #555;
	    display: block;
	    font-weight: normal;
	    border:  1px solid #CCC;

	    
	}
	.tab_style_base_new tr td {
	    border:  1px solid #CCC;
	}
	.table_header tr th:last-child {
	    border-right: 1px solid #ccc;
	}

	.ncsc-form-radio-list li {
	    float: left;
	    margin-right: 10px;
	}
</style>

<div>
	<form id="fGoods" method="post"  rel="iframe-form" class="form-x" action="<?=$actionUrl?>">
		<table class="table table_header tab_style_base table_view">
			<tr>
				<td class="lefttd"></td>
				<td class="righttd">
					<div>温馨提醒：本活动仅限于已入驻平台的商品参与;</div>
				</td>
			</tr>
			<tr>
				<td class="lefttd"><div class="label"><span class="red">*</span>活动开始时间：</div></td>
				<td class="righttd">
					<div><?php echo Html::time(array("name"=>"starttime", "value"=>$productBuy['starttime'], "dateFmt"=>2)); ?></div>
				</td>
			</tr>
			<tr>
				<td class="lefttd"><div class="label"><span class="red">*</span>活动结束时间：</div></td>
				<td class="righttd">
					<div><?php echo Html::time(array("name"=>"endtime", "value"=>$productBuy['endtime'], "dateFmt"=>2)); ?></div>
				</td>
			</tr>
			<tr>
				<td class="lefttd"><div class="label"><span class="red">*</span>活动商品：</div></td>
				<td class="righttd">
					<div><?php echo Html::button(array("type"=>"popup_listpage","name"=>"button","value"=>"搜索","_width"=>"1000","_height"=>"700","_title"=>"搜索商品","_url"=>"/Product/ActivityGoods/searchGoods"));?>
					<input type="text" disabled="disabled" id="productname" class="input-text width250" value="<?=$productBuy['productname']?>">
					</div>
					<input type="hidden" name="productid" id="productid" value="<?=$productBuy['productid']?>">
				</td>
			</tr>
			<tr>
				<td class="lefttd"><div class="label">供货价：</div></td>
				<td class="righttd">
					<div><input type="text" name="supplyprice" class="input-text width250" id="supplyprice" value="<?=DePrice($productBuy['supplyprice'])?>"></div>
				</td>
			</tr>
			<tr>
				<td class="lefttd"><div class="label">活动价格：</div></td>
				<td class="righttd">
					<div><input type="text" name="prouctprice" id="prouctprice" class="input-text width250" value="<?=DePrice($productBuy['prouctprice'])?>"></div>
				</td>
			</tr>
			<tr>
				<td class="lefttd"><div class="label">销售价格：</div></td>
				<td class="righttd">
					<div><input type="text" name="saleprice" id="saleprice" class="input-text width250" value="<?=DePrice($productBuy['saleprice'])?>"></div>
				</td>
			</tr>
			<tr>
				<td class="lefttd"><div class="label">牛豆数：</div></td>
				<td class="righttd">
					<div><input type="text" name="bullamount" id="bullamount" class="input-text width250" value="<?=DePrice($productBuy['bullamount'])?>"></div>
				</td>
			</tr>
			<tr>
				<td class="lefttd"><div class="label">活动库存：</div></td>
				<td class="righttd">
					<div><input type="text" name="productstorage" id="productstorage" class="input-text width250" value="<?=$productBuy['productstorage']?>"></div>
				</td>
			</tr>
			<?php if($productBuy['activeproductnum'] > 0) { ?>
				<tr>
					<td class="lefttd"><div class="label">参与活动的商品数量：</div></td>
					<td class="righttd">
						<div><input type="text" name="activeproductnum" id="activeproductnum" class="input-text width250" value="<?=$productBuy['activeproductnum']?>"></div>
					</td>
				</tr>
			<?php }?>
			<tr>
				<td class="lefttd"><div class="label">单人限购数量：</div></td>
				<td class="righttd">
					<div><input type="text" name="limitbuy" id="limitbuy" class="input-text width250" value="<?=!empty($productBuy['limitbuy']) ? $prductBuy['limitbuy'] : -1?>">件 （只能填写-1或者大于0，填写-1代表不限购）</div>
				</td>
			</tr>
			<?php if(empty($productBuy['enable'])) {?>
			<tr>
				<td class="lefttd"></td>
				<td class="righttd">
					<input type="radio" name="enable" value="1" <?php if($productBuy['enable']==1){ ?>checked="checked"<?php }?>>上架
					<input type="radio" name="enable" value="-1" <?php if($productBuy['enable']==-1){ ?>checked="checked"<?php }?>>下架
				</td>
			</tr>
			<?php }?>
			<tr>
				<td class="lefttd"></td>
				<td class="righttd">
					<div class="form-button" style="margin:0;padding:0">
						<button id="submit" class=" btn btn-danger" value="提交" type="submit">提交</button>
					</div>
				</td>
			</tr>
		</table>
	</form>
</div>

<!--
<link rel="stylesheet" href="/jQueryFileUpload/css/fileupload.css">
<script src="/jQueryFileUpload/js/vendor/jquery.ui.widget.js"></script>
<script src="/jQueryFileUpload/js/load-image.all.min.js"></script>
<script src="/jQueryFileUpload/js/canvas-to-blob.min.js"></script>
<script src="/jQueryFileUpload/js/jquery.iframe-transport.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload-process.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload-image.js"></script>
<script src="/jQueryFileUpload/js/jquery.fileupload-validate.js"></script>
<script src="/jQueryFileUpload/js/fileupload.js"></script>
-->


<!---文本编辑框的js-->
<!--
<script type="text/javascript" charset="utf-8" src="/Ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Ueditor/ueditor.all.min.js"> </script>
-->
<script type="text/javascript">
	// function(){
	// 	$("#searchGoods").onclick(function(){

	// 	});
	// }
</script>
{include file="Pub/footer" /}