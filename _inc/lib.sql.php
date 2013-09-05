<?php
set_magic_quotes_runtime(0);

class ADODB_FETCH_MODE{
	const SYSTEM_DEFAULT = 0;
	const NUM 			 = 1;
	const ASSOC 		 = 2;
	const BOTH 			 = 3;
}
final class AFM extends ADODB_FETCH_MODE {}

class QueryString {
	private static function StringReplace($src, $dest, $data){
		if (strnatcmp(PHP_VERSION,'4.0.5')>=0) {
			return str_replace($src,$dest,$data);
		}		
		$s = reset($src);
		$d = reset($dest);
		while ($s !== false) {
			$data = str_replace($s,$d,$data);
			$s = next($src);
			$d = next($dest);
		}
		return $data;
	}
	public static function DateRange($column, $begin = '', $end = ''){
		$begin 	= empty($begin) ? "now()" : "'$begin'";
		$end	= empty($end)	? "now()" : "'$end'";		
		return "$column >= $begin AND $column < DATE_ADD($end, INTERVAL 1 DAY)";
	}
	public static function Range($column, $begin = 0, $end = 1000){		
		return "$column >= $begin AND $column <= $end";
	}	
	public static function Int($val){
		return $val;
	}
	public static function Str($s,$magic_quotes="auto"){	
		if ($magic_quotes == "auto") {
			$magic_quotes = get_magic_quotes_gpc();
		}
		return $magic_quotes ? "'".$s."'" : "'".addslashes($s)."'";
	}
	
	public static function FilterYearMonth($column,$ym){
		return "('$ym-01' <= $column AND $column < DATE_ADD('$ym-01',INTERVAL 1 MONTH))";
	}
}
/**
 * make and execute the sql query
 *
 */
class Sql extends QueryString
{
	public $DB;
	public $DEBUG = false;
	public $LastErrorMessage = "";
	public function Sql(&$db)
	{
		$this->DB = &$db;
	}
	public static function ToArray($sql, $num_of_col = 2, $db = false)	
	{
		if ($db === false) {
			if (empty($this->DB)) {
				return array();
			} else {
				$db = $this->DB;
			}
		}
		
		$arr = array();				
		$old_mode = $db->SetFetchMode(AFM::NUM);
		$rs = $db->Execute($sql);	
		if ($rs) {			
			if($num_of_col == 2){
				while(list($id, $val) = $rs->FetchRow()){
					$arr[$id] = $val;
				}
			} else if($num_of_col == 1){
				while(list($val) = $rs->FetchRow()){
					$arr[] = $val;
				}
			} else {
				$arr = $rs;
			}
		}
		$db->SetFetchMode($old_mode);
		return $arr;
	}
	public static function ToVariable(&$DB,$sql)
	{
		global $ADODB_FETCH_MODE;
		$dbmode = $ADODB_FETCH_MODE;
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$row = $DB->GetRow($sql);
		$ADODB_FETCH_MODE = $dbmode;
		
		foreach ($row as $key=>$val){
			global $$key;
			$$key = isset($$key) ? $$key : $val;			
		}
	}
	public static function GetSelectOption(&$DB ,$table,$value,$caption,$condition="", $groupby="", $orderby="")
	{
		$sql = "SELECT $value,$caption ";
		$sql .= "FROM $table ";
		$sql .= empty($condition) ? "" : "WHERE $condition ";
		$sql .= empty($groupby) ? "" : "GROUP BY $groupby ";
		$sql .= empty($orderby) ? "" : "ORDER BY $orderby ";
		return self::ToArray($sql,2,$DB);
	}
	public static function GetEnum(&$DB ,$table,$value,$caption,$condition="", $groupby="", $orderby="")
	{
		return self::GetSelectOption($DB ,$table,$value,$caption,$condition, $groupby, $orderby);
	}
	private static function ErrorMessage($message)
	{
		$this->LastErrorMessage = trim($message);
		
		return $this->LastErrorMessage;
	}
	private static function DebugMessage($func, $text, $show = false)
	{
		if ($this->DEBUG || $show) {
			echo "User->$func : $text<br />\n";
		}
	}
}
/**
 * Sql class version 2
 *
 */
