 var currentObj;
        var activateNum = 0;
        var maxNum = 0;
        var editor;
        $(function () {
            if ('632' > 0) {
                $("#IsEnableRadio").find("input:radio[name=IsEnable][value='True']").attr("checked", "checked");
                $("#EditTypeRadio").find("input:radio[name=EditType][value='1']").attr("checked", "checked");
                $("#EditTypeRadio").find("input:radio[name=EditType]").attr("disabled", "disabled");
                InitData($("#hidContents").val());
                //$("#hidPicFolder").val('2016021904330168');
                showMode(1);
            }
            BatchUploadImage.Init("J_selectImage", 100, 100, '5', "J_imageView", 200, 0, 0, true, $("#hidPicFolder").val());
            $("#content").sortable({ items: " > div", cursor: "move" });
            var tempMaxNum = $("#content > div:first-child").attr("maxnum");
            if (isNaN(tempMaxNum)) {
                maxNum = 0;
            } else {
                maxNum = parseInt(tempMaxNum);
            }

            $("#EditTypeRadio").on("click", "input:radio[name=EditType]", function () {
                var mode = $(this).val();
                showMode(mode);
            })
        })
        //编辑模式显示
        function showMode(mode) {
            if (mode == '1') {
                $("#SeeGet").show();
                $("#CodeEdit").hide();
            } else {
                $("#SeeGet").hide();
                $("#CodeEdit").show();
            }
        }
        
        //保存
        function Save() {
            var content = $("#content").text();
            if (content.indexOf("在右侧设置") > 0) {
                art.dialog.alert("您还有未添加的内容！");
                return false;
            }
            if ($.validatebox.valid($(".valid"))) {
                var query = $(".valid").GetPostData();
                query.Enable = $('#IsEnableRadio input:radio:checked').val();
                var checkVal = $('#EditTypeRadio input:radio:checked').val();
                query.EditType = checkVal;
                if (checkVal == '1') {
                    if ($("#content").html() == null || $("#content").html() == '') {
                        art.dialog.alert("请添加文章正文！");
                        return false;
                    }
                    query.ArticleProduct = getSelectPro();
                    $("#content > div:first-child").attr("maxnum", maxNum);
                    query.Content = escape($("#content").html());
                } else if (checkVal == '2') {
                    query.PhotoString = JSON.stringify(GetPhotoList());
                    query.Content = escape($("#CodeEditContent").val());
                }
                $.U1Ajax("/Admin/Article/CreateOrEditArticle", query, function (data) {
                    art.dialog.tips(data.Message);
                    if (data.Result > 0) {
                        var title = '文章列表';
                        if ('1' == '5')
                            title = "商家文章";
                        setTimeout(function () {
                            RefleshTab(title, true);
                        }, 1500);
                    }
                });
            } else {
                setTimeout(function () {
                    art.dialog.tips('您还有信息没完善!');
                }, 3000);
            }
        }

        function selectProduct() {
            var url = "/Admin/Product/SelectProductList?ProductID=" + getSelectPro() + "&singeSel=false";
            var title = "设置关联商品";
            Dialog.OpenWindow(url, title, 950, 522);
        }
        //获取图片
        function GetPhotoList() {
            var photoArry = [];
            var count = 0;
            $("#J_imageView li img").each(function (i, op) {
                var m_ImgPic = $(this).attr("src");
                count = (i + 1);
                photoArry.push({ "Pic": m_ImgPic, "Sort": (i + 1), "PicType": '5' });
            });
            //if (photoArry.length == 0) {
            //    art.dialog.tips("最少上传一张商家图片!");
            //    return;
            //}
            if (photoArry.length > 0) {
                return photoArry;
            }
            return "";
        }
        function deleteComo(productid) {
            $(".data-" + productid).remove();
        }

        function getSelectPro() {
            var m_SelectValue = "";
            var eles = $('.idchk');
            if (eles.length > 0) {
                for (var i = 0; i < eles.length; i++) {
                    m_SelectValue += eles[i].value + ",";
                }
                m_SelectValue = m_SelectValue.substring(0, m_SelectValue.length - 1);
            }
            return m_SelectValue;
        }
        //查看图片
        function ShowImage() {
            $("#ShowImage").show();
        }
        function CloseImage() {
            $("#ShowImage").hide();
        }
        //预览
        function PreviewContent() {
            $("#Preview").show();
            var content = $("#CodeEditContent").val();
            var imgList = GetPhotoList();
            var html = GetImgSrc(content, imgList);
            $(".ContentDetail").html(html);
            $("#CodeEditContent").val(html);
            /********给图片选择商品*******/
            $(".ContentDetail img[target=EditImg]").bind("click", function () {
                currentObj = this;
                //选择商品
                var url = "/Admin/Product/SelectProductList?ShowType=1&&singeSel=true";
                Dialog.OpenWindow(url, "选择商品", 950, 522);
            })
        }
        //关闭预览
        function ClosePreView() {
            $("#Preview").hide();
        }

        function GetImgSrc(htmlstr, imgList) {
            var reg = /<img.+?src=('|")?([^'"]+)('|")?(?:\s+|>)/gim;
            var arr = [];
            while (tem = reg.exec(htmlstr)) {
                arr.push(tem[2]);
            }
            if (arr.length > 0) {
                var thisPic = "";
                for (var i in arr) {
                    var imgName = arr[i].substring(arr[i].lastIndexOf('/') + 1);
                    for (var j = 0; j < imgList.length; j++) {
                        thisPic = imgList[i].Pic;
                        replaceName = thisPic.substring(thisPic.lastIndexOf('/') + 1);
                        if (imgName == replaceName) {
                            htmlstr = htmlstr.replace(arr[i], thisPic);
                        }
                    }
                }
            }
            return htmlstr;
        }
        function GetGroupID(productList) {          
            $(currentObj).attr("onclick", "goProductDetail(" + productList[0].ProductID + ")");
            $("#CodeEditContent").val($(".ContentDetail").html());
        }

        function goProductDetail(id) {
            art.dialog.tips(id);
        }

        //选择商品完，回调方法
        function getProductListCallBack(productList)
        {
            var TrStr = "";
            var cl_template = $("[rel=cl_template]").html();
            if (productList && productList.length > 0) {
                for (var i = 0; i < productList.length; i++) {
                    var chk = "<input type='hidden' name='checkboxid' class='idchk' value='" + productList[i].ProductID + "' />"
                    TrStr += cl_template.replace(/<!--\$ProductCode-->/g, productList[i].ProductCode)
                    .replace(/<!--\$ProductName-->/g, productList[i].ProductName + chk)
                    .replace(/<!--\$ProductID-->/g, productList[i].ProductID);
                }
                $(".data-tr").append(TrStr);
            }
        }
    