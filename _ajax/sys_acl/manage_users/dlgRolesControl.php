<?php
	F::GetSubmit("uid");

	$sql = "SELECT role_id FROM ac_users_roles WHERE user_id = $uid";
	$role = Sql::ToArray($sql,1,$DB);
	
	$sql = "SELECT * FROM ac_roles a";
	if ($rs = $DB->GetArray($sql)) {
		foreach ($rs as $row) {	
			$role_id 		= $row['role_id'];
			$role_name	 	= $row['role_name'];
			$description 	= $row['description'];
			echo F::CheckBox("ids",$role_id,$role)." $role_name - <i>$description</i><br />";
		}
	}

	echo F::Button("SaveRoles($uid)","Save");
?>