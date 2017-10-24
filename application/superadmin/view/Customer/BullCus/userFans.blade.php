{include file="Pub/header" /}
<style type="text/css">
/*     .item-right button{ */
/*         float: right; */
/*     } */
   
/*     .order-detail-container .orderBasicInfo ul{ */
/*         height: 150px; */
/*     } */
    .page-container{
        margin-bottom: 150px;
    }

/*     .btn */
/*     { */
/*         margin-top: 50px; */
/*     } */
    .addOrder{ 
        padding-top: 0;
    }
    .order_detail_title{
        margin-top:0;
        font-size: 18px;

    }
    .agintsLi ul li{
        height: 40px;
        line-height: 40px;
        font-size: 16px;
    }
    .agintsLi .tip{
        display: inline-block;
        float: left;
        width: 125px;
        text-align: right;
        margin-right: 10px;
    }
    .agintsLi a{
        color: #06c;

    }
</style>
<div class="page-container">
	<div class="font-bold subtitle order_detail_title"></div>
	<div class="agintsLi">
		<ul>
			<?php foreach ($fansList as $fans) {?>
				<li><span class="tip"><?php echo $fans['name'].'列表:';?></span><span class="c-999"><a href="<?php echo $fans['url']?>">查看</a></span></li>
			<?php }?>
		</ul>
	</div>
</div>
{include file="Pub/footer" /}