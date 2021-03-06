function any(val) {
	return true;
}
function isEmpty(str){
	if (str== null){return true;}return(trimStr(str).length==0);
}
function isFunction(func){
	if (func == null) {
		return false;
	}
	return (typeof func == 'function');
}
function isNumber(val) {	// valid number, allow {positive,negative}{float,integer}
	var pattern = /^([0-9]|(-[0-9]))[0-9]*((\.[0-9]+)|([0-9]*))$/;
	val = trimStr(val);
	if(val == ""){
		return false;
	}
	return pattern.test(val) ;
}
function isInteger(val) {	// valid integer, allow {positive,negative}{integer}
 	var pattern = /^(\d|(-\d))\d*$/;
	val = trimStr(val);
	if(val == ""){
		return false;
	}
	return pattern.test(val);
}
function isUnsigned(val){	// valid unsigned integer, allow {positive}{integer}
	var pattern = /^\d*$/;
	val = trimStr(val);
	if(val == ""){
		return false;
	}
	return pattern.test(val) ;
}
function isAlphaNumeric(str){
	regExp = /^\w*$/;
	return str.match(regExp);
}
function isDate(str){		// valid date format "YYYY-MM-DD"
	
	var result=str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
	if(result==null) return false;
	var d=new Date(result[1], result[3]-1, result[4]);
	return (d.getFullYear()==result[1] && d.getMonth()+1==result[3] && d.getDate()==result[4]);
}
function isTime(str){		// valid time format "HH:MM:SS"
  var resule=str.match(/^(\d{1,2})(:)?(\d{1,2})\2(\d{1,2})$/);
	if (result==null) return false;
	if (result[1]>24 || result[3]>60 || result[4]>60) return false;
	return true;
}
function isDatetime(str){	// valid date format "YYYY-MM-DD HH:MM:SS"
	var result=str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/);
	if(result==null) return false;
	var d= new Date(result[1], result[3]-1, result[4], result[5], result[6], result[7]);
	return (d.getFullYear()==result[1] && (d.getMonth()+1)==result[3] && d.getDate()==result[4] && d.getHours()==result[5] && d.getMinutes()==result[6] && d.getSeconds()==result[7]);
}
function isValidDate(str){
	// valid date format "YYYY-MM-DD"
	var result=str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
	if(result==null) return false;
	var d=new Date(result[1], result[3]-1, result[4]);
	return (d.getFullYear()==result[1] && d.getMonth()+1==result[3] && d.getDate()==result[4]);
}
function isValidTime(str){
	// valid time format "HH:MM:SS"
  var resule=str.match(/^(\d{1,2})(:)?(\d{1,2})\2(\d{1,2})$/);
	if (result==null) return false;
	if (result[1]>24 || result[3]>60 || result[4]>60) return false;
	return true;
}
function isValidDatetime(str){
	// valid date format "YYYY-MM-DD HH:MM:SS"
	var result=str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/);
	if(result==null) return false;
	var d= new Date(result[1], result[3]-1, result[4], result[5], result[6], result[7]);
	return (d.getFullYear()==result[1] && (d.getMonth()+1)==result[3] && d.getDate()==result[4] && d.getHours()==result[5] && d.getMinutes()==result[6] && d.getSeconds()==result[7]);
}
function isUnCheck(obj){
	return obj.val() == obj.attr('clicked');
}
function isRadioButtonChecked(input_name){
	var checkVal = getRadioButtonValue(input_name);
	return (checkVal != '');
}

