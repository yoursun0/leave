<?php
jqGrid::GetSubmit();

$limit = $rows;

$condition = jqGrid::MakeCondition($_search,$searchField,$searchOper,$searchString);

$sql = "SELECT COUNT(*) FROM ac_users";
$sql .= $condition;
$response = jqGrid::CalcPage($DB,$sql,$total_pages,$page,$start,$limit);

$sql = "SELECT u.user_id,u.user_name,u.user_login,a.area_name, d.dept_name,u.user_type,u.user_status,u.email FROM ac_users u left join ac_dept d on (u.dept_id = d.dept_id) left join ac_area a on (d.area_id = a.area_id) ";
$sql .= $condition;
$sql .=	" ORDER BY $sidx $sord LIMIT $start , $limit";

$i=0;
foreach (jqGrid::GetData($DB,$sql) as $row){
	$id = $row['user_id'];
	$opt = F::Button("ShowRolesControl($id)","Roles");
	$opt .= F::Button("Test($id)","Test");
	
    $response->rows[$i]['id']=$id;
    $response->rows[$i]['cell']=array($opt,$row['user_name'],$row['user_login'],$row['area_name']." - ".$row['dept_name'],$row['user_type'],$row['user_status'],$row['email']);
    $i++;
}
echo json_encode($response);
?>