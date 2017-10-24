<footer data-am-widget="footer" class="am-footer am-footer-default" data-am-footer="{  }">
  
    <div class="am-footer-miscs ">
        
        <p>© 深圳市云牛惠科技有限公司&nbsp;&nbsp;&nbsp;&nbsp; 版权所有&nbsp;&nbsp;&nbsp;&nbsp;</p>
        <p> <a href="http://www.miitbeian.gov.cn/" target="_blank">粤ICP备17022027号-1</a> </p>
    </div>
  </footer>
<script src="/mobile/js/jquery.min.js"></script>
<script src="/mobile/js/amazeui.min.js"></script>
<!-- <script src="http://cdn.amazeui.org/amazeui/2.7.2//mobile/js/amazeui.min.js"></script> -->
<script>
    $("#chooseSec").on("click",function(e){
        $("#my-actions").modal('toggle');
    });

    //选择规格
    $(".sec-items").on("click","span",function(){
        $(this).addClass('active').siblings().removeClass('active');
    });

    //添加数量
    $(".sec-amount span").on("click",function(){
        var num = parseInt($("#amount").text());
        if($(this).hasClass('plus')){
            if(num>1){
                num--;
            }
        }
        if($(this).hasClass('add')){
            num++;
        }
        $("#amount").html(num);
    });
</script>
</html>