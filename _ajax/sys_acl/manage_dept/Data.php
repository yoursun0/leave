<?php
jqGrid::GetSubmit();

$limit = $rows;

$condition = jqGrid::MakeCondition($_search,$searchField,$searchOper,$searchString);

$sql = "SELECT COUNT(*) FROM ac_dept";
$sql .= $condition;
$response = jqGrid::CalcPage($DB,$sql,$total_pages,$page,$start,$limit);

$sql = "SELECT d.dept_id,d.dept_name,a.area_id,a.area_name FROM ac_dept d left join ac_area a on (d.area_id = a.area_id) ";
$sql .= $condition;
$sql .=	" ORDER BY $sidx $sord LIMIT $start , $limit";

$i=0;
foreach (jqGrid::GetData($DB,$sql) as $row){
	$id = $row['dept_id'];
	
    $response->rows[$i]['id']=$id;
    $response->rows[$i]['cell']=array($row['area_name'],$row['dept_name']);
    $i++;
}
echo json_encode($response);
?>