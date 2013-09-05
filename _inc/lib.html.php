<?php
final class Html
{
	public static function BreakLine($no = 1)
	{
		$html = "";
		while ($no-- > 0) {
			$html .= "<br />\n";
		}
		return $html;
	}
	public static function Span($id, $content, $otherParam = '')
	{
		if(!empty($otherParam)) $otherParam = " ".$otherParam;
	    return ('<span id="'.$id.'"'.$otherParam.' />'.$content.'</span>');
	}
	public static function Section($id, $title,$content,$width = 0, $otherParam = '')
	{
		if(!$otherParam) $otherParam = " ".$otherParam;
		$html = '
		<div'.($width > 0 ? ' style="width:'.$width.'px"' : "").'>
			<div class="sectionHead">'.$title.'</div>
			<div id="'.$id.'">'.$content.'</div>
		</div>';
		
		return $html;
		//<div class="sectionHead" onclick="showSection(\''.$id.'\')">'.$title.'</div>
	}
	public static function PageTitle($title,$bar=''){
		return '<table class="pages_title"><tr>
<td class="pages_title_l"></td><td class="pages_title_c">'.$title.'</td><td class="pages_title_r"></td>
<td>&nbsp;&nbsp;</td>'.(empty($bar) ? '' : '<td class="pages_title_l"></td><td class="pages_title_c titleBar">'.$bar.'</td><td class="pages_title_r"></td>').'</tr></table>';
		//return "<h2>$title</h2>\n";
	}
	public static function LoadingBox($title, $content)
	{
	  	$html =  '<div class="LoadingBox"><table align="center" width="300" height="100">';
		$html .= '	<tr><td>';
	  	$html .= '		<p><img alt="Loading" src="'.GlobalConfig::PATHS_IMAGE.'loading_32_2.gif" /> '.$title.'</p>';
		$html .= '	</td></tr>';
		$html .= '	<tr><td>';
	  	$html .= $content;
		$html .= '	</td></tr>';
	  	$html .= '</table></div>';	  	
	  	return $html;
	}
	public static function WarningMessage($msg) {
		return "<span class=\"WarnMsg\">${msg}</span>\n";
	}
	public static function ErrorMessage($msg) {
		return "<span class=\"ErrorMsg\">${msg}</span>\n";
	}
	public static function FormatFileSize ($bytes, $precision = 1){
		$suffix = array ('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
		$index = floor (log ($bytes + 1, 1024)); // + 1 to prevent -INF
		return sprintf ("%0.{$precision}f %s", $bytes / pow (1024, $index), $suffix[$index]);
	}
}
class JavaScript
{
	const Begin = "\n<script language=\"JavaScript\" type=\"text/javascript\">\n";
	const End 	= "\n</script>\n";

	/* basic javascript function */
	public static function Add($script)
	{
		echo self::Begin.$script.self::End;
	}
	public static function RedirectTo($url, $stop = true)
	{
		self::Add('window.location = "'.$url.'"');
		if($stop)exit;
	}

	
	/* server side gen. script */
	public static function SetFocus($form_name,$obj_name)
	{
		self::Add("document.".$form_name.".".$obj_name.".select();\ndocument.".$form_name.".".$obj_name.".focus();");
	}
	public static function FillEmpty($query,$str = "-")
	{
		self::Add('FillEmpty("'.$query.'","'.$str.'")');
	}
	public static function TableSorter($disable = array(),$id = "#mainTable")
	{
		$h = "";
		foreach ($disable as $col) {
			if (!empty($h)) { $h .= ";";}
			$h .= $col.": {sorter: false}";
		}
		
		$s ="
		$(document).ready(function($) {
			$('$id').tablesorter({
				headers: {
					$h
				}
			});
		});";
		self::Add($s);
	}
}
final class J extends JavaScript {
	
}
?>