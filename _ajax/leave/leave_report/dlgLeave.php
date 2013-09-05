<?php	require_once("_ajax/leave/common.php"); ?>
<?php
	F::GetSubmit(array("leave_id"));
/*	
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
	*/
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
."<tr><td class='Caption' style='width: 20%;'>Type</td><td class='Content' style='width: 30%;'>".$leave_type_array[$row['leave_type']]." <span id='total_day' style='color: red;'>( ".round($row['total_day'], 1)." Days)</span>".F::Select("leave_type", $leave_type_array, $row['leave_type'], "", false)."</td>"
."<td class='Caption' style='width: 15%;'>Username</td><td class='Content' style='width: 35%;'>".$row['user_name']."</td></tr>"
."<tr><td class='Caption other_info_box' id='other_info_lbl'>&nbsp;</td>"
."<td class='Content other_info_box'>".$row['other_info']."</td>"
."<td class='Caption pl_box'>PL(s)</td><td class='Content pl_box'>"
.$row['pl_name_1']." &nbsp; &nbsp; &nbsp;"
.$row['pl_name_2']."</td></tr>"
."<tr><td class='Caption'>Job Involved：</td><td class='Content'>".F::TextArea("job_involved", 5, 30, "", $row['job_involved'], " style=\"width:200px;\"")."</td>"
."<td colspan='2'><font color='#ff9900'>(Please fill the JobNo. /PL /PC for planning)<br><br>Example：</font><font color='#7396ff'><br>J1234 / Mr. Lam / Winnie Poon, <br>J2244 / Paul / Flora Li,<br>J3344 / Antony Leung / Stephanie Tang</font></td></tr>"
."<tr><td class='Caption'>Remarks</td><td class='Content' colspan=3>".$row['remark']."</td></tr>"
	
."<tr><th class=\"Header\" colspan=\"4\">Number of rows： ".$display_row." </th></tr>";
echo "<tr><td colspan=4><table class='InputForm' width='300'>";
echo "<tr><td class='Caption'>&nbsp;</td><td class='Caption'>From (Date)</td><td class='Caption'>To (Date)</td><td class='Caption'>Length</td></tr>\n";
$leave_day_idx = 0;
$data_rows = count($leave_item_rows);
foreach($day_array as $d_idx => $d_name){
	if($leave_day_idx < $data_rows){
		$leave_from_value = $leave_item_rows[$leave_day_idx]['leave_from'];
		$leave_to_value = $leave_item_rows[$leave_day_idx]['leave_to'];
		$leave_length_value = $leave_item_rows[$leave_day_idx]['leave_length'];
	
		$temp_value = "";
		$leave_day_idx ++;
		echo "<tr id='leave_day_".$leave_day_idx."'><td class='Caption'>#".$d_name." </td><td class='Content'>";
		echo "From ".$leave_from_value
		."</td><td class='Content'>To "
		.$leave_to_value
		."</td><td class='Content'>"
		.$leave_length_array[$leave_length_value]
		."</td></tr>\n";
	}
}
echo "</table></td></tr>\n";

if(count($approval_item_rows) > 0) echo "<tr><th class=\"Header\" colspan=\"4\"></th></tr>";
foreach($approval_item_rows as $approval_item_row){
		echo "<tr><td class='Caption'>Approved By </td><td class='Content' colspan=3>"
		.$approval_item_row['approver_name']
		."</td></tr>\n";
}

echo "<tr><th class=\"Header\" colspan=\"4\"></th></tr>";
echo "<tr><td class='Caption'></td><td class='Content' colspan=3>";

if($row['status'] == '30' && isset($_SESSION["roles"][119])){
	echo F::Submit("delLeave($leave_id)","Delete")." ";
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
		$("#leave_type").hide();
		onLeaveTypeUpdate();
	});
	
</script>
