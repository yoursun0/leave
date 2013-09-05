<?php
class Forms2
{
	/* Server Side Method */
	public static function PostValue($field,$default=""){
		return (isset($_POST[$field])?$_POST[$field]:$default);
	}
	public static function GetValue($field,$default=""){
		return (isset($_GET[$field])?$_GET[$field]:$default);
	}
	public static function SubmitValue($field,$default=""){
		$post = self::PostValue($field, $default);
		$get = self::GetValue($field, $default);
		return (!empty($post) && $post!=$default?$post:$get);
	}
	public static function GetSubmit($fields,$default=""){
		if (is_array($fields)){
			foreach($fields as $f){
				global $$f;
				if(!isset($$f))$$f=$default;
				if(isset($_POST[$f])){$$f=$_POST[$f];}elseif(isset($_GET[$f])){$$f=$_GET[$f];}
			}
		} else {global $$fields;$$fields=self::SubmitValue($fields,$default);}
	}
	public static function FieldSpecialChars($value){
		$special_char = array("&", "\"");
		$replace_char = array("&amp;", "&quot;");
		return str_replace($special_char, $replace_char, $value);
	}
	
	/* UI - Standard Component */
	public static function Label($text,$for = '', $other_param = ''){
		$html = "<label";
		$html .= empty($for) ? "" : " for='$for'";
		$html .= empty($other_param) ? "" : " $other_param";
		$html .= ">$text</label>";
		return $html;
	}
	public static function Hidden($name, $var = '', $other_param = '') {
	    return ('<input id="'.$name.'" name="'.$name.'" type="hidden"  value="'. self::FieldSpecialChars($var) .'" '.$other_param.' />');
	}
	public static function Text($name, $var = '', $size = 20, $max = 0, $other_param = ""){
		/*
		if(stripos($other_param, "onblur=")  === false){
			$other_param .= " onblur=trimThis(this);";
		}
		*/
		$size = intval($size);
		$max  = intval($max);
		$str = "size=\"$size\"";
		if ($max)	$str .= " maxlength=\"$max\"";
		return('<INPUT id="'.$name.'" name="'.$name.'" type="text" '. $str .' value="'.self::FieldSpecialChars($var).'" '.$other_param.' />');
	}
	public static function TextArea ($name, $rows, $cols, $wrap = "", $varr = "", $other_param = "") {
		$str = '<textarea name="' . $name .'" id="'.$name.'"';
		$str .= $rows > 0 ? ' rows="'.$rows.'"' : "";
		$str .= $cols > 0 ? ' cols="'.$cols.'"' : "";
		$str .= empty($wrap) ? "" : ' wrap="'.strtolower($wrap).'"';
		$str .= ' '.$other_param.'>';
		$str .= self::FieldSpecialChars($varr);
		$str .= '</textarea>';
		return($str);
	}	
	public static function CheckBox($name, $value, $checked = "", $other_param ="") {
		$varr = $checked;
		$varr_array = array();
		$str = '<input type="checkbox" name="'.$name.'[]"  id="'.$name.'[]" value="'.self::FieldSpecialChars($value).'" ';
		if(is_array($varr)){
			foreach($varr as $v){
				array_push($varr_array, is_array($v) ? $v[0] : $v);
			}
			$str .= in_array($value, $varr_array) ? "checked" : "";
		} else {
			$str .= ($varr === true || $varr == $value) ? "checked" : "";
		}
		return ($str." $other_param />");
	}
	public static function Radio($name, $value, $varr = "", $other_param = "") {
		$str = '<input type="radio" name="'.$name.'" id="'.$name.'" value="'.self::FieldSpecialChars($value).'" '.$other_param;
		if (strval($varr) == strval($value))	$str .= ' checked';
		$str .= ' />';
		return $str;
	}
	public static function Select($name, $options, $varr = array(), $other_param = "", $blankOption = true, $optionsBefore = array())
	{
		if (!is_array($varr)) {
			$varr = empty($varr) ? array() : array($varr);
		}

		$str  = "<select id=\"$name\" name=\"$name\" $other_param>\n";

		// create blank option
		if($blankOption) $str .= "<option></option>\n";
		
		while(list($id, $content) = each($optionsBefore)) {			
			$checked = isset($varr) && in_array($id,$varr) ? " selected" : "";			
			$str .= "<option value=\"".self::FieldSpecialChars($id)."\" $checked>".self::FieldSpecialChars($content)."</option>\n";
		}
		while(list($id, $content) = each($options)) {
			$checked = isset($varr) && in_array($id,$varr) ? " selected" : "";	
			$str .= "<option value=\"".self::FieldSpecialChars($id)."\" $checked>".self::FieldSpecialChars($content)."</option>\n";
		}
		$str .= "</select>\n";
		return($str);
	}
	public static function Password($name, $other_param = "") {
		return('<INPUT type="password" name="'.$name.'"  id="'.$name.'" '.$other_param.' />');
	}
	public static function Button($func,$text = "Button", $other_param = ""){
		return  '<input type="button" value="'.$text.'" onclick="'.$func.'" '.$other_param.' />';
		//return '<button onclick="'.$func.'" '.$other_param.'>'.$text."</button>";
	}
	public static function Submit($func,$text = "Button", $other_param = ""){
		return '<button onclick="'.$func.'" '.$other_param.' class="Submit">'.$text."</button>";
	}
	
