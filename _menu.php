<a>&nbsp;</a>
<div style="height:300px;overflow:auto;">
	<ul class="LeftMenu">
		<?php
			if ($USER->CheckPages("leave","leave_approve") == true){
				mkModulesLink("leave","Leave Records","leave_approve");
			} else {
				mkModulesLink("leave","Leave Records","leave_processing");
			}
			mkModulesLink("sys_acl","Access Control");
			mkModulesLink("sys_myaccount","Change Password");
		?>
	</ul>
</div>

<?php
function mkModulesLink($call,$name,$default_page_name = ""){
	global $USER;
	if ($USER->CheckModules($call) == true) {
		echo "<li onclick=\"OpenModule('$call','$default_page_name');SelectModule(this)\">$name</li>\n";
	}
}
?>