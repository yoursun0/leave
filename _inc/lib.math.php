<?php
class Math
{
	function PercentBar($count, $max)
	{
		if ($max == 0) {
			return "";
		}
		$percent = $count / $max * 100;
		
		$html = number_format($percent, 2)." %";
		$bar_width = number_format($percent,0);
		if($bar_width > 100) $bar_width = 100;
		$html .= "<table width='100' height='5' cellspacing='0' cellpadding='0' bgcolor='#eeeeee' class='percent_bar'><tr>";		
		if ($bar_width > 0) {
			$html .= "<td bgcolor='green' width=".$bar_width."></td>";			
		}
		$html .= "<td width=".(100-$bar_width)."></td></tr></table>";
		return $html;
	}
}
?>