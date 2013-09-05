function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

var IE = (document.all) ? true: false;
//document.onkeydown = keyDown
if (document.layers) document.captureEvents(Event.KEYDOWN);

function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function MM_findObj(n, d) { //v4.01
  var p,i,x;
  if(!d) d=document;
  if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);
  }
  if(!(x=d[n])&&d.all) x=d.all[n];

  for (i=0;!x&&i<d.forms.length;i++)
  	x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) 
  	x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n);
  
  return x;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}

function allowRadioUnCheck(inObj){
	var obj = $(inObj);
	if(!obj.attr('disabled')){
		var allRadio = $("input[name='"+obj.attr('id')+"']");
		allRadio.parent().removeClass('checkedTd');
		if(obj.attr('clicked') == obj.val()){
			allRadio.attr('clicked','');
			obj.removeAttr('checked');
			obj.blur();
			if(obj.attr('jq')) eval(obj.attr('jq'));
		} else {
			allRadio.attr('clicked',obj.val());
			obj.parent().addClass('checkedTd');
			obj.blur();
			if(obj.attr('jq')) eval(obj.attr('jq'));
		}
	}
}

function disableIt(obj, enableFlag){
        obj.disabled = enableFlag;
        //var z = (enableFlag) ? 'disabled' : 'enabled';
        //alert(obj.type + ' now ' + z);
}

function extracheck(obj)
{
        return !obj.disabled;
}

function change_dropdown_other(qid, selectObj, otherObj){
	var id = selectObj.options[selectObj.selectedIndex].value;
	if(id != ''){
		var hiddenObj = MM_findObj('other_'+qid+'_'+id);
		if(hiddenObj != null && hiddenObj != undefined) hiddenObj.value = otherObj.value;
	}
}

function change_dropdown(qid, selectObj){
	MM_showHideLayers('span_other_'+qid,'','hide');
	var otherObj = MM_findObj('other_'+qid);
	disableIt(otherObj, true);
	var id = selectObj.options[selectObj.selectedIndex].value
	var hiddenObj = MM_findObj("other_"+qid+"_"+id);
	if(hiddenObj != null && hiddenObj != undefined){ 
		otherObj.value = hiddenObj.value;
		MM_showHideLayers('span_other_'+qid,'','show');
		disableIt(otherObj, false);
		window.setTimeout( function(){ otherObj.select(); otherObj.focus(); }, 0);
	} else {
		otherObj.value = '';	
	}
}

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

function ValidateNumber(txtControl, fieldCaption){
	trimThis(txtControl);
	if (isNaN(txtControl.value)){
		alert(jsLangText.jsFillNum);
		window.setTimeout( function(){ txtControl.select(); txtControl.focus(); }, 0);
		return false;
	}
	else{
		return true;
	}
}			
    
function ValidateBetween(txtControl, nMin, nMax, fieldCaption){
	if (!ValidateNumber(txtControl, fieldCaption)) return (false);

	var nValue = txtControl.value;
	if(nValue == "") return true;
	
	if (nValue >= nMin && nValue <= nMax) {
		return (true);
	} else {
		alert(jsLangText.jsValBtw1 + " " + nMin + " " + jsLangText.jsValBtw2 + " " + nMax + jsLangText.jsValBtw3);
		window.setTimeout( function(){ txtControl.select(); txtControl.focus(); }, 0);
		return (false);
	}
}

