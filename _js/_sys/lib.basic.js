function killErrors() {
    return true;
}
//window.onerror = killErrors;

/* MM Lib */
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
function MM_swapImgRestore() { //v3.0
 var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
 var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
 var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
 if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_findObj(n, d) { //v4.01
 var p,i,x; if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
 d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
 if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
 for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
 if(!x && d.getElementById) x=d.getElementById(n); return x;
}
function MM_swapImage() { //v3.0
 var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
 if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}

/* Basic Function */
function rTrimStr(sValue){
	var i;
	for (i=sValue.length; i > 0; i--){
		if (sValue.substr(i-1,1) != " ") return sValue.substr(0,i);
	}
	return( "" );
}
function lTrimStr(sValue){
	var i;
	for (i=0; i < sValue.length; i++){
		if (sValue.substr(i,1) != " ") return sValue.substr(i);
	}
	return( "" );
}
function trimStr(sValue){
	return rTrimStr(lTrimStr(sValue));
}
function trimThis(obj){
	obj.value = trimStr(obj.value);
}
function FillEmpty(query,str){
	str = str.length == 0 ? "-" : str;
	$(query).each(function (i) {if (!this.innerHTML) {this.innerHTML = str;}});
	return true;
}
function stopEvent(evt){               
    if(window.event){window.event.cancelBubble=true;}
	else{evt.stopPropagation()};
}

var CurrentModule	= "";
var CurrentPages 	= "";
var ReferModule 	= "";
var ReferPages 		= "";

var goPrintDoc		= null;
var goPrint			= null;

//Global Config
var gcAjaxTimeout	= 60000;
var gcAjaxMethod	= "post";
var gcAjaxReturn	= "json"

var P_DEFAULT_AJAX_OPTIONS = $.extend({ //default
    dataType		: "json",
	//callback function ref.
	afterValidate	: false,
	ok				: false,
	fail			: false,
	warn			: false,
	
	displayLayer	: false,
	showSuccessMsg	: true,
	showWarnMsg		: true,
	showErrorMsg	: true,
	resetOnSuccess	: false,
	closeOnSuccess  : false,
	other			: false,
	global			: true
},{});

function OpenModule(module_name, page_name){
    $('#gModule').val(module_name);
    $('#gPages').val(page_name);
    $.ajax({
        url		: "pages.php",
        type	: gcAjaxMethod,
        dataType: 'html',
        timeout	: gcAjaxTimeout,
        data	: {
            gModule: module_name,
            gPages: ""
        },
        error	: g_HandleResponseError,
        success	: function(html){
            $('#nav').html(html);
            $('#nav').droppy();
			
			if($.browser.msie == true && $.browser.version == 6.0){
	            $("#nav").hover(function(){
	                setTimeout('$("#divPages select").css({ visibility: "hidden" })', 0);
	            }, function(){
	                setTimeout('$("#divPages select").css({ visibility: "visible" })', 0);
	            });				
			}
      
            ReferModule = CurrentModule;
            CurrentModule = module_name;
            if(page_name == "") page_name = 'home';
            OpenPages(page_name);
        }
    });//end of ajax
}
function OpenPages(pages_name){	
	module_name = $('#gModule').val();
	$('#gPages').val(pages_name);
	$.ajax({url: "pages.php",
		type		: gcAjaxMethod,
		dataType	: 'html',
		timeout		: gcAjaxTimeout,
        data: {
            gModule: module_name,
            gPages: pages_name
        },
        error	: g_HandleResponseError,
		success		: function(html){
			ReferPages = CurrentPages;
			CurrentPages = pages_name;
			p_resize = false;
			
			for(i = $.datepicker._inst.length; i > 0; i--){
				$.datepicker._inst[i-1] = null;
			}
			$.datepicker._nextId = 0;			
			$.mce.remove();
			$('#divPages').html(html);
		}
	});//end of ajax
}
function OpenDialog(caption,func,param,opts){
    if (isEmpty(func)) return WarningDialog("warning : empty function name");
	$.mce.remove();
	tb_ajaxDialog(caption,"ajax.php",MakePostData(func,null,param),opts);
}
function OpenFile(func,param,opts){
	var url = "ajax.php";
	param = MakePostData(func,null,param);
	if (param.length > 0){
		url += "?" + param;
	}	
    ifrm = document.getElementById('iFile');
    ifrm.src = url;
}
function PrintPages(){
	var oIframe = document.getElementById('iPrint');
	goPrint = (oIframe.contentWindow || oIframe.contentDocument);
	if (goPrint.document) goPrintDoc = $(goPrint.document).find('#divPrintContent');

	goPrintDoc.html($('#divPages').html());
	goPrint.focus();
	goPrint.print();
}
function RefreshPages(){
	OpenPages(CurrentPages);
}
function MakePostData(func, formParam, otherParam){
    var defaultParam = {
        gModule: $('#gModule').val(),
        gPages: $('#gPages').val(),
        gMethod: func
    };
    var postData = $.param(defaultParam);
    if (formParam !== null) {
        postData += "&" + formParam;
    }
    if (otherParam) {
		if (typeof otherParam != "string" ) {
			otherParam = jQuery.param(otherParam);	
		}
        postData += "&" + otherParam;
    }    
    return postData;
}
function RequiredIsEmpty(obj){
	switch(obj.attr("type")) {
		case "checkbox" : return ($('input:checked:enabled[name="' + obj.attr("name") + '"]').length == 0);
		case "radio" 	: return ($('input:checked:enabled[name="' + obj.attr("name") + '"]').length == 0);
		default			: return isEmpty(obj.val());
	}
}
function ValidateRequired(query) {
	var error = 0;
	var fristErrorObj = null;
	$(query + ' .required:input').each(function(){
		var obj  = $(this);
		if(RequiredIsEmpty(obj)) {
			obj.parents('tr:first').addClass('Error');
			if(0 == error++) {
				fristErrorObj = obj;
			}
		} else {
			obj.parents('tr:first').removeClass('Error');			
		}
	});    
    if (fristErrorObj) { //set focus to the frist empty field
        window.setTimeout(function(){
            fristErrorObj.focus();
        }, 0);
    }
	return error;
}

function g_HandleResponseError(XMLHttpRequest, textStatus, errorThrown){
	WarningDialog(textStatus);	
}
function g_HandleResponse(json,opts){
	if(opts.dataType == "json") {
	    switch (json.type) {
	        case "ok":
	        case "success":
	            if (isFunction(opts.ok)) {						
	                if(opts.ok(json)){}
	            }
				if(opts.resetOnSuccess) {
					$('#mainForm').clearForm();
				}
				if(opts.showSuccessMsg && json.msg) {
					WarningDialog(json.msg);
				}
				if(opts.closeOnSuccess) {
					tb_remove();
				}
	            break;
	        case "warn":
	        case "warning":
	            if (isFunction(opts.warn)) {
	                if(opts.warn(json)){}
	            }
				if(opts.showWarnMsg && json.msg) {
					WarningDialog(json.msg);
				}
	            break;
	        case "error":
	            if (isFunction(opts.fail)) {
	                if(opts.fail(json)){}
	            }
				if(opts.showErrorMsg && json.msg) {
					WarningDialog(json.msg);
				}
	            break;
	    }
	} else  {
		if(opts.displayLayer) {
			if(opts.displayLayer) {
				$(opts.displayLayer).html(json);						
			}
		}
		if (isFunction(opts.ok)) {
			if(opts.ok(json)){}
		}
	}
}

function SubmitAction(func,param,opts) {
    if (isEmpty(func)) return WarningDialog("warning : empty function name");
    opts = $.extend({},P_DEFAULT_AJAX_OPTIONS, opts);
    $.ajax({
        url		: "ajax.php",
        type	: gcAjaxMethod,
        timeout	: gcAjaxTimeout,
        dataType: opts.dataType,
        data	: MakePostData(func,null,param),
		global	: opts.global,
        error: g_HandleResponseError,
        success: function(json){g_HandleResponse(json,opts);}
    });
}
function SubmitOptions(func,displayArea,param,opts) {
    if (isEmpty(func)) return WarningDialog("warning : empty function name");
    opts = $.extend({},P_DEFAULT_AJAX_OPTIONS,{
        dataType		: "html",
		displayLayer	: displayArea
    }, opts);
    $.ajax({
        url		: "ajax.php",
        type	: gcAjaxMethod,
        timeout	: gcAjaxTimeout,
        dataType: opts.dataType,
        data	: MakePostData(func,null,param),
        error: g_HandleResponseError,
        success: function(json){g_HandleResponse(json,opts);}
    });
}
function SubmitPages(func , opts, param) {
    if (isEmpty(func)) return WarningDialog("warning : empty function name");
    opts = $.extend({},P_DEFAULT_AJAX_OPTIONS,{
		resetOnSuccess	: false
    }, opts);
	if(ValidateRequired("#divPages")) {	return WarningDialog("Please fill the required field");}
    $.ajax({
        url		: "ajax.php",
        type	: gcAjaxMethod,
        timeout	: gcAjaxTimeout,
        dataType: opts.dataType,
        data	:  MakePostData(func,$('#mainForm').serialize(),param),
        error: g_HandleResponseError,
        success: function(json){g_HandleResponse(json,opts);}
    });
}
/**
 * Submit the dialog form to server over ajax post method
 * 
 * @param {String} func 
 * ajax file name
 * @param {Object} param
 * submit param
 * e.g. {id:1234}
 * @param {Object} opts
 * callback function & other options
 */
function SubmitDialog(func , param, opts){
    if (isEmpty(func)) return WarningDialog("warning : empty function name");
    opts = $.extend({},P_DEFAULT_AJAX_OPTIONS,{ 
		closeOnSuccess  : true
    }, opts);
	if(ValidateRequired("#dlgForm")) {	return WarningDialog("Please fill the required field");}
    $.ajax({
        url		: "ajax.php",
        type	: gcAjaxMethod,
        timeout	: gcAjaxTimeout,
        dataType: opts.dataType,
        data	: MakePostData(func,$('#dlgForm').serialize(),param),
        error: g_HandleResponseError,
        success: function(json){
			g_HandleResponse(json,opts);
        }
    });//end of ajax
}
function ConfirmDialog(message){
	return confirm(message);
}
function WarningDialog(message){
	alert(message);
	return false;
}
function InputDialog(message,vaild,errormsg,defaultval) {
	var msg 	= message;
	var msg_err = msg + "\n" + errormsg;
	var iVal	= defaultval;
	do {
		iVal = prompt(msg,iVal);
		if (iVal != null) {			
			if (vaild(iVal)) {
				break;
			} else {
				msg = msg_err;
			}
		}

	} while (iVal != null)
	return 	iVal;
}
