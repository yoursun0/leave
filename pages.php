<?php	
include("_inc/lib.forms.php");
unset($gModule,$gPages);
F::GetSubmit(array("gModule","gPages"));
if(empty($gModule))die("Error : Please select the module");
if(empty($gPages)){
	$path="_pages/$gModule.php";
	if(!file_exists($path))die("Error : Call undefined module");
	$jsPath = "_js/$gModule.js";
}else{
	$path="_pages/$gModule/$gPages.php";
	if(!file_exists($path))die("Error : Call undefined pages");
	$jsPath="_js/$gModule/$gPages.js";
}
include("_database.php");
include_once("_inc/lib.basic.php");
include_once("_inc/lib.sql.php");
include_once('_inc/lib.user.php');
include_once('_inc/lib.html.php');
include_once('_inc/lib.datetime.php');
include_once("_inc/funcs.php");

$USER = new User($DB);
if(!$USER->CheckSession(false))die("Connection timeout. Pls login again.");
if (empty($gPages) || strtolower($gPages)=="home") {
	if(!$USER->CheckModules($gModule))die("Access Denied");
} else {
	if(!$USER->CheckPages($gModule,$gPages))die("Access Denied");
}
if (file_exists($jsPath)) {	
	if (Config::Debug) {
		echo '<script type="text/javascript" src="'.$jsPath.'"></script>';
	} else {
		echo '<script language="JavaScript">';
		readfile($jsPath);
		echo '</script>';
	}
}
include($path);
exit;
?>