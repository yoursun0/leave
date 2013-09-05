<?php
Forms::GetPostGetValues(array("oper", "id"));
$fs = array("mod_id","page_call","page_name","page_status","description");
Forms::GetPostGetValues($fs);


if ($oper == "add") {
	$Insert = new InsertSQL($DB,"ac_pages");
	foreach ($fs as $f) {
		$Insert->AddStr($$f,$f);
	}
	$Insert->Execute();

} else if ($oper == "edit") {
	$Update = new UpdateSQL($DB,"ac_pages","page_id",$id);
	foreach ($fs as $f) {
		$Update->Str($f,$$f);
	}
	$Update->Execute();
} else if ($oper == "del") {
	$sql = "DELETE FROM ac_pages WHERE page_id=$id";
	$DB->Execute($sql);
}
?>