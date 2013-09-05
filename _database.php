<?php
include_once('_config.php');
include_once('_inc/adodb/adodb.inc.php');
$DB=&ADONewConnection(Config::DatabaseType);
$DB->Connect(Config::DatabaseHost,Config::DatabaseUser,Config::DatabasePasswod,Config::DatabaseName);
$DB->hasAffectedRows=true;
$ADODB_FETCH_MODE=ADODB_FETCH_ASSOC;
$DB->Execute("SET NAMES ".Config::DatabaseCharset);
?>