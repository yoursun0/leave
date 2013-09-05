<?php
################################################################################
# Settings & Init.
################################################################################

include_once('_inc/lib.html.php');

$today 	= date('Y-m-d');
//$today	= '2010-02-17';

$sql			= <<<SQL
SELECT leave_period, leave_from, leave_to, leave_length, u.user_id, u.user_name, email, dept_name
FROM leave_item i, `leave` l, ac_users u, ac_dept d
WHERE ('$today' BETWEEN `leave_from` AND `leave_to`)
  AND i.leave_id = l.leave_id
  AND l.status = 30
  AND l.user_id = u.user_id
  AND u.dept_id = d.dept_id
ORDER BY d.dept_name, user_name
SQL;
$leaveItems 	= Q::GetArray($sql);


################################################################################
# Main
################################################################################



################################################################################
# Output
################################################################################

echo Html::PageTitle('Leave Notice (' . date('l, j F Y', strtotime($today)) . ')');

echo <<<HTML
<style type="text/css">
#leave_notice th, #leave_notice td{
	font-size:18px;
}
</style>
HTML;

//echo '<h1></h1>';
echo '<table id="leave_notice" class="tablesorter" width="600" style="font-size:16px;">';

$currentDepartmentName = '';
foreach ($leaveItems as $item) {	
	if ($currentDepartmentName <> $item['dept_name']) {
		$currentDepartmentName = $item['dept_name'];
			
		echo '<tr style="background:#ccc;"><th colspan="2">' . $currentDepartmentName . '</th></tr>';
	}

	$item['leave_period'] = nl2br($item['leave_period']);
		
	echo <<<HTML
<tr>
	<td width="180" style="vertical-align:top;">{$item['user_name']}</td>
	<td>{$item['leave_period']}</td>
</tr>
HTML;
}
echo '</table>';


################################################################################
# Functions
################################################################################

?>