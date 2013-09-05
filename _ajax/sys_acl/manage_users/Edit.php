<?php
F::GetSubmit(array("oper","id"));
$fs = array("user_name","user_login","dept_id","user_type","user_status","email");
F::GetSubmit($fs);

if ($oper == "add") {
	$Insert = new InsertSQL(&$DB,"ac_users");
	foreach ($fs as $f) {
		$Insert->Str($f,$$f);
	}
	$Insert->Pw("user_pw",$user_login);
	$Insert->Execute();

} else if ($oper == "edit") {
	$Update = new UpdateSQL(&$DB,"ac_users","user_id",$id);
	foreach ($fs as $f) {
		$Update->Str($f,$$f);
	}
	$Update->Execute();
} else if ($oper == "del") {
	$sql = "DELETE FROM ac_users WHERE user_id=$id";
	$DB->Execute($sql);
}
?>