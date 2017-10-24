(function($){
	var T = {};
	var data = [];
	T.assign = function(key,val){
        data[key] = val;
	}
	T.parse = function(string){
	    for(key in data){
	        string = string.replace("{"+key+"}",data[key]);
	    }
	    return string;
	}
	window.TMPL = T;
})(jQuery);