<?	require_once("_ajax/leave/common.php"); ?>
<?
	F::GetSubmit(array("date", 'search_department_id',"search_user_id", "search_group"));
?>
<table id="mainTable" class="tablesorter" width="100%">
<thead>
<tr>
	<th>Submission Date</th>
	<th>Status</th>
	<th>Department</th>
	<th>User Name</th>
	<th>Type</th>
	<th>Date</th>
	<th>Job Involved</th>
	<th>Remarks</th>
</tr>
</thead>
<tbody>
<?php
list($y, $m) =split("-", $date);
// *********************************
if($m == "00"){
	// all month
	$condition = "DATE_FORMAT( o.modify_time, '%Y' ) = '$y'";
} else {
	$condition = "DATE_FORMAT( o.modify_time, '%Y-%m' ) = '$date'";
}

$condition .=($search_department_id == "" ? "" : " and o.dept_id='$search_department_id'");
$condition .=($search_user_id == "" ? "" : " and o.user_id='$search_user_id'")
." and o.status";
if ($search_group == 'F') {
	$condition .= "=30";
} elseif ($search_group == 'P') {
	$condition .= " between 10 and 20";
} else {
	$condition .= "= -99";
}
$condition .= " order by o.leave_id asc ";

$html_rows = getLeaveDisplayHtml($condition);
foreach($html_rows as $html) echo $html."\n";
?>
</tbody>
</table>
<script>
$(function($) {
	$("#mainTable").tablesorter({
		headers: { 
            0: { sorter: false }, 
            1: { sorter: false }, 
            2: { sorter: false }, 
            3: { sorter: false }, 
            4: { sorter: false }, 
            5: { sorter: false }, 
            6: { sorter: false }, 
            7: { sorter: false }
        } 
	});
});
</script>
