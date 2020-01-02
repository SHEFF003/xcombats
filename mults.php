<?php
header('Content-Type: text/html; charset=windows-1251');
		
define('GAME',true);
include('_incl_data/__config.php');	
include('_incl_data/class/__db_connect.php');	
include('_incl_data/class/__user.php');
	
if( $u->info['admin'] > 0 ) {
	$sp = mysql_query('SELECT * FROM `mults`');
	while( $pl = mysql_fetch_array($sp) ) {
		echo ''.$u->microLogin($pl['uid'],1).' пересечение с '.$u->microLogin($pl['uid2'],1).' <br>';
	}
}
?>