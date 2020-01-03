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

if( $u->info['admin'] == 0) {
	die('<meta http-equiv="refresh" content="0; URL=http://xcombats.com/">');
}

if(isset($_GET['id_dn'])) {
	$_POST['id_dn'] = $_GET['id_dn'];
	$_POST['xx'] = $_GET['xx'];
	$_POST['yy'] = $_GET['yy'];
}

if( $_POST['new_bot_colvo'] < 1 ) {
	$_POST['new_bot_colvo'] = 1;
}

?>
<form method="post" action="?gotonew">
id пещеры: <input name="id_dn" value="<?=$_POST['id_dn']?>"><br>
x : <input name="xx" value="<?=$_POST['xx']?>"><br>
y : <input name="yy" value="<?=$_POST['yy']?>"><br>
<input type="submit" value="Перейти">
</form>
--------------- Боты -------------:<br>
<? 

if(isset($_POST['new_bot_id'])) {
	mysql_query('INSERT INTO `dungeon_bots` (`id_bot`,`bot_group`,`for_dn`,`colvo`,`x`,`y`,`atack`,`go_bot`) VALUES ("'.mysql_real_escape_string($_POST['new_bot_id']).'","'.mysql_real_escape_string($_POST['new_bot_group']).'","'.mysql_real_escape_string($_POST['id_dn']).'","'.mysql_real_escape_string($_POST['new_bot_colvo']).'", "'.mysql_real_escape_string($_POST['xx']).'","'.mysql_real_escape_string($_POST['yy']).'","1","'.mysql_real_escape_string($_POST['new_bot_go']).'")');
}elseif(isset($_GET['delete'])) {
	mysql_query('DELETE FROM `dungeon_bots` WHERE `id2` = "'.mysql_real_escape_string($_GET['delete']).'" LIMIT 1');
}

$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `dn` = "0" AND `for_dn` = "'.mysql_real_escape_string($_POST['id_dn']).'" AND `x` = "'.mysql_real_escape_string($_POST['xx']).'" AND `y` = "'.mysql_real_escape_string($_POST['yy']).'" ORDER BY `id2`');
$i = 1;
while($pl = mysql_fetch_array($sp)) {
	 if( $pl['id_bot'] > 0 ){
		  $bot = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$pl['id_bot'].'" LIMIT 1'));
		  echo $i.'.['.$pl['id2'].'] <b>'.$bot['login'].'</b> [id '.$bot['id'].'] , [x'.$pl['colvo'].'] <a href="?delete='.$pl['id2'].'&id_dn='.$pl['for_dn'].'&xx='.$pl['x'].'&yy='.$pl['y'].'">удалить</a>  <br>';
	 } elseif( $pl['bot_group'] != '' ){
		  $bots = explode( ',', $pl['bot_group'] );
		  $jjj=0;
		  echo $i.' .['.$pl['id2'].']';
		  while( $jjj < count($bots) ){
			   $bot = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$bots[$jjj].'" LIMIT 1'));
			   if( isset($bot['login']) ) echo ' <strong>'.$bot['login'].'</strong> [id '.$bot['id'].'], ';
			   $jjj++;
		  }
		  echo ' [x'.$pl['colvo'].'] <a href="?delete='.$pl['id2'].'&id_dn='.$pl['for_dn'].'&xx='.$pl['x'].'&yy='.$pl['y'].'">удалить</a><br/>';
	 }
	$i++;
}

echo '<hr>';
?>
<form method="post" action="?gotonew">
Добавить нового:<br />
<input type="hidden" name="id_dn" value="<?=$_POST['id_dn']?>">
<input type="hidden" name="xx" value="<?=$_POST['xx']?>">
<input type="hidden" name="yy" value="<?=$_POST['yy']?>">
ID бота: <input name="new_bot_id" value="<?=$_POST['new_bot_id']?>"><br />
Случайные боты: <input name="new_bot_group" value="<?=$_POST['new_bot_group']?>"><br />
Количество: <input name="new_bot_colvo" value="<?=(0+$_POST['new_bot_colvo'])?>"><br />
Перемещается: <input name="new_bot_go" value="<?=(0+$_POST['new_bot_go'])?>"> (0 - нет, 1 - да)<br />
<input type="submit" value="Отправить" />
</form>