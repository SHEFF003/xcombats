<?php
define('GAME',true);
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__user.php');
//
if(isset($u->info['id'])) {
	$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `uid` = "'.$u->info['id'].'" AND (`val` = "vkauth" OR `val` = "fbauth" OR `val` = "okauth") LIMIT 1'));
	if(isset($mcf['id'])) {
		die('Вы уже подтвердили одну страницу в социальной сети.');
	}
}
if(!isset($u->info['id'])) {
	echo 'Вы не авторизовались в игре.';
}elseif(isset($_GET['vkconnect'])) {
	//
	require_once('vk/VK.php');
	require_once('vk/VKException.php');
	//
	$vk_config = array(
		'app_id'        => '5145826',
		'api_secret'    => 'V90yIzlgSglfgrnHw7Ny',
		'callback_url'  => 'http://xcombats.com/social.php?vkconnect',
		'api_settings'  => 'offline,friends,email'
	);
	$vk = new VK\VK($vk_config['app_id'], $vk_config['api_secret']);
	//
	echo '<center style="font-size:20px;"><br><br>';
	if(isset($_GET['error']) && $_GET['error'] == 'access_denied') {
		echo 'Вы отказались привязывать свой аккаунт в социальной сети ВКонтакте.';
	}else{
		$access_token = $vk->getAccessToken($_REQUEST['code'], $vk_config['callback_url']);
		if( $access_token['user_id'] > 0 ) {
			$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `val` = "vkauth" AND `ok` = "'.$access_token['user_id'].'" LIMIT 1'));
			$ubn = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `id` = "'.$mcf['uid'].'" AND `banned` = 0 LIMIT 1'));
			if(!isset($ubn['id'])) {
				unset($mcf);
			}
			if(isset($mcf['id'])) {
				echo 'Ваш аккаунт уже был привязан к одному из персонажей!';
			}else{
				$bank = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
				//
				if(!isset($bank['id'])) {
					echo 'id игрока: '.$u->info['id'].'<br>Откройте игровой банковский счет и попробуйте снова.';
				}else{
					mysql_query('UPDATE `bank` SET `money2` = `money2` + 1, `money1` = `money1` + 150 WHERE `id` = "'.$bank['id'].'" LIMIT 1');
					mysql_query('INSERT INTO `mini_actions` (`uid`,`time`,`val`,`var`,`var2`,`ok`) VALUES (
						"'.$u->info['id'].'","'.time().'","vkauth","'.mysql_real_escape_string($access_token['email']).'",
						"'.mysql_real_escape_string($access_token['access_token']).'","'.mysql_real_escape_string($access_token['user_id']).'"
					)');	
					//
					echo 'Ваш аккаунт успешно привязан! Спасибо!<br>На счет персонажа <b>'.$u->info['login'].'</b> зачислен 1 екр. и 150 кр.';
				}
			}
		}else{
			echo 'Информация не получена, попробуйте снова.';
		}
	}
	echo '<br><br>(Через 5 секунд окно автоматически закроется)';
	echo '</center>';
	echo '<script>window.opener.location.href=\'main.php?inv=1\';setTimeout("window.close();",5000);</script>';
	//
}elseif(isset($_GET['fbconnect'])) {
	//
	require_once('vk/FB.php');
	// Пример использования класса:
	session_start();
	if (!empty($_GET['error'])) {
		// Пришёл ответ с ошибкой. Например, юзер отменил авторизацию.
		die($_GET['error']);
	} elseif (empty($_GET['code'])) {
		// Самый первый запрос
		OAuthFB::goToAuth();
	} else {
		// Пришёл ответ без ошибок после запроса авторизации
	
		if (!OAuthFB::checkState($_GET['state'])) {
			die("The state does not match. You may be a victim of CSRF.");
		}
	
		if (!OAuthFB::getToken($_GET['code'])) {
			die('Error - no token by code');
		}
	
		$user = OAuthFB::getUser();
		if(isset($user->id)) {
			$mcf = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `val` = "fbauth" AND `ok` = "'.$user->id.'" LIMIT 1'));
			$ubn = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `id` = "'.$mcf['uid'].'" AND `banned` = 0 LIMIT 1'));
			if(!isset($ubn['id'])) {
				unset($mcf);
			}
			if(isset($mcf['id'])) {
				echo 'Ваш аккаунт уже был привязан к одному из персонажей!';
			}else{
				$bank = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" LIMIT 1'));
				//
				if(!isset($bank['id'])) {
					echo 'id игрока: '.$u->info['id'].'<br>Откройте игровой банковский счет и попробуйте снова.';
				}else{
					mysql_query('UPDATE `bank` SET `money2` = `money2` + 1, `money1` = `money1` + 150 WHERE `id` = "'.$bank['id'].'" LIMIT 1');
					mysql_query('INSERT INTO `mini_actions` (`uid`,`time`,`val`,`var`,`var2`,`ok`) VALUES (
						"'.$u->info['id'].'","'.time().'","fbauth","'.mysql_real_escape_string($user->id).'",
						"","'.mysql_real_escape_string($user->id).'"
					)');	
					//
					echo 'Ваш аккаунт успешно привязан! Спасибо!<br>На счет персонажа <b>'.$u->info['login'].'</b> зачислен 1 екр. и 150 кр.';
				}
			}
			echo '<br><br>(Через 5 секунд окно автоматически закроется)';
			echo '</center>';
			echo '<script>window.opener.location.href=\'main.php?inv=1\';setTimeout("window.close();",5000);</script>';
		}else{
			die('Не удалось получить данные вашей странички Facebook');
		}
		/*
		 * Вот и всё - мы узнали основные данные авторизованного юзера.
		 * $user в этом примере состоит из двух полей: id, name.
		 * Делайте с ними что угодно - регистрируйте, авторизуйте, ругайте...
		 */
	}
	//
}else{
	echo 'Мы не работает с подобной социальной сетью.';
}
?>