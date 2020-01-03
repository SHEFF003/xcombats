<?php
session_start();

function er($e)
{
	 global $c;
	 die('<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><meta http-equiv="Content-Language" content="ru"><TITLE>Произошла ошибка</TITLE></HEAD><BODY text="#FFFFFF"><p><font color=black>Произошла ошибка: <pre>'.$e.'</pre><b><p><a href="http://'.$c[0].'/">Назад</b></a><HR><p align="right">(c) <a href="http://'.$c[0].'/">'.$c[1].'</a></p></body></html>');
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

if(!isset($u->info['id']) || $u->info['ip'] != IP || $u->info['admin'] == 0) {
	die('<meta http-equiv="refresh" content="0; URL=http://xcombats.com/">');
}

if(isset($_GET['id_dn'])) {
	$_POST['id_dn'] = $_GET['id_dn'];
	$_POST['xx'] = $_GET['xx'];
	$_POST['yy'] = $_GET['yy'];
	$_POST['botlogin'] = $_GET['botlogin'];
}

if( $_POST['new_bot_colvo'] < 1 ) {
	$_POST['new_bot_colvo'] = 1;
}

?>
<form method="post" action="?gotonew">
id пещеры: <input name="id_dn" value="<?=$_POST['id_dn']?>"><br>
botlogin: <input name="botlogin" value="<?=$_POST['botlogin']?>"><br>
<input type="submit" value="Перейти">
</form>
--------------- Боты -------------:<br>
<?
$sp = mysql_query('SELECT * FROM `test_bot` WHERE login LIKE `%Рубака Глубин [8]%`');
$i = 1;
while($pl = mysql_fetch_array($sp)) {
	//$bot = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$pl['id'].'" LIMIT 1'));
	//echo $i.'.['.$pl['id'].'] <b>[login:'.$bot['login'].']</b> [id '.$bot['id'].'] , [stats:'.$bot['stats'].'] '.$bot['obraz'].'&level='.$bot['level'].'<br>';
	echo "<br/>+<br/>";
	$i++;
}
?> 

</hr>
 