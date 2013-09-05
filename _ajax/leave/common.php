<?php
	include_once("_inc/phpMailer/class.phpmailer.php");

	$leave_type_array = array("10"=>"Annual Leave","20"=>"Sick Leave","30"=>"Wedding Leave","40"=>"Maternity Leave","50"=>"Non Pay Leave","60"=>"Other Leave","70"=>"Pay Leave");
	$leave_length_array = array("D"=>"Full Day","A"=>"AM","P"=>"PM");
	$status_array = array("-99"=>"Deleted","-98"=>"Canceled", "-1"=>"Reject", "0"=>"Fill In", "10"=>"Submitted", "20"=>"Processing", "30"=>"Approved");
	$week_name_array = array("1"=>"Mon","2"=>"Tue","3"=>"Wed","4"=>"Thu","5"=>"Fri","6"=>"Sat","7"=>"Sun");
	$day_array = array("1"=>"1", "2"=>"2", "3"=>"3", "4"=>"4", "5"=>"5", "6"=>"6", "7"=>"7", "8"=>"8", "9"=>"9", "10"=>"10");

	function logAction($leave_id, $msg, $logDetails = true){
		global $DB, $USER;
		$Insert = new InsertSQL($DB,"action_log");
		$Insert->Clear();
		$Insert->Now("create_time");
		$Insert->Str("leave_id", $leave_id);
		$Insert->Str("msg", $USER->Name.": ".$msg.genLeaveLogDetail($leave_id));
		$Insert->Execute();
	}
	
	function getUserInfoByUserId($ids, $type="all"){
		if(!is_array($ids)) $ids = array($ids);
		$users_array = array();
		if($type == "all"){
			if(count($ids) > 0){
				$sql = "select user_id, user_name, email from ac_users where user_id in (".join(", ", $ids).")";
				$users_array = Q::GetArray($sql);
			}
		} else {
			$sql = "select $type from ac_users where user_id in (".join(", ", $ids).")";
			$users_array = Q::ToArray($sql, 1);
		}
		return $users_array;
	}
	
	function genLeaveLogDetail($leave_id){
		global $leave_type_array, $leave_length_array;
		
		$sql = "select approver_name from approval where leave_id='$leave_id' order by approval_id asc";
		$approval_rows = Q::ToArray($sql, 1);
		$approval_details = (count($approval_rows) > 0 ? "\nSend To: ".(join(", ", $approval_rows)) : "");
		
		$sql = "select * from `leave` where leave_id='$leave_id'";
		$row = Q::GetRow($sql);
		
		$log = "\nApply : ".$leave_type_array[$row['leave_type']]." ";
		if(in_array($row['leave_type'], array("60", "70"))){
			// show other info if 其它 / 补假
			$log .= " (".$row['other_info'].")";
		}
		if($row['total_day'] != 0)	$log .= " ".round($row['total_day'], 1)." Day(s)";
		
		if($row['pl_name_1'] != "")	$log .= "\nPL (1): ".$row['pl_name_1'];
		if($row['pl_name_2'] != "")	$log .= "\nPL (2): ".$row['pl_name_2'];

		
		if($row['leave_period'] != ""){
			$log .= "\nLeave Details:";
			$periods = split("\n", $row['leave_period']);
			foreach($periods as $period) $log .= "\n* ".$period;
		}

		$log .= $row['job_involved']
		.($row['remark'] == "" ? "" : "\nRemark: ".$row['remark'])
		.$approval_details
		."\n".str_repeat("=", 50)
		;
		return $log;
	}
	
	function genEmailBody($leave_id){
		global $leave_type_array, $leave_length_array;

		$sql = "select approver_name from approval where leave_id='$leave_id' order by approval_id asc";
		$approval_rows = Q::ToArray($sql, 1);
		$approval_details = (count($approval_rows) > 0 ? "<tr><td>Send To:</td><td>".(join(", ", $approval_rows))."</td></tr>" : "");
		
		$sql = "select * from `leave` where leave_id='$leave_id'";
		$row = Q::GetRow($sql);
		
		$log = "<table><tr valign='top'><td>Apply :</td><td style='color: red;'>".$row['user_name']." Apply ".$leave_type_array[$row['leave_type']];
		if(in_array($row['leave_type'], array("60", "70"))){
			// show other info if 其它 / 补假
			$log .= " (".$row['other_info'].")";
		}
		$log .= " ".round($row['total_day'], 1)." Day(s)</td></tr>"
		
		.($row['pl_name_1'] == "" ? "" : "<tr valign='top'><td>PL (1)</td><td>".$row['pl_name_1']."</td></tr>")
		.($row['pl_name_2'] == "" ? "" : "<tr valign='top'><td>PL (2)</td><td>".$row['pl_name_2']."</td></tr>")
		.($row['job_involved'] == "" ? "" : "<tr valign='top'><td>Job involved: </td><td>".str_replace("\n", "<br>", $row['job_involved'])."</td></tr>")
		.($row['remark'] == "" ? "" : "<tr valign='top'><td>Remark: </td><td>".$row['remark']."</td></tr>")
		."<tr valign='top'><td>Leave Details: </td><td>".$row['leave_period']."</td></tr>"
		.$approval_details
		."</table>"
		;
		return $log;
	}
	
	function sendLeaveEmail($app_name, $body, $sender, $to, $cc = array()){
		
		$debug_email = "";
		$mail = new PHPMailer();
		$mail->FromName = $sender[0]['user_name'];
		$mail->From     = $sender[0]['email'];
		$mail->IsSMTP();
		$mail->SMTPAuth = false;
		//$mail->Host     = "mail.cshk.com";
		$mail->Host     = "mail1.consumersearch-group.com";
		$mail->CharSet	= "utf-8";
		$mail->Encoding = "base64";
		
		$mail->IsHTML(true);
	    $mail->Body		= "<style>\nbody, div, th, td {font-family: Arial; font-size: 12px;} \n</style>\n".str_replace("\n", "<br>", $body)."<br><br> <a href='https://www.csg-worldwide.com/leave'>https://www.csg-worldwide.com/leave/</a>";
	    $mail->Subject  = "=?UTF-8?B?".base64_encode("Leave Application ($app_name)")."?=";
	    
	    if($debug_email != "") $mail->AddAddress($debug_email, $debug_email);
	    foreach($to as $people){
	    	if($debug_email == ""){
	    		$mail->AddAddress($people['email'], $people['user_name']);
	    	} else {
	    		$mail->Body .= "<div>TO: ".$people['email']." (".$people['user_name'].")</div>\n";
	    	}
	    }
	    foreach($cc as $people){
	    	if($debug_email == ""){
	    		$mail->AddCC($people['email'], $people['user_name']);
	    	} else {
	    		$mail->Body .= "<div>CC: ".$people['email']." (".$people['user_name'].")</div>\n";
	    	}
	    }
	    $header = $mail->CreateHeader();
	    $body = $mail->CreateBody();
	    
	    //file_put_contents('C:\\xampp\\mail.htm',$header.PHP_EOL.$body.$mail->Body."<hr />",FILE_APPEND );
	    if(!$mail->Send()){
            Ajax::Success("Send Mail Failure: ".$mail->ErrorInfo, $r);
	    }

	    return true;
	}

	function clearTextIf($text, $removeText, $default = ""){
		return ($text == $removeText) ? $default : $text;
	}
	
	function DayDiff($DateFrom, $DateTo){
		// for format yyyy-mm-dd
		$f = split("-", $DateFrom);
		$t = split("-", $DateTo);
		$diff = mktime(0,0,0,$t[1],$t[2],$t[0]) - mktime(0,0,0,$f[1],$f[2],$f[0]);
		return $diff / (60 * 60 * 24);
	}
	
	function getLeaveDisplayHtml($condition = "1"){
		global $status_array, $leave_type_array;
		$sql = "SELECT o.leave_id, date_format(o.create_time, '%Y-%m-%d<br>%H:%i:%s') as create_time, o.user_name, a.area_name, d.dept_name, o.leave_type, o.leave_period, o.total_day, o.status, o.other_info, o.job_involved, o.remark"
		." FROM `leave` o"
		." join ac_dept d on (o.dept_id = d.dept_id) join ac_area a on (o.area_id = a.area_id)"
		." WHERE $condition";
		
		$html_rows = array();
		
		if ($rs =  Q::GetArray($sql)) {
			foreach ($rs as $row) {
				$temp_leave_id = $row['leave_id'];
				
				$app_names = "";
				if($row['status'] > 0 && $row['status'] < 30){
					$sql = "select approver_name from approval where leave_id='".$temp_leave_id."' and status > 0";
					$approver_names = Q::ToArray($sql, 1);
					if(count($approver_names) > 0)	$app_names = "<br>- ".join("<br>- ", $approver_names);
				}
			
				$leave_type_str = $leave_type_array[$row['leave_type']];
				if(in_array($row['leave_type'], array("60", "70"))){
					// show other info if 其它 / 补假
					$leave_type_str .= " (".$row['other_info'].")";
				}
				$leave_type_str .= "<br>".round($row['total_day'], 1)." Days";
				
				$html_rows[] = "<tr class=\"leaveItem\" rel=\"".$temp_leave_id."\">"
				."<td>".$row['create_time']."</td>"
				."<td class='status".$row['status']."'>".$status_array[$row['status']].$app_names."</td>"
				."<td>".$row['area_name']."<br>".$row['dept_name']."</td>"
				."<td>".$row['user_name']."</td>"
				."<td>".$leave_type_str."</td>"
				."<td>".str_replace("\n", "<br>", $row['leave_period'])."</td>"
				."<td>".str_replace("\n", "<br>", $row['job_involved'])."</td>"
				."<td>".str_replace("\n", "<br>", $row['remark'])."</td>"
				."</tr>\n";
			}
		}
		return $html_rows;
	}
	
	function saveLeaveApplication($leave_id, $display_row, $new_action, $approval_row = 1,$message = ""){
		global $DB, $USER, $leave_length_array, $leave_type_array;
		
		$r = new stdClass();
		$leave_from = "";
		$leave_to = "";
		$leave_length = "";
		
		$leave_period = "";
		$total_day = 0.0;
		global $leave_period, $total_day;
		
		$sql = "select status from `leave` where leave_id='$leave_id'";
		$current_status = Q::getOne($sql);
		
		// check access right in different stages
		switch($current_status){
			case 0:
				if(!in_array($new_action, array("delete", "update", "submit"))){
					$r->error_msg = "Update failure because this application updated by other user or deleted.";
					return $r;
				}
				break;
			case 10:
				if(!in_array($new_action, array("delete", "submit", "approve", "final_approve"))){
					$r->error_msg = "Update failure because this application updated by other user or deleted.";
					return $r;
				}
				break;
			case 20:
				if(!in_array($new_action, array("delete", "approve", "final_approve"))){
					$r->error_msg = "Update failure because this application updated by other user or deleted.";
					return $r;
				}
				break;
			case 30:
				if(!in_array($new_action, array("delete"))){
					$r->error_msg = "Update failure because this application updated by other user or deleted.";
					return $r;
				}
				break;
			case 100: $r->error_msg = "Update failure because this application approved."; break;
			case -99: $r->error_msg = "Update failure because this application cancalled."; return $r;
			case -1: $r->error_msg = "Update failure because this application rejected."; break;
			default: $r->error_msg = "Update failure because this application updated by other user or deleted."; break;
		}

		if($new_action == "delete" && $current_status == 0){
			// not keep history is new application
			$sql = "delete from `action_log` where leave_id = '$leave_id'";
			Q::Execute($sql);
			$sql = "delete from `approval` where leave_id = '$leave_id'";
			Q::Execute($sql);
			$sql = "delete from `leave_item` where leave_id = '$leave_id'";
			Q::Execute($sql);
			$sql = "delete from `leave` where leave_id = '$leave_id'";
			Q::Execute($sql);
		} elseif ($new_action == "delete" && $current_status == 30) {
			$action_log_mapping = array(
				"delete"=>"Delete application"
				,"update"=>"Update application"
				,"submit"=>"Submit application"
				,"approve"=>"Approve application"
				,"final_approve"=>"Final approve application"
			);
			
			$sql = "SELECT `user_id` FROM `ac_users_roles` WHERE `role_id` = 118";
			$ids = Q::ToArray($sql , 1);
			$ToUserIds =  array($USER->Id);
/*			$sql = "select involve_user_ids from `leave` where leave_id='$leave_id'";
			$involve_user_id_str = Q::GetOne($sql);
			$ids = ($involve_user_id_str == "") ? array() : split(",", $involve_user_id_str);
			$ToUserIds = array();*/
			
			$fs = array('status', 'remark');
			
			$remark = Q::GetOne("SELECT `remark` FROM `leave` WHERE `leave_id` = $leave_id");
			$remark = trim($remark);
			if (empty($remark)) {
				$remark = "delete reason : ".$message;	
			} else {
				$remark = $remark."; \n"."delete reason : ".$message;	
			}
			//$status = $status_mapping[$new_action];
			$status = -99;
			
			$Update = new UpdateSQL($DB,"leave","leave_id",$leave_id);
			foreach ($fs as $f) {
				$Update->Str($f,$$f);
			}
			$Update->Execute();
			
			logAction($leave_id, "Delete application");
			
			if($status != 0){
				// send email 
				$request_user_name = Q::GetOne("select user_name from `leave` where leave_id='$leave_id'");
				$ToUserIds = array(Q::GetOne("select user_id from `leave` where leave_id='$leave_id'"));
				$email_body =  "<b>".$action_log_mapping[$new_action]." - by ".$USER->Name."</b><br><br>".genEmailBody($leave_id);
				$sender = getUserInfoByUserId($USER->Id);
				$cc = getUserInfoByUserId(array_diff($ids, $ToUserIds));
				//$cc = getUserInfoByUserId($ids);
				$to = getUserInfoByUserId($ToUserIds);
				
				$email_body .= "<br />Delete reason : $message";
				
				sendLeaveEmail($request_user_name, $email_body, $sender, $to, $cc);
			}
		}else {
		
			
			$sql = "select involve_user_ids from `leave` where leave_id='$leave_id'";
			$involve_user_id_str = Q::GetOne($sql);
			$ids = ($involve_user_id_str == "") ? array() : split(",", $involve_user_id_str);
			$ToUserIds = array();
				
			// clear old ot_details
			$sql = "delete from `leave_item` where leave_id='$leave_id'";
			Q::Execute($sql);
			$sql = "delete from approval where leave_id='$leave_id'";
			Q::Execute($sql);
			
			$fs = array("leave_id", "leave_from", "leave_to", "leave_length");
			$Insert = new InsertSQL($DB, "leave_item");
	
			for($i = 1; $i <= intval($display_row); $i++){
				F::GetSubmit(array("leave_from_".$i, "leave_to_".$i, "leave_length_".$i));
				
				$leave_from = $GLOBALS["leave_from_".$i];
				$leave_to = $GLOBALS["leave_to_".$i];
				$leave_length = $GLOBALS["leave_length_".$i];
				
				if($leave_from == "" && $leave_to == "") continue;
				if($leave_to == "")	$leave_to = $leave_from;

				if($leave_from == $leave_to){
					$leave_period .= "\n$leave_from (".$leave_length_array[$leave_length].")";
					$total_day += ($leave_length == "D" ? 1.0 : 0.5);
				} else {
					$leave_period .= "\n$leave_from to $leave_to (".$leave_length_array[$leave_length].")";
					$total_day += ($leave_length == "D" ? 1.0 : 0.5) * (DayDiff($leave_from, $leave_to) + 1);
				}
				
				$Insert->Clear();
				$Insert->str('leave_id', $leave_id);
				$Insert->str('leave_from', $leave_from);
				$Insert->str('leave_to', $leave_to);
				$Insert->str('leave_length', $leave_length);
				$newId = $Insert->Execute();
			}
			if(strpos("\n", $leave_period) === 0) $leave_period = substr($leave_period, 1); // remove first \n 
			
			$Insert = new InsertSQL($DB, "approval");
			for($i = 1; $i <= intval($approval_row); $i++){
				F::GetSubmit(array("approver_user_id_".$i));
				$approver_user_id = $GLOBALS["approver_user_id_".$i];
				if($approver_user_id == "") continue;
	
				if($new_action != "update"){
					$ids[] = $approver_user_id;
					$ToUserIds[] = $approver_user_id;
				}
				
				$Insert->Clear();
				$Insert->str('leave_id', $leave_id);
				$Insert->str('request_user_id', $USER->Id);
				$Insert->str('approver_user_id', $approver_user_id);
				$Insert->str('request_name', $USER->Name);
				$Insert->str('approver_name', Q::GetOne("select user_name from ac_users where user_id='$approver_user_id'"));
				$Insert->str('status', (($new_action == "submit" || $current_status > 0) ? "1" : "0"));
				$sql = $Insert->MakeSQL();
				$newId = $Insert->Execute();
			}
			
			$fs = array('leave_type', 'pl_user_id_1', 'pl_user_id_2', 'pl_name_1', 'pl_name_2', 'modify_time', 'leave_period', 'total_day', 'status', 'other_info', 'job_involved', 'remark');
			F::GetSubmit($fs);
			foreach($fs as $fs_name) global $$fs_name;
			// only 补假 need PL information
			if($leave_type == "70"){
				$pl_name_1 = Q::GetOne("select user_name from ac_users where user_id='$pl_user_id_1'");
				$pl_name_2 = Q::GetOne("select user_name from ac_users where user_id='$pl_user_id_2'");
			} else {
				$pl_user_id_1 = "0";
				$pl_user_id_2 = "0";
				$pl_name_1 = "";
				$pl_name_2 = "";
			}
			
			// only 其它 / 补假 need other_info
			if(!in_array($leave_type, array("60", "70"))){
				$other_info = "";
			}
			
			$status_mapping = array(
				"delete"=>"-99"
				,"update"=>"0"
				,"submit"=>"10"
				,"approve"=>"20"
				,"final_approve"=>"30"
			);
			
			$status = $status_mapping[$new_action];
			$Update = new UpdateSQL($DB,"leave","leave_id",$leave_id);
			foreach ($fs as $f) {
				if($f == "modify_time"){
					$Update->Now($f);
				} else {
					$Update->Str($f,$$f);
				}
			}
			$Update->Execute();
			
			$action_log_mapping = array(
				"delete"=>"Delete application"
				,"update"=>"Update application"
				,"submit"=>"Submit application"
				,"approve"=>"Approve application"
				,"final_approve"=>"Final approve application"
			);
			
			if(in_array($new_action, array("submit","approve","final_approve")) || 
					(in_array($new_action, array("submit","approve","final_approve")) && $current_status == 30) ){
				// update involved user id
				if(!in_array($USER->Id, $ids)) $ids[] = $USER->Id;
				
				if($new_action == "final_approve"){
					// cc to Jessica and Sin Man when final approve
					$sql = "select u.user_id from ac_roles r, ac_users_roles ur, ac_users u	where r.role_id = ur.role_id and ur.user_id = u.user_id	and r.role_name = 'email_alert'";
					$email_alert_array = Q::ToArray($sql, 1);
					$ids = array_merge($ids, $email_alert_array);
				}

				// cc to other final_approver
				/*
				$sql = "select u.user_id, u.user_name
from ac_roles r, ac_users_roles ur, ac_users u
where 
r.role_id = ur.role_id
and ur.user_id = u.user_id
and r.role_name = 'approver'
order by u.user_name";
				$final_approver_array = Q::ToArray($sql);
				// if($ToUserIds)
				$diff_approver = array_diff($final_approver_array, $ToUserIds);
				if(count($final_approver_array) != count($diff_approver)){
					foreach($diff_approver as $final_approver_id){
						if(!in_array($final_approver_id, $ids)) $ids[] = $final_approver_id; 
					}
				}
*/
				$ids = array_unique(array_merge($ids, $ToUserIds));
				$Update = new UpdateSQL($DB, "leave", "leave_id", $leave_id);
				$Update->Str('involve_user_ids', join(",", $ids));
				$Update->Execute();
			}
			
			logAction($leave_id, $action_log_mapping[$new_action]);
			
			if($status != 0){
				// send email 
				$request_user_name = Q::GetOne("select user_name from `leave` where leave_id='$leave_id'");
				$email_body =  "<b>".$action_log_mapping[$new_action]." - by ".$USER->Name."</b><br><br>".genEmailBody($leave_id);
				$sender = getUserInfoByUserId($USER->Id);
				$cc = getUserInfoByUserId(array_diff($ids, $ToUserIds));
				$to = getUserInfoByUserId($ToUserIds);
				
				sendLeaveEmail($request_user_name, $email_body, $sender, $to, $cc);
			}
		}
		
		return $r;
	}
?>
