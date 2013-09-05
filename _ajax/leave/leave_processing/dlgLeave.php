<?	require_once("_ajax/leave/common.php"); ?>
<?
	F::GetSubmit(array("leave_id"));
	
	$sql = "select u.user_id, u.user_name
from ac_roles r, ac_users_roles ur, ac_users u
where 
r.role_id = ur.role_id
and ur.user_id = u.user_id
and r.role_name = 'PL'
order by u.user_name";
	$pl_array = Q::ToArray($sql);
	
	$sql = "select u.user_id, u.user_name
from ac_roles r, ac_users_roles ur, ac_users u
where 
r.role_id = ur.role_id
and ur.user_id = u.user_id
and r.role_name = 'approver'
order by u.user_name";
	$approver_array = Q::ToArray($sql);
	
	$sql = "SELECT o.leave_id, date_format(o.create_time, '%Y-%m-%d<br>%H:%i:%s') as create_time, o.user_name, a.area_name, d.dept_name, o.leave_type, o.leave_period, o.total_day, o.status, o.other_info, o.job_involved, o.remark, o.pl_user_id_1, o.pl_user_id_2, o.pl_name_1, o.pl_name_2"
." FROM `leave` o"
." join ac_dept d on (o.dept_id = d.dept_id) join ac_area a on (o.area_id = a.area_id) where o.leave_id = '$leave_id'";
	$row = Q::GetRow($sql);
	
	$sql = "SELECT * from `leave_item` where leave_id='$leave_id' order by leave_item_id asc";
	$leave_item_rows = Q::GetArray($sql);
	$display_row = count($leave_item_rows);
	if($display_row < 1 ) $display_row = 1;
	
	$sql = "SELECT approver_user_id, approver_name from approval where leave_id='$leave_id' order by approval_id asc";
	$approval_item_rows = Q::GetArray($sql);
	$approval_row = count($approval_item_rows);
	if($approval_row < 1 ) $approval_row = 1;
	
	
echo "<table class='InputForm' width='100%'>"
."<tr><td class='Caption'>Fill in date</td><td class='Content'>".$row['create_time']."</td><td class='Caption'>&nbsp;</td><td class='Content'>&nbsp;</td></tr>"
."<tr><td class='Caption' style='width: 20%;'>Type</td><td class='Content' style='width: 30%;'>".F::Select("leave_type", $leave_type_array, $row['leave_type'], " onChange='onLeaveTypeUpdate()'", false)."</td>"
."<td class='Caption' style='width: 15%;'>Username</td><td class='Content' style='width: 35%;'>".$row['user_name']."</td></tr>"
."<tr><td class='Caption other_info_box' id='other_info_lbl'>&nbsp;</td>"
."<td class='Content other_info_box'>".F::Text("other_info", $row['other_info'], 30, 0, " style=\"width:180px;\"")."</td>"
."<td class='Caption pl_box'>PL(s)</td><td class='Content pl_box'>"
.F::Select("pl_user_id_1", $pl_array, $row['pl_user_id_1'], " style='width: 120px;'")
.F::Select("pl_user_id_2", $pl_array, $row['pl_user_id_2'], " style='width: 120px;'")."</td></tr>"
."<tr><td class='Caption'>Job Involved</td><td class='Content'>".F::TextArea("job_involved", 5, 30, "", $row['job_involved'], " style=\"width:200px;\"")."</td>"
."<td colspan='2'><font color='#ff9900'>(Please fill the JobNo. /PL /PC for planning)<br><br>Example：</font><font color='#7396ff'><br>J1234 / Mr. Lam / Winnie Poon, <br>J2244 / Paul / Flora Li,<br>J3344 / Antony Leung / Stephanie Tang</font></td></tr>"
."<tr><td class='Caption'>Remarks</td><td class='Content' colspan=3>".F::Text("remark", $row['remark'], 50, 0, " style=\"width:600px;\"")."</td></tr>"
	
