<?php
include_once('_database.php');
include_once('_inc/lib.forms.php');
include_once('_inc/lib.user.php');
include_once('_inc/lib.sql.php');
	
//clear session
session_unset();

//init. var
F::GetSubmit(array("loginName","passwd","msg"));

//try to login
if (!empty($loginName)) {
	$USER = new User($DB);
	if (!$USER->Login($loginName,$passwd)) {
		$msg = $USER->LastErrorMessage;
	}
}

//found session, redirect to home
if(isset($_SESSION['userid'])){
    header("Location: home.php");
   	exit;
}
?>
<html>
    <head>
        <meta content="text/html; charset=<?=Config::Charset?>" http-equiv="content-type">
        <!-- stylesheet -->
        <style type="text/css">
            * {
                font-family: Arial,Helvetica,sans-serif;
            }
            
            body {
                background-color: #fff;
                padding: 0px;
                margin: 0px;
                font-size: 12px;
            }
            
            #divMain {
                width: 80%;
            }
            
            #loginForm {
                display: block;
                width: 250px;
                height: 150px;
                padding: 12px;
                float: left;
            }
            
            #loginForm label {
                padding-top: 6px;
                display: block;
                margin-left: 2px;
                font-size: 16px;
                font-weight: bold;
                color: #666;
            }
            
            #loginName, #passwd {
                width: 95%;
                font-size: 16px;
                font-weight: bold;
            }
            
            #btnSubmit {
                margin: 10px;
                font-size: 16px;
                font-weight: bold;
            }
            
            #divMsg {
                background-color: #FEFF9F;
                padding: 6px;
                font-weight: bold;
                font-size: 14px;
            }
        </style>
        <title><?=Config::Title?></title>
    </head>
    <body>
        <table style="height:100%;width:100%" cellpadding="0" cellspacing="0" summary="">
            <tr>
                <td valign="top">
                    <img src="_img/logo.gif" align="top" alt="Logo" style="padding:3px">
                </td>
            </tr>
            <tr style="height:14px;background-image:url(_img/login/sd_top.gif)">
                <td>
                </td>
            </tr>
            <tr style="height:30px;background-color:#F5F5F5;">
                <td valign="top">
                    <?=empty($msg)? "&nbsp;" : "<div id='divMsg'>$msg</div>";?>
                </td>
            </tr>
            <tr style="height:100%;background-color:#F5F5F5;">
                <td align="center" valign="middle">
                    <table id="divMain" summary="">
                        <tr>
                            <td style="border-right:1px solid #ccc">
                                <p style="font-size:30px">
                                    <?=Config::LoginTitle?>
                                </p>
                            </td>
                            <td style="width:250px">
                                <form name="loginForm" id="loginForm" method="post" action="">
                                    <label for="loginName">
                                        Name
                                    </label>
                                    <?=F::Text("loginName",$loginName,0,20)?>
                                    <label for="passwd">
                                        Password
                                    </label>
                                    <?=F::Password("passwd","maxlength='20'")?>
                                    <div style="width:100%;text-align:center">
                                        <input id="btnSubmit" type="submit" value="Login"/>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr style="height:14px;background-image:url(_img/login/sd_bottom.gif)">
                <td>
                </td>
            </tr>
            <tr style="height:24px;">
                <td>
                </td>
            </tr>
        </table>
        <!-- javascript -->
        <script type="text/javascript" src="_js/_sys.min/jquery-1.2.3.js"></script>
        <script type="text/javascript" src="_js/_sys/lib.basic.js"></script>
        <script type="text/javascript">
            $(function(){
                if ($("#loginName").val() == "") setTimeout('$("#loginName").focus();', 0);
                else setTimeout('$("#passwd").focus();', 0);
            });
        </script>
    </body>
</html>
