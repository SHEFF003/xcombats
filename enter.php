<?php
define('GAME',true);

function GetRealIp()
{
 if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
 {
   $ip=$_SERVER['HTTP_CLIENT_IP'];
 }
 elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
 {
  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
 }
 else
 {
   $ip=$_SERVER['REMOTE_ADDR'];
 }
 return $ip;
}

define('IP',GetRealIp());

include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__chat_class.php');

session_start();

if( isset($_GET['login'])) {
	$_POST['login'] = $_GET['login'];
	$_POST['pass'] = $_GET['pass'];
}

if( isset($_SESSION['login']) ) {
	$_POST['login'] = $_SESSION['login'];
	$_POST['pass'] = $_SESSION['pass'];
}

if( isset($_GET['cookie_login']) && $_GET['cookie_login'] != '' ) {
	setcookie('login',$_GET['cookie_login'],time()+60*60*24*7,'',$c['host']);
	setcookie('pass',$_GET['cookie_pass'],time()+60*60*24*7,'',$c['host']);
	header('location: /bk');
	die();
}

function error($e)
{
	 global $c;
	 die('<html><head>
	 <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	 <meta http-equiv="Content-Language" content="ru"><TITLE>Произошла ошибка</TITLE></HEAD>
	 <BODY text="#FFFFFF"><p><font color=black>
	 Произошла ошибка: <pre>'.$e.'</pre><b><p><a onClick="window.history.go(-1); return false;" href="#">Назад</b></a><HR>
	 <p align="right">(c) <a href="http://'.$c['host'].'/">'.$c['name'].'</a></p>
	 <!--Rating@Mail.ru counter--><!--// Rating@Mail.ru counter-->
	 </body></html>');
}

$socauth = false;

if(isset($_GET['vk-auth'])) {
	
    $client_id = '5145826'; // ID приложения
    $client_secret = 'V90yIzlgSglfgrnHw7Ny'; // Защищённый ключ
    $redirect_uri = 'http://xcombats.com/enter?vk-auth'; // Адрес сайта

    $url = 'http://oauth.vk.com/authorize';

    $params = array(
        'client_id'     => $client_id,
        'redirect_uri'  => $redirect_uri,
        'response_type' => 'code'
    );

	if(isset($_GET['code'])) {
		$result = false;
		$params = array(
			'client_id' => $client_id,
			'client_secret' => $client_secret,
			'code' => $_GET['code'],
			'redirect_uri' => $redirect_uri
		);
			
		$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
		
		if (isset($token['access_token'])) {
			$params = array(
				'uids'         => $token['user_id'],
				'fields'       => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
				'access_token' => $token['access_token']
			);
	
			$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
			if (isset($userInfo['response'][0]['uid'])) {
				$userInfo = $userInfo['response'][0];
				$result = true;
			}
		}
			
		if(isset($userInfo['uid'])) {
			$scl = mysql_fetch_array(mysql_query('SELECT * FROM `mini_actions` WHERE `val` = "vkauth" AND `ok` = "'.mysql_real_escape_string($userInfo['uid']).'" ORDER BY `time` DESC LIMIT 1'));
			if(isset($scl['id'])) {
				$scl = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$scl['uid'].'" LIMIT 1'));
				$_POST['login'] = $scl['login'];
				$socauth = true;
			}else{
				error('Ваш аккаунт ВКонтакте#'.$userInfo['uid'].' не прикреплен ни к одному из персонажей.');
			}
		}else{
			error('Неудалось авторизироваться через социальную сеть ВКонтакте');
		}
	}
}

$u = mysql_fetch_array(mysql_query('SELECT `u`.`pass2`,`u`.`id`,`u`.`auth`,`u`.`login`,`u`.`pass`,`u`.`city`,`u`.`ip`,`u`.`ipreg`,`u`.`online`,`u`.`banned`,`u`.`admin`,`u`.`host_reg`,`u`.`securetime`,`u`.`timereg` FROM `users` AS `u` WHERE `u`.`login`="'.mysql_real_escape_string($_POST['login']).'" ORDER BY `id` ASC LIMIT 1'));

