

//批量上传
var BatchUploadImage = [];
BatchUploadImage = {
    //获取图片
    GetImageList: function (id, _type) {
        var m_ImageArry = new Array();
        var m_Id = id + " img";
        $("#" + m_Id).each(function (i) {
            var _selected = false;
            var _id = $(this).attr("id");
            var _url = $(this).attr("src");
            m_ImageArry.push({ "Pic": _url, "Sort": (i + 1), "PicType": _type });
        });
        if (m_ImageArry.length > 0) {
            return JSON.stringify(m_ImageArry);
        }
        return null;
    },
    //设置为封面
    setTop: function (id, contentID) {
        $("#" + contentID + " dd").siblings().removeClass("r");
        $("#liimg_" + id).addClass("r");
    },
    //删除图片
    DeletePhotos: function (id) {
        $("#liimg_" + id).remove();
    },
    //初始化
    /*
    ** id：要初始化的ID
    ** PreviewWidth：预览图的宽度，默认为100；
    ** PreviewHeight：预览图的高度，默认为100；
    ** type：图片类型，自定义的值
    ** contentid：图片放置的div ID，默认为：J_imageView
    ** maxNum：上传最大的张数，不限制就不传或为0
    ** Width：限制的图片宽度
    ** Height:限制的图片高度
    **IsGetOldName:是否获取图片原名，默认为不获取
    */
    Init: function (id, PreviewWidth, PreviewHeight, type, contentid, maxNum, Width, Height, IsGetOldName, upPath) {

        if (!IsGetOldName) {
            IsGetOldName = false;
        }
        if (!upPath) {
            upPath = "";
        }
        KindEditor.ready(function (K) {
            var editor = K.editor({
                allowFileManager: true,
                cssPath: '/Resource/Content/Scripts/Controls/kindeditor-4.1.10/plugins/code/prettify.css',
                uploadJson: '/Resource/Content/Scripts/Controls/kindeditor-4.1.10/asp.net/file_manager_jsontwo.ashx?PhotoType=' + type + "&Width=" + Width + "&Height=" + Height + "&IsGetOldName=" + IsGetOldName + "&UpPath=" + upPath
            });
            K('#' + id).click(function () {
                editor.loadPlugin('multiimage', function () {
                    editor.plugin.multiImageDialog({
                        clickFn: function (urlList) {
                            var _html = "";
                            var m_RandomId = "";
                            var m_ContentID = contentid ? contentid : "J_imageView";
                            var m_Obj = $("#" + m_ContentID);
                            var m_MaxNum = maxNum ? maxNum : 0;
                            if (m_Obj.length == 0) {
                                if (contentid) {
                                    _html = "<div id='" + contentid + "'>";
                                } else {
                                    _html = "<div id='J_imageView'>";
                                }
                            }
                            var m_PreviewWidth = PreviewWidth ? PreviewWidth : 100;
                            var m_PreviewHeight = PreviewHeight ? PreviewHeight : 100;
                            var m_objddNum = $(m_Obj).children("li").length;
                            K.each(urlList, function (i, data) {
                                if ((m_objddNum + (i + 1)) > m_MaxNum && m_MaxNum > 0) {
                                    art.dialog.tips("上传的图片超过了" + m_MaxNum + "张!");
                                    return;
                                }
                                m_RandomId = GetRandomNum(10000, 999999) + i.toString();
                                _html += " <li id=\"liimg_" + m_RandomId + "\" data-value=" + m_RandomId + "><div><span date-value=" + m_RandomId + "><a href=\"javascript:void(0)\" class=\"doubleEditBtn\" style=\"padding: 0px;\">";
                                if (IsGetOldName) {
                                    _html += "<img id=\"img_" + m_RandomId + "\" src=" + data.url + " width='" + m_PreviewWidth + "' height='" + m_PreviewHeight + "' /><b onclick=\"BatchUploadImage.DeletePhotos(" + m_RandomId + ")\"></b></a></span><span>" + data.filename + " </span></div></li>";
                                } else {
                                    _html += "<img id=\"img_" + m_RandomId + "\" src=" + data.url + " width='" + m_PreviewWidth + "' height='" + m_PreviewHeight + "' /><b onclick=\"BatchUploadImage.DeletePhotos(" + m_RandomId + ")\"></b></a></span></div></li>";
                                }
                                //_html += "<dd class=\"f\" id=img_" + m_RandomId + ">";
                                //_html += "<img src=" + data.url + " width='" + PreviewWidth + "px' height='" + PreviewHeight + "px' id=" + m_RandomId + " class=\"ProductImgClass\">";
                                //_html += "<a class=\"del\" onclick=\"BatchUploadImage.DeletePhotos(" + m_RandomId + ")\">删除</a></dd>";
                                //<a class=\"top\" onclick=\"BatchUploadImage.setTop(" + m_RandomId + ",'" + m_ContentID + "')\">设为封面</a> | 
                            });
                            if (m_Obj.length == 0) {
                                _html += "</div>";
                            }
                            if (m_Obj.length == 0) {
                                $("#" + id).after(_html);
                            } else {
                                if (contentid) {
                                    $("#" + contentid).append(_html);
                                } else {
                                    $("#J_imageView").append(_html);
                                }
                            }
                            $("#" + contentid + " li").each(function () {
                                var id = $(this).attr("data-value");
                                var m_id = "img_" + id;
                                jQuery.fn.imageShowPreview(m_id, 600, 400);
                            });

                            editor.hideDialog();
                        }
                    });
                });
            });
        });
    },
}

//随机获取最大数和最小数之间
function GetRandomNum(Min, Max) {
    var Range = Max - Min;
    var Rand = Math.random();
    return (Min + Math.round(Rand * Range));
}