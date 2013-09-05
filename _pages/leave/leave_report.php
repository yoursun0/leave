<?php
	echo Html::PageTitle("Report");
?>

<?
$sql = "select u.user_id, u.user_name
from ac_roles r, ac_users_roles ur, ac_users u
where 
r.role_id = ur.role_id
and ur.user_id = u.user_id
and r.role_name = 'Staff'
order by u.user_name";
$user_options = Q::ToArray($sql);
$department_options = Q::ToArray("SELECT `dept_id`, `dept_name` FROM ac_dept WHERE `area_id` = 1");

// echo "Job No.: ".F::Text("search_job_no", "", 10, 10)." &nbsp; &nbsp; &nbsp; ";
echo "
Department : ".F::Select('search_department_id', $department_options, '', 'style="width:150px" class="box" onchange="OptionChanged()"', false, array(""=>"All"))
."Staff : ".F::Select("search_user_id",$user_options,"",'style="width:150px" class="box" onchange="OptionChanged()"',false, array(""=>"All"))
." &nbsp; &nbsp; &nbsp; Progress : [ "
,'<label style="cursor:pointer;">',F::Radio("search_group","F","F","onclick='OptionChanged()'")," Finish </label>"
,'<label style="cursor:pointer;">',F::Radio("search_group","P","","onclick='OptionChanged()'")," Processing </label>"
,'<label style="cursor:pointer;">',F::Radio("search_group","D","","onclick='OptionChanged()'")," Deleted </label>"
." ]<br>\n".
F::MonthPicker("divMonth",Date::YearMonth());
?>
<div id="leaveList" style="padding: 0;"></div>
<script>
/*
$(function($) {
	$("#search_job_no").keydown(function(event){
		if(event.keyCode == 13) OptionChanged();
	});
});
*/
</script>