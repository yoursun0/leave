<?php
$fs = array("role_name","description","role_status");
F::GetSubmit($fs);
F::GetSubmit(array("ids","role"));

$sql = "select count(role_name) from ac_roles where role_name = ".Q::str($role_name)." and role_id != ".Q::str($role);
$name_count = intval(Q::GetOne($sql));

if($name_count > 0){
	Ajax::Error("Role name already exists");
} else {
	$role_status = "A";
	$Update = new UpdateSQL($DB,"ac_roles","role_id",$role);
	foreach ($fs as $f){
		$Update->Str($f,$$f);
	}
	$Update->Execute();

	$sql = "DELETE FROM ac_roles_pages WHERE role_id=$role";
	$DB->Execute($sql);

	if (is_array($ids)) {
		$Insert = new InsertSQL($DB,"ac_roles_pages");
		foreach ($ids as $id) {
			$Insert->Clear();
			$Insert->Str("role_id",$role);
			$Insert->Str("page_id",$id);
			$Insert->Execute();
		}	
	}
	Ajax::Success();
}
?>