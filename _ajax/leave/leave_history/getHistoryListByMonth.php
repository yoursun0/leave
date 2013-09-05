<?php	require_once("_ajax/leave/common.php"); ?>
<?php
	F::GetSubmit(array("date", "search_user_id", "search_group"));
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
$condition .= "and o.user_id='".$USER->Id."' order by o.leave_id asc";

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
