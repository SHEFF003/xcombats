<?php

die('Проект закрыт на доработку. Вся информация и доступ будет в 17:00 по МСК.');

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

if(!defined('GAME'))
{
	die();
}

session_start();
if($_SESSION['tbl'] > time()) {
	unset(
		$_POST['buy'] , $_POST['item'] , $_GET['buy'] , $_GET['item']
	);
}else{
	$_SESSION['tbl'] = time() + 0.500;
}

$dbgo = mysql_pconnect('localhost','xcombats','');
mysql_select_db('xcombats',$dbgo);
mysql_query('SET NAMES cp1251');

if(!function_exists('GetRealIp')) {
	function GetRealIpTest(){
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			return $_SERVER['HTTP_CLIENT_IP'];
		else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		return $_SERVER['REMOTE_ADDR'];
	}
	$ipban = GetRealIpTest();
}else{
	$ipban = GetRealIp();
}

$ipbant = mysql_fetch_array(mysql_query('SELECT * FROM `block_ip` WHERE `ip` = "'.mysql_real_escape_string($ipban).'" OR `ip` = "'.mysql_real_escape_string($_COOKIE['ip']).'" LIMIT 1'));

$mtm = time();

/*if(isset($_COOKIE['login'])) {
	$test = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `antireflesh` WHERE `login` = "'.mysql_real_escape_string($_COOKIE['login']).'" AND `time` = "'.$mtm.'" LIMIT 1'));
	if($test[0] > 0) {
		//die();
	}
}

mysql_query('INSERT INTO `antireflesh` ( `page`,`login`,`time`,`ip` ) VALUES (
	"'.mysql_real_escape_string($_SERVER['REQUEST_URI']).'","'.mysql_real_escape_string($_COOKIE['login']).'","'.$mtm.'","'.mysql_real_escape_string($ipban).'"
) ');
*/
if(isset($ipbant['id']) || isset($_GET['ipban'])) {
	echo 'Ваш ip %<b>'.$ipban.'</b> заблокирован. Код блокировки: '.$ipban['id'].'<br>По всем возникшим вопросам обращайтесь по эл.почте: <a href="mailto:support@xcombats.com">support@xcombats.com</a>';
	die();
}
unset($ipbant);
?>