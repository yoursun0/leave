<?php	require_once("_ajax/leave/common.php"); ?>
<?php
$sql = "select dept_id from ac_users where user_id='".$USER->Id."'";
$temp_dept_id = $DB->GetOne($sql);
$sql = "select area_id from ac_dept where dept_id='$temp_dept_id'";
$temp_area_id = $DB->GetOne($sql);

$Insert = new InsertSQL($DB,"leave");
$Insert->Clear();
$Insert->Str("user_id", $USER->Id);
$Insert->Str("user_name",$USER->Name);
$Insert->Str("area_id",$temp_area_id);
$Insert->Str("dept_id",$temp_dept_id);
$Insert->Str("leave_type","10");
$Insert->Str("pl_user_id_1", "0");
$Insert->Str("pl_name_1","");
$Insert->Str("pl_user_id_2", "0");
$Insert->Str("pl_name_2","");
$Insert->Now("create_time");
$Insert->Now("modify_time");
$newId = $Insert->Execute();

logAction($newId, "Add new application", false);

$html_rows = getLeaveDisplayHtml("o.leave_id='$newId'");

$r = new stdClass();
$r->leave_id = $newId;
$r->html = $html_rows[0];
Ajax::Success("", $r);
?>