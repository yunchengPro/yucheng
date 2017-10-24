
$.extend({
    U1Ajax: function (url, postData, successHandle, Async, errorHandle, timeOut) {
        var async = true;
        if (Async != null) {
            async = Async;
        }
        //        if (!NotShowMessage) {
        //            WaitNotice();
        //        }
        var m_timeOut = 30000; //默认30秒
        if (0 == timeOut || timeOut > 0) {
            m_timeOut = timeOut;
        }
        $.ajax({
            type: "POST",
            url: url,
            data: postData,
            async: async,
            success: function (result) {
                if (result && result.Code == -999) {
                    //alert(result.Message);
                    if (errorHandle) {
                        errorHandle();
                        $.unblockUI();
                        return;
                    }
                }
                if (successHandle) {
                    successHandle(result);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("服务器没有响应或已超时，请稍后在试......");
                $.unblockUI();
                if (errorHandle)
                    errorHandle();
            }
        });

    }
});

$.extend({
    WaitMessage: function (div) {
        var offset = div.offset();
        var tmpDiv = "<div class=‘wait' style='left:" + offset.left + ", top: " + offset.top + "'></div>";
        div.append(tmpDiv);
    }
});



//$.U1Ajax(url, postData, function (result) { 

//}, Async);

//var postData = $("#testID").GetPostData();

$.fn.GetPostData = function () {
    var data = {};
    $(this).find("[col]").each(function (i, value) {

        var field = $(value).attr("col");

        if (value.tagName == "INPUT") {
            if (value.type == "checkbox") {
                if ($(value).prop("checked") == true) {
                    if (data[field]) {
                        data[field] = data[field] + "," + $(value).val();
                    } else {
                        data[field] = $(value).val();
                    }
                }
            }
            else if (value.type == "radio") {

                if ($(value).prop("checked") == true) {
                    data[field] = $(value).val();
                }
            }
            else {
                data[field] = $(value).val();
            }
        }

        else if (value.tagName == "SELECT") {
            data[field] = $(value).val();
        }
        else if (value.tagName == "DIV") {
            data[field] = $(value).html();
        }
        else if (value.tagName == "IMG") {
            data[field] = $(value).attr("src");
        }
        else if (value.tagName == "SPAN") {
            data[field] = $(value).html();
        }
        else if (value.tagName == "TEXTAREA") {
            data[field] = $(value).val();
        }

    });
    return data;
}


$.fn.GetExcelPostData = function () {
    var data = "";

    $(this).find("[col]").each(function (i, value) {

        var field = $(value).attr("col");
        if ((value.tagName == "INPUT" || value.tagName == "SELECT") && $(value).val() != "") {
            if (data == "") {
                data += "?" + field + "=" + $(value).val();
            } else {
                data += "&" + field + "=" + $(value).val();
            }
        }
        else if ((value.tagName == "DIV" || value.tagName == "SPAN") && $(value).html() != "") {
            if (data == "") {
                data += "?" + field + "=" + $(value).html();
            } else {
                data += "&" + field + "=" + $(value).html();
            }

        }
    });
    return data;
}

function RemveNotice() {
    var shied = $("#shield");
    if (shied.length > 0) {
        $("#shield").remove();
    }
}
function WaitNotice(id) {
    $(".frame_loading").remove();
    var img = "/Content/Images/winloading.gif";
    var shield = document.createElement("DIV");
    if (id == null || typeof (id) == "undefined") {
        shield.id = "shield";
    }
    else {
        shield.id = id;
    }
    shield.style.position = "absolute";
    shield.style.left = (document.body.clientWidth / 2);
    shield.style.top = document.body.clientHeight / 2;
    shield.style.width = 100;
    shield.style.height = 100;
    //window.alert(document.body.scrollHeight);

    shield.style.background = "white";
    shield.style.textAlign = "center";
    shield.style.zIndex = "10000";
    shield.style.filter = "alpha(opacity=80)";
    shield.style.opacity = 0.8;
    //shield.style.border-width=thick;
    shield.innerHTML = "<img src='" + img + "'/>正在处理数据，请稍后。。。";
    document.body.appendChild(shield);
}


