$(function(){
	leave_id = "";
	$('#divMonth').monthpicker({
		name:"date",
		onChanged: function(date) { 
			getHistoryListByMonth();
		}
	});
	getHistoryListByMonth();
});

function getHistoryListByMonth(){
	SubmitOptions("getHistoryListByMonth", "#leaveList", $("#date").serialize() , { 
		ok: function(html){
			$("#leaveList").html(html);
			$(".leaveItem").click(function(){
				var temp_status = $(this).children().eq(1).attr("class").substring(6);
				$(".selectedLeave").removeClass("selectedLeave");
				dlgLeave($(this).addClass("selectedLeave").attr("rel"));
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