$auth = mysql_fetch_array(mysql_query('SELECT * FROM `logs_auth` WHERE `uid` = "'.$u['id'].'" AND `ip` = "'.mysql_real_escape_string(IP).'" LIMIT 1'));
if( $c['securetime'] > 0 && IP != $u['ip'] && IP != $u['ipreg'] && !isset($auth['id']) && $u['securetime'] < $c['securetime'] && $u['timereg'] < $c['securetime'] ) {
	error('Вы не можете войти на персонажа "'.$_POST['login'].'".<br>Скорее всего вы давно не меняли пароль. Для смены перейдите по ссылке: <a href="/repass.php?login='.htmlspecialchars($_POST['login'],NULL,'cp1251').'">СМЕНА ПАРОЛЯ</a><br><br>Вам необходимо сменить пароль для безопасности персонажа, на почту по которой зарегистрирован персонаж придет новый случайно сгенерированный пароль.<br>Если у вас нет доступа к E-mail: Зарегистрируйте нового персонажа и обратитесь к Администрации, либо модераторам.');
}


/*if($u['host_reg'] == 'r-bk.com' && $u['online'] == 0) {
	$_POST['pass'] = md5($_POST['pass']);
	if($u['pass'] == md5($_POST['pass'])) {
		$u['pass'] = $_POST['pass'];
		mysql_query('UPDATE `users` SET `pass` = "'.mysql_real_escape_string($_POST['pass']).'",`online` = "'.time().'" WHERE `id` = "'.mysql_real_escape_string($u['id']).'" LIMIT 1');	
		error('Войдите с главной странице еще раз. Пароль был расшифрован.');
	}
}*/

if( md5(md5($_POST['pass'])) == $u['pass'] ) {
	$_POST['pass'] = md5($_POST['pass']);
}