	public static function ajaxSelect($name, $options, $onchange, $varr = array(), $other_param = "", $blankOption = false, $optionsBefore = array())
	{
		$other_param .= ' onchange="'.$onchange.'" ';
		return self::Select($name,$options,$varr,$other_param,$blankOption,$optionsBefore);
	}
	
	
	public static function ajaxTr($id,$cells,$prefix = "R",$class = "",$other_param = ""){
		$s = "<tr id=\"$id\" ";
		if (empty($class)) {$s .= ' class="'.$class.'"';}
		if (empty($other_param)) {$other_param = ' '.$other_param;}
		$s .= ">";
		foreach ($cells as $cell) {
			$s .= "<td>".$cell."</td>";
		}
		return $s."</tr>";
	}
	/**/
	public static function Br($no = 1) {
		return str_repeat("<br />\n",$no);
	}
	public static function Hr()	{
		return "<hr />";
	}
	public static function Tr($label,$content){
		return 	"<tr><td class='Caption'>$label ".
				(strpos($content,'class="required"') ? "<span class='MarkupRequired'> ＊ </span>" : "").
				"</td><td class='Content'>$content</td></tr>";
	}
	public static function ColGroup($w1,$w2 = null){
		if (is_array($w1)) {
			$s = "<colgroup>";
			foreach ($w1 as $w)	$s .= "<col width='$w' />";
			return $s."</colgroup>";
		}
		return "<colgroup><col width='$w1' />".(empty($w2) ? "" : "<col width='$w2' />")."</colgroup>";		
	}
	public static function SectionTitle($title="&nbsp;") {
		return "<tr><th  class='Header' colspan='2'>$title</th></tr>";
	}
	public static function RowSpace(){
		return "<tr><th  class='Space' colspan='2'></th></tr>";
	}

	public static function MonthPicker($name,$date,$other_param=""){
		return "<div id='$name' title='$date' class='monthpicker' $other_param></div>";
	}
	public static function CssButton($call,$text = "", $image = "add", $class = "slim_btn"){
		return "<a href=\"javascript:;\" onclick=\"$call\" class=\"$class\">".
			($image === false ? "" : '<img src="'.Path::BtnIcon.$image.'.gif" />').
			(empty($text) ? "" : " $text&nbsp;")."</a>";
	}
	public static function IcoButton($call,$text = "", $image = "add", $class = "icon_btn"){
		$text = str_replace(array("\r", "\n", "\""), array(""," ", "&quot;"),$text);
		return "<a href=\"javascript:;\" onclick=\"$call\" class=\"$class\" title=\"$text\">".
			($image === false ? "" : '<img src="'.Path::BtnIcon.$image.'.gif"'
			.(empty($text) ? "" : " alt=\"$text\"").' />')."</a>";
	}
}
/**
 * class : Forms v2.0
 *
 */
final class F extends Forms2{}
class FieldValidation
{
	const END = ";";
	public static function OnBlur($script)	{
		return " onblur=\"".$script."\" ";
	}
	public static function Required($title = '') {
		return ' class="required" title="'.$title.'" ';
	}
	public static function Contains($nTxt,$control = 'this',$txt = '') {
		//javascript : function ValidateContains(txtControl, nTxt)
		return self::OnBlur("ValidateContains($control,'$nTxt')");
	}
	public static function AlphaNumeric($control = 'this',$txt = '') {
		//javascript : function ValidateAlphaNumeric(txtControl,fieldCaption)
		return self::OnBlur("ValidateAlphaNumeric($control,'$txt')");
	}
	public static function Length($maxLength, $minLength = 0, $control = 'this',$txt = '') {
		//javascript : function ValidateLength(txtControl, maxLength, minLength, fieldCaption)
		return self::OnBlur("ValidateLength($control,$maxLength,$minLength,'$txt')");
	}
	public static function Date($other="",$control="this",$txt = '') {
		//javascript : function ValidateDate(txtControl, fieldCaption)
		return empty($other) ? self::OnBlur("ValidateDate($control,'$txt')") : "ValidateDate($control,'$txt');$other";	
	}
	public static function Email($other="",$control="this") {
		//javascript : function ValidateEmail(txtControl)
		return empty($other) ? self::OnBlur("ValidateEmail($control)") : "ValidateEmail($control);$other";
	}	
	public static function Int($other="",$control="this",$txt = '') {
		//javascript : function ValidateInteger(txtControl, fieldCaption)
		return empty($other) ? self::OnBlur("ValidateInteger($control,'$txt')") : "ValidateInteger($control,'$txt');$other";	
	}
}
final class FV extends FieldValidation{}


class OptionsBar
{
	public $_cells = array();
	public function OptionsBar($caption=false,$content=false){
		$this->_cells = array();
		if ($caption) {
			$this->addCaption($caption);
		}
		if ($content) {
			$this->addContent($content);
		}
	}
	public function addCaption($str,$other_param=""){
		$this->_cells[] = "<td class='caption' $other_param>$str</td>";
	}
	public function addContent($str,$other_param=""){
		$this->_cells[] = "<td class='content' $other_param>$str</td>";		
	}
	public function output(){
		echo "<div class='p_optionbar'><table><tr>".join("",$this->_cells)."</tr></table></div>";
	}
}

final class Forms
{
	/* Server Side Method */
	public static function GetPostValue($name, $default = '')
	{
		global $_POST;		
		return (isset($_POST[$name]) ? $_POST[$name] : $default);
	}
	public static function GetPostGetValue($name, $default = '')
	{
		$val = self::GetPostValue($name, $default);
		return ($val != $default ? $val : self::GetGetValue($name, $default));
	}
	public static function GetPostGetValues($fields, $default = '') {
		foreach($fields as $f){
			global $$f;
			if(!isset($$f)) $$f = $default;
			if(isset($_POST[$f])){
				$$f = $_POST[$f];
			} else if(isset($_GET[$f])){
				$$f = $_GET[$f];
			}
		}
	}	
	public static function GetGetValue($name, $default = '')
	{
		global $_GET;		
		return (isset($_GET[$name]) ? $_GET[$name] : $default);
	}
	public static function GetGetPostValue($name, $default = '')
	{
		$val = self::GetGetValue($name, $default);
		return ($val != $default ? $val : self::GetPostGetValue($name, $default));
	}
	public static function FieldSpecialChars($val){
		$special_char = array("&", "\"");
		$replace_char = array("&amp;", "&quot;");
		return str_replace($special_char, $replace_char, $val);
	}

	public static function CalcPageNumber(&$p, &$firstIndex, $total_row, $rowPerPage = 0)
	{
		$rowPerPage = $rowPerPage == 0 ? GlobalConfig::ROW_PER_PAGE : $rowPerPage;
		if(empty($p)) $p = 1;
		$p = intval($p);
		$maxPageNum = intval(($total_row - 1) / $rowPerPage + 1);
		if($maxPageNum <= 0) $maxPageNum = 1;
		if($p <= 0) $p = 1;
		if($p > $maxPageNum) $p = $maxPageNum;
		$firstIndex = ($p - 1) * $rowPerPage;
	}
		
	/* UI - Customize Component */
	public static function ViewButton($url)
	{
		return "<a href=\"".$url."\"><img src='".Path::Image."viewmag.png' border='0' align='absmiddle' alt='View' /></a>";
	}
	public static function ReportButton($url,$size = 16,$title = false)
	{
		if ($title === false) {
			$title = "";
		}
		return "<a href=\"".$url."\"><img src='".Path::Image."klipper_dock.png' border='0' align='absmiddle' alt='Report' />$title</a>";
	}
	public static function EditButton($url,$size = 16,$title = false)
	{
		switch ($size) {
			case 16	:
			default	: $file = "btn_edit.png";
		}
		if ($title === false) {
			$title = "";
		}
		return '<a href="'.$url.'" class="btn"><img src="'.Path::Image.$file.'" border="0" align="absmiddle" /> '.$title.'</a>';
	}
	public static function DeleteButton($url,$size = 16,$title = false)
	{
		switch ($size) {
			case 24	: $file = "btn_del_24.png";		break;
			case 11 : $file = "btn_del_11.png";		break;
			case 16	:
			default	: $file = "btn_del_16.png";
		}
		if ($title === false) {
			$title = "";
		}
		return '<a href="'.$url.'" class="btn"><img src="'.Path::Image.$file.'" border="0" align="absmiddle" /> '.$title.'</a>';
	}
	public static function RefreshButton($url,$size = 16,$title = false)
	{
		switch ($size) {
			case 24	: $file = "btn_refresh_24.png";		break;
			case 16	:
			default	: $file = "btn_refresh_16.png";
		}
		if ($title === false) {
			$title = "";
		}
		return '<a href="'.$url.'" class="btn"><img src="'.Path::Image.$file.'" border="0" align="absmiddle" /> '.$title.'</a>';
	}
	public static function CaleButton($id,$size = 16,$showTitle = false){
		switch ($size) {
			case 24	: $file = "btn_cale_24.png";		break;
			case 16	:
			default	: $file = "cal.gif";
		}
		return "<a href='javascript:;'><img src='".Path::Image.$file."' id='".$id."' border='0' align='absmiddle' alt='Click here to select date and time' /></a>";
	}
	public static function BulletLink($text, $url = 'javascript:;', $auto_break = true)
	{
		return '<img src="'.Path::Image.'function_bullet.gif" align="absmiddle">
                <a href="'.$url.'">'.$text.'</a>'.($auto_break ? "<br />\n" : "\n");
	}
	public static function NatvigationBar($currentPage, $totalRowNum, $tableWidth = "100%", $align = "center", $rowPerPage = 0)
	{	
		$rowPerPage = $rowPerPage == 0 ? GlobalConfig::ROW_PER_PAGE : $rowPerPage;
		if($totalRowNum <= $rowPerPage) return "";
		
		$currentPage = intval($currentPage);
		if(empty($currentPage)) $currentPage = 1;
		$imgPath = Path::Image;
		$maxPageNum = 1;
	
		$maxPageNum = intval(($totalRowNum - 1) / $rowPerPage + 1);
		if($maxPageNum <= 0) $maxPageNum = 1;
		if($currentPage <= 0) $currentPage = 1;
		if($currentPage > $maxPageNum) $currentPage = $maxPageNum;
	
		$html = "<span align='$align'>";
		//$html .= "\n";
		//$html .= "Page:\n";
	
		if($currentPage <= 1){
			$html .= "<img src=\"" . $imgPath . "0.gif\" width=\"16\" height=\"16\" align='absmiddle' />";
		} else {
			$html .= "<img src=\"" . $imgPath . "Back.gif\" width=\"16\" height=\"16\" alt=\"Previous Page\" onclick=\""
			."gotoPage(" . ($currentPage - 1) . ")\" style=\"cursor: hand;\" align='absmiddle' />";
		}
		$html .= " ";
		$html .= "<select name=\"pageControl\" onchange=\"gotoPage(this.options[this.selectedIndex].value)\">\n";
		for($i = 1 ; $i <= $maxPageNum ; $i++){
			$html .= "<option value=\"" . $i . "\"";
			if($i == $currentPage) $html .= " selected";
			$html .= ">" . $i . "</option>\n";
		}
		$html .= "</select>\n";
		$html .= " ";
		if($currentPage >= $maxPageNum){
			$html .= "<img src=\"" . $imgPath . "0.gif\" width=\"16\" height=\"16\" align='absmiddle' />";
		} else {
			$html .= "<img src=\"" . $imgPath . "Forward.gif\" width=\"16\" height=\"16\" alt=\"Next Page\" onclick=\""
			. "gotoPage(" . ($currentPage + 1) . ")\" style=\"cursor: hand;\" align='absmiddle' />";
		}
	
		$html .= "&nbsp; &nbsp; Total: $totalRowNum record(s)";
		$html .="</span>";
		return $html;
	}
	
	/* UI - Standard Component */
	public static function CheckBox($_name, $value, $varr = "", $other_param ="") {
	//    if ($varr == null)
	//        $varr =& $GLOBALS['HTTP_POST_VARS'];
		$varr_array = array();
		if(is_array($varr)){
			foreach($varr as $v){
				if(is_array($v))
					array_push($varr_array, $v[0]);
				else
					array_push($varr_array, $v);
			}
		}
	
		$str = '<INPUT type="checkbox" name="'.$_name.'[]"  id="'.$_name.'[]" value="' . self::FieldSpecialChars($value) .'"';
		if(is_array($varr)){
			if(in_array($value, $varr_array))
				$str .= ' checked'; 
		} else {
			if ($varr == $value)
				$str .= ' checked';
		}
		$str .= $other_param;
		$str .= ' />';
		return($str);
	}
	public static function Hidden($name, $var = '', $otherParam = '') 
	{
		if(!empty($otherParam)) $otherParam = " ".$otherParam;		
	    return ('<input type="hidden" name="'.$name.'" id="'.$name.'" value="'. self::FieldSpecialChars($var) .'"'.$otherParam.' />');
	}
	public static function HiddenValues($fields, $setval = true)
	{
		$html = "";
		foreach($fields as $f){
			global $$f;
			$html .= Forms::Hidden($f,($setval ? $$f : ''))."\n";
		}
		return $html;	
	}
	
	public static function Text($_name, $size = 20, $max = 0, $var = "", $other_param = "")
	{
		if(stripos($other_param, "onblur=")  === false){
			$other_param .= " onblur=trimThis(this);";
		}		
		if(!empty($other_param)) $other_param = " ".$other_param;
		$size = intval($size);
		$max  = intval($max);
		$str = "size=\"$size\"";
		if ($max)	$str .= " maxlength=\"$max\"";
		return('<INPUT type="text" '. $str .' name="'.$_name.'" id="'.$_name.'" value="'.self::FieldSpecialChars($var).'"'.$other_param.' />');
	}
	public static function TextArea ($_name, $rows, $cols, $wrap, $varr = "", $other_param = "") {
		if(stripos($other_param, "onblur=")  === false){
			$other_param .= " onblur=trimThis(this);";
		}
		
		if(!empty($other_param)) $other_param = " ".$other_param;
		
		$str = '<textarea name="' . $_name .'" id="'.$_name.'"';
		if($rows > 0)
			$str .= ' rows="' . $rows . '"';
		if($cols > 0)
			$str .= ' cols="' . $cols . '"';
		if($wrap != '')
			$str .= ' wrap="' . strtolower($wrap) . '"';
		$str .= $other_param.'>';
	
		$str .= $varr;
		$str .= '</textarea>';
		return($str);
	}	
	public static function Select($_name, $options, $varr = array(), $otherParam = "", $blankOption = true, $optionsBefore = array()) 
	{
		if (!is_array($varr)) {
			if (empty($varr)) {
				$varr = array();
			} else {
				$varr = array($varr);				
			}
		}
		
		if(!empty($otherParam)) $otherParam = " ".$otherParam;
		
		$str  = "<select name=\"$_name\" id=\"$_name\"$otherParam>\n";

		while(list($cid, $content) = each($optionsBefore)) {
			$checked = '';
			if (isset($varr) && array_search($cid,$varr) !== false)
				$checked = ' selected';
			$str .= "<option value=\"".self::FieldSpecialChars($cid)."\"${checked}>".self::FieldSpecialChars($content)."</option>\n";
		}
		
		// create blank option
		if($blankOption) $str .= "<option></option>\n";
		
		while(list($cid, $content) = each($options)) {
			$checked = '';
			if (isset($varr) && array_search($cid,$varr) !== false)
				$checked = ' selected';
			$str .= "<option value=\"".self::FieldSpecialChars($cid)."\"${checked}>".self::FieldSpecialChars($content)."</option>\n";
		}
		$str .= "</select>\n";
		return($str);
	}

	public static function Button($_name, $_value = null, $other_param = ""){
		if(!empty($other_param)) $other_param = " ".$other_param;
	    if ($_value == null) $_value = 'Button';
	    return '<INPUT type="button" name="'.htmlspecialchars($_name).'" value="'.$_value.'"'.$other_param.' />';
	}
	public static function ButtonForFunctionCall($_value, $call, $other_param = "")
	{		
		if(!empty($other_param)) $other_param = " ".$other_param;
	    if ($_value == null) $_value = 'Button';
	    return '<BUTTON onclick="'.$call.'" '.$other_param.'>'.$_value."</BUTTON>";
	    //return '<INPUT type="button" value="'.$_value.'" onclick="'.$call.'" '.$other_param.' />';
	}
	public static function Password($_name, $other_param = "") {
		if(!empty($other_param)) $other_param = " ".$other_param;
		return('<INPUT type="password" name="'. htmlspecialchars($_name) .'"  id="'.$_name.'"'.$other_param.' />');
	}
	public static function Submit($_name, $_value = null, $other_param = "") {
		if(!empty($other_param)) $other_param = " ".$other_param;
	    if ($_value == null)
	        $_value = _('Submit');
	    return '<INPUT type="submit" name="'.htmlspecialchars($_name).'" value="'.$_value.'"'.$other_param.' />';
	}
	public static function SubmitBasic()
	{
		return '<input type="submit" value="Submit">';                  
	}
	public static function ResetBasic()
	{
		return '<input type="reset" value="Reset">';
	}
	public static function Radio($_name, $value, $varr = "", $other_param = "") {
		$str = '<input type="radio" name="'.$_name.'" id="'.$_name.'" value="' . self::FieldSpecialChars($value) .'" '.$other_param;
		if (strval($varr) == strval($value))
			$str .= ' checked';
		$str .= ' />';
		return($str);
	}
}
?>