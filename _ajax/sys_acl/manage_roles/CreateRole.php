<?php
F::GetSubmit("new_role");

if (!empty($new_role)) {
	$Insert = new InsertSQL($DB,"ac_roles");
	$Insert->Str("role_name",$new_role);
	$id	 = $Insert->Execute();
	//$id = 999;
	if ($id) {	
		$response->id = $id;	
		$response->name = $new_role;
		$response->type = "success";
	} else {
		$response->type = "error";
		$response->msg = "This role name is exist";
	}	
} else {
	$response->type = "error";
	$response->msg = "please input the role name";
}
echo json_encode($response);
?>