<?php
F::GetSubmit(array("ids","uid"));

$sql = "DELETE FROM ac_users_roles WHERE user_id=$uid";
$DB->Execute($sql);

$Insert = new InsertSQL(&$DB,"ac_users_roles");
foreach ($ids as $id) {
	$Insert->Clear();
	$Insert->Str("user_id",$uid);
	$Insert->Str("role_id",$id);
	$Insert->Execute();
}
?>