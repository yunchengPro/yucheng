        var jericho = {
            showLoader: function() {
                $('#divMainLoader').css('display', 'block');
            },
            removeLoader: function() {
                $('#divMainLoader').css('display', 'none');
            },
            buildTree: function() {
					$('.menu-item a').click(function() {
                    $.fn.jerichoTab.addTab({
                        tabFirer: $(this),
                        title: $(this).text(),
                        closeable: true,
                        iconImg: $(this).attr('iconImg'),
                        data: {
                            dataType: 'iframe',
                            dataLink: $(this).attr('alt')
                        }
                    }).showLoader().loadData();
                });
            },
            openTab: function(url,title){
                $.fn.jerichoTab.addTab({
                        title: title,
                        closeable: true,
                        data: {
                            dataType: 'iframe',
                            dataLink: url
                        }
                    }).showLoader().loadData();
            },
            buildTabpanel: function() {
                $.fn.initJerichoTab({
                    renderTo: '.tab_cont',
                    uniqueId: 'myJerichoTab',
                    contentCss: { 'height': $('.tab_cont').height() - 0 },
                    tabs: [{
                            title: 'Dashboard',
                            closeable: false,
                            iconImg: '/images_style/tab_icon/monitor.png',
                            data: { dataType: 'iframe', dataLink: '/dashboard/index' },
                            onLoadCompleted: function(h) {
                                $('<b style="color:red" />').html('The JerichoTab processed in ' + (new Date().getTime() - d1) + ' milliseconds!').appendTo(h);
                            }
                        }],
                        activeTabIndex: 0,
                        loadOnce: true
                    });
                }
            }
        $().ready(function() {
            d1 = new Date().getTime();
            jericho.showLoader();
            do_resize();

           jericho.buildTree();
           jericho.buildTabpanel();
           jericho.removeLoader();
        })

        $(window).resize(function() {
            do_resize();
        })
        window.do_resize = function(){
            var w = $(document).width();
            var h = $(document).height();
			if ($.browser.msie) {
				$('.tab_cont').css({ width: w - 18, height: h - 83, 'display': 'block', 'overflow': 'hidden', 'margin-left': 0 });
			} else {
				$('.tab_cont').css({ width: w - 15, height: h - 80, 'display': 'block', 'overflow': 'hidden', 'margin-left': 0 });
			}
        }