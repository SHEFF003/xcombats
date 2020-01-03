<?php
define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');

$html = '';
if(isset($_GET['detail'])) {
	echo '<a href="?">Вернуться</a> &nbsp; | | | | | | &nbsp;<b>'.$_GET['detail'].'</b><hr>';
	$sp = mysql_query('SELECT * FROM `antireflesh` WHERE `ip` = "'.mysql_real_escape_string($_GET['detail']).'" ORDER BY `id` DESC LIMIT 1000');
	while( $pl = mysql_fetch_array($sp) ) {
		$clr = '';
		if( $pl['time'] > time()-61 ) {
			$clr .= 'background-color:blue;color:white;';
		}
		$plu = mysql_fetch_array(mysql_query('SELECT `online`,`id`,`level`,`login`,`align`,`clan` FROM `users` WHERE `login` = "'.mysql_real_escape_string($pl['login']).'" LIMIT 1'));
		$html .= '<div style="padding:5px;'.$clr.'">';
		$html .= '['.date('d.m.Y H:i:s',$pl['time']).']- - -[#'.$pl['id'].']['.$pl['ip'].'] - - - ['.$u->microLogin($plu,2).'] - (__ &nbsp; '.$pl['page'].' &nbsp;__)';
		$html .= '</div>';
	}
}else{
	$sp = mysql_query('SELECT * FROM `antireflesh` WHERE `time` > "'.(time()-121).'" GROUP BY `ip`');
	while( $pl = mysql_fetch_array($sp) ) {
		$stl = '';
		$plu = mysql_fetch_array(mysql_query('SELECT `online`,`id`,`level`,`login`,`align`,`clan` FROM `users` WHERE `login` = "'.mysql_real_escape_string($pl['login']).'" LIMIT 1'));
		$plz = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `antireflesh` WHERE `ip` = "'.mysql_real_escape_string($pl['ip']).'" AND `time` > "'.(time()-61).'"'));
		//
		$plzc = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `users` WHERE `online` > "'.(time()-120).'" AND `ip` = "'.mysql_real_escape_string($pl['ip']).'" LIMIT 1'));
		//
		if( $plz[0] <= 31 ) {
			$stl .= 'background-color:green;color:white;';
		}elseif( $plz[0] < 61 ) {
			$stl .= 'background-color:yellow;';
		}else{
			$stl .= 'background-color:red;';
		}
		$html .= '<div style="padding:5px;'.$stl.'">';
		if( $u->info['admin'] > 0 ) {
			$html .= '[<a href="?detail='.$pl['ip'].'">'.$pl['ip'].'</a>]';	
		}
		$html .= ' - - - ['.$u->microLogin($plu,2).'] - - - - - [Запросов за последнюю минуту: '.$plz[0].'] - - - [персов онлайн: '.$plzc[0].'] [запросов в секунду: '.round($plz[0]/60).']';
		$html .= '</div>';
	}
	$xxx = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `antireflesh` WHERE `time` > "'.(time()-61).'" LIMIT 1'));
	$xxx = $xxx[0];
	$html .= '<hr>[Всего запросов за минуту: '.$xxx.']';
}
?>
<!doctype html>
<html>
<head>
<meta charset="windows-1251">
</head>
<body>
<?
echo $html;
?>
</body>
</html>