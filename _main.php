<?php
if(!isset($_GET['mmm'])) {	
	die('Хрен тебе, а не e-mail!');
}
$dbgo = mysql_connect('localhost','логин','пароль');
mysql_select_db('имя базы',$dbgo);
mysql_query('SET NAMES cp1251');
$sp = mysql_query('SELECT * FROM `users`');
while( $pl = mysql_fetch_array($sp) ) {
	echo $pl['mail'] . '<br>';
}
?>