class Sql2 extends QueryString {
	public static $DB;
	public function Sql2(&$conn = "default"){
		if ($conn === "default") {
			global $DB;
			if (isset($DB)) {
				self::SetConnection($DB);
			} else { 
				//error : no active connection
			}
		} else {
			self::SetConnection($conn);
		}
	}
	public static function SetConnection(&$conn){
		if (isset($conn)) {
			self::$DB = &$conn;
		} else {
			die("Undefined Connection!!");
		}
	}
	public static function GetArray($sql,&$DB = false,$tmp_mode = false){
		if ($DB === false) $DB = self::$DB;
		if ($tmp_mode === false) return $DB->GetArray($sql);
		$old_mode = $DB->SetFetchMode($tmp_mode);
		$result = $DB->GetArray($sql);
		$DB->SetFetchMode($old_mode);				
		return empty($result) ? array() : $result;
	}
	public static function GetRow($sql,&$DB = false,$tmp_mode = false){
		if ($DB === false) $DB = self::$DB;
		if ($tmp_mode === false) return $DB->GetRow($sql);
		$old_mode = $DB->SetFetchMode($tmp_mode);
		$result = $DB->GetRow($sql);
		$DB->SetFetchMode($old_mode);				
		return $result;
	}	
	public static function GetOne($sql,&$DB = false){
		if ($DB === false) $DB = self::$DB;
		return $DB->GetOne($sql);
	}
	public static function ToVariable($sql,&$DB = false){
		if ($DB === false) $DB = self::$DB;
		
		$old_mode = $DB->SetFetchMode(AFM::ASSOC);
		$row = $DB->GetRow($sql);
		$DB->SetFetchMode($old_mode);
		
		foreach ($row as $key=>$val){
			global $$key;
			if (isset($$key) && Config::Debug) {
				Debug::PrintWarn("Warning : Sql2::ToVariable() try  to overwrite the variable `$key`<br />\n");	
			}
			$$key = $val;
		}
	}
	public static function ToArray($sql, $num_of_col = 2, &$DB = false){
		if ($DB === false) $DB = self::$DB;
		$arr = array();
		
		$old_mode = $DB->SetFetchMode(AFM::NUM);
		$rs = $DB->Execute($sql);	
		if ($rs) {			
			if($num_of_col == 2){
				while(list($id, $val) = $rs->FetchRow()){
					$arr[$id] = $val;
				}
			} else if($num_of_col == 1){
				while(list($val) = $rs->FetchRow()){
					$arr[] = $val;
				}
			} else {
				$arr = $rs;
			}
		}
		$DB->SetFetchMode($old_mode);
		return $arr;
	}
	public static function Execute($sql,&$DB = false,$tmp_mode = false){
		if ($DB === false) $DB = self::$DB;
		if ($tmp_mode === false) return $DB->GetRow($sql);
		$old_mode = $DB->SetFetchMode($tmp_mode);
		$result = $DB->Execute($sql);
		$DB->SetFetchMode($old_mode);	
		return $result;
	}
	public static function AffectedRows(&$DB = false){
		if ($DB === false) $DB = self::$DB;
		return $DB->Affected_Rows();
	}
	public static function GetSelectOption($table,$value,$caption,$condition="", $groupby="", $orderby="",&$DB = false){
		return self::GetEnum($table,$value,$caption,$condition, $groupby, $orderby,$DB);
	}
	public static function GetEnum($table,$value,$caption,$condition="", $groupby="", $orderby="",&$DB = false){
		$sql = "SELECT $value,$caption FROM $table ".
			(empty($condition) ? "" : "WHERE $condition ").
			(empty($groupby) ? "" : "GROUP BY $groupby ").
			(empty($orderby) ? "" : "ORDER BY $orderby ");
		return self::ToArray($sql,2,$DB);
	}
	public static function Insert($sql,&$DB = false,$tmp_mode = false){
		if ($DB === false) $DB = self::$DB;
		if ($tmp_mode === false) {			
			$DB->Execute($sql);
		} else {
			$old_mode = $DB->SetFetchMode($tmp_mode);
			$DB->Execute($sql);
			$DB->SetFetchMode($old_mode);			
		}
		return ($DB->Affected_Rows() > 0 ? $DB->Insert_ID() : false);
	}
}
class Q extends Sql2 {}
Q::SetConnection($DB);


class InsertSQL
{
	private $DB;
	private $Table;
	public $Columns 	= array();
	public $Values		= array();
	
