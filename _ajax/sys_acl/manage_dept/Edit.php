<?php
F::GetSubmit(array("oper","id"));
$fs = array("area_id","dept_name");
F::GetSubmit($fs);

if ($oper == "add") {
	$Insert = new InsertSQL($DB,"ac_dept");
	foreach ($fs as $f) {
		$Insert->Str($f,$$f);
	}
	$Insert->Execute();

} else if ($oper == "edit") {

	$Update = new UpdateSQL($DB,"ac_dept","dept_id",$id);
	foreach ($fs as $f) {
		$Update->Str($f,$$f);
	}
	$Update->Execute();

} else if ($oper == "del") {
	$sql = "DELETE FROM ac_dept WHERE dept_id=$id";
	$DB->Execute($sql);
}
?>