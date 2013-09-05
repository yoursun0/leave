<?php
	echo Html::PageTitle("Access Control - Manage Roles");

?>
<div id="divRoles"></div>
<?php
	$opt = Sql::GetSelectOption($DB,"ac_roles","role_id","role_name");
	echo F::ajaxSelect("role",$opt,"RoleChanged(this)");
	echo F::Text("new_role");
	echo F::Button("CreateRole()","Create");
?>
<hr	/>
<div id="divDetail">
	
</div>