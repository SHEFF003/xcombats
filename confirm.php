<?php
include('_incl_data/__config.php');
?>
<HTML><HEAD>
<link rel=stylesheet type="text/css" href="http://img.xcombats.com/css/main.css">
<meta content="text/html; charset=utf-8" http-equiv=Content-type>
<META Http-Equiv=Cache-Control Content=no-cache>
<meta http-equiv=PRAGMA content=NO-CACHE>
<META Http-Equiv=Expires Content=0>
<TITLE>Бойцовский Мир. Подтверждение операции через e-mail.</TITLE>
</HEAD>
<body leftmargin=10 topmargin=20 marginwidth=0 bgcolor=e2e0e0>
<?
			if ($_GET['id']!='' && $_GET['code']!='') {
				define('GAME',true);
				include ("_incl_data/class/__db_connect.php");
				$confirm = mysql_query("SELECT * FROM `emailconfirmation` WHERE `id` = '".mysql_real_escape_string($_GET['id'])."' AND `code` = '".mysql_real_escape_string($_GET['code'])."' AND (`active` = '1' OR `pass` = '1' OR `email` = '1' OR `qu_an` = '1')");
				if (mysql_num_rows($confirm) == 0 or mysql_num_rows($confirm) == '') die("<br><br><br><h3>Ссылка устарела!</h3>");
				$confirm = mysql_fetch_array($confirm,MYSQL_ASSOC) or die("Ошибка обработки запроса!!");
				if($confirm['active'] == 1) {
				mysql_query("UPDATE `users` SET `emailconfirmation` = '1', `securetime` = '".(time()+259200)."' WHERE `id` = '".mysql_real_escape_string($confirm['id'])."'");
				echo "<br><br><br><h3>Подтверждение смены пароля/email через почту включено</h3>";
				}elseif($confirm['pass'] == 1) {
				mysql_query("UPDATE `users` SET `pass` = '".mysql_real_escape_string($confirm['pa_em'])."', `securetime` = '".(time()+259200)."' WHERE `id` = '".mysql_real_escape_string($confirm['id'])."'");
				echo "<br><br><br><h3>Удачно сменили пароль</h3>";
				}elseif($confirm['email'] == 1) {
				mysql_query("UPDATE `users` SET `email` = '".mysql_real_escape_string($confirm['pa_em'])."', `securetime` = '".(time()+259200)."' WHERE `id` = '".mysql_real_escape_string($confirm['id'])."'");
				echo "<br><br><br><h3>Удачно сменили email</h3>";				
				}elseif($confirm['qu_an'] == 1) {
				mysql_query("UPDATE `users` SET `a1` = '".mysql_real_escape_string($confirm['question'])."',`q1` = '".mysql_real_escape_string($confirm['answer'])."', `securetime` = '".(time()+259200)."' WHERE `id` = '".mysql_real_escape_string($confirm['id'])."'");
				echo "<br><br><br><h3>Удачно сменили секретный вопрос / ответ</h3>";				
				}
				mysql_query("DELETE FROM `emailconfirmation` WHERE `id` = '".mysql_real_escape_string($_GET['id'])."' AND `code` = '".mysql_real_escape_string($_GET['code'])."'");
			}else{?>
<FORM>
<h4>Подтверждение операции через e-mail</h4>
Введите код: <INPUT type=text name='entcode' value='' size=40><INPUT type=submit value="Готово">
</FORM>		
<?}?>

</BODY>
</HTML>