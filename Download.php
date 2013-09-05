<?php	
F::GetSubmit("id");
if($id) {
	$sql = "SELECT name FROM file_doc WHERE file_id = $id";
	$name = $DB->GetOne($sql);
	$path = dirname($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'])."/root/".$id;
	
// Force the download
header('Cache-Control: private');
header('Pragma: private');
header("Cache-Control: no-cache, must-revalidate");
header("Content-Disposition: attachment; filename=\"".$name."\"");
header("Content-Length: ".filesize($path));
header("Content-Type: application/application/octet-stream;");
	
	
	//header("Content-Type: application/force-download");
	//header('Content-Disposition: attachment; filename="'.$name.'"');
	//header('Content-type: application/octet-stream'); 
	//header("Content-Type: application/force-download");

	@ readfile($path);
}
?>