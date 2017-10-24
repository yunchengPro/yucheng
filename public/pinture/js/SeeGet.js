
document.write("<script charset=\"utf-8\" src=\"/Ueditor/ueditor.config.js\"></script>")
document.write("<script charset=\"utf-8\" src=\"/Ueditor/ueditor.all.js\"></script>")
document.write("<script charset=\"utf-8\" src=\"/Ueditor/_examples/addCustomizeDialog.js\"></script>")
document.write("<script charset=\"utf-8\" src=\"/Ueditor/_examples/addCustomizebutton.js\"></script>")
// document.write("<script charset=\"utf-8\" src=\"/Ueditor/_examples/addCustomizeArticle.js\"></script>")
document.write("<script charset=\"utf-8\" src=\"/Ueditor/_examples/addCustomizeService.js\"></script>")



function createKindEditor(id, defaultValue) {
    if (!defaultValue) {
        defaultValue = "";
    }
    $("#" + id).html("");
    var m_Html = "<textarea id=\"container\" name=\"container\" style=\"width:400px;height:150px;\" readonly=\"false\" >" + defaultValue + "</textarea>";
    $("#" + id).html(m_Html);
    var option = { 
            toolbars: [
                [
                    'source','fullscreen', '|','bold', 'italic', 'underline', 'strikethrough',  'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                    'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                     'fontfamily', 'fontsize', '|','inserttable', 'deletetable','unlink','|','justifyleft', 'justifycenter', 'justifyright', 'justifyjustify' 
                ]
            ]
        };
    editor = new UE.ui.Editor(option);  
    textarea:'container'; //与textarea的name值保持一致  
    editor.render('container'); 

    //editor = UE.getEditor('container');
}
function InsertUrl(param) {
    var url = new Array();
    for (var i = 0; i < param.length; i++) {
        url.push('<a href="' + param[i].url + '">' + param[i].ProductName + '</a><br/>');
    }
    for (var i = 0; i < url.length; i++) {
        editor.exec('inserthtml', url[i]);
    }
}

function CreateUrl(param) {
    var url = new Array();
    for (var i = 0; i < param.length; i++) {
        url.push(param[i].url);
    }
    for (var i = 0; i < url.length; i++) {
        editor.exec('createlink', url[i]).hideDialog().focus();
    }
}

//进入商品详情页面
function productDetail(ID, url) {
    var url_f = "'" + url + "'";
    var title = "商品详情(" + ID + ")";
    openPage(title, url);
}

