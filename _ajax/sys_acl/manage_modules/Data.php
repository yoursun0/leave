<?php
Forms::GetPostGetValues(array("page","rows","sidx","sord","_search","searchField","searchOper","searchString"));

function mkCondition($field,$opt,$val){
	switch ($opt) {
		case "bw": return "$field LIKE '$val%'";		//bw - begins with ( LIKE val% )
		case "eq": return "$field = '$val'";			//eq - equal ( = )
		case "ne": return "$field <> '$val'";			//ne - not equal ( <> )
		case "lt": return "$field < '$val'";			//lt - little ( < )
		case "le": return "$field <= '$val'";			//le - little or equal ( <= )
		case "gt": return "$field > '$val'";			//gt - greater ( > )
		case "ge": return "$field >= '$val'";			//ge - greater or equal ( >= )
		case "ew": return "$field LIKE '%$val'";		//ew - ends with (LIKE %val )
		case "cn": return "$field LIKE '%$val%'";		//cn - contain (LIKE %val% )
	}
	return false;
}

$limit = $rows;
if(!$sidx) $sidx =1;

// calculate the number of rows for the query. We need this to paging the result
$sql = "SELECT COUNT(*) FROM ac_modules";
$sql .= $_search == "true" ? " WHERE ".mkCondition($searchField,$searchOper,$searchString)." " : "";
$count = $DB->GetOne($sql);

// calculation of total pages for the query
$total_pages = $count >0 ? ceil($count/$limit) : 0;

// if for some reasons the requested page is greater than the total
// set the requested page to total page
if ($page > $total_pages) $page=$total_pages;

// calculate the starting position of the rows
$start = $limit*$page - $limit; 
if($start <0) $start = 0;

// the actual query for the grid data
$sql = "SELECT mod_id,mod_call,mod_name,description,mod_status
		FROM ac_modules ";
$sql .= $_search == "true" ? " WHERE ".mkCondition($searchField,$searchOper,$searchString)." " : "";
/*
if ($_search == "true") {
	$cond = mkCondition($searchField,$searchOper,$searchString);
	$sql .= " WHERE $cond ";
}
*/
$sql .=	"ORDER BY $sidx $sord LIMIT $start , $limit";

$rs  = $DB->GetArray($sql);

// constructing a JSON
$response->page = $page;
$response->total = $total_pages;
$response->records = $count;
$i=0;
foreach ($rs as $row){	
    $response->rows[$i]['id']=$row['mod_id'];
    $response->rows[$i]['cell']=array($row['mod_id'],$row['mod_call'],$row['mod_name'],$row['description'],$row['mod_status']);
    $i++;	
}
// return the formated data
echo json_encode($response);
?>