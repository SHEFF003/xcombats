<?php
if(!isset($_GET['mmm'])) {	
	die('���� ����, � �� e-mail!');
}
$dbgo = mysql_connect('localhost','�����','������');
mysql_select_db('��� ����',$dbgo);
mysql_query('SET NAMES cp1251');
$sp = mysql_query('SELECT * FROM `users`');
while( $pl = mysql_fetch_array($sp) ) {
	echo $pl['mail'] . '<br>';
}
?>