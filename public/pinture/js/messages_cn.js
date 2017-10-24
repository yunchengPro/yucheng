var funlang='';
var strCookie=document.cookie;
var arrCookie=strCookie.split("; ");
for(var langi=0;langi<arrCookie.length;langi++){
	var langarr=arrCookie[langi].split("=");
	if("edcrmlang"==langarr[0]){
		funlang=langarr[1];
		break;
	} 
}
if (funlang=='' || funlang=='zh_CN'){
		jQuery.extend(jQuery.validator.messages, {
		   required: "必填字段",
		   remote: "请修正该字段",
		   email: "请输入正确的Email",
		   url: "请输入合法的网址",
		   date: "请输入合法的日期",
		   dateISO: "请输入合法的日期 (ISO).",
		   number: "请输入合法的数字",
		   digits: "只能输入整数",
		   creditcard: "请输入合法的信用卡号",
		   equalTo: "请再次输入相同的值",
		   accept: "请输入拥有合法后缀名的字符串",
		   maxlength: jQuery.validator.format("请输入一个长度最多是 {0} 的字符串"),
		   minlength: jQuery.validator.format("请输入一个长度最少是 {0} 的字符串"),
		   rangelength: jQuery.validator.format("请输入一个长度介于 {0} 和 {1} 之间的字符串"),
		   range: jQuery.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
		   max: jQuery.validator.format("请输入一个最大为 {0} 的值"),
		   min: jQuery.validator.format("请输入一个最小为 {0} 的值")
		});
}
if (funlang=='en_US'){
		jQuery.extend(jQuery.validator.messages, {
		  required: "Required fields",
		  remote: "Please fix this field",
		  email: "Please enter a valid email format",
		  url: "Please enter a valid URL",
		  date: "Please enter a valid date",
		  dateISO: "Please enter a valid date (ISO).",
		  number: "Please enter a valid number",
		  digits: "Enter only integer",
		  creditcard: "Please enter a valid credit card number",
		  equalTo: "Please enter the same value again",
		  accept: "Please enter the string has a legitimate extension",
		  maxlength: jQuery.validator.format("Please enter a maximum length of string is {0}"),
		  minlength: jQuery.validator.format("Please enter a minimum length of string is {0}"),
		  rangelength: jQuery.validator.format("Please enter a length of string between {0} and {1} between"),
		  range: jQuery.validator.format("Please enter a value between {0} and {1} between"),
		  max: jQuery.validator.format("Please enter a maximum value of {0}"),
		  min: jQuery.validator.format("Please enter a minimum value of {0}")
		});
}		



