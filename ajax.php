<?php	
include("_inc/lib.forms.php");
unset($gModule,$gPages,$gMethod);
F::GetSubmit(array("gModule","gPages","gMethod"));
if(empty($gModule) || empty($gPages))die("Error :　Empty request");
unset($path);$path="_ajax/".$gModule."/".(empty($gPages)?"":"$gPages/")."$gMethod.php";
if(!file_exists($path))die("Error : Call undefined method");
include("_database.php");
include_once("_inc/lib.basic.php");
include_once("_inc/lib.sql.php");
include_once('_inc/lib.user.php');
include_once('_inc/lib.datetime.php');
include_once('_inc/lib.html.php');
include_once("_inc/funcs.php");
$USER = new User($DB);
$USER->CheckSession();
if(!$USER->CheckPages($gModule,$gPages)){die("Access Denied");}
else{include($path);}
exit;
?>