<div class="download-bar">
	<div class="close-box">
		<a href="javascript:void(0)">
			<img src="<?=$publicDomain?>/mobile/img/icon/download_close@2x.png" />
		</a>
	</div>
	<div class="bar-content">
		<div class="logo-box"><img src="<?=$publicDomain?>/mobile/img/icon/LOGO2.png" /></div>
		<div class="bar-desc">
			<div>
				<div>你买单，我送钱</div>
				<div>赶快下载<label class="red"></label>手机客户端</div>
			</div>
			<a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.ynh.nnh.tt">点击下载</a>
		</div>
		
	</div>
</div>
<script>
	$(function(){
		$(".download-bar .close-box a").click(function(){
			$(this).parent().parent().fadeOut();
		});
	});
</script>