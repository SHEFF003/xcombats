<?php

die('Что-то тут не так...');

define('GAME',true);
include('_incl_data/class/__db_connect.php');

//рейтинг персонажей

$sp = mysql_query('SELECT * FROM `users_rating` ORDER BY `id` DESC');
while( $pl = mysql_fetch_array($sp) ) {
	echo '['.$pl['uid'].']<br>';
}

?>