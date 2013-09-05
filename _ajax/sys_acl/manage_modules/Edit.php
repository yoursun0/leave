<?php
Forms::GetPostGetValues(array("oper", "id"));
$fs = array("mod_call","mod_name","mod_status","description");
Forms::GetPostGetValues($fs);


if ($oper == "add") {
	$Insert = new InsertSQL($DB,"ac_modules");
	foreach ($fs as $f) {
		$Insert->AddStr($$f,$f);
	}
	$Insert->Execute();

} else if ($oper == "edit") {
	$Update = new UpdateSQL($DB,"ac_modules","mod_id",$id);
	foreach ($fs as $f) {
		$Update->Str($f,$$f);
	}
	$Update->Execute();
} else if ($oper == "del") {
	$sql = "DELETE FROM ac_modules WHERE mod_id=$id";
	$DB->Execute($sql);
}
?>