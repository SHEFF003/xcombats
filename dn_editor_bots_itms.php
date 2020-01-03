<?php
session_start();

function er($e)
{
	 global $c;
	 die('<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta http-equiv="Content-Language" content="ru"><TITLE>Произошла ошибка</TITLE></HEAD><BODY text="#FFFFFF"><p><font color=black>Произошла ошибка: <pre>'.$e.'</pre><b><p><a href="http://'.$c[0].'/">Назад</b></a><HR><p align="right">(c) <a href="http://'.$c[0].'/">'.$c[1].'</a></p></body></html>');
}

function GetRealIp()
{
	if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

define('IP',GetRealIp());
define('GAME',true);

include_once('_incl_data/__config.php');
include_once('_incl_data/class/__db_connect.php');
include_once('_incl_data/class/__user.php');

if($u->info['admin'] == 0) {
	die('<meta http-equiv="refresh" content="0; URL=http://xcombats.com/">');
}

?>
<form method="post" action="?gotonew">
id бота: <input name="id_bot" value="<?=$_POST['id_bot']?>"><br>
<input type="submit" value="Перейти">
</form>
--------------- Бот -------------<br>
<?
$pl = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.mysql_real_escape_string($_POST['id_bot']).'" LIMIT 1'));
if(isset($pl['id'])) {
	
	if(isset($_GET['save'])) {
		
		mysql_query('UPDATE `test_bot` SET
			
			`login` = "'.mysql_real_escape_string($_POST['login']).'",
			`obraz` = "'.mysql_real_escape_string($_POST['obraz']).'",
			`level` = "'.mysql_real_escape_string($_POST['level']).'",
			`sex` = "'.mysql_real_escape_string($_POST['sex']).'",
			`name` = "'.mysql_real_escape_string($_POST['name']).'",
			`agressor` = "'.mysql_real_escape_string($_POST['agressor']).'",
			`expB` = "'.mysql_real_escape_string($_POST['expB']).'",
			`stats` = "'.mysql_real_escape_string($_POST['stats']).'",
			`itemsUse` = "'.mysql_real_escape_string($_POST['itemsUse']).'",
			`p_items` = "'.mysql_real_escape_string($_POST['p_items']).'" WHERE `id` = "'.$pl['id'].'"
			
		LIMIT 1');
		
		$pl = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.mysql_real_escape_string($_POST['id_bot']).'" LIMIT 1'));
		
	}
	
?>
<form method="post" action="?gotonew&save"><input type="hidden" name="id_bot" value="<?=$_POST['id_bot']?>">
Логин: <input name="login" value="<?=$pl['login']?>"><br />
Образ: <input name="obraz" value="<?=$pl['obraz']?>"><br />
Уровень: <input name="level" value="<?=$pl['level']?>"><br />
Пол: <input name="sex" value="<?=$pl['sex']?>"> (0 - муж. , 1 - жен.)<br />
Имя: <input name="name" value="<?=$pl['name']?>"><br />
Агрессия: <input name="agressor" value="<?=$pl['agressor']?>"> (0-100%)<br />
Опыт (%): <input name="expB" value="<?=$pl['expB']?>"><br />
Статы:<br />
<textarea rows="5" cols="75" name="stats"><?=$pl['stats']?></textarea>
<br />
Предметы (на боте, 2605 - использует приемы. Перечислять чреез запятую id предмета):<br />
<textarea rows="5" cols="75" name="itemsUse"><?=$pl['itemsUse']?></textarea>
<br />
Предметы которые падают с бота:<br />
<textarea rows="5" cols="75" name="p_items"><?=$pl['p_items']?></textarea>
<br><input type="submit" value="Сохранить"></form>
<?
}elseif(isset($_POST['id_bot'])) {
	echo 'Бот не найден';
}
?>