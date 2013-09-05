$(function(){
	leave_id = "";
});

function getLeaveListByUserId(user_id){
	SubmitOptions("getLeaveListByUserId", "#leaveList", {
		user_id: user_id
	}, { ok: function(html){
			$("#leaveList").html(html);
			$(".leaveItem").click(function(){
				var temp_status = $(this).children().eq(1).attr("class").substring(6);
				$(".selectedLeave").removeClass("selectedLeave");
				dlgLeave($(this).addClass("selectedLeave").attr("rel"));
			});
		}
	});
}

function addLeave(){
	SubmitAction('addLeave',{},{
		ok:function(json){
			$(".selectedLeave").removeClass("selectedLeave");
			$table = $("#mainTable");
			$(">tbody", $table).append(json.html);
            $table.trigger("update"); 
			$(".leaveItem[rel=" + json.leave_id + "]").click(function(){
				var temp_status = $(this).children().eq(1).attr("class").substring(6);
				if (temp_status == "0" || temp_status == "10") {
					$(".selectedLeave").removeClass("selectedLeave");
					dlgLeave($(this).addClass("selectedLeave").attr("rel"));
				}
			}).addClass("selectedLeave");
			dlgLeave(json.leave_id);
		}
	});
}

function dlgLeave(id){
	leave_id = id;
	$body = $('body');
	OpenDialog("Leave","dlgLeave",{"leave_id" : id}, {
		width: 740,
		height: $body.height() - 100
	});
}

function showHideRow(RowFrom, RowTo, displayRow, idPrefix){
	for(var i = RowFrom; i <= RowTo; i++){
		if(i > displayRow){
			$("#"+idPrefix+i).hide();
		} else {
			$("#"+idPrefix+i).show();
		}
	}
}

function leaveIsValidDate(str){
	// valid date format "YYYY-MM-DD"
	if(str == "") return true;
	var result=str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
	if(result==null) return false;
	var d=new Date(result[1], result[3]-1, result[4]);
	return (d.getFullYear()==result[1] && d.getMonth()+1==result[3] && d.getDate()==result[4]);
}

function checkLeaveDateInput(RowFrom, RowTo, idPrefix){
	var validCount = 0;
	for (var i = RowFrom; i <= RowTo; i++) {
		var tempSDateStr = $("#"+idPrefix+"from_" + i).val();
		var tempEDateStr = $("#"+idPrefix+"to_" + i).val();
		if(tempSDateStr == "" && tempEDateStr == "") continue;
		if (!leaveIsValidDate(tempSDateStr) || !leaveIsValidDate(tempEDateStr)) {
			alert("Row # " + i + ", invalid date.");
			return false;
		}
		
		if (tempEDateStr != "" && tempSDateStr > tempEDateStr) {
			alert("Row #" + i + ", end date larger than begin date.");
			return false;
		}
		validCount += 1;
	}
	if (validCount < 1) {
		alert("Please fill the date.");
		return false;
	}
	return true;
}

function saveLeave(id, action){
	var display_row = $("#display_row").val();
	var approval_row = $("#approval_row").val();
	if (!checkLeaveDateInput(1, display_row, "leave_")) {
		return false;
	}
	
	if(action == "submit"){
		leave_type = $("#leave_type").val();
		if(leave_type == "60") {
			other_info = $("#other_info").val();
			if (other_info == "") {
				alert("You chose the other leave, please specify reason.");
				return false;
			}
		} else if(leave_type == "70") {
			other_info = $("#other_info").val();
			if (other_info == "") {
				alert("You chose the pay leave, please fill the Job No.");
				return false;
			}
			pl1 = $("#pl_user_id_1").val();
			pl2 = $("#pl_user_id_2").val();
			if (pl1 == "") {
				alert("You chose the pay leave, please fill the PL.");
				return false;
			}
			if (pl1 == pl2) {
				alert("Please fill the left if only one PL.");
				return false;
			}
		}

		if ($("#approver_user_id_1").val() == "") {
			alert("Please select approver.");
			return false;
		} else {
			approver_list = "\n" + $("#approver_user_id_1").children("[@selected]").text();
		} 
		if(!confirm("Confirmation!\n\nSubmit to the following users? \n"+approver_list)) return false;
	}
	SubmitDialog("saveLeave", {leave_id: id, new_action: action}, {
	ok: function(json){
		updateLeaveItem(id, json);
	}
	});
}

function delLeave(id){
	if(!confirm("Delete?")){
		return false;
	}
	
	SubmitDialog("delLeave", {leave_id: id}, {
	ok: function(json){
		$(".leaveItem[rel='" + id+"']").remove();
	}
	});
}

function updateLeaveItem(id, json){
	if (json.error_msg) {
		alert(json.error_msg + "");
		if (json.status == "") {
			$(".leaveItem[rel=" + id + "]").remove();
		}
	} else {
		$(".leaveItem[rel=" + id + "]").replaceWith(json.html);
		$(".leaveItem[rel=" + json.leave_id + "]").click(function(){
			var temp_status = $(this).children().eq(1).attr("class").substring(6);
			if (temp_status == "0" || temp_status == "10") {
				$(".selectedLeave").removeClass("selectedLeave");
				dlgLeave($(this).addClass("selectedLeave").attr("rel"));
			}
		});
	}
}

function onLeaveTypeUpdate(){
	leave_type_value = $("#leave_type").val();
	switch(leave_type_value){
		case "60": 
			// 其它
			$("#other_info_lbl").html("Please specify");
			$(".other_info_box").show();
			$(".pl_box").hide();
			break;
		case "70":
			// 补假
			$("#other_info_lbl").html("Job No.");
			$(".other_info_box").show();
			$(".pl_box").show();
			break;
		default:
			$(".other_info_box").hide();
			$(".pl_box").hide();
	}
}