function InitData(valData) {
    $("#content").html(valData);
    $("#content .txtEdit").each(function (i) {
        $(this).find("a").click(function (event) {
            event.preventDefault();
        });
        $(this).find("span:first").bind("click", function () {
            BindClick(this);
        });
    });
}
/******绑定事件********/
function BindClick(obj) {
    $("#content .txtEdit").each(function (i) {
        $(this).removeClass("txtEditCur");
    });
    $(obj).parent().addClass("txtEditCur");
    var id = $(obj).parent().attr('id');
    activateNum = id.substr(7, 999); //激活新添加的文本框块
    var flag = $(obj).parent().attr("flag");


    //$(".operateEdit").hide();
    var top = $(obj).offset().top;
    //var height = $("#content" + activateNum).height();
    //alert($(document).scrollTop());

    var height = $(".operateEdit").height();
    //alert(height);
    
    $(".operateEdit").offset({top:top});
    
    setTimeout(function(){
        $(".operateEdit").show();
    },200);
    //alert($(".operateEdit").offset().top);

    
    switch (flag) {
        case "txt":
            if ("<div class=\"addtxt txtEditCur\"><i></i><s></s><h2>请在右侧设置文字内容</h2><p class=\" operate clear\" style=\"display: none;\"></p></div>" == $("#" + id + " span").html()) {
                createKindEditor("EditorList", "");
            } else {
                createKindEditor("EditorList", $("#" + id + " span").html());
            }
            $(".operateEditBox").hide();
            $("#txdCnt").show();
            break;
        case "img":
            $(".operateEditBox").hide();
            $("#imgCnt").show();
            if ($($(obj).parent().find("img")[0]).attr("src") != '' && $($(obj).parent().find("img")[0]).attr("src") != undefined) {
                $("#showImage").show();
                $("#showImage").attr("src", $($(obj).parent().find("img")[0]).attr("src"));
            } else {
                $("#showImage").hide();
            }
    }
    
    //var top = $("#content" + activateNum).offset().top;
    //alert($(obj).html());

}
$(function () {
   
    /*****添加文本********/
    $(".txtBtn01").click(function () {
        createKindEditor("EditorList", "");
        $(".operateEdit").show();
        $(".operateEditBox").hide();
        $("#txdCnt").show();
        //editor2.html("");        
        createDiv("txt");
        var top = $("#content" + activateNum).offset().top;
        var height = $("#content" + activateNum).height();
        //$(".operateEdit").css("top", top + height / 2 + "px");
    });
    /*****添加图片********/
    $(".ImgBtn01").click(function () {
        $(".operateEdit").show();
        $(".operateEditBox").hide();
        $("#imgCnt").show();
        createDiv("addimg");
        $("#showImage").attr("src", "");
        var top = $("#content" + activateNum).offset().top;
        var height = $("#content" + activateNum).height();
      
    });

    /****动态创建文本框*****/
    function createDiv(flag) {
        var lastChildDiv = $('#content > div:last-child');
        var nextChildDiv = $("#content" + activateNum).next();
        $("#content .txtEdit").each(function (i) {
            $(this).removeClass("txtEditCur");
        });
        if (lastChildDiv == undefined || lastChildDiv.length == 0) {
           
            $('#content').append("<div id='content1' class='txtEdit txtEditCur'><i></i><s></s><span id='iphoneContent1'></span></div>");
            $('#content1').attr('flag', flag);//缓存一个标识符
            $('#content1').attr('maxnum', 1);//缓存一个标识符
            maxNum = 1;
            activateNum = maxNum; //激活当前文本框块
            switch (flag) {
                case "txt":
                    $("#iphoneContent1").append("<div class='addtxt txtEditCur'><i></i><s></s><h2>请在右侧设置文字内容</h2><p class=' operate clear' style='display: none;'></p></div>");
                    break;
                case "img":
                    $("#iphoneContent1").append("<img id='contentImg1' src='' />");
                    break;
                case "addimg":
                    $("#iphoneContent1").append("<div class='addtxt txtEditCur'><i></i><s></s><h2>请在右侧设置要添加的图片</h2><p class='operate clear'></p></div>");
                    $('#content1').attr('flag', "img");//缓存一个标识符
                    break;
            }
            $("#iphoneContent1").append("");
            $("span#iphoneContent1").bind("click", function () {
                BindClick(this);
            })
            return;
        }
        var idNum = maxNum;
        var nextIdNum;
        var componentIsEmpty = false;
        if (nextChildDiv == undefined || nextChildDiv.length == 0) {
            if (activateNum != 0) {
                componentIsEmpty = checkComponentIsEmpty($("#content" + activateNum).attr("flag"), $("#content" + activateNum).attr('id').substr(7, 999));
                if (componentIsEmpty) {
                    nextIdNum = $("#content" + activateNum).attr('id').substr(7, 999);
                }
            } else {
                componentIsEmpty = checkComponentIsEmpty(lastChildDiv.attr("flag"), lastChildDiv.attr('id').substr(7, 999));
                nextIdNum = lastChildDiv.attr('id').substr(7, 999);
            }
        } else {
            var bool1 = checkComponentIsEmpty(nextChildDiv.attr("flag"), nextChildDiv.attr("id").substr(7, 999));
            var bool2 = checkComponentIsEmpty($("#content" + activateNum).attr("flag"), $("#content" + activateNum).attr('id').substr(7, 999));
            if (bool1) {
                nextIdNum = nextChildDiv.attr('id').substr(7, 999);
            }
            if (bool2) {
                nextIdNum = $("#content" + activateNum).attr('id').substr(7, 999);
            }
            componentIsEmpty = bool1 || bool2;
        }
        var selectNum = activateNum != 0 ? activateNum : nextIdNum;
        switch (flag) {
            case "txt":
                if (componentIsEmpty) {	//如果下一个组件为空，则转换为文本块
                    activateNum = idNum;
                    $("#iphoneContent" + nextIdNum).empty();
                    $("#iphoneContent" + nextIdNum).append("<div class='addtxt txtEditCur'><i></i><s></s><h2>请在右侧设置文字内容</h2><p class=' operate clear' style='display: none;'></p></div>");
                } else {
                    //新增一块
                    maxNum = maxNum + 1;
                    activateNum = maxNum; //激活新添加的文本框块
                    $('#content' + selectNum).after("<div id='content" + activateNum + "' class='txtEdit txtEditCur'><i></i><s></s><span id='iphoneContent" + activateNum + "'><div class='addtxt'><i></i><h2>请在右侧设置文字内容</h2><p class=' operate clear' style='display: none;'></p></div></span></div>");
                }
                $('#content' + activateNum).addClass("clearFloat");
                $('#content' + activateNum).attr('flag', flag);//缓存一个标识符
                $('#content1').attr('maxnum', activateNum);//缓存一个标识符
                $("#content" + activateNum).removeClass("dieteticCnt_box_list");
                $("#iphoneContent" + activateNum).removeClass("imgbox");
                break;
            case "addimg":
                if (componentIsEmpty) {	//如果最后一个组件为空，则转换为图片块
                    activateNum = idNum;
                    $("#iphoneContent" + nextIdNum).empty();
                    $("#iphoneContent" + nextIdNum).append("<div class='addtxt'><i></i><s></s><h2>请在右侧设置要添加的图片</h2><p class='operate clear'></p></div>");
                } else {
                    //新增一块
                    maxNum = maxNum + 1;
                    activateNum = maxNum; //激活新添加的文本框块
                    $('#content' + selectNum).after("<div id='content" + activateNum + "' class='txtEdit txtEditCur'><i></i><s></s><span id='iphoneContent" + activateNum + "'></span></div>");
                    $("#iphoneContent" + activateNum).append("<div class='addtxt'><i></i><s></s><h2>请在右侧设置要添加的图片</h2><p class='operate clear'></p></div>");
                }
                $('#content' + activateNum).addClass("clearFloat");
                $('#content' + activateNum).attr('flag', "img");//缓存一个标识符
                $("#content" + activateNum).removeClass("dieteticCnt_box_list");
                $("#iphoneContent" + activateNum).removeClass("imgbox");
                break;

            case "img":
                if (componentIsEmpty) {	//如果最后一个组件为空，则转换为图片块
                    activateNum = idNum;
                    $("#iphoneContent" + nextIdNum).empty();
                    $("#iphoneContent" + nextIdNum).append("<img id='contentImg" + activateNum + "' src='' />");
                } else {
                    //新增一块
                    maxNum = maxNum + 1;
                    activateNum = maxNum; //激活新添加的文本框块
                    $('#content' + selectNum).after("<div id='content" + activateNum + "' class='txtEdit txtEditCur'><i></i><s></s><span id='iphoneContent" + activateNum + "'></span></div>");
                    $("#iphoneContent" + activateNum).append("<img id='contentImg" + activateNum + "' src='' />");
                }
                $('#content' + activateNum).addClass("clearFloat");
                $('#content' + activateNum).attr('flag', flag);
                $('#content1').attr('maxnum', maxNum);
                $("#content" + activateNum).removeClass("dieteticCnt_box_list");
                $("#iphoneContent" + activateNum).removeClass("imgbox");
                break;
        }
        $("span#iphoneContent" + activateNum).bind("click", function () {
            BindClick(this);
        })
    }

    //判断最后一个是否为空
    function checkComponentIsEmpty(flag, idNum) {
        if (flag == 'img') {
            if ($("#contentImg" + idNum).attr("src") == '' || $("#contentImg" + idNum).attr("src") == undefined) {
                return true;
            }
            return false;
        } else {
            if ($("#iphoneContent" + idNum).html().trim() == '' || $("#iphoneContent" + idNum).text().trim() == "请在右侧设置文字内容") {
                return true;
            }
            return false;
        }
    }

    /***********删除***********/
    $("#content").on('click', 'i', function () {
        var id = $(this).parent("div").attr('id');
        var idNum = activateNum;
        var flag = $("#content" + id).attr("flag");
        switch (flag) {
            case "txt":
                //editor.html("");
                break;
            case "img":
                $("#showImage").attr('src', '');
                break;
        }
        var div = document.getElementById("content" + idNum);
        var parent = div.parentElement;
        parent.removeChild(div);
        activateNum = 0;
        $(".operateEdit").hide();
    });

    $(".operateEdit").on('click', 's', function () {
        close();
    });

    $("#file_upload-queue").on('click',function(){
        $('#iphoneContent' + activateNum).text("");
        var imgurl= $("#f_classpic").val();
        //alert(imgurl);
        if(imgurl == ''){
            alert('请先添加图片');
            return false;
        }
        var m_str =  '<img src="//10.70.40.250/'+imgurl+'" />';//UE.getEditor('container');//editor.html();
        $('#iphoneContent' + activateNum).append(m_str); 
       
        $("#content .txtEdit").each(function (i) {
            $(this).find("a").click(function (event) {
                event.preventDefault();
            });
        });
          
    });

    $(".operateEditBox").on('click', '.pubBtn_orange', function () {
        $('#iphoneContent' + activateNum).text("");
        var m_str =  editor.getContent() ;//UE.getEditor('container');//editor.html();
       
        $('#iphoneContent' + activateNum).append(m_str); 
        close();
        $("#content .txtEdit").each(function (i) {
            $(this).find("a").click(function (event) {
                event.preventDefault();
            });
        });
    })

    function close() {
        $("#content .txtEdit").each(function (i) {
            $(this).removeClass("txtEditCur");
        });
        $(".operateEdit").hide();
    }

})