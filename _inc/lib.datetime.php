<?php
class Date
{
/*****************************************************************************
 *  Date Calculation
 *****************************************************************************/
	function AddDay($date,$adj = 1)
	{		
		return date("Y-m-d", strtotime("$adj day",strtotime($date)));
	}
	function AddWeek($date,$adj = 1)
	{		
		return date("Y-m-d", strtotime("$adj week",strtotime($date)));
	}
	function AddMonth($date,$adj = 1)
	{
		return date("Y-m-d", strtotime("$adj month",strtotime($date)));		
	}
	function AddYear($date,$adj = 1)
	{
		return date("Y-m-d", strtotime("$adj year",strtotime($date)));		
	}

/*****************************************************************************
 *  Get Value From Date or DateTime String
 *****************************************************************************/
	/**
	 * Get the day of week, 
	 *
	 * @param string $date is date string, like '2000-01-01'
	 * @param string $format 'w' will return 0 ~ 6; 'l' will return Sunday through Saturday)
	 * @return string | int
	 */
	public function GetDayOfWeek($date,$format = 'w'){
		return date($format, strtotime($date));
	}
	public function GetDaysInMonth($date)
	{
		return date('t',strtotime($date));
	}
	public function GetFristDateOfWeek($date)
	{
		$adj = Date::GetDayOfWeek($date) * -1;			
		return date("Y-m-d", strtotime("$adj day",strtotime($date)));
	}
	public function GetFristDateOfMonth($date)
	{
		return date("Y-m-01", strtotime($date));
	}
	public function GetLastDateOfMonth($date)
	{
		return date("Y-m-d", strtotime("-1 day +1 month", strtotime(Date::GetFristDateOfMonth($date))));
	}
	public function GetDatesOfThisWeek()
	{
		return Date::GetDatesOfWeek(Date::Today());
	}
	public function GetDatesOfThisMonth()
	{
		return Date::GetDatesOfMonth(Date::Today());
	}
	public function GetDatesOfWeek($date)
	{
		$d = Date::GetFristDateOfWeek($date);
		$ds = array();
		for ($i = 0; $i < 7; $i++) {
			array_push($ds,$d);
			$d = Date::AddDay($d);
		}
		return $ds;
	}
	public function GetDatesOfMonth($date)
	{
		$d = Date::GetFristDateOfMonth($date);
		$ds = array();
		for ($i = 0; $i < Date::GetDaysInMonth($date); $i++) {			
			array_push($ds,$d);
			$d = Date::AddDay($d);
		}	
		return $ds;	
	}
	public function GetDateByString($datetime)
	{
		 return date("Y-m-d", strtotime($datetime));
	}
	
	public function DaysAgo($datetime)
	{
		return Date::DaysDiff($datetime,"now");
	}
	/**
	 * Compare two date and return diff. days
	 *
	 * @param String $begin Start Date
	 * @param String $end End Date
	 * @return Int 
	 * 		$begin = $end : return 0
	 * 		$begin < $end : return positive number
	 * 		$begin > $end : return netgative number
	 */
	public function DaysDiff($begin,$end)
	{
		$b = unixtojd(strtotime($begin));
		$e = unixtojd(strtotime($end));
		return $e - $b;
	}
	public function Year($date = "now")
	{
		if ($date == "now") {
			return date("Y");
		}
		return substr($date,0,4);
		//return date("Y",strtotime($date));
	}
	public function YearMonth($date = "now")
	{
		if ($date == "now") {
			return date("Y-m");
		}
		return date("Y-m",strtotime($date));
	}
	public function Month($date = "now"){
		if ($date == "now") {
			return date("m");
		}		
		return date("m",strtotime($date));
	}
	public function Day($date = "now")
	{
		if ($date == "now") {
			return date("d");
		}		
		return date("d",strtotime($date));		
	}
	public function Yesterday($date = "now")
	{
		if ($date == "now") {
			return Date::AddDay(Date::Today(),-1);
		}		
		return Date::AddDay($date,-1);
	}
	public function Today()
	{
		return date("Y-m-d");
	}	
	
	public function Format($date,$format = "Y-m-d"){
		return date($format,strtotime($date));		
	}
	public function Display($date,$replace = "-") {
		if (empty($date) || $date == "0000-00-00" || $date == "0000-00-00 00:00:00")return $replace;
		if ($date == "1900-01-01" || $date == "1900-01-01 00:00:00")return "NA";
		if ($date == "1900-01-02" || $date == "1900-01-02 00:00:00")return "DK";
		return $date;
	}
}
?>