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

if(isset($_GET['mail'])) {
	$key = md5('mailconf*15+'.$_GET['mail']);
	if($_GET['key'] != $key) {
		echo 'Ключ не совпадает, напишите Администрации по E-mail: admin@xcombats.com';
	}elseif(isset($_GET['cancel'])) {
		//Отказ от рассылки
		echo 'Вы отказались от рассылки на эл.почту: <b>'.$_GET['mail'].'</b>.';
	}else{
		//
		$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `var` = "'.mysql_real_escape_string($_GET['mail']).'" LIMIT 1'));
		$user = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$mcf['uid'].'" LIMIT 1'));
		//Подписание на рассылку
		if(!isset($user['id'])) {
			echo 'Зарегистрируйтесь чтобы привязать эл.почту <b>'.$_GET['mail'].'</b> к персонажу.';
		}else{
			$bank = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$mcf['uid'].'" ORDER BY `id` DESC LIMIT 1'));
			if($mcf['ok'] > 0) {
				echo 'Вы уже подтверждали этот E-mail ранее! <b>'.date('d.m.Y H:i:s',$mcf['ok']).'</b>';
			}elseif(!isset($bank['id'])) {
				echo 'Сначала создайте счет в игровом банке, чтобы мы смогли перевести туда 1 екр.';
			}else{
				//
				mysql_query('UPDATE `mini_actions` SET `ok` = "'.time().'" WHERE `id` = "'.$mcf['id'].'" LIMIT 1');
				mysql_query('UPDATE `bank` SET `money2` = `money2` + 1 WHERE `id` = "'.$bank['id'].'" LIMIT 1');
				//
				echo 'Вы успешно подписались на рассылку новостей для эл.почты <b>'.$_GET['mail'].'</b>, на счет персонажа <b>'.$user['login'].'</b> зачислен 1 екр.';
			}
		}
	}
}else{
	echo 'E-mail не найден.';
}

echo '<br><br>- - - - - - -<br><br>С уважением,<br>Администрация СБК &copy; <a href="http://xcombats.com/">xcombats.com</a>';

?>