
/// <reference path="../../Scripts/jquery.js" />

U1 = {};



$.fn.U1CC = function () {
    var str = "<li class=\"U1CC\"><span title=\"关键字\" class=\"title\"><select class=\"u1cc_select\" onchange=\"U1.conditionChange(this)\">";
    var fitst = "";
    $(".ul_hide").find("li").each(function (i, value) {
        var title = $(value).attr("title");
        str = str + "<option value=\"" + title + "\">" + title + "</option>";
        if (fitst == "") {
            fitst = $(value).html();
        }
    });
    str = str + "</select>：</span>\r\n" + fitst + "</li>";

    $(this).children().first().prepend($(str));

    // $("li.U1CC [col]").attr("col-text", "select.u1cc_select");
};

U1.conditionChange = function (obj) {
    var value = $(obj).val();
    var htmlNew = $(".ul_hide").find("li[title=" + value + "]").html();
    $($(".searchBar .U1CC").children()[1]).remove();
    $(".searchBar .U1CC").append(htmlNew);

    // $("li.U1CC [col]").attr("col-text", "select.u1cc_select");
};

var U1EasyGrid_Defaults =
{
    width: "auto",
    height: "auto",
    striped: true,
    idfield: "ID",
    pageSize: 15,
    fit: true,
    fitColumns: true,
    pagination: true,
    singleSelect: true,
    nowrap: false,
    pageTag: window.location.href,
    //工具栏
    toolbar: [
            ],
    //选择列
    onHeaderContextMenu: function (e, field) {
        DataGridExtend.headerContextMenu(e, field, $(this).attr('id'), this.pageTag + '.' + $(this).attr('id'));
    },
    //加载成功的时候，初始化隐藏列
    onLoadSuccess: function (data) {
        DataGridExtend.loadSuccessInit($(this).attr('id'), this.pageTag + '.' + $(this).attr('id'));
        //DataGridExtend.ShowTip_Url();
        if (typeof (setToolMsg) != "undefined") {
            setToolMsg(data);
        }
        if (this.onLoadSuccess) {
            this.onLoadSuccess(data);
        }
    },
    onLoadError: function () {
        alert("数据加载中,请稍侯!");
    },
    onClickRow: function (rowIndex, rowData) {
        if (this.onLoadSuccess) {
            this.onClickRow(rowIndex, rowData);
        }
    },
    onDblClickRow: function (rowIndex, rowData) {
        if (this.onLoadSuccess) {
            this.onDblClickRow(rowIndex, rowData);
        }
    },
    onSelect: function (rowIndex, rowData) {
        if (this.onLoadSuccess) {
            this.onSelect(rowIndex, rowData);
        }
    },
    onUnselect: function (rowIndex, rowData) {
        if (this.onLoadSuccess) {
            this.onUnselect(rowIndex, rowData);
        }
    },
    onRowContextMenu: function (e, rowIndex, rowData) {
        if (this.onLoadSuccess) {
            this.onRowContextMenu(e, rowIndex, rowData);
        }
    },
    resize: function () {
        $(this).datagrid("resize", { height: 200 });
    }
}

$.fn.U1EasyGrid = function (gridOptions) {

    var p = $.extend({}, U1EasyGrid_Defaults, gridOptions || {});


    $(this).datagrid(p);

    //搜素栏
    var searchBar = $(".searchBar");
    if (searchBar.length > 0) {
        //var html = "<div class=\"searchBarContainer\"><table cellpadding=\"0\" cellspacing=\"0\" ><tr><td class=\"searchBarBody\"></td><td style=\"width: 80px\"><a id=\"sb\"iconCls=\"icon-search\" plain=\"true\" href=\"javascript:void(0)\" onclick=\"Search()\">搜索</a></td></tr></table></div>";
        //html = html + "<div id=\"mm\" style=\"width:100px;display:none;\"></div>";
        var html = "<div class=\"searchBarContainer\"></div>";
        $(".datagrid-toolbar").append(html);
        $(".searchBarContainer").append(searchBar);
        var splitbuttonEnable = false;
        if (searchBar.attr("searchsetting") == "true") {
            $("#mm").append("<div iconCls=\"icon-setting\" onclick=\"U1.searchSet()\">搜索设置</div>");
            splitbuttonEnable = true;
            $("#mm").append("<div iconCls=\"icon-save\" onclick=\"U1.searchSet()\">保存方案</div>");
        }
        //        if (searchBar.attr("saveplan") == "true") {
        //            $("#mm").append("<div iconCls=\"icon-save\" onclick=\"U1.searchSet()\">保存搜索方案</div>");
        //            splitbuttonEnable = true;
        //        }
        if (splitbuttonEnable) {
            $("#sb").splitbutton({
                menu: '#mm'
            });
        } else {
            $("#sb").linkbutton();
        }
        searchBar.show();
    }
    //工具栏
    $(".datagrid-toolbar").append($(".toolBar"));
    //messageBar
    $(".datagrid-toolbar").append($(".messageBar"));

    var grid_id = $(this).attr("id");
    $(window).resize(function () {
        $("#" + grid_id).datagrid("resize");
    });

};