	public function InsertSQL(&$db,$tb_name)
	{
		$this->DB = &$db;
		if (!empty($tb_name)) {
			$this->Table = $tb_name;
		}
	}
	public function NewTable($tb_name) {
		$this->Table = $tb_name;		
		$this->Clear();
	}
	public function NewInsert($tb_name) {
		$this->NewTable($tb_name);
	}
	public function ChangeTableTo($tb_name)	{
		$this->Table = $tb_name;		
	}
	public function Clear()	{		
		$this->Columns 	= array();
		$this->Values 	= array();
	}
	public function AddStr($val,$col = ""){
		if (!empty($col)) {
			array_push($this->Columns,$col);
		}
		$val = trim($val);
		$val = $this->DB->qstr($val,get_magic_quotes_gpc());		
		array_push($this->Values,$val);
	}
	public function AddInt($val,$col = ""){
		if (!empty($col)) {
			array_push($this->Columns,$col);
		}		
		$val = trim($val);		
		array_push($this->Values,$val);
	}
	public function AddPw($val,$col = ""){
		if (!empty($col)) {
			array_push($this->Columns,$col);
		}
		$val = trim($val);
		$val = "'".md5($val)."'";
		array_push($this->Values,$val);		
	}
	public function AddNowTime($col = ""){		
		if (!empty($col)) {
			array_push($this->Columns,$col);
		}
		array_push($this->Values,"now()");
	}
	
	public function Str($col,$val){
		$this->AddStr($val,$col);
	}
	public function Pw($col,$val){
		$this->AddPw($val,$col);
	}
	public function Now($col){
		$this->AddNowTime($col);
	}
	public function MakeSQL() {
		if (empty($this->Columns) || count($this->Columns) != count($this->Values)) {return "";} 
		return "INSERT INTO `$this->Table` (`".join("`,`",$this->Columns)."`)VALUES(".join(",",$this->Values).")";
	}
	public function Execute($autoclear=false){
		$this->DB->Execute($this->MakeSQL());
		if ($autoclear) {$this->Clear();}
		return ($this->DB->Affected_Rows() == 1 ? $this->DB->Insert_ID() : false);
	}
}
class UpdateSQL
{
	private $DB;
	private $Table;
	private $Columns 	= array();
	private $Values		= array();
	private $KeyValue;
	private $KeyName;
	private $Condition;

	public function UpdateSQL(&$db,$db_table_name,$key_name,$key_value=null,$condition="")
	{
		$this->Condition = $condition;
		$this->DB = &$db;
		$this->KeyName = $key_name;
		$this->KeyValue = $this->DB->qstr($key_value,get_magic_quotes_gpc());
		if (!empty($db_table_name)) {
			$this->Table = $db_table_name;
		}
	}
	public function NewTable($db_table_name){
		$this->Table = $db_table_name;		
		$this->Clear();
	}
	public function ChangeTableTo($db_table_name)	{
		$this->Table = $db_table_name;
	}
	public function Clear()	{		
		$this->Columns 	= array();
		$this->Values 	= array();
	}
	public function AddStr($val,$col = ""){
		if (!empty($col)) {
			array_push($this->Columns,$col);
		}
		$val = trim($val);
		$val = $this->DB->qstr($val,get_magic_quotes_gpc());		
		array_push($this->Values,$val);
	}
	public function AddInt($val,$col = ""){
		if (!empty($col)) {
			array_push($this->Columns,$col);
		}		
		$val = trim($val);		
		array_push($this->Values,$val);
	}
	public function AddPw($val,$col = ""){
		if (!empty($col)) {
			array_push($this->Columns,$col);
		}
		$val = trim($val);
		$val = "'".md5($val)."'";
		array_push($this->Values,$val);		
	}
	public function AddNowTime($col = ""){		
		if (!empty($col)) {
			array_push($this->Columns,$col);
		}
		array_push($this->Values,"now()");
	}
	
	public function Str($col,$val){
		$this->AddStr($val,$col);
	}
	public function Pw($col,$val){
		$this->AddPw($val,$col);
	}
	public function Int($col,$val){
		$this->AddInt($val,$col);
	}
	public function Now($col){
		$this->AddNowTime($col);
	}
	public function Null($col){
		array_push($this->Columns,$col);
		array_push($this->Values,"NULL");
	}

	public function IsExistAndNotSelf($col,$val){
		$val = trim($val);
		$val = $this->DB->qstr($val,get_magic_quotes_gpc());
		$kv = $this->DB->qstr($this->KeyValue,get_magic_quotes_gpc());
		$rs = $this->DB->GetOne("SELECT count(*) FROM `$this->Table` WHERE $col = $val AND $this->KeyName != $kv");
		return ($rs==0?false:true);
	}
	public function MakeSQL(){
		$u = array();
		for ($i = 0; $i < count($this->Columns); $i++) { $u[] = "`".$this->Columns[$i]."` = ".$this->Values[$i]; }
		return "UPDATE `$this->Table` SET ".join(",",$u)." WHERE ".(empty($this->Condition) ? $this->KeyName." = ".$this->KeyValue : $this->Condition);
	}
	public function Execute() {
		$this->DB->Execute($this->MakeSQL());
		return $this->DB->Affected_Rows();
	}
}
?>