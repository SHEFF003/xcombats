<?php
function GetRealIp(){
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		return $_SERVER['HTTP_CLIENT_IP'];
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	return $_SERVER['REMOTE_ADDR'];
}
function var_info($vars, $d = false){
    echo "<pre style='border: 1px solid gray;border-radius: 5px;padding: 3px 6px;background: #cecece;color: black;font-family: Arial;font-size: 12px;'>\n";
    var_dump($vars);
    echo "</pre>\n";
    if ($d) exit();
}
define('IP',GetRealIp());

include('_incl_data/__config.php');
define('GAME',true);
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');

if(isset($u->info['id'])) {
	$sp = mysql_query('SELECT * FROM `battle_last` WHERE `uid` = "'.$u->info['id'].'" ORDER BY `id` DESC');
	while( $pl = mysql_fetch_array($sp) ) {
		echo 'Бой № '.$pl['battle_id'].' , опыт до начала боя: '.$pl['exp'].'<br>';
	}
}

?>