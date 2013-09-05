<?php
include_once('_database.php');
include_once('_inc/lib.user.php');
include_once('_inc/lib.sql.php');
$USER = new User(&$DB,&$_SESSION);
if ($USER->CheckSession()) {header("Location: home.php");}
?>