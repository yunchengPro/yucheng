<div id="mt-20">

	<div class="mt-20" >
		<table class="table table-border table-bordered table-bg table-hover table-sort">
	        <thead>
		        <tr>
				<?php
					//表头
					if(!empty($list_set)){
						foreach($list_set as $key=>$item){
							$item['nowrap'] = isset($item['nowrap'])?$item['nowrap']:true;

				?><?php  $align  = !empty($item['text-align'])?'text-align:'.$item['text-align'].';':'text-align:left;';?>
						<th style="<?php echo $align;?>white-space:<?php echo $item['nowrap']==true?"nowrap":"normal"?>;" <?php echo !empty($item['width'])?" width=\"".$item['width']."\"":"";?> <?php echo $item['nowrap']==true?"title=\"".$item['name']."\"":""?> <?=$item['head_str']?>>
						<?php 
							if($key=='checkbox'){
								echo "<input type=\"checkbox\" id=\"cbx-sel-all\">";
							}else{
								if($item['sort'])
									echo SortTitle($key,$item['name'],$sort_by,$sort_order);
								else
									echo $item['name'];
								if($item['data_sort'])
									echo Html::button(array("name"=>$key,"value"=>"排序","type"=>"data_sort","_url"=>$item['_url'],"_title"=>"是否确定排序？"));

								if($item['edit_data'] && is_array($item['edit_data']))
									echo Html::button(array("name"=>$key,"value"=>"修改","type"=>"edit_data","_url"=>$item['edit_data']['_url'],"_title"=>"确定要修改数据吗？"));
								if(!empty($item['data_arr']) && !empty($item['data_color']))
									$list_set[$key]['data_arr'] = Html::color(array("value"=>$item['data_arr'],"color"=>$item['data_color']));
							}
						?>
						</th>
				<?php
						}
					}
				?>
				
				</tr>
	        </thead>
	      
	        <tbody>
	        <?php
			//主体数据
			//$list = !empty($this->list)?$this->list:$this->paginator;
			if(count($pagelist)){
				$num = 0;
				$rowid = 1;
			foreach ($pagelist as $k =>  $row){
				//$row = (object)$row;
		?>
			<tr _rowid="<?=$row['id']?>" >
			<?php
				if(!empty($list_set)){
					foreach($list_set as $key=>$item){
						$item['nowrap'] = isset($item['nowrap'])?$item['nowrap']:true;
			?>
						<td align="<?=$item['align']!=''?$item['align']:'center'?>" <?php echo !empty($item['width'])?" width=\"".$item['width']."\"":"";?> style="white-space:<?php echo $item['nowrap']==true?"nowrap":"normal"?>;" <?=$item['data_str']?> >
							
							<?php if(!empty($item['thumb']) && is_array($item['thumb'])){?>
								<a target="_blank" href="<?=ShowThumb($row[$key],300)?>" class="a_preview">
								<img class='_load_img'  src="/newui/static/h-ui.admin/images/loadingnew.gif"  data-original="<?=ShowThumb($row[$key],$item['thumb']['width'],$item['thumb']['height'])?>" width="<?=$item['thumb']['width']?>" height="<?=$item['thumb']['height']?>" /></a>
								<?php $thumb = true; ?>
							<?php }else if($item['data_sort']==true){
									echo Html::text(array("name"=>$key,"value"=>$row[$key],"other"=>"size='3' _id='".$row['id']."' _url='".$item['_url']."'","class"=>"data_sort"));
							}else if($item['edit_data'] && is_array($item['edit_data'])){					
								if($item['edit_data']['type'] == 'text'){
									echo Html::text(array("name"=>$key,"value"=>$row[$key],"other"=>"size='1' _id='".$row['id']."' _url='".$item['_url']."'","class"=>"edit_data input"));
								}elseif($item['edit_data']['type'] == 'select'){
									echo Html::select(["name"=>$key,"top_option"=>"--请选择--","class"=>"select edit_data","option"=>$item['edit_data']['option'],"value"=>$row[$key], "other"=>"style='width:".$item['edit_data']['width']."px;' _id='".$row['id']."'"]);
								}

						  	}else if($key=='checkbox'){?>
						  			<?php if($row["checkbox"]!='disable'){ ?>
									<input type="checkbox" class='ids' name="ids[]" value="<?php echo !$item['noencode']?Encode($row['id']):$row['id']; ?>">
									<?php } ?>
							<?php }else if($key=='rowid'){ ?>
									<?php echo $rowid;?>
							<?php }else{ ?>
									<?php 
										if(!empty($item['data_arr'])){
											echo $item['data_arr'][$row[$key]];
										}else{
											echo $row[$key];
										}
									?>
							<?php } ?>
							
							
						</td>
			<?php
					}
					$rowid++;
				}
			?>
				</tr>

			<?php 
				}
			?>
			<tr>
					<td colspan="<?=count($list_set)?>" class="footer_wrap_new">
						{include file="Pub/pubPage" /}
					</td>
				</tr>
			<?php
				}else{
			?>
			<tr>
	        <td colspan="<?=count($list_set)?>" class="empty" align="center" style="text-align: center;">没有符合条件的记录</td>
	    	</tr>
			<?php }?>
	        </tbody>
	    </table>
	</div> 

	<!-- <div class="footer_wrap"  id="footer_wrap" > -->
	    
	
</div>  

<?php if($thumb == true){?>
	<script src="/js/jquery.imagePreview.1.0.js"></script>
	<script>
		$(function(){

			$("img._load_img").each(function(){
				$(this).attr("src",$(this).attr("data-original"));
			});

			//图片预览
			(function($){
				$("a.a_preview").preview();
			})(jQuery);

		});
	</script>
<?php }?>
<?php if(!$full_page){?>
<script type="text/javascript" src="/js/crm_list.js"></script>
<?php }?>
<!--
<script type="text/javascript" src="/js/crm_common.js"></script>
<script type="text/javascript" src="/js/crm_list.js"></script>
<script type="text/javascript" src="/js/init.js"></script>
<script type="text/javascript" src="/js/ysl.js"></script>
<script type="text/javascript" src="/js/listtable.js"></script>
-->
    

