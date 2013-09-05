<?php
class Debug
{
	private static $RunningTime = array();
	
	public static function PrintArray(&$array,$show = true) {
		if (isset($array)) {		
			$str = "<pre class='debug'>\nvar ".self::GetVariableName($array)." = ".var_export($array,true).";</pre>\n";
		} else {
			$str = self::ErrorMsg("Unknown variable");
		}
		if ($show) {
			echo $str;
		}
		return $str;
	}	
	public static function PrintPre($str){
		echo (isset($str) ? "<pre>\n".$str."</pre>\n" : self::ErrorMsg("Unknown variable"));
	}
	public static function StartTime($name="MAIN"){
		self::$RunningTime[$name]['end'] = null;
		self::$RunningTime[$name]['total'] = null;
		self::$RunningTime[$name]['start'] = microtime();
	}
	public static function EndTime($name="MAIN"){	
		self::$RunningTime[$name]['end'] = microtime();		 
	}
	public static function RunTime($name="MAIN"){		
		if (empty(self::$RunningTime[$name]['end'])) {self::EndTime();}
		$mtime = explode(" ",self::$RunningTime[$name]['start']);
		$starttime = $mtime[1] + $mtime[0];
		$mtime = explode(" ",self::$RunningTime[$name]['end']);
		$endtime = $mtime[1] + $mtime[0];
		return self::$RunningTime[$name]['total'] = $endtime - $starttime;
	}
	public static function PrintRunTime($name="MAIN"){
		if (empty(self::$RunningTime[$name]['total'])) {self::RunTime();}
		$mtime = explode(" ",self::$RunningTime[$name]['start']);
		$starttime = $mtime[1] + $mtime[0];
		$totaltime=self::$RunningTime[$name]['total'];
		echo "The action[$name] started at ".date("Y-m-d H:i:s",$starttime).". The running time is ".round($totaltime*1000,16)." ms"; 		
	}
	public static function PrintWarn($msg = "Warning"){
		echo self::WarnMsg($msg)."<br />\n";
	}	
	public static function PrintError($msg = "Error"){
		echo self::ErrorMsg($msg)."<br />\n";
	}
	public static function GetVariableName(&$var, $scope=false, $prefix='unique', $suffix='value')	{
		$vals = $scope ? $scope : $GLOBALS;
		$old = $var;
		$var = $new = $prefix.rand().$suffix;
		$vname = false;
		foreach($vals as $key => $val) {
			if($val === $new) $vname = $key;
		}
		$var = $old;
		return $vname;
	}	
	public static function WarnMsg($msg)	{
		return '<font color="red">'.$msg.'</font>';
	}
	public static function ErrorMsg($msg)	{
		return '<font color="red">'.$msg.'</font>';
	}
}

class LoopProtect
{
	public $Max;
	public $Count;
	public function LoopProtect($max = 100) {
		$this->Count = 0;
		$this->Max = $max;
	}
	public function Run($i = false){
		if ($this->Count >= $this->Max) break;
		return "".(++$this->Count).($i !== false ? ":$i" : "");
	}
	public function Reset(){
		$this->Count = 0;
	}
}
	
class Basic
{
	public static function JS($script){
		return "<script type=\"text/javascript\">$script</script>";
	}
	public static function Count(&$var,$value) {
		$c=isset($value)?$value:0;$var=isset($var)?$var+$c:$c;
	}
	public static function Percentage($val,$base=100,$digi=2) {
		return ($base>0)?round($val/$base*100,$digi):0;
	}
	public static function Display(&$val,$replace="-") {
		return isset($val)?$val:$replace;
	}
	public static function TableTitle($title){
		return "<h3>$title</h3>\n";
	}
	public static function MakeArray($s){
		$a = array();
		$AAs = preg_split("/[,]/", $s);
		foreach($AAs as $AA){
			$AA = trim($AA);
			if(strpos($AA, "-") === false){
				$a[] = $AA;
			} else {
				$BBs = preg_split("/[-]/", $AA);
				if(count($BBs) != 2){
					echo "mkArray error: Invalid range found in string (".$AA.")" ;
					return array();
				} else {
					$BBs[0] = trim($BBs[0]);
					$BBs[1] = trim($BBs[1]);
					if(ereg('^[0-9]*$', $BBs[0]) && ereg('^[0-9]*$', $BBs[1])){
						if($BBs[0] < $BBs[1]){
							for($i = $BBs[0]; $i <= $BBs[1]; $i++){
								$a[] = $i;
							}
						} else {
							echo "mkArray error: Invalid range found in string (".$AA.")" ;
							return array();
						}
					} else if(ereg('^[A-Za-z]$', $BBs[0]) && ereg('^[A-Za-z]$', $BBs[1])){
						if(ereg('^[A-Z]$', $BBs[0])){
							$BBs[0] = strtoupper($BBs[0]);
							$BBs[1] = strtoupper($BBs[1]);
						} else {
							$BBs[0] = strtolower($BBs[0]);
							$BBs[1] = strtolower($BBs[1]);
						}
	
						if($BBs[0] < $BBs[1]){
							for($i = $BBs[0]; $i <= $BBs[1]; $i++){
								$a[] = $i;
							}
						} else {
							echo "mkArray error: Invalid range found in string (".$AA.")" ;
							return array();
						}
					} else {
						echo "mkArray error: Invalid range found in string (".$AA.")" ;
						return array();
					}
				}
			}
		}
		return $a;
	}
	public static function ExportHeader($filename,$init_charset = true){
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
	//	header("Content-Type: application/octet-stream; charset=BIG5");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=$filename ");
		header("Content-Transfer-Encoding: binary ");
		if ($init_charset) {
			echo '<meta content="text/html; charset='.Config::Charset.'" http-equiv="content-type">';
		}
	}
	public static function DownloadFile($file,$filename){
		$b = get_browser(null, true);
		if ($b['browser'] === "IE") {
	 		$filename = urlencode($filename);		
		}
		
		if(file_exists($file)) {
	        header('Content-Description: File Transfer');
	        header('Content-Type: application/octet-stream');
	        header('Content-Disposition: attachment; filename="'.$filename.'"');
	        header('Content-Transfer-Encoding: binary');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	        header('Pragma: public');
	        header('Content-Length: ' . filesize($file));
	        flush();
	        readfile($file);
	        exit;
	    }
	}
	public static function RandomKeys($length){
		$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
		$key = "";
		for($i=0;$i<$length;$i++)$key.=$pattern{rand(0,35)};
		return $key;
	}
	public static function TableSorter($selector = ".tablesorter"){
		return self::JS("$(function(){ $('$selector').tablesorter(); });");
	}
	public static function Toggle($selector = ".toggle"){
		return self::JS("$(function(){ $('$selector').toggleElements(); });");
	}
}


