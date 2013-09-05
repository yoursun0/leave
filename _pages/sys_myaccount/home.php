<?php
	echo Html::PageTitle("My Account - ".$USER->Name."");
?>
<form id="frmChangePw" onsubmit="return false;">
<fieldset>
	<legend>Change Password</legend>
	<table class="InputForm" width="400">
	<?php
	echo 
		F::Tr("Old Password",			F::Password("oldpw")).
		F::Tr("New Password",			F::Password("newpw1")).
		F::Tr("Retype New Password ",	F::Password("newpw2")).
		F::Tr("",						F::Button("ChangePassword()","Change"));
	?>
	</table>
</fieldset>
</form>