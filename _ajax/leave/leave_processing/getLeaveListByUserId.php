<?	require_once("_ajax/leave/common.php"); ?>
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
F::GetSubmit("user_id");

$html_rows = getLeaveDisplayHtml("o.user_id='$user_id' and o.status between 0 and 20 order by o.leave_id asc");
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
