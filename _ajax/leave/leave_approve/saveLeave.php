<?php
require_once("_ajax/leave/common.php");

F::GetSubmit(array('leave_id', 'display_row', 'new_action'));
$approval_row = 1;

$r = new stdClass();

$saveResult = saveLeaveApplication($leave_id, $display_row, $new_action, $approval_row);
if(isset($saveResult->error_msg)) $r->error_msg = $saveResult->error_msg;


$html_rows = getLeaveDisplayHtml("o.leave_id = '$leave_id'");
$r->leave_id = $leave_id;
$r->html = $html_rows[0];

Ajax::Success("", $r);
?>
