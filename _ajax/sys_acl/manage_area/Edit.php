<?php
Forms::GetPostGetValues(array("oper", "id"));
$fs = array("area_name","sort_order");
Forms::GetPostGetValues($fs);


if ($oper == "add") {
	$Insert = new InsertSQL(&$DB,"area");
	foreach ($fs as $f) {
		$Insert->AddStr($$f,$f);
	}
	$Insert->Execute();

} else if ($oper == "edit") {
	$Update = new UpdateSQL($DB,"area","area_id",$id);
	foreach ($fs as $f) {
		$Update->Str($f,$$f);
	}
	$Update->Execute();
} else if ($oper == "del") {
	$sql = "DELETE FROM area WHERE area_id=$id";
	$DB->Execute($sql);
}
?>