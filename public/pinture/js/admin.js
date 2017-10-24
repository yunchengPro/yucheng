//admin.js
function openWindowWithPost(url, keys) {
 
    var newWindow = window.open(url);
    if (!newWindow){
        return false;
    }
    
    var html = "";
    html += "<html><head></head><body><form id='formid' method='post' action='"+url+"'>";

    if(typeof(keys) =='object'){
        for(var s in keys){
            html += "<input type='hidden' name='"+s+"' value='"+keys[s]+"'/>";
        }
    }
    html+= "<\/form><script>document.getElementById('formid').submit();<\/script><\/body><\/html>";
    newWindow.document.write(html);
    return newWindow;
}