
    /*上传照片的js--上传头像*/
function change(url,id,dataid,htmlid){
        var url = url;
        var id = id;
        var dataid = dataid;
        var htmlid = htmlid;
        //上传文件
        $.ajaxFileUpload({
            url:url,//处理图片脚本
            secureuri :false,
            fileElementId : id,//file控件id
            data:{"id":dataid},
            type:"post",
            dataType : 'json',
            success : function (data){
               // alert(data.success);
                var html = '&nbsp;&nbsp;&nbsp;&nbsp<img src="'+ data.msg +'" width="100" height="30" />上传图片路径：<input type="text" name="f_headportraits" readonly="readonly" value="'+data.msg+'" size="50" />'
                $("#"+htmlid).html(html);
            },
          	error: function(data){
              alert("数据传输失败！");
          	}
        })
	} 