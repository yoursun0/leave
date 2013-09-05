<?php
F::GetSubmit("role");

$sql = "DELETE FROM ac_roles_pages WHERE role_id=$role";
$DB->Execute($sql);

$sql = "DELETE FROM ac_user_roles WHERE role_id=$role";
$DB->Execute($sql);

$sql = "DELETE FROM ac_roles WHERE role_id=$role";
$DB->Execute($sql);

Ajax::Success();
?>