function checkNumInArrange(val, s, needAlert){
	intExp = /^[0-9]*$/;
	
	if(!val.match(intExp)){
		if(needAlert) alert("Error (1): Invalid  number (" + val + ")");
		return false;
	}
	
	var intVal = parseInt(val, 10);
	var AAs = s.split(",");

	for(var i = 0; i < AAs.length; i++){
		AA = trimStr(AAs[i]);
		if(AA.indexOf("-") < 0){
			if(!AA.match(intExp)){
				// if(needAlert) alert("Error (2): Invalid number (" + AA + ")");
				return false;
			}
			if(intVal == parseInt(AA)) return true;
		} else {
			BBs = AA.split("-");
			if(BBs.length != 2){
				// if(needAlert) alert("Error (3): Invalid range found in string (" + AA +")");
				return false;
			} else {
				BBs[0] = trimStr(BBs[0]);
				BBs[1] = trimStr(BBs[1]);
				
				if(BBs[0].match(intExp) && BBs[1].match(intExp)){
					BBs[0] = parseInt(BBs[0], 10);
					BBs[1] = parseInt(BBs[1], 10);
					if(BBs[0] < BBs[1]){
						if(intVal >= BBs[0] && intVal <= BBs[1]) return true;
					} else {
						// if(needAlert) alert("Error (4): Invalid range found in string (" + AA +")");
						return false;
					}
				} else {
					// if(needAlert) alert("Error (5): Invalid range found in string (" + AA + ")");
					return false;
				}
			}
		}
	}
	return false;
}

function ValidateContains(txtControl, nTxt){
	trimThis(txtControl);
	if(txtControl.value == "") return true;
	checkPass = checkNumInArrange(txtControl.value, nTxt, false);
	if(!checkPass){
		alert(jsLangText.jsMustBeInFormat1 + " " + nTxt + " " + jsLangText.jsMustBeInFormat2);
		window.setTimeout( function(){ txtControl.select(); txtControl.focus(); }, 0);
		return false;
	} else {
		return true;
	}
}

function ValidateRange(nMin, nMax) {
	if (nMax < nMin) {
		alert("The Max value must be greater than the Min value.");
		return false;
	}
	return true;
}

function ValidatePositive(txtControl, fieldCaption){
	if (!ValidateNumber(txtControl, fieldCaption)) {
		return (false);
	}
	var nValue = txtControl.value;
	if (nValue < 0) {
		alert(jsLangText.jsFillPosNum);
		window.setTimeout( function(){ txtControl.select(); txtControl.focus(); }, 0);
		return false;
	}
	else
		return true;
}

function ValidateInteger(txtControl, fieldCaption){
	if (!ValidateNumber(txtControl, fieldCaption)) {
		return (false);
	}
	var nValue = txtControl.value;
	var nValue2 = parseInt(nValue, 10) + "";
	if(nValue == '' || (nValue == nValue2)){
		return true;
	} else {
		alert(jsLangText.jsFillInt);
		window.setTimeout( function(){ txtControl.select(); txtControl.focus(); }, 0);
		return false;
	}
}

function ValidatePosInteger(txtControl, fieldCaption){
	if (!ValidateNumber(txtControl, fieldCaption)) {
		return (false);
	}
	var nValue = txtControl.value;
	var nValue2 = parseInt(nValue, 10);
	if(nValue == '' || ((nValue == nValue2+"") && nValue2 >= 0)){
		return true;
	} else {
		alert(jsLangText.jsFillPosInt);
		window.setTimeout( function(){ txtControl.select(); txtControl.focus(); }, 0);
		return false;
	}
}

function ValidatePercent(txtControl, fieldCaption){
	if(ValidatePosInteger(txtControl, fieldCaption)){
		if(ValidateBetween(txtControl, 0, 100, fieldCaption))
			return true;
	}
	return false;
}

function ValidateDecimalPlace(txtControl, nIntPlace, nDecPlace, fieldCaption){
	trimThis(txtControl);
	var strFormat = "";
	for (var i=0;i<nIntPlace;i++)
		strFormat = strFormat + "#";

	if(nDecPlace > 0) strFormat = strFormat + ".";
	
	for (var i=0;i<nDecPlace;i++)
		strFormat = strFormat + "#";

	if (!ValidateNumber(txtControl, fieldCaption)) {
		window.setTimeout( function(){ txtControl.select(); txtControl.focus(); }, 0);
		return (false);
	}

	if(nDecPlace < 1){
		if(!ValidateInteger(txtControl, fieldCaption)){
			return (false);
		}
	}
	
	var nValue = txtControl.value
	if(nValue.substr(0,1) == "-") nIntPlace++;	
	var DecPlacePos = (nValue.indexOf("."))
	// If integer
	if (DecPlacePos <= 0){
		if (nValue.length > nIntPlace){
			alert(jsLangText.jsMustBeInFormat1 + " \"" + strFormat + "\" " + jsLangText.jsMustBeInFormat2);
			window.setTimeout( function(){ txtControl.select(); txtControl.focus(); }, 0);
			return false;
		} else {
			return true;
		}
	}
	else if (DecPlacePos > nIntPlace){
		alert(jsLangText.jsMustBeInFormat1 + " \"" + strFormat + "\" " + jsLangText.jsMustBeInFormat2);
		window.setTimeout( function(){ txtControl.select(); txtControl.focus(); }, 0);
		return false;
	}
	else if ((nValue.length-(DecPlacePos+1)) > nDecPlace) {
		alert(jsLangText.jsMustBeInFormat1 + " \"" + strFormat + "\" " + jsLangText.jsMustBeInFormat2);
		window.setTimeout( function(){ txtControl.select(); txtControl.focus(); }, 0);
		return false;
	}
	else {
		if((DecPlacePos+1) == nValue.length){
			txtControl.value = nValue.substr(0,DecPlacePos);
		}
		return true;
	}
	
}

function ValidateMandatory(nValue, fieldCaption){
	if (trimStr(nValue).length < 1) {
		alert(jsLangText.jsCannotEmpty);
		return false;
	}
	else
		return true;
}

function ValidateAmt(txtControl, CompareAmt, fieldCaption){
	if (!ValidateNumber(txtControl, fieldCaption)) {
		return (false);
	}
	var InputAmt = txtControl.value;

	if (InputAmt > CompareAmt) {
		alert(jsLangText.jsFieldLessThan1+ " " + CompareAmt + " " + jsLangText.jsFieldLessThan2);
		window.setTimeout( function(){ txtControl.select(); txtControl.focus(); }, 0);
		return false;
	}
	else
		return true;
}

function ValidateLength(strValue, maxLength, fieldCaption){
	size = strValue.length;
	if (size > maxLength){
		alert(jsLangText.jsNotMoreThanChars1 + " " + maxLength + " " + jsLangText.jsNotMoreThanChars2)
		return false;
	}
	return true;
}

function ValidateAlphaNumeric(strValue, fieldCaption, needAlert){
	regExp = /^\w*$/;
	if(strValue.match(regExp)){
		return true;
	} else {
		if(needAlert){
			alert(jsLangText.jsAlphaNumeric);
		}
		return false;
	}
		
// ***************************************
//	^ means the beginning of the string.
//	\w means a "word" character (0-9 and a-z)
//	* means any amount of the character in front of the *.
//	$ means the end of the string.

}

function ValidateEmail(txtControl){ 
   trimThis(txtControl);
   email = txtControl.value;
   if (email == "") return true; 
   result = true;

   invalidChars = " /:,;" 
  
   for (i=0; i < invalidChars.length; i++){ 
      badChar = invalidChars.charAt(i); 
      if (email.indexOf(badChar, 0) != -1){ 
         result = false; 
      } 
   } 
  
   atPos = email.indexOf("@", 1); 
   if (result && atPos == -1) result = false;
   if (result && email.indexOf("@", atPos+1) != -1) result = false; 

   periodPos = email.indexOf(".", atPos); 
   if (result && periodPos == -1) result = false;
   if (result && periodPos+3 > email.length) result = false; 
   if (result && (periodPos - atPos) == 1) result = false;
   

   if(!result){
   	alert(jsLangText.jsInvalidEmailFmt);
	window.setTimeout( function(){ txtControl.select(); txtControl.focus(); }, 0);
   }  
   return result; 
} 

function survey_details(sid){
	var sDetailsWin = window.open("survey_info.php?sid="+sid,"sDetailsWin","width=700,height=400,scrollbars=1");
	sDetailsWin.focus();
}

function response_details(rid){
	if(rid != ''){
		var sDetailsWin = window.open("response_details.php?rid="+rid,"sDetailsWin","width=700,height=400,scrollbars=1");
		sDetailsWin.focus();
	}
}

function popWin(url){
	var pWin = window.open(url, "pWin", "width=700,height=400,scrollbars=1");
	pWin.focus();
}

function getTextBoxIntValue(obj, idx){
	if(idx < 0){
		if(obj.value == "") return 0;
		return parseInt(obj.value, 10);
	} else {
		if(obj[idx].value == "") return 0;
		return parseInt(obj[idx].value, 10);
	}
}

function autoDisableOtherField(){
	for(var i = 0; i < other_field_check.length ; i++){
		var var_type = other_field_check[i][0];
		var var_name = other_field_check[i][1];
		var var_value = other_field_check[i][2];
		var other_name = other_field_check[i][3];
		
		var other_obj = MM_findObj('input_'+other_name);
		
		if(var_type == 'radio'){
			if(getRadioButtonValue('input_'+var_name) != var_value){
				other_obj.disabled = true;
			}
		} else if(var_type == 'checkbox'){
			if(getCheckBoxValue('input_'+var_name).indexOf("|"+var_value+"|") < 0){
				other_obj.disabled = true;
			}
		}
	} 
}

function enableRadioOtherField(radioName){
	for(var i = 0; i < other_field_check.length ; i++){
		var var_type = other_field_check[i][0];
		var var_name = other_field_check[i][1];
		var var_value = other_field_check[i][2];
		var other_name = other_field_check[i][3];
		
		if(var_type == 'radio' && var_name == radioName){
			var other_obj = MM_findObj('input_'+other_name);
			if(getRadioButtonValue('input_'+var_name) == var_value){
				other_obj.disabled = false;
			} else {
				other_obj.disabled = true;
			}
		}
	} 
}

function isCheckBoxValueContains(inputName, val){
	if(inputName.lastIndexOf("[]") == -1) inputName += "[]";
	var selectedObj = MM_findObj(inputName);
	if(selectedObj){
		if(!selectedObj.length){
			if(!selectedObj.disabled && selectedObj.value == val && selectedObj.checked == true){
				return true;
			}
		} else {
			for(i = 0; i < selectedObj.length; i++){
				if(selectedObj[i].value == val){
					if(!selectedObj[i].disabled && selectedObj[i].checked == true){
						return true;
					}
					break;
				}
			}
		}
	}
	return false;
}

function getCheckedCount(inputName, isUniAns){
	if(isUniAns == undefined) isUniAns = '';
	if(inputName.lastIndexOf("[]") == -1) inputName += "[]";
	
	var allCheckedR = $('input:checked:enabled[name="' +inputName+ '"]');
	if(isUniAns == '') return allCheckedR.length;
	return allCheckedR.filter('[uniAns="'+isUniAns+'"]').length;
}

function getCheckBoxSize(inputName){
	return $('input[id="' + inputName + '"]').length;
}

function validCheckBoxUniAns(checkBoxObj){
	var thisCb = $(checkBoxObj);
	if(thisCb.length == 0) return false; // quit if no obj found
	var allCb = $('input[name="'+ thisCb.attr('name') +'"]');
	
	var isUniAns = thisCb.attr('uniAns');
	var checkBoxSize = allCb.length;

	if(checkBoxSize > 1){
		if(thisCb.attr('checked')){
			if(isUniAns == 'Y'){
				allCb.each(function(i){
					$(this).attr('disabled','disabled');
				});
				thisCb.removeAttr('disabled');
			} else {
				allCb.each(function(i){
					var r = $(this);
					if(r.attr('uniAns') == 'Y') r.attr('disabled','disabled');
				});
			}
		} else {
			var uniCount = allCb.filter(':checked:enabled[uniAns="Y"]').length;
			var nonUniCount = allCb.filter(':checked:enabled[uniAns="N"]').length;
			
			if(uniCount == 0){
				allCb.each(function(i){
					var r = $(this);
					if(r.attr('uniAns') == 'N')	r.removeAttr('disabled');
				});
			}
			if(nonUniCount == 0){
				allCb.each(function(i){
					var r = $(this);
					if(r.attr('uniAns') == 'Y') r.removeAttr('disabled');
				});
			}
		}
		
		allCb.each(function(i){
			var r = $(this);
			updateUncheckedOtherField(r.attr('name'), r.val(), !r.attr('checked'));
		});
	} else if(checkBoxSize == 1){
		updateUncheckedOtherField(thisCb.attr('name'), thisCb.val(), !thisCb.attr('checked'));
	}

}

function updateUncheckedOtherField(checkBoxName, checkValue, disabledStatus){
	for(var i = 0; i < other_field_check.length ; i++){
		var var_type = other_field_check[i][0];
		var var_name = "input_" + other_field_check[i][1] + "[]";
		var var_value = other_field_check[i][2];
		var other_name = other_field_check[i][3];
		
		if(var_name != checkBoxName || var_value != checkValue){
			continue;
		} else {
			MM_findObj('input_'+other_name).disabled = disabledStatus;
			break;
		}
	}
}

function autoDisableCheckBox(checkBoxName){
	var allCb = $('input[name="'+checkBoxName+'"]');
	if(allCb.length > 1){
		var uniCount = allCb.filter(':checked:enabled[uniAns="Y"]').length;
		var nonUniCount = allCb.filter(':checked:enabled[uniAns="N"]').length;
		
		if(uniCount > 0){
			allCb.each(function(i){
				var r = $(this);
				if(!r.attr('checked')) r.attr('disabled','disabled');
			});
		}
		if(nonUniCount > 0){
			allCb.each(function(i){
				var r = $(this);
				if(r.attr('uniAns') == 'Y') r.attr('disabled','disabled');
			});
		}
	}
}

function getCheckBoxValue(inputName){
	if(inputName.lastIndexOf("[]") == -1) inputName += "[]";
	var val = "|";
	$('input:checked:enabled[name="'+inputName+'"]').each(function(i){
		val += $(this).val() + "|";
	});
	return val;
}



function validCheckBox(currObj, max_size){
	var cb_seq_name = currObj.name.substr(0, currObj.name.length-2);
	var cb_seq_obj = MM_findObj("cb_seq_"+cb_seq_name);
	if(cb_seq_obj){
		cb_seq_obj.value = cb_seq_obj.value.replace("|"+currObj.value+"|", "|");
		cb_seq_obj.value += currObj.value + "|";
	}
	if(validMaxCheckedSize(currObj.name, max_size)){
		validCheckBoxUniAns(currObj);
		return true;
	}
	return false;
}

function validMaxCheckedSize(inputName, max_size){
	checkedSize = getCheckedCount(inputName, '');
	if(checkedSize > max_size){
		alert(jsLangText.jsMaxCheckedBoxSize1 + max_size + jsLangText.jsMaxCheckedBoxSize2);
		return false;	
	}
	return true;
}

function showDiv(divName, inputNames){
	var divObj = MM_findObj(divName);
	if(divObj.style){
		divObj.style.display = 'block';
		divObj.style.visibility = 'visible';
		if(inputNames != ''){
			inps = inputNames.split(",");
			for(i = 0; i < inps.length; i++){
				var inp = MM_findObj(inps[i]);
				if(inp){
					inp.disabled = false;
					if(inp.length){
						for(j = 0; j <inp.length; j++){
							if(inp[j]) inp[j].disabled = false;
						}
					}
				}
			}
		}
	}
}

function hideDiv(divName, inputNames){
	var divObj = MM_findObj(divName);
	if(divObj.style){
		divObj.style.display = 'none';
		divObj.style.visibility = 'hidden';
		if(inputNames != ''){
			inps = inputNames.split(",");
			for(i = 0; i < inps.length; i++){
				var inp = MM_findObj(inps[i]);
				if(inp){
					inp.disabled = true;
					if(inp.length){
						for(j = 0; j < inp.length; j++){
							if(inp[j]) inp[j].disabled = true;
						}
					}
				}
			}
		}
	}
}

function clear_select(obj){
	if(obj.length){
		for(i = obj.length -1; i >= 0; i--){
			obj.options[i] = null;
		}
	}
}

function set_list_to_select(obj, list, default_value){
	var default_selectedIndex = 0;
	for(i = 0; i< list.length; i++){
		obj.options[i] = new Option(list[i], list[i]); 
		if(list[i] == default_value) default_selectedIndex = i;
	}
	obj.selectedIndex = default_selectedIndex;
}

	function isRadioButtonChecked(input_name){
		var checkVal = getRadioButtonValue(input_name);
		return (checkVal != '');
	}
	
	function getRadioValue(inputName){
		return getRadioButtonValue(inputName);
	}
	
	function getRadioButtonValue(input_name){
		var checkVal = '';	
		var obj = MM_findObj(input_name);
		if(obj){
			if(obj.length){
				for(checkVali = 0; checkVali < obj.length; checkVali++){
	   				if(obj[checkVali].checked == true){
	   					checkVal = obj[checkVali].value;
						// if (checkVal == obj[checkVali].clicked) checkVal = '';
	   					break;
	   				}
	   			}
			} else {
				if(obj.checked == true){
					checkVal = obj.value;
					// if (checkVal == obj.clicked) checkVal = '';
				}
			}
   		}
   		return checkVal;
	}
	
	function isDropdownSelected(input_name){
		var val = getDropdownValue(input_name)
   		return (val != '');
	}
	
	function getDropdownValue(input_name){
		var val = '';
		var obj = MM_findObj(input_name);
		if(obj){
			val = obj[obj.selectedIndex].value;
   		}
   		return val;
	}
	
	function mandatoryTwoDimensionTextInput(input_name){
		var isEmpty = false;
		var obj = MM_findObj('input_'+input_name+'[]');
		if(obj){
			if(obj.length){
				for(i = 0; i < obj.length; i++){
					if(isInputBoxEmptyOneRow('input_'+input_name+'[]', i)){
						highLightOneRow('tr_'+input_name, '#FFD9DA', i);
						isEmpty = true;
					} else {
						highLightOneRow('tr_'+input_name, '#FFFFFF', i);
					}
						
				}
			} else {
				if(isInputBoxEmpty('input_'+input_name+'[]')){
						isEmpty = true;
						highLight('tr_'+input_name, '#FFD9DA');
					} else {
						highLight('tr_'+input_name, '#FFFFFF');
						isEmpty = false;
					}
			}
		}

		return isEmpty;
	}
	
	function isInputBoxEmptyOneRow(input_name, rowIndex){
		var isEmpty = true;
		var obj = MM_findObj(input_name);
		if(obj){
			if(obj.length){
				if(rowIndex < obj.length){
					if(obj[rowIndex].value != '') isEmpty = false;
				}
			}
		}
		return isEmpty;
	}
	
	function isInputBoxEmpty(input_name){
		var isEmpty = true;
		var obj = MM_findObj(input_name);
		if(obj){
			if(obj.value != '' || obj.disabled) isEmpty = false;
		}
		return isEmpty;
	}
	
	function highLightOneRow(tr_name, tr_color, rowIndex){
		// hightLight only one row if more than one rows contains same tr_name 
		var obj = MM_findObj(tr_name);
		if(obj){
			if(obj.length){
				if(rowIndex < obj.length){
					obj[rowIndex].style.backgroundColor = tr_color;
				}
			}
		} 
	}
	
	function highLight(tr_name, tr_color){
		$('tr[id="'+tr_name+'"]').each(function(i){
			$(this).css({ backgroundColor:tr_color });
		});
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

function mkArray(s){
	intExp = /^[0-9]*$/;
	txtExp = /^[A-Za-z]*$/;
	capExp = /^[A-Z]*$/;
	var a = new Array();
	var AAs = s.split(",");
	
	for(var i = 0; i < AAs.length; i++){
		AA = trimStr(AAs[i]);
		if(AA.indexOf("-") < 0){
			a.push(parseInt(AA, 10));
		} else {
			BBs = AA.split("-");
			
			if(BBs.length != 2){
				// alert("mkArray error(1): Invalid range found in string (" + AA +")");
				return new Array();
			} else {
			
			
				BBs[0] = trimStr(BBs[0]);
				BBs[1] = trimStr(BBs[1]);
				
				if(BBs[0].match(intExp) && BBs[1].match(intExp)){
					// alert(BBs[0] + " " + BBs[1]);
					BBs[0] = parseInt(BBs[0], 10);
					BBs[1] = parseInt(BBs[1], 10);
					if(BBs[0] < BBs[1]){
						for(j = BBs[0]; j <= BBs[1]; j++){
							a.push(j);
						}
					} else {
						// alert("mkArray error(2): Invalid range found in string (" + AA +")");
						return new Array();
					}
				/* // not finished
				} else if(BBs[0].match(txtExp) && BBs[1].match(txtExp)){
					if(BBs[0].match(capExp)){
						BBs[0] = BBs[0].toUpperCase();
						BBs[1] = BBs[1].toUpperCase();
					} else {
						BBs[0] = BBs[0].toLowerCase();
						BBs[1] = BBs[1].toLowerCase();
					}
					if(BBs[0] < BBs[1]){
						for(j = BBs[0]; j <= BBs[1]; j++){
							a.push(j);
						}
					} else {
						// alert("mkArray error(3): Invalid range found in string (" + AA + ")") ;
						return new Array();
					}
				} */
				
				} else {
					// alert(BBs[0] + " ?? " + BBs[1] + " if = " +(intExp.test(BBs[0]) && intExp.test(BBs[1])));
					
					// alert("mkArray error(4): Invalid range found in string (" + AA + ")");
					return new Array();
				}
			}
		}
	}
	return a;
}

function arrayDiff(c, v, m){
	// return different of two array
    var d = [], e = -1, h, i, j, k;
    for(i = c.length, k = v.length; i--;){
        for(j = k; j && (h = c[i] !== v[--j]););
        h && (d[++e] = m ? i : c[i]);
    }
    return d;
};

/*
function autoSum(input_name, target_input_name, decimalPlace){
	var objs = MM_findObj(input_name);
	var tar = MM_findObj(target_input_name);
	xTotal = 0;
	if(objs && tar){
		if(objs.length){
			for(i = 0; i <objs.length; i++){
				x = parseFloat(objs[i].value);
				if(!isNaN(x))	xTotal += x;
			}
		} else {
			x = parseFloat(objs.value);
			if(!isNaN(x)) xTotal += x;
		}
		tar.value = xTotal.toFixed(decimalPlace); ;
	}
}

function autoSum2(){
	var idx = 0;
	var args=autoSum2.arguments;
	var obj = args[0];
	var obj2 = MM_findObj(obj.name);
	if(obj == obj2){
		idx = 0;
	} else {
		for(i = 0; i < obj2.length; i++){
			if(obj == obj2[i]){
				idx = i;
				break;
			}
		}
	}
	var target_input_name = args[1];
	var decimalPlace = args[2];
	var total = 0;
	for(i = 3; i < args.length; i++){
		obj = MM_findObj(args[i]);
		if(obj){
			if(obj.length){
				if(obj[idx].value != "") total += parseInt(obj[idx].value, 10);
			} else {
				if(obj.value != "") total += parseInt(obj.value);
			}
		}
	}
	obj = MM_findObj(target_input_name);
	if(obj){
		if(obj.length){
			obj[idx].value = total;
		} else {
			obj.value = total;
		}
	}
}
*/

// check the radio button is on check or uncheck action
function isUnCheck(obj){
	return obj.val() == obj.attr('clicked');
}
