<?php 
	$page_param = pagesParam(array("total"=>$total,'page'=>$page,'pagesize'=>$pagesize));
?>

<?php if ($total): ?>
<div class="paginationControl" style="white-space:nowrap;">
总计<?php echo $page_param['pages'];?> 页,&nbsp;当前第 <?php echo $page;?> 页,&nbsp;共计 <?php echo $total;?> 条&nbsp;
每页 &nbsp;<input type="text" class="input-text page-input" name="pageSize" id="pageSize" size="3" value="<?=$pagesize?>" onkeypress="return listTable.changePageSize(event)" />&nbsp;
<!-- first page -->
<?php if($page!=1):?>
<a onclick="javascript:listTable.pageClick('<?php echo goPageUrl($urlPath,1);?>');" href="javascript:void(0);" class="page_list">首页</a> |
<?php //else:?>
<!-- <span class="disabled">首页</span> | -->
<?php endif;?>
<!-- Previous page link -->
<?php if (isset($page_param['previous_page'])): ?>
<a onclick="javascript:listTable.pageClick('<?php echo goPageUrl($urlPath,$page_param['previous_page']);?>');" href="javascript:void(0);" class="page_list">上一页</a> |
<?php //else: ?>
<!-- <span class="disabled">上一页</span> | -->
<?php endif; ?>

<!-- Numbered page links -->
<?php foreach ($page_param['pagesInRange'] as $value): ?>
<?php if ($page != $value): ?>
<a onclick="javascript:listTable.pageClick('<?php echo goPageUrl($urlPath,$value);?>');" href="javascript:void(0);" class="page_list"><?= $value; ?></a> |
<?php else: ?>
<B><?= $value; ?> </B>|
<?php endif; ?>
<?php endforeach; ?>
<!-- Next page link -->
<?php if (isset($page_param['next_page'])): ?>
<a onclick="javascript:listTable.pageClick('<?php echo goPageUrl($urlPath,$page_param['next_page']);?>');" href="javascript:void(0);" class="page_list">下一页</a> |
<?php //else: ?>
<!-- <span class="disabled">下一页</span> | -->
<?php endif; ?>
<!-- last page -->
<?php if($page!=$page_param['pages']):?>
<a onclick="javascript:listTable.pageClick('<?php echo goPageUrl($urlPath,$page_param['pages']);?>');" href="javascript:void(0);" class="page_list">尾页</a>
<?php //else:?>
<!-- <span class="disabled">尾页</span> -->
<?php endif;?>
&nbsp;&nbsp;

跳到 &nbsp;<input type="text" class="input-text page-input" name="page" id="page" size="1" value="<?=$page?>" onkeypress="return listTable.changePage(event,'<?=goPageUrl($urlPath,'');?>')" />页&nbsp;


</div>
<?php endif; ?>
