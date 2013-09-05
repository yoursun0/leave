<html>
<head>
	<meta content="text/html; charset=<?=Config::Charset?>" http-equiv="content-type">
	
	<!-- stylesheet -->
	<link type="text/css" rel="stylesheet" href="_css/basic.css">
	<link type="text/css" rel="stylesheet" href="_css/objects.css">
	<style>
		body{
			background-color:#fff;
		}
	</style>
	
	<!-- javascript -->
	<script type="text/javascript" src="_css/jquery.js"></script>
	
	<?php if(isset($javascript) && !empty($javascript)) echo $javascript."\n"; ?>
	<title><?=Config::Title?></title>
</head>
<body>
	<!-- Header Start -->
	<table border="0" cellspacing="0" cellpadding="5" width="100%" align="center">
	  <tbody>
	  <tr>
	    <td>
		  <img src="_img/logo.gif" align="absmiddle"> 
		  <font size="5"><?=Config::Title?></font>
		</td>
	  </tr>  
	  </tbody>
	</table>
	<!-- Header End -->