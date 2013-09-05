<?php
require_once("_ajax/leave/common.php");

F::GetSubmit(array('leave_id', 'new_action','message'));

$r = new stdClass();

F::GetSubmit(array('leave_id', 'display_row'));
$approval_row = 1;
$new_action = "delete";
$saveResult = saveLeaveApplication($leave_id, $display_row, $new_action, $approval_row,$message);
if(isset($saveResult->error_msg)) $r->error_msg = $saveResult->error_msg;

$r->leave_id = $leave_id;

Ajax::Success("", $r);
?>