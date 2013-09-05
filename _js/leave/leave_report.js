$(function(){
	leave_id = "";
	$('#divMonth').monthpicker({
		name:"date",
		onChanged: function(date) { 
			OptionChanged();
		}
	});
	OptionChanged();
});

function OptionChanged(){
	SubmitOptions("OptionChanged", "#leaveList", $("#date,#search_job_no,#search_department_id,#search_user_id,input[@name=search_group][@checked]").serialize() , { 
		ok: function(html){
			$("#leaveList").html(html);
			$(".leaveItem").click(function(){
				var temp_status = $(this).children().eq(1).attr("class").substring(6);
				dlgLeave($(this).attr("rel"));
			});
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

function delLeave(id){
    var reason = InputDialog("Please input the reason", any, "Please input the number", "");
	
    if (isEmpty(reason)) { 	
    	alert("Reason can't empty!");
    } else {

	    if (!confirm("Delete this application? : \n\nReason : " + reason)) {
	    	return false;
	    }
		SubmitDialog("delLeave", {leave_id: id,message: reason}, {
			ok: function(json){
				OptionChanged();
			}
		});   
    }
}