class Ajax
{
	public static function Success($msg = "",$other = null) {
		$r = $other === null ? new stdClass() : $other;
		$r->type = "ok";
		if (!empty($msg)) {
			$r->msg = $msg;
		}
		exit(json_encode($r));
	}
	public static function Error($msg = "",$other = null) {
		$r = $other === null ? new stdClass() : $other;
		$r->type = "error";
		if (!empty($msg)) {
			$r->msg = $msg;
		}
		exit(json_encode($r));
	}
	public static function Warning($msg = "",$other = null) {
		$r = $other === null ? new stdClass() : $other;
		$r->type = "warn";
		if (!empty($msg)) {
			$r->msg = $msg;
		}
		exit(json_encode($r));
	}
}

class jqGrid
{
	public static function GetSubmit(){
		global $sidx;
		if(!$sidx)$sidx=1;
		F::GetSubmit(array("page","rows","sidx","sord","_search","searchField","searchOper","searchString"));		
	}
	public static function MakeCondition($search,$field,$opt,$val){
		if ($search != "true") return "";
		switch ($opt) {
			case "bw": $c = "$field LIKE '$val%'";	//bw - begins with ( LIKE val% )
			case "eq": $c = "$field = '$val'";		//eq - equal ( = )
			case "ne": $c = "$field <> '$val'";		//ne - not equal ( <> )
			case "lt": $c = "$field < '$val'";		//lt - little ( < )
			case "le": $c = "$field <= '$val'";		//le - little or equal ( <= )
			case "gt": $c = "$field > '$val'";		//gt - greater ( > )
			case "ge": $c = "$field >= '$val'";		//ge - greater or equal ( >= )
			case "ew": $c = "$field LIKE '%$val'";	//ew - ends with (LIKE %val )
			case "cn": $c = "$field LIKE '%$val%'";	//cn - contain (LIKE %val% )
		}
		return " WHERE $c ";
	}
	public static function CalcPage(&$DB,$sql,&$total_pages,&$page,&$start,$limit) {
		$count = $DB->GetOne($sql);
		$total_pages = $count >0 ? ceil($count/$limit) : 0;
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;
		$r = new stdClass();
		$r->page = $page;
		$r->total = $total_pages;
		$r->records = $count;		
		return $r;
	}
	public static function GetData(&$DB,$sql) {
		$rs = $DB->GetArray($sql);
		return is_array($rs) ? $rs : array();
	}
}

final class B extends Basic {};
function mkPagesLink($call,$name){
	global $USER,$gModule;
	if ($USER->CheckPages($gModule,$call) == true) echo "<li><a href=\"javascript:;\" onclick=\"blur();OpenPages('$call')\">$name</a></li>\n";
}
function mkSubMenu($arr,$title){
	global $USER,$gModule;
	if (!is_array($arr)) return "";
	
	$show = false;
	$menu = "";
	foreach ($arr as $key=>$name) {
		if ($USER->CheckPages($gModule,$key) == true) {
			$menu .= "<li><a href=\"javascript:;\" onclick=\"blur();OpenPages('$key');\">$name</a></li>\n";
			$show = true;
		}
	}
	if ($show === true)	echo "<li><a href=\"javascript:;\" onclick=\"blur();\">$title</a><ul>$menu</ul></li>";
}
?>