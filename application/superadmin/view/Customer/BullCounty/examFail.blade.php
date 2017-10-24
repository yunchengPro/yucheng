{include file="Pub/header" /}
<style type="text/css">
    .item-right button{
        float: right;
    }
   
    .order-detail-container .orderBasicInfo ul{
        height: 150px;
    }
    .page-container{
        margin-bottom: 150px;
    }

    .btn
    {
        margin-top: 50px;
    }
    .addOrder{
        padding-top: 0;
    }
    .order_detail_title{
        margin-top:0;
        font-size: 18px;

    }
    .agintsLis textarea{
	   width:100%;
       height:150px;
    }
</style>
<div class="page-container addOrder">
	<div class="font-bold subtitle order_detail_title">拒绝理由</div>
	<div class="agintsLis">
		<form action="<?=$action_arr['updateExam']?>" method="post">
    		<textarea rows="" cols="" name="remark" placeholder="输入拒绝原因"></textarea>
    		<p class="text-c">
    			<button class="btn btn-danger" type="submit">确定</button>
    		</p>
		</form>
	</div>
</div>
{include file="Pub/footer" /}