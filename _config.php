<?php
final class Config
{
	const Debug				= true;
	
	/* Basic */
	const Name				= "LEAVE";
	const Version			= "1_01_13_beta";
	const Title				= "Leave Application System (Hong Kong Office)";
	const LoginTitle		= "Leave Application System<br/>(Hong Kong Office)";
	const Extension			= ".php";
	const Charset			= "utf-8";
	
	/* Server */
	const ServerCharset		= "big5";
	
	/* Database Config */
	const DatabaseType		= "mysql";
	const DatabaseHost		= "localhost";
	const DatabaseUser 		= "root";
	const DatabasePasswod	= "password";
	const DatabaseName		= "eleave";
	const DatabaseCharset	= "UTF8";
	
	const ENABLE_MAIL_MERGE	= true;
}
final class Path
{
	/* System Paths */	
	const Base			= "";
	const Javascript	= "_javascript/";
	const CSS			= "_css/";
	const Image			= "_image/";
	const Libary		= "_inc/";
	const Adodb			= "_inc/adodb/";
	const Calender		= "_inc/jscalendar/";
	const BtnIcon		= "_img/sys.slimbutton/";
}
class Style
{
	/* Basic */
	// const BackgroundColor		= "FFFFE1";
	
	/* Data Table */
	// const RowMouseOver			= "99CCFF";
	// const RowMouseOut			= "FFFFE1";
	// const RowMouseOverSelected	= "99CCFF";
	// const RowMouseOutSelected	= "FFFFE1";
	
	const RowPerPage			= 10;
}


class Enumerate {
	public function Sex() {
		return array("M"=>"男","F"=>"女");
	}
	public function Gender() {
		return self::Sex();
	}
}
class Enum extends Enumerate {}

session_name("PHPSID_".Config::Name.Config::Version);
session_start();
?>