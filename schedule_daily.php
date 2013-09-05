<?php
if (!isset($_GET['key']) || $_GET['key'] != 'IcoV9YrVwLrbvZ') {
	die('Access Denied');
}

################################################################################
# Settings & Init.
################################################################################

include_once('_database.php');
include_once('_inc/lib.sql.php');
include_once('_inc/phpMailer/class.phpmailer.php');

$today 	= date('Y-m-d');
//$today	= '2010-02-17';

$from = '28916687@csg-worldwide.com';
$to = array(
	array('cshk.all@csg-worldwide.com', 		'All'),
//	array('timmy.tin@csg-worldwide.com', 		'Timmy Tin'),
//	array('lawrence.yau@consumersearch-group.com', 		'Lawrence Yau'),
//	array('warren.chau@cshk.com', 		'Warren Chau'),
);

################################################################################
# Data
################################################################################

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



$dateString = date('l, j F Y', strtotime($today));
$title		= 'Leave Notice (' . $dateString . ')';

if (!empty($leaveItems)) {
	$mailBody 	= getMailBody($leaveItems);
} else {
	$mailBody	= "<h3>No Leave Application</h3>";
}

//if (!empty($leaveItems)) {

	$mail = new PHPMailer();
	$mail->FromName = Config::Title;
	$mail->From     = $from;
	$mail->SMTPAuth = false;
	$mail->Host     = 'mail.cshk.com';
	$mail->CharSet	= 'utf-8';
	$mail->Encoding = 'base64';
	$mail->IsSMTP();
	$mail->IsHTML(true);
	
	$mail->Subject  = "=?UTF-8?B?".base64_encode($title)."?=";
	$mail->Body		= $body = <<<HTML
<style>
body, div, th, td {font-family: Arial; font-size: 18px;}
#footer {font-size:12px; color:#999;}
</style><h2>$title</h2>$mailBody<br><br>
<div id="footer">
This is an automatically generated email, please do not reply to this email.<br />
<a href='https://www.csg-worldwide.com/leave'>https://www.csg-worldwide.com/leave</a>
</div>
HTML;
	
	foreach ($to as $address) {
		$mail->AddAddress($address[0], $address[1]);
	}
	
	$header = $mail->CreateHeader();
	$mail->CreateBody();
	
	if(false === ($result = $mail->Send())){
		$errorInfo = $mail->ErrorInfo;
	}
// }


################################################################################
# Output
################################################################################

echo <<<HTML
<html>
<head>
<style>
body, div, span, th, td, h1, h2, h3{font-family: Arial; color:#333;}
body, div, span, th, td{font-size: 12px;}
</style>
</head>
<body>
HTML;

printSection('Current Time', date('Y-m-d H:i:s'));
if (!empty($leaveItems)) {
	printSection('Mail Header', $header);
	printSection('Mail Body', $body);
	printSection('Send Result', ($result ? 'Success' : 'Failure'));
	if (!$result) {
		printSection('Error Info', $errorInfo);
	}
} else {
	printSection('Message', 'No Leave Records');
}

echo '</body></html>';

################################################################################
# Functions
################################################################################
function printSection($title, $content) {
	echo '<h1>' , $title , '</h1>';
	echo '<pre>' , $content , '</pre>';
}

function getMailBody($leaveItems) {
$html = <<<HTML
<table id="leave_notice" width="600">
HTML;

	
	$currentDepartmentName = '';
	foreach ($leaveItems as $item) {	
		if ($currentDepartmentName <> $item['dept_name']) {
			$currentDepartmentName = $item['dept_name'];
				
			$html .= '<tr style="background:#ddd;"><th colspan="2">' . $currentDepartmentName . '</th></tr>';
		}
		
		$item['leave_period'] = nl2br($item['leave_period']);
		
$html .= <<<HTML
<tr>
	<td width="180" valign="top">{$item['user_name']}</td>
	<td>{$item['leave_period']}</td>
</tr>
HTML;
	}
	$html .= '</table>';
	
	return $html;
}
?>