."<tr><th class=\"Header\" colspan=\"4\">Number of rows： ".F::Select("display_row", $day_array, $display_row, " onChange=\"showHideRow(1, 10, parseInt(this.options[this.selectedIndex].value), 'leave_day_');\"", false)." </th></tr>";
echo "<tr><td colspan=4><table class='InputForm'>";
echo "<tr><td class='Caption'>&nbsp;</td><td class='Caption'>From (Date)</td><td class='Caption'>To (Date)</td><td class='Caption'>Length</td></tr>\n";
$leave_day_idx = 0;
$data_rows = count($leave_item_rows);
foreach($day_array as $d_idx => $d_name){
	$leave_from_value = "";
	$leave_to_value = "";
	$leave_length_value = "";
	if($leave_day_idx < $data_rows){
		$leave_from_value = $leave_item_rows[$leave_day_idx]['leave_from'];
		$leave_to_value = $leave_item_rows[$leave_day_idx]['leave_to'];
		$leave_length_value = $leave_item_rows[$leave_day_idx]['leave_length'];
	}
	$temp_value = "";
	$leave_day_idx ++;
	echo "<tr id='leave_day_".$leave_day_idx."'><td class='Caption'>#".$d_name." </td><td class='Content'>";
	echo "From ".F::text("leave_from_".$leave_day_idx, $leave_from_value, 10, 10, " class='leavedatepicker'")
	."</td><td class='Content'>To "
	.F::text("leave_to_".$leave_day_idx, $leave_to_value, 10, 10, " class='leavedatepicker'")
	."</td><td class='Content'>"
	.F::Select("leave_length_".$leave_day_idx, $leave_length_array, $leave_length_value, "", false)
	."</td></tr>\n";
}
echo "</table></td></tr>\n";
echo "<tr><th class=\"Header\" colspan=\"4\"></th></tr>";

$data_rows = count($approval_item_rows);
foreach($day_array as $d_idx => $d_name){
	$temp_approver_user_id = "";
	if($data_rows >= $d_idx) $temp_approver_user_id = $approval_item_rows[$d_idx-1]['approver_user_id'];
	echo "<tr id='approval_row_".$d_idx."'><td class='Caption'>Approved By </td><td class='Content' colspan=3>"
	.F::Select("approver_user_id_".$d_idx, $approver_array, $temp_approver_user_id, " style='width: 180px;'")
	."</td></tr>\n";
}

echo "<tr><th class=\"Header\" colspan=\"4\"></th></tr>";
echo "<tr><td class='Caption'></td><td class='Content' colspan=3>";
if($row['status'] == '0'){
	echo F::Submit("saveLeave(".$leave_id.", 'update')","Save")." "
	.F::Submit("saveLeave(".$leave_id.", 'submit')","Save and Submit")." "
	.F::Submit("delLeave($leave_id)","Delete")." ";
} elseif($row['status'] == '10'){
	echo F::Submit("saveLeave(".$leave_id.", 'submit')","Save and Re-Submit")." "
	.F::Submit("delLeave($leave_id)","Delete")." ";
}
echo F::Submit("tb_remove()","Close")." "
."</td></tr>"
."</table>\n";

?>
<div class='toggle' title='Show Action Log'>
<table class="InputForm">
<?
	$sql = "select * from action_log where leave_id='$leave_id' order by log_id";
	$rows = Q::GetArray($sql);
	foreach($rows as $row){
		echo "<tr valign='top'><td class='Content'>".$row['create_time']."</td>"
		."<td>".str_replace("\n", "<br>",$row['msg'])."</td>"
		."</tr>\n";
	}
?>
</table>
</div>
<?
echo B::Toggle();
?>
<script>
	$(function(){
		var displayRow = parseInt($("#display_row").val(), 10);
		showHideRow(1, 10, displayRow, "leave_day_");
		var approvalRow = 1;
		showHideRow(1, 10, approvalRow, "approval_row_");
		$('.leavedatepicker').datepicker();
		onLeaveTypeUpdate();
	});
	
</script>