if(!isset($u['id']))
{
	error('Логин "'.$_POST['login'].'" не найден в базе.');
}elseif($u['pass']!=md5($_POST['pass']) && $socauth == false)
{
	error('Неверный пароль к персонажу "'.$_POST['login'].'".');
	mysql_query("INSERT INTO `logs_auth` (`country`,`uid`,`ip`,`browser`,`type`,`time`,`depass`) VALUES ('".mysql_real_escape_string($_SERVER["HTTP_CF_IPCOUNTRY"])."','".$u['id']."','".mysql_real_escape_string(IP)."','".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."','3','".time()."','".mysql_real_escape_string($_POST['pass'])."')");
}elseif($u['banned']>0)
{
	$fm = mysql_fetch_array(mysql_query('SELECT * FROM `users_delo` WHERE `uid` = "'.$u['id'].'" AND `hb`!=0 ORDER BY `id` DESC LIMIT 1'));
	if(!isset($fm['id'])) {
		$fm['text'] = 'Причина блокировки: <i>Причина пока-что не указана.</i>';
	}
	error(
		'Персонаж <b>'.$_POST['login'].'</b> заблокирован.'.
		'<br>'.$fm['text'].'<br>'.
		'<br><b>Внимание!</b> Если Вы уверены, что произошла ошибка и Вы ничего не нарушали, оставьте, пожалуйста, вашу заявку на <a href="mailto:support@xcombats.com">support@original.com</a>'.
		'<br>Перед тем как писать, <b>ВНИМАТЕЛЬНО</b> ознакомитесь с <a target="_blank" href="http://xcombats.com/lib/zakon/">действующими законами</a> и <a target="_blank" href="http://xcombats.com/lib/polzovatelskoe-soglashenie/">пользовательским соглашением</a>.'
		.'<br><br>Если Вы заблокированы правомерно, то у Вас есть шанс выйти из блока на платной основе (минимальная сумма 100 ЕКР, данная сумма идёт на баланс вашего игрового персонажа и внутри игры можно распоряжаться ею как угодно).'
	);
}else{
	
	//Второй пароль
	if( $u['pass2'] != '' && $u['pass2'] != '0' ) {
		$_SESSION['login'] = $_POST['login'];
		$_SESSION['pass'] = $_POST['pass'];
		$good2 = false;
		$koko = '';
		
		if( md5($_POST['code']) == $u['pass2'] ) {
			$good2 = true;
			unset($_SESSION['login'],$_SESSION['pass']);
		}else{
			$koko = 'Неверный второй пароль';
			setcookie('login','',time()-60*60*24,'',$c['host']);
			setcookie('pass','',time()-60*60*24,'',$c['host']);
			setcookie('login','',time()-60*60*24);
			setcookie('pass','',time()-60*60*24);
		}
		
		if( $koko != '' ) {
			$koko = '<font color="red"><b>'.$koko.'</b></font>';
		}
		if( $good2 == false ) {
?>
			<HTML><HEAD>
				<link rel=stylesheet type="text/css" href="http://img.vip-bk.ru/i/main.css">
				<meta content="text/html; charset=windows-1251" http-equiv=Content-type>
				<META Http-Equiv=Cache-Control Content=no-cache>
				<meta http-equiv=PRAGMA content=NO-CACHE>
				<META Http-Equiv=Expires Content=0>
				<TITLE>Второй пароль</TITLE>
			</HEAD>
			<body bgcolor=666666>
			<H3><FONT COLOR="black">Запрос второго пароля к персонажу.</FONT></H3>
			<?=$koko?>
			<div align="center">
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="100%" height="100%">
					<param name="movie" value="http://xcombats.com/psw2.swf" />
					<param name="quality" value="high" />
					<param name="wmode" value="transparent">
					<embed src="http://xcombats.com/psw2.swf"
						   quality="high"
						   type="application/x-shockwave-flash"
						   WMODE="transparent"
						   width="600"
						   height="250"
						   pluginspage="http://www.macromedia.com/go/getflashplayer" />
				</object>
			</div>
			</BODY>
			</HTML>
<?
			die();
		}
		
	}
	
	$st = mysql_fetch_array(mysql_query('SELECT * FROM `stats` WHERE `id`="'.$u['id'].'" LIMIT 1'));
	if(!isset($st['id']))
	{
		mysql_query("INSERT INTO `stats` (`id`,`stats`) VALUES ('".$u['id']."','s1=3|s2=3|s3=3|s4=3|rinv=40|m9=5|m6=10')");
	}
	$on = mysql_fetch_array(mysql_query('SELECT * FROM `online` WHERE `uid`="'.$u['id'].'" LIMIT 1'));
	if(!isset($on['id']))
	{
		mysql_query("INSERT INTO `online` (`uid`,`timeStart`) VALUES ('".$u['id']."','".time()."')");
	}
	if(isset($_COOKIE['login']) || isset($_COOKIE['pass']))
	{
		setcookie('login','',time()-60*60*24,'',$c['host']);
		setcookie('pass','',time()-60*60*24,'',$c['host']);
	}
	
	//мульты
	if($u['admin']==0)
	{
		$ipm1 = mysql_fetch_array(mysql_query('SELECT * FROM `logs_auth` WHERE `uid` = "'.mysql_real_escape_string($u['id']).'" AND `ip`!="'.mysql_real_escape_string($u['ip']).'" ORDER BY `id` ASC LIMIT 1'));
		$ppl = mysql_query('SELECT * FROM `logs_auth` WHERE `ip`!="" AND (`ip` = "'.mysql_real_escape_string($u['ip']).'" OR `ip`="'.mysql_real_escape_string($ipm1['ip']).'" OR `ip`="'.mysql_real_escape_string($u['ipreg']).'" OR `ip`="'.mysql_real_escape_string(IP).'" OR `ip`="'.mysql_real_escape_string($_COOKIE['ip']).'")');
		while($spl = mysql_fetch_array($ppl))
		{
			$ml = mysql_fetch_array(mysql_query('SELECT `id` FROM `mults` WHERE (`uid` = "'.$spl['uid'].'" AND `uid2` = "'.$u['id'].'") OR (`uid2` = "'.$spl['uid'].'" AND `uid` = "'.$u['id'].'") LIMIT 1'));
			if(!isset($ml['id']) && $spl['uid']!=$inf['id'] && $spl['ip']!='' && $spl['ip']!='127.0.0.1' && $spl['ip']!='188.120.246.101')
			{
				mysql_query('INSERT INTO `mults` (`uid`,`uid2`,`ip`) VALUES ("'.$u['id'].'","'.$spl['uid'].'","'.$spl['ip'].'")');
			}
		}
	}
	
	/*if( (int)date('m') == 2 ) {
		if( (int)date('d') >= 12 && (int)date('d') <= 14 ) {
			mysql_query('DELETE FROM `eff_users` WHERE `id_eff` = 365 AND `uid` = "'.$u['id'].'"');
			mysql_query('INSERT INTO `eff_users` (
				`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`
			) VALUES (
				"365","'.$u['id'].'","День Рождения Клуба","add_speedhp=200|add_speedmp=200|add_exp=200","47","'.time().'"
			)');
			$chat->send('',$u['room'],$u['city'],'',$u['login'],'В честь дня рождения проекта вы получаете эффект &quot;День Рождения Клуба&quot;! (Эффект обновляется каждый раз когда вы заходите на персонажа)',time(),6,0,0,0,1);
		}
	}*/
	
	if(isset($_COOKIE['ip']) && $_COOKIE['ip']!=IP)
	{
		mysql_query("INSERT INTO `logs_auth` (`country`,`uid`,`ip`,`browser`,`type`,`time`,`depass`) VALUES ('".mysql_real_escape_string($_SERVER["HTTP_CF_IPCOUNTRY"])."','".$u['id']."','".mysql_real_escape_string($_COOKIE['ip'])."','".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."','1','".time()."','".mysql_real_escape_string($_POST['pass'])."')");
	}
	
	setcookie('login',$_POST['login'],time()+60*60*24*7,'',$c['host']);
	setcookie('pass',$u['pass'],time()+60*60*24*7,'',$c['host']);
	setcookie('login',$_POST['login'],time()+60*60*24*7);
	setcookie('pass',md5($_POST['pass']),time()+60*60*24*7);
	setcookie('ip',IP,time()+60*60*24*150,'');
	
	if($u['online'] < time()-520) {
		$sp = mysql_query('SELECT `user` FROM `friends` WHERE `friend` = "'.$u['id'].'"');
		while( $pl = mysql_fetch_array($sp) ) {
			$usr = mysql_fetch_array(mysql_query('SELECT `id`,`online`,`login`,`city`,`room` FROM `users` WHERE `id` = "'.$pl['user'].'" LIMIT 1'));
			if( isset($usr['id']) && $usr['online'] > time()-600 ) {
				$chat->send('',$usr['room'],$usr['city'],'',$usr['login'],'Вас приветствует: <b>'.$u['login'].'</b>.',time(),6,0,0,0,1);
			}
		}
	}
	
	$apu = '';

	mysql_query('UPDATE `dump` SET `ver` = "1",`upd` = "2" WHERE `uid` = "'.$u['id'].'"');

	if($u['auth'] != md5($u['login'].'AUTH'.IP) || $_COOKIE['auth'] != md5($u['login'].'AUTH'.IP) || $u['auth']=='' || $u['auth']=='0')
	{		
		if($u['auth'] != '' && $u['auth'] != '0' && $u['ip'] != IP) {
			mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('1','capitalcity','0','','".$u['login']."','В предыдущий раз этим персонажем заходили с другого компьютера ".date('d.m.Y H:i',$u['online']).". (Предыдущий ip: %".$u['ip'].")','-1','6','0')");
		}
		$apu = "`auth` = '".md5($u['login'].'AUTH'.IP)."',";
		setcookie('auth',md5($u['login'].'AUTH'.IP),time()+60*60*24*365,'','xcombats.com');
	}
	
	if($u['repass'] == 0) {
		$ipnew = IP;
	}else{
		$ipnew = $u['ip'];
	}
	
	if($u['login'] != 'Tarkus') {
		mysql_query("INSERT INTO `logs_auth` (`country`,`uid`,`ip`,`browser`,`type`,`time`,`depass`) VALUES ('".mysql_real_escape_string($_SERVER["HTTP_CF_IPCOUNTRY"])."','".$u['id']."','".IP."','".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."','0','".time()."','".mysql_real_escape_string($_POST['pass'])."')");
	}
	mysql_query("UPDATE `users` SET ".$apu."`ip`='".$ipnew."',`dateEnter`='".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."',`online`='".time()."' WHERE `login` = '".mysql_real_escape_string($_POST['login'])."' AND `pass` = '".mysql_real_escape_string(md5($_POST['pass']))."' LIMIT 1");
	
	if(isset($_POST['active_code_key'])) {
		header('location: /active.php?code='.htmlspecialchars($_POST['active_code_key'],NULL,'cp1251'));
	}else{
		header('location: /bk');
	}
}
?>