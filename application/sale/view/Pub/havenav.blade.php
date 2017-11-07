    	    <nav class="nav-wrap">
                <div class="nav-item <?php echo strtolower($UrlPath)=='index/index/index'?'active':''; ?> ">
                    <a href="/index/index/index">
                        <span class="mall"></span>
                        <p>商城</p>
                    </a>
                </div>
                <div class="nav-item <?php echo strtolower($UrlPath)=='article/index/articleList'?'active':''; ?>">
                    <a href="/Article/Index/articleList?newstype=1">
                        <span class="news"></span>
                        <p>资讯</p>
                    </a>
                </div>
                <div class="nav-item <?php echo strtolower($UrlPath)=='user/shopcart/index'?'active':''; ?>">
                    <a href="/user/shopcart/index">
                        <span class="cart"></span>
                        <p>购物车</p>
                    </a>
                </div>
                    
                <div class="nav-item <?php echo strtolower($UrlPath)=='user/index/index'?'active':''; ?>">
                    <a href="/user/index/index">
                        <span class="niu"></span>
                        <p>我的</p>
                    </a>
                </div>  
            </nav>
		</div>
	</body>
</html>