<?php
F::GetSubmit(array("oldpw","newpw1","newpw2"));

//check input
$newpw1 = trim($newpw1);
$newpw2 = trim($newpw2);
if ($newpw1 != $newpw2) {
	Ajax::Error("'New Password' not equal to 'Retype New Password'");
}
/*
if (strlen($newpw1) < 6) {
	Ajax::Error("The new password too short.");	
}
*/
if (md5($oldpw) != Q::GetOne("SELECT user_pw FROM ac_users WHERE user_id = ".$USER->Id)) {
	Ajax::Error("Please input correct 'Old Password'");
}

//change password
$Update = new UpdateSQL($DB,"ac_users","user_id",$USER->Id);
$Update->Pw("user_pw",$newpw1);
$Update->Execute();
Ajax::Success("Password Changed");
?>
