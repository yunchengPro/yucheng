(function($){
	var W = window;
	var D = document;
	var Y = {};
	var TIP_CONTAINER = null;

	Y.isIE6 = !window.XMLHttpRequest;

	Y.bind = function(obj,fn){
		var slice = Array.prototype.slice;
		var args = slice.call(arguments, 2);
		return function(){
			var _obj = obj || this, _args = args.concat(slice.call(arguments, 0));
			if (typeof(fn) == 'string') {
				if (_obj[fn]) {
					return _obj[fn].apply(_obj, _args);
				}
			} else {
				return fn.apply(_obj, _args);
			}
		};
	}
	Y.getParameter = function(param){
		var r = new RegExp("(\\?|#|&)"+param+"=([^&#]*)(&|#|$)");
	    var m = window.location.href.match(r);
	    return (!m?"":m[2]);
	}
	Y.SelAll = function(srcElement){
		_this = srcElement;
		$("input[type=checkbox]").each(function(){
			if (_this != $(this) && !$(this).attr("disabled")){
				$(this).attr("checked",$(_this).attr("checked"));
			}
		});
	}
	Y.show_word_count = function(source,target,limit,funlang){

	    if ($(source).length == 0){
	        return ;
	    }
        var show_count = function(){

            var len = limit - $(source).val().length;

			if (funlang=='' || funlang=='zh_CN'){
			   var str = len>0?"还能输入<em>"+len+"</em>字":"超出<em class='error'>"+len+"</em>字";
			}
			if (funlang=='en_US'){
			   var str = len>0?"<em>"+len+"</em> characters left":"overranging <em class='error'>"+len+"</em>";
			}

            $(target).html(str);
        }
        show_count();
        $(source).bind("keyup",show_count);
	}
	/**
	 * Show Tips
	 * @param {Mix} arg1
	 * @param {Integer} wtype
	 * @param {Integer} time
	 * @param {Function} closeCallback
	 */
	Y.Tip = function(arg1, wtype, time, closeCallback){
		this.container = TIP_CONTAINER;
		var cfg = arg1;
		if(typeof(arg1) == 'string'){
			cfg = {
				'msg': arg1,
				'type': parseInt(wtype,10) || 0,
				'time': (time > 0 ? time*1000 : 2000)
			};
		}
		this.config = $.extend({},{
			'msg': '',
			'type': 0,
			'time': 2000,
			'auto': true,
			'callback': closeCallback
		},cfg);

		//auto
		if(this.config.auto){
			this.show();
			if(this.config.time){
				setTimeout(Y.bind(this,function(){
					this.hide();
				}), this.config.time);
			}
		}
	};
    Y.getBaseUrl = function(){
        var l = window.location;
        return l.protocol + "//" + l.hostname + (l.port?":"+l.port:'');
    }
	/**
	 * show tip
	 */
	Y.Tip.prototype.show = function(){
		if(!this.container){
			this.container = TIP_CONTAINER = $('<div />').addClass("ysl-tip-container-wrap").appendTo('body');
		}
		var html = ([
			'<span class="ysl-tip-container">',
				'<span class="ysl-tip-icon-',this.config.type,'"></span>',
				'<span class="ysl-tip-content">',this.config.msg,'</span>',
			'</div>'
		]).join('');
		this.container.html(html).show();
	};

	Y.showLoading = function(cfg){
		Y.Popup._createMask();
		var loading = $(".ysl-loading-wrap");
		if (!loading[0]){
			loading = $("<div/>").addClass("ysl-loading-wrap").appendTo('body');
		}
		loading.css("visibility","visible");
	}

	Y.hideLoading = function(){
		if (Y.Popup.mask){
			Y.Popup.mask.fadeOut(50);
		}
		$(".ysl-loading-wrap").css("visibility","hidden");
	}

	/**
	 * hide tip
	 */
	Y.Tip.prototype.hide = function(){
		if(this.container){
			this.container.hide();
			this.config.callback && this.config.callback(this);
		}
	};

	/**
	 * hide all tip
	 */
	Y.Tip.closeAll = function(){
		if(TIP_CONTAINER){
			TIP_CONTAINER.hide();
		}
	}

	/**
	 * destory tip container
	 */
	Y.Tip.prototype.destory = function(){
		this.container.remove();
	};

	Y.tab = function(ctrl,ctn,cfg){
		var defaults = {
			cls:'current',
			event:'mouseover',
			type:'itype',
			url:'href',
			click_refresh:false
		}
		var tabCtn = $(ctn).children();
		var tabCtrl = $(ctrl).children();
		var cfg = $.extend({},defaults,cfg);
		$.each(tabCtrl,function(idx){
			$(this).bind(cfg.event,function(e){
				$.each(tabCtrl,function(_idx){_idx==idx ? $(this).addClass(cfg.cls) : $(this).removeClass(cfg.cls);});
				if ($(this).attr(cfg.type) == 'ajax'){
					$(tabCtn[idx]).html("<i class='icon_loading'></i>");
					$(tabCtn[idx]).load($(this).attr(cfg.url));
					if (!cfg.click_refresh){
						$(this).attr(cfg.type,'');
					}
				}   else if($(this).attr(cfg.type) == 'iframe'){
				    var url = $(this).attr(cfg.url);
                    var iframe_idx = $(this).attr("iname");//Y.md5(url);
                    if ($("#"+iframe_idx).length == 0){
                        var iframe = $("<iframe frameborder='0' frameborder='no' width='100%' scrolling='auto'></iframe>");
                        iframe.attr("src",url).attr("id",iframe_idx).attr("name",iframe_idx);
                        $(ctn).append(iframe);
                    }
                    $(ctn).children().hide();
                    $("#"+iframe_idx).show();
                    e.preventDefault();
                    return false;
				}
				$.each(tabCtn,function(_idx){_idx==idx ? $(this).show() : $(this).hide();});
				e.preventDefault();
				return false;
			})
		})
	}

	/**
	 * 套的iframe太多了，用这个来取得主iframe
	 */
	Y.getMainIframe = function(){
		if (top.DLERP_TOP_PAGE){
			var target = window;
			while(target.parent != top){
				target = target.parent;
			}
		}	else	{
			var target = top;
		}
		return target;
	}

	Y.getFormData = function(frm){
		var data={};
		var val = 0;

		$("#"+frm+" input").each(function() {
			var val = $(this).val();
			if ($(this).attr("type") == "checkbox" && !$(this).attr("checked")){
				val = 0;
			}
			if ($(this).attr('name')){
			     data[$(this).attr('name')] = val;
			}
	    });
	    $("#"+frm+" textarea").each(function() {
			var val = $(this).val();
			data[$(this).attr('name')] = val;
	    });
	    $("#"+frm+" select").each(function(){
	    	data[$(this).attr("name")] = $(this).val();
	    });
	   return data;
	}
    Y.genFormString = function(frm){
    	return $("#"+frm).serialize();
    }
	Y.auto_iframe_height = function(element,interval){
		/* 只在onload设置高度
		var iframe = $("#"+element);
		iframe.load(function(){
			if (iframe.attr("src")){
				iframe.css("height",iframe[0].contentWindow.document.body.scrollHeight);
			}
		});
		*/
		///* 定时检查高度
		var interval = interval | 1000;
		setInterval(function(){
			var iframe = document.getElementById(element);
			try{
				var bHeight = iframe.contentWindow.document.body.scrollHeight;
				var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
				var height = Math.max(bHeight, dHeight);

				if (height > 0 && iframe.height && height != iframe.height){
					iframe.height =  height;

				}
			}	catch(ex){}
		},interval);
		//*/
	}
    Y.set_iframe_adjust = function(){
        if (window.frameElement){
            var bHeight = window.document.body.scrollHeight;
    		var dHeight = window.document.documentElement.scrollHeight;
    		var height = Math.max(bHeight, dHeight);
    		window.frameElement.style.height = height+'px';
    	}
    }
	Y.Popup = function(options){
			var defaults = {
				title : 'title',
				content : {},
				width : '400',
				height : '',
				movable : true,
				zIndex : 1000,
				maskOpacity : 0.2,
				escKeyClose : true,
				onOpen : false,
				onClose : false,
				top: 'center',
				buttons : []
			}
			this.options = $.extend({},defaults,options);
			this.status;
			this.onOpen = typeof(this.options.onOpen) === 'function' ? this.options.onOpen : false;
			this.onClose = typeof(this.options.onClose) === 'function' ? this.options.onClose : false;
			this.init();

			!Y.Popup._collection ? Y.Popup._collection = [this] : Y.Popup._collection.push(this);

	}

	Y.Popup._createMask = function(){
		var that = this;
		if(!this.mask){
			this.mask = $('<div />');
			this.mask.attr('class','mod_popup_mask').css({
				position : Y.isIE6 ? 'absolute' : 'fixed',
				top : 0,
				left : 0,
				width : '100%',
				height : Y.isIE6 ? Math.max($(window).height(),$('body').height()) : '100%',
				backgroundColor : '#000',
				opacity : 0.2,
				zIndex : 990
			});

			this.mask.appendTo('body');
			if(Y.isIE6){
				$('<iframe />').css({width:'100%',height:'100%',opacity:0}).appendTo(this.mask);
				$(window).bind('resize',function(){
						that._IE6MaskAdjust();
				});
			}
		}
		else{
			this.mask.show();
		}
	};

	Y.Popup._IE6MaskAdjust = function(){
		this.mask.css({height:Math.max($(window).height(),$('body').height()),width:Math.max($(window).width(),$('body').width())});
	};

	Y.Popup.closeAll = function(){
		try {
			for(var i=Y.Popup._collection.length-1; i>=0; i--){
				Y.Popup._collection[i].close();
			}
		}catch(e){}
	};

	Y.Popup.closeMe = function(){
		var parent_btn = $(".close_btn",$(window.frameElement).parent().parent());
		parent_btn.get(0).click();
	}

	Y.PopupSelect = function(cfg,callback){
		var cfg = $.extend({},{
				'title': '请选择',
				'url': false,
				'width': 300,
				'height': 220,
				'top':'top'
			},cfg);
		cfg.content = {'iframe':cfg.url};
		cfg.onClose = function(){
			return function(){
				if (this.popTargetCtn[0].contentWindow.returnValue != null){
					if (typeof(callback) === 'string'){
						$("#"+callback).val(this.popTargetCtn[0].contentWindow.returnValue);
						return true;
					}
					if (typeof(callback) === 'function'){
						callback(this.popTargetCtn[0].contentWindow.returnValue);
						return true;
					}
				}
			}
		}(cfg);
		var target = Y.getMainIframe();
		var popup = new target.YSL.Popup(cfg);
		return false;
	};

	Y.returnSelect = function(val){
		window.returnValue = val;
		parent.YSL.Popup.closeAll();
	};

	Y.md5 = function(sText){
        var MD5_T = [
            0x00000000, 0xd76aa478, 0xe8c7b756, 0x242070db,
            0xc1bdceee, 0xf57c0faf, 0x4787c62a, 0xa8304613,
            0xfd469501, 0x698098d8, 0x8b44f7af, 0xffff5bb1,
            0x895cd7be, 0x6b901122, 0xfd987193, 0xa679438e,
            0x49b40821, 0xf61e2562, 0xc040b340, 0x265e5a51,
            0xe9b6c7aa, 0xd62f105d, 0x02441453, 0xd8a1e681,
            0xe7d3fbc8, 0x21e1cde6, 0xc33707d6, 0xf4d50d87,
            0x455a14ed, 0xa9e3e905, 0xfcefa3f8, 0x676f02d9,
            0x8d2a4c8a, 0xfffa3942, 0x8771f681, 0x6d9d6122,
            0xfde5380c, 0xa4beea44, 0x4bdecfa9, 0xf6bb4b60,
            0xbebfbc70, 0x289b7ec6, 0xeaa127fa, 0xd4ef3085,
            0x04881d05, 0xd9d4d039, 0xe6db99e5, 0x1fa27cf8,
            0xc4ac5665, 0xf4292244, 0x432aff97, 0xab9423a7,
            0xfc93a039, 0x655b59c3, 0x8f0ccc92, 0xffeff47d,
            0x85845dd1, 0x6fa87e4f, 0xfe2ce6e0, 0xa3014314,
            0x4e0811a1, 0xf7537e82, 0xbd3af235, 0x2ad7d2bb,
            0xeb86d391
          ];

          var MD5_round1 = [
            [ 0, 7, 1], [ 1,12, 2],
            [ 2,17, 3], [ 3,22, 4],
            [ 4, 7, 5], [ 5,12, 6],
            [ 6,17, 7], [ 7,22, 8],
            [ 8, 7, 9], [ 9,12,10],
            [10,17,11], [11,22,12],
            [12, 7,13], [13,12,14],
            [14,17,15], [15,22,16]
          ];

          var MD5_round2 = [
            [ 1, 5,17], [ 6, 9,18],
            [11,14,19], [ 0,20,20],
            [ 5, 5,21], [10, 9,22],
            [15,14,23], [ 4,20,24],
            [ 9, 5,25], [14, 9,26],
            [ 3,14,27], [ 8,20,28],
            [13, 5,29], [ 2, 9,30],
            [ 7,14,31], [12,20,32]
          ];

          var MD5_round3 = [
            [ 5, 4,33], [ 8,11,34],
            [11,16,35], [14,23,36],
            [ 1, 4,37], [ 4,11,38],
            [ 7,16,39], [10,23,40],
            [13, 4,41], [ 0,11,42],
            [ 3,16,43], [ 6,23,44],
            [ 9, 4,45], [12,11,46],
            [15,16,47], [ 2,23,48]
          ];

          var MD5_round4 = [
            [ 0, 6,49], [ 7,10,50],
            [14,15,51], [ 5,21,52],
            [12, 6,53], [ 3,10,54],
            [10,15,55], [ 1,21,56],
            [ 8, 6,57], [15,10,58],
            [ 6,15,59], [13,21,60],
            [ 4, 6,61], [11,10,62],
            [ 2,15,63], [ 9,21,64]
          ];

          function MD5_F(x, y, z) { return (x & y) | (~x & z); }
          function MD5_G(x, y, z) { return (x & z) | (y & ~z); }
          function MD5_H(x, y, z) { return x ^ y ^ z; }
          function MD5_I(x, y, z) { return y ^ (x | ~z); }

          var MD5_round = [
            [MD5_F, MD5_round1],
            [MD5_G, MD5_round2],
            [MD5_H, MD5_round3],
            [MD5_I, MD5_round4]
          ];

          function MD5_pack(n32) {
            return String.fromCharCode(n32 & 0xff)
              + String.fromCharCode((n32 >>> 8) & 0xff)
              + String.fromCharCode((n32 >>> 16) & 0xff)
              + String.fromCharCode((n32 >>> 24) & 0xff);
          }

          function MD5_unpack(s4) {
            return  s4.charCodeAt(0)
              | (s4.charCodeAt(1) <<  8)
              | (s4.charCodeAt(2) << 16)
              | (s4.charCodeAt(3) << 24);
          }

          function MD5_number(n) {
            while (n < 0)
              n += 4294967296;
            while (n > 4294967295)
              n -= 4294967296;
            return n;
          }

          function MD5_apply_round(x, s, f, abcd, r) {
            var a, b, c, d;
            var kk, ss, ii;
            var t, u;

            a = abcd[0];
            b = abcd[1];
            c = abcd[2];
            d = abcd[3];
            kk = r[0];
            ss = r[1];
            ii = r[2];

            u = f(s[b], s[c], s[d]);
            t = s[a] + u + x[kk] + MD5_T[ii];
            t = MD5_number(t);
            t = ((t<<ss) | (t>>>(32-ss)));
            t += s[b];
            s[a] = MD5_number(t);
          }

          function MD5_hash(data) {
            var abcd, x, state, s;
            var len, index, padLen, f, r;
            var i, j, k;
            var tmp;

            state = [0x67452301, 0xefcdab89, 0x98badcfe, 0x10325476];
            len = data.length;
            index = len & 0x3f;
            padLen = (index < 56) ? (56 - index) : (120 - index);
            if(padLen > 0) {
              data += '\x80';
              for(i = 0; i < padLen - 1; i++)
                data += '\x00';
            }
            data += MD5_pack(len * 8);
            data += MD5_pack(0);
            len += padLen + 8;
            abcd = [0, 1, 2, 3];
            x = [16];
            s = [4];

            for(k = 0; k < len; k += 64) {
              for(i = 0, j = k; i < 16; i++, j += 4) {
                x[i] = data.charCodeAt(j)
                  | (data.charCodeAt(j + 1) <<  8)
                  | (data.charCodeAt(j + 2) << 16)
                  | (data.charCodeAt(j + 3) << 24);
              }
              for(i = 0; i < 4; i++)
                s[i] = state[i];
              for(i = 0; i < 4; i++) {
                f = MD5_round[i][0];
                r = MD5_round[i][1];
                for(j = 0; j < 16; j++) {
                  MD5_apply_round(x, s, f, abcd, r[j]);
                  tmp = abcd[0];
                  abcd[0] = abcd[3];
                  abcd[3] = abcd[2];
                  abcd[2] = abcd[1];
                  abcd[1] = tmp;
                }
              }

              for(i = 0; i < 4; i++) {
                state[i] += s[i];
                state[i] = MD5_number(state[i]);
              }
            }

            return MD5_pack(state[0])
              + MD5_pack(state[1])
              + MD5_pack(state[2])
              + MD5_pack(state[3]);
          }

          var i, out, c;
          var bit128;

          bit128 = MD5_hash(sText);
          out = '';
          for(i = 0; i < 16; i++) {
            c = bit128.charCodeAt(i);
            out += '0123456789abcdef'.charAt((c>>4) & 0xf);
            out += '0123456789abcdef'.charAt(c & 0xf);
          }
          return out;
	}
	Y.KEYS ={
        48:"0", 49:"1", 50:"2", 51:"3", 52:"4", 53:"5", 54:"6", 55:"7", 56:"8",
        57:"9", 59:";", 61:"=", 65:"a", 66:"b", 67:"c", 68:"d",
        69:"e", 70:"f", 71:"g", 72:"h", 73:"i", 74:"j", 75:"k", 76:"l", 77:"m",
        78:"n", 79:"o", 80:"p", 81:"q", 82:"r", 83:"s", 84:"t", 85:"u", 86:"v",
        87:"w", 88:"x", 89:"y", 90:"z", 107:"+", 109:"-", 110:".", 188:",",
        190:".", 191:"/", 192:"'", 219:"[", 220:"\\", 221:"]", 222:"\"",
        8:"backspace",  9:"tab",   13:"return",    19:"pause",  27:"escape",  32:"space",
        33:"pageup",  34:"pagedown",  35:"end",     36:"home",   37:"left",   38:"up",
        39:"right",  40:"down",   44:"printscreen",   45:"insert",  46:"delete",
        112:"f1",   113:"f2",   114:"f3", 115:"f4",  116:"f5",   117:"f6",   118:"f7",
        119:"f8",   120:"f9",   121:"f10", 122:"f11",  123:"f12",
        144:"numlock",  145:"scrolllock"
    };
	Y.getHotKeyName = function(e){
        var e = e || event;
        var code = e.keyCode;
        var result = '';
        if (code == 16 || code == 17 || code == 18) return;
        if (e.altKey) result += "ALT+";
        if (e.ctrlKey) result += "CTRL+";
        if (e.shiftKey) result += "SHIFT+";
        result += Y.KEYS[code].toUpperCase();
        return result;
	}
	Y.insertByCursor = function(element,str){
		var tc = document.getElementById(element);		
	    var tclen = tc.value.length;
	    tc.focus();
	    if(typeof document.selection != "undefined")
	    {
	        document.selection.createRange().text = str;  
	    }
	    else
	    {
	        tc.value = tc.value.substr(0,tc.selectionStart)+str+tc.value.substring(tc.selectionStart,tclen);
	    }
	}

	//Popup.prototype
	Y.Popup.prototype = {
		init : function(){
			var that = this;
			this._showPopup();
			this.status = 1;
			this.popCloseBtn.bind('click',function(e){
				that.close();
				e.preventDefault();
			});

			$(window).bind('resize scroll',function(){
				that._recenter();
			});

			$(document).bind('keydown',function(e){
				var key = e.keyCode;
				if (that.options.escKeyClose && key === 27) {
					that.close();
					e.preventDefault();
				}
			});

			if(this.options.movable){
				this._dragEventBind();
			}

			if(this.options.buttons.length != 0){
				this._buttonsEventBind();
			}

			if(this.onOpen){this.onOpen();}
		},

		_showPopup : function(){
			Y.Popup._createMask();
			this.popContainer = $('<div />').attr('class','mod_popup_container').css({
				position:'absolute',
				top: (this.options.top == 'top')?($(window).scrollTop()+20)+'px':$(window).height() > this.options.height ? $(window).scrollTop()+($(window).height()-this.options.height)/2 : $(window).scrollTop(),
				//top: $(window).height() > this.options.height ? ($(window).scrollTop()+($(window).height()-this.options.height)/2)*0.4 : $(window).scrollTop()*0.4,
				left:'50%',
				width:this.options.width,
				//height:this.options.height,
				backgroundColor:'#fff',
				zIndex:this.options.zIndex,
				marginLeft:-(this.options.width/2)
			});


			this.popHd = $('<div />').attr({'class':'mod_popup_hd'}).append(
				this.popTitle = $('<h3 />').html(this.options.title),
				this.popCloseBtn = $('<a />').attr({'class':'close_btn','href' : 'javascript:void(0);'})
			);
			this.popBd = $('<div />').attr({'class':'mod_popup_bd'}).css({height:this.options.height});

			if(this.options.content){
				if(this.options.content.html){
					this.popTargetCtn = this.options.content.html;
				}
				else if(this.options.content.inline){
					this.popTargetCtn = $(this.options.content.inline).children();
					this.popTargetCtnOrigin = $(this.options.content.inline);
				}
				else if(this.options.content.iframe){
					this.popTargetCtn = $('<iframe />').attr({'src':this.options.content.iframe, 'frameBorder':0,'width':'100%','height':'100%'});
				}
				this.popBd.append(this.popTargetCtn);
			}

			if(this.options.buttons.length != 0){
				this.popFt = $('<div />').attr({'class':'mod_popup_ft'});
				for(var i = 0,len = this.options.buttons.length ; i < len ; i++){
						this.popFt.append($('<a href="javascript:;" class="pop_btn '+(this.options.buttons[i].extraClass ? this.options.buttons[i].extraClass : '')+'"><span>'+this.options.buttons[i].name+'</span></a>'));
				}
			}

			this.popContainer.append(this.popHd,this.popBd,this.popFt).appendTo('body');
		},


		_buttonsEventBind : function(){
			var that = this;
			var buttons = this.popFt.children();
			var handle;
			for(i=0, len = buttons.length ; i < len ; i++){
				handle = (typeof(this.options.buttons[i].handle) === 'function') ? this.options.buttons[i].handle : function(){that.close()};
				$(buttons[i]).bind('click',handle);
			}
		},

		_recenter : function(){
			if($(window).height() < this.popContainer.outerHeight()) {return false;};
			this.popContainer.css({
					top: $(window).scrollTop()+($(window).height()-this.popContainer.outerHeight())/2
			});
		},

		_dragEventBind: function(){
			var posX,posY;
			var that = this;
			this.popHd.css({'cursor':'move','-moz-user-select':'none','-webkit-user-select':'none'})
			.bind('selectstart',function(){
				return false;
			});
			this.popHd.find('h3').bind('mousedown',function(e){
				posX = e.pageX;
				posY = e.pageY;
				that.dragActive = true;
			});

			$(document).bind('mousemove',function(e){

				if(that.dragActive){
					var popX = that.popContainer.offset().left;
					var popY = that.popContainer.offset().top;
					that.popContainer.css({
						left : (that.popContainer.position().left + e.pageX - posX),
						top : (that.popContainer.position().top + e.pageY - posY)
					});
					posX = e.pageX ;
					posY = e.pageY ;

				}
			})
			.bind('mouseup',function(){
				that.dragActive = false;
			});
		},

		close : function(){
			var that = this;
			if(this.onClose){
				this.onClose();
			}
			if(this.popTargetCtnOrigin){
				this.popTargetCtn.appendTo(this.popTargetCtnOrigin);
			}
			$(window).unbind('resize scroll',function(){
				that._recenter();
			});
			this.popContainer.fadeOut(50).remove();
			this.status = 0;

			for(var i=Y.Popup._collection.length-1; i>=0; i--){
				if(!Y.Popup._collection[i].status){
					Y.Popup._collection.splice(i,1);
				}
			}

			if(Y.Popup._collection.length != 0){return false;}
			Y.Popup.mask.fadeOut(50);

		}
	};


	W.YSL = Y;
})(jQuery);