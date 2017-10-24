		<!--底部导航-->
		<style>
			
		</style>
		<nav class="nav-wrap">
			<div class="nav-item <?php echo strtolower($UrlPath)=='index/index/index'?'active':''; ?>"><a href="/index/index/index">
				<span class="shop"></span>
				<p>牛店</p>
			</a></div>
			<!-- <div class="nav-item"><a href="#">
				<span class="bag"></span>
				<p>牛品</p>
			</a></div>
			<div class="nav-item"><a href="javasrcipt:void(0)">
				<img src="<?=$publicDomain?>/mobile/img/icon/ic_home_logo@2x.png" class="logo"/>
			</a></div>
			<div class="nav-item"><a href="#">
				<span class="rang"></span>
				<p>消息</p>
			</a></div> -->
			<div class="nav-item <?php echo strtolower($UrlPath)=='user/index/index'?'active':''; ?>"><a href="/user/index/index">
				<span class="niu"></span>
				<p>我的</p>
			</a></div>
			
			
		</nav>
		
		
		<section class="tl-mask" id="area-mask"></section>
		<section class="niu-area">
			<div class="area-list">
				<div class="one-area">
					<a href="#">
						<p><img src="<?=$publicDomain?>/mobile/img/icon/ic_division_cash@2x.png" /></p>
						<p>牛票专区</p>
					</a>
				</div>
				<div class="one-area">
						<a href="#">
						<p><img src="<?=$publicDomain?>/mobile/img/icon/ic_division_dou@2x.png" /></p>
						<p>牛豆专区</p>
					</a>
				</div>
				<div class="one-area">
						<a href="#">
						<p><img src="<?=$publicDomain?>/mobile/img/icon/ic_division_cash_dou@2x.png" /></p>
						<p>牛票+牛豆专区</p>
					</a>
				</div>
			</div>
			<div class="area-colse">
				<img src="<?=$publicDomain?>/mobile/img/icon/ic_home_close@2x.png" id="close-x"/>
			</div>
		</section>
		<script>
		 $(function(){
	    	$(".logo").on("click",function(){
	    		$("#area-mask,.niu-area").show();
	    	});
	    	$("#area-mask,#close-x").click(function(){
	    		$("#area-mask,.niu-area").hide();
	    	});
	    });
		</script>
		<!--/end 底部导航-->