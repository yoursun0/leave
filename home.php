<?php 
include_once('_database.php');
include_once('_inc/lib.user.php');
include_once('_inc/lib.sql.php');
$USER = new User($DB,$_SESSION);
$USER->CheckSession();
?>
<html><head>
	<meta content="text/html; charset=<?=Config::Charset?>" http-equiv="content-type">
	<link type="text/css" rel="stylesheet" href="_css/basic.css">
	<link type="text/css" rel="stylesheet" href="_css/jq.ui.css">	<!-- UI -->
	<link type="text/css" rel="stylesheet" href="_css/jq.ui2.css">	<!-- UI 2 -->
	<link type="text/css" rel="stylesheet" href="_css/leave.css">
	
	<title><?=Config::Title?></title>
</head><body>
<div id="divPageLoad" style="width: 100%;height: 100%; display:none;">
	<span style="position: absolute;top: 40%;left:0;width: 100%;text-align: center;font-weight: bold;font-size: 30px;">Loading ...</span>
</div>
<?php flush(); ?>
<iframe id="iPrint" src="print.html" style="border:0;width:0;height:0;left:0;top:0;position:absolute;"></iframe>
<iframe id="iFile" src="blank.html" style="display:none;"></iframe>
<!-- Application Variable -->
<form name="appForm" id="appForm" onsubmit="return false" style="display:none" action="">
	<input type="hidden" name="gModule" id="gModule" /><input type="hidden" name="gPages" id="gPages" />
</form>
<form name="mainForm" id="mainForm" onsubmit="return false" action="" style="margin:0;">
<!-- Header:Start -->
<table width="100%" summary="">
	<tr style="background-color:#052D00;height:20px">
    	<td style="white-space:nowrap"><a href="index.php" title="Back to home" onMouseOut="MM_swapImgRestore();" onMouseOver="MM_swapImage('btn_home','','_img/btn_home_1.png',1)">
			<img src="_img/btn_home.png" vspace="1" border="0" align="top" name="btn_home" alt="Home" /></a>
			<span style="color:#fff;font-size:14px;font-weight:bold;margin-top: 4px; position: absolute;">&nbsp;Welcome : <span style="color:#ff0;"><?=isset($USER) && $USER->Id > 0  ? $USER->Name : "&nbsp;" ?></span></span></td>
    	<td align="right" valign="middle">
    		<a href="javascript:;" onclick="PrintPages()"><img src="_img/print.png" alt="Print" border="0" align="top" vspace="2" /></a>&nbsp;	
    		<a href="login.php"><img src="_img/logout.gif" alt="Logout" border="0" align="top" vspace="2" /></a>&nbsp;
		</td>
  	</tr>
</table>
<!-- Header:End -->
<!-- Main:Start -->
<table width="100%" summary=""><tr>
	<td valign="top" bgcolor="#9C9C9C">
		<div class="ui-accordion" style="float:left;"  id="acc_menu"><?php include("_menu.php"); ?></div>
		<div class="ui-accordion" style="margin:0;padding:0;float:left;height:16px;background-image:url(_img/monthpicker/bg.png);"> </div>
	</td>
    <td width="100%"><table width="100%" summary="">
		<tr style="height:27px"><td class="module_top_left">&nbsp;</td><td><ul id='nav'><li>&nbsp;</li></ul></td><!--td class="module_top_right">&nbsp;</td--></tr>
		<tr><td class="module_mid_left">&nbsp;</td>
			<td valign="top"><div id="divPages" style="height:400px;width:100%;background-color:white;overflow:auto"><?php include('home.inc.php');?></div></td>
		<!--td class="module_mid_right">&nbsp;</td--></tr>
		<tr style="height:3px"><td colspan="2" class="module_mid_bottom"></td></tr>
	</table></td>
</tr></table>
<!-- Main:End -->
</form>
<!-- javascript -->
<?php
$jsPath = array(
	"_js/_sys.min/jquery-1.2.3.js",
	"_js/_sys.min/jq.dimensions.js",
	"_js/_sys.min/jq.forms.js",
	"_js/_sys/jq.splitter.js",
	
	"_js/_sys.min/jq.ui-personalized-1.5.1.js",
	"_js/_sys.min/jq.droppy.js",
	"_js/_sys.min/jq.selectboxes.js",
	"_js/_sys.min/jq.treeview.js",
	"_js/_sys.min/jq.simple.tree.js",
	"_js/_sys.min/jq.contextmenu.r2.js",
	"_js/_sys.min/jq.tablesorter.js",
	"_js/_sys.min/jq.blockui.js",
	"_js/_sys.min/jq.thickbox.js",
	"_js/_sys.min/jq.ajaxfileupload.js",
	
	"_js/_sys.min/json2.js",
	"_js/_sys.min/jq.jqGrid.DnR.js",
	"_js/_sys.min/jq.jqGrid.Modal.js",
	"_js/_sys.min/jq.jqGrid.base.js",
	"_js/_sys/jq.jqGrid.formedit.js",
//	<!--script type="text/javascript" src="_js/_sys/jq.contextmenu.r2.js"></script-->
//	<!--script type="text/javascript" src="_js/_sys/jq.jqGrid.inlinedit.js"></script-->
//	<!--script type="text/javascript" src="_js/_sys/jq.jqGrid.postext.js"></script-->
//	<!--script type="text/javascript" src="_js/_sys/jq.jqGrid.subgrid.js"></script-->
	"_js/_tiny_mce/tiny_mce.js",
	"_js/_sys.min/jq.tiny_mce.js",
	
	"_js/_sys/jq.monthpicker.js",
	"_js/_sys.min/jq.toggleElements.js",
	
	"_js/_sys/lib.basic.js",
	"_js/_sys.min/lib.validation.js",
	"_js/_sys.min/lang.eng.js",
	"_js/_sys/FieldValidation.js"
);
foreach ($jsPath as $path){
	echo "<script type=\"text/javascript\" src=\"$path\"></script>";
}
flush();
?>
<script type="text/javascript">
var P_JQ_BODY;
var P_JQ_DIV_PAGES;
var p_jq_selected_module_link;
var p_onResize = false;
$(function($){
	P_JQ_BODY = $('body');
	P_JQ_DIV_PAGES = $('#divPages');
	$('#acc_menu').accordion();
	$(".LeftMenu li").mouseover(function(){$(this).addClass("over")}).mouseout(function(){$(this).removeClass("over")});
	$().ajaxStart(function(){setTimeout(function(){$.blockUI();},0);})
	.ajaxStop(function(){setTimeout(function(){$.unblockUI();},0);})
	.ajaxError(function(){setTimeout(function(){$.unblockUI();},0);});
	WindowResize();
	$("#divPageLoad").remove();
	<?
	$sql = "select count(*) from ac_users where user_id='".$USER->Id."' and md5(user_login) = user_pw";
	if(intval(Q::GetOne($sql)) > 0){
		echo "OpenModule('sys_myaccount','home');\n";
		echo "alert(\"Please change your login password.\");\n";
	} elseif ($USER->CheckPages("ot","ot_approve") == true){
		echo "OpenModule('ot','ot_approve');\n";
	} elseif($USER->CheckPages("ot","ot_processing") == true) {
		echo "OpenModule('ot','ot_processing');\n";
	}
?>

});
$(window).resize(WindowResize);
function SelectModule(obj){
	if(p_jq_selected_module_link){p_jq_selected_module_link.removeClass("selected");}
	p_jq_selected_module_link = $(obj).addClass("selected");
}

function WindowResize(){
    var h=P_JQ_BODY.height()-($.browser.msie?62:58);P_JQ_DIV_PAGES.css({height:(h<325?325:h)});
    if(p_onResize) p_onResize();
}


</script>
</body></html>