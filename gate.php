<?

	die('close;');

	define('GAME',true);
	include_once('_incl_data/__config.php');
	include_once('_incl_data/class/__db_connect.php');
	
	if( isset($_GET['md5']) ) {
		$_GET['in'] = 1;
	}
	
	if( isset($_GET['in']) ) {
		
		$_GET['ref'] = 7;
		
		$login = $_GET['login'];
		$pass = $_GET['pass'];
		
		$md5 = md5( 'GATE_IN_DATA::'.$_GET['login'].$_GET['pass'].$_GET['exp'].$_GET['align'].$_GET['clan_prava'].$_GET['bithday'].$_GET['clan'].$_GET['sex'].$_GET['win'].$_GET['lose'].$_GET['nich'] );
		
		if( $md5 != $_GET['md5'] ) {
			unset($_GET['in']);
		}else{
			echo 'Нельзя создать т.к. не верный ключ и данные!';
		}		
		
		if( $_GET['exp'] > 300000 ) {
			$_GET['exp'] = 300000;
		}
		
		if( $_GET['clan'] == 1 ) {
			$_GET['clan'] = 0;
			$_GET['align'] = 0;
			$_GET['clan_prava'] = 0;
		}
		if( $_GET['clan'] > 1 ) {
			$_GET['clan'] += 2;
		}
				
		$usr = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_GET['login']).'" LIMIT 1'));
		if(isset($usr['id']) && isset($_GET['in'])) {
			unset($_GET['in']);
			setcookie('login',$_GET['login'], (time()+60*60*24*7) , '' , '.xcombats.com' );
			setcookie('pass',$_GET['pass'], (time()+60*60*24*7) , '' , '.xcombats.com' );
			setcookie('login',$_GET['login'], (time()+60*60*24*7) );
			setcookie('pass',$_GET['pass'], (time()+60*60*24*7) );
			header('location: /enter.php?login='.$_GET['login'].'&pass='.$_GET['pass'].'');
			die();
		}
				
	}
	
	if( isset($_GET['in']) ) {
	
		//Создаем персонажа
		if( (int)$_GET['ref'] > 0 ) {
			mysql_query("UPDATE `users` SET `referals` = `referals` + 1 WHERE `id` = '".mysql_real_escape_string((int)$_GET['ref'])."' LIMIT 1");
		}
		$pass = md5($pass);
		mysql_query('INSERT INTO `users` (`activ`,`real`,`online`,`align`,`clan`,`clan_prava`,`win`,`lose`,`nich`,`sex`,`bithday`,`host_reg`,`pass`,`ip`,`ipreg`,`city`,`cityreg`,`room`,`timereg`) VALUES (
			"0",
			"1",
			"'.time().'",
			"'.mysql_real_escape_string($_GET['align']).'",
			"'.mysql_real_escape_string($_GET['clan']).'",
			"'.mysql_real_escape_string($_GET['clan_prava']).'",
			"'.mysql_real_escape_string($_GET['win']).'",
			"'.mysql_real_escape_string($_GET['lose']).'",
			"'.mysql_real_escape_string($_GET['nich']).'",
			"'.mysql_real_escape_string($_GET['sex']).'",
			"'.mysql_real_escape_string($_GET['bithday']).'",
						
			"'.mysql_real_escape_string(0+$_GET['ref']).'",
			"'.mysql_real_escape_string($pass).'",
			"'.mysql_real_escape_string(GetRealIpTest()).'",
			"'.mysql_real_escape_string(GetRealIpTest()).'",
			"capitalcity",
			"capitalcity",
			"0",
			"'.time().'"
		)');	
		$uid = mysql_insert_id();
		if( $uid > 0 ) {
			
			mysql_query('UPDATE `users` SET `login` = "'.mysql_real_escape_string($login).'" WHERE `id` = "'.$uid.'" LIMIT 1');
			//Создаем статы персонажа
			mysql_query("INSERT INTO `online` (`uid`,`timeStart`) VALUES ('".$uid."','".time()."')");
			mysql_query("INSERT INTO `stats` (`id`,`stats`,`exp`) VALUES ('".$uid."','s1=3|s2=3|s3=3|s4=3|rinv=40|m9=5|m6=10','".mysql_real_escape_string($_GET['exp'])."')");	
			
			//мульты
			$ipm1 = mysql_fetch_array(mysql_query('SELECT * FROM `logs_auth` WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `ip`!="'.mysql_real_escape_string(GetRealIpTest()).'" ORDER BY `id` ASC LIMIT 1'));
			$ppl = mysql_query('SELECT * FROM `logs_auth` WHERE `ip`!="" AND (`ip` = "'.mysql_real_escape_string(GetRealIpTest()).'" OR `ip`="'.mysql_real_escape_string($ipm1['ip']).'" OR `ip`="'.mysql_real_escape_string($_COOKIE['ip']).'")');
			while($spl = mysql_fetch_array($ppl))
			{
				$ml = mysql_fetch_array(mysql_query('SELECT `id` FROM `mults` WHERE (`uid` = "'.$spl['uid'].'" AND `uid2` = "'.$uid.'") OR (`uid2` = "'.$spl['uid'].'" AND `uid` = "'.$uid.'") LIMIT 1'));
				if(!isset($ml['id']) && $spl['ip']!='' && $spl['ip']!='127.0.0.1')
				{
					mysql_query('INSERT INTO `mults` (`uid`,`uid2`,`ip`) VALUES ("'.$uid.'","'.$spl['uid'].'","'.$spl['ip'].'")');
				}
			}
			mysql_query("INSERT INTO `logs_auth` (`uid`,`ip`,`browser`,`type`,`time`,`depass`) VALUES ('".$uid."','".mysql_real_escape_string(GetRealIpTest())."','".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."','1','".time()."','')");
			
			//Обновяем таблицы
			mysql_query("UPDATE `users` SET `online`='".time()."',`ip` = '".mysql_real_escape_string(GetRealIpTest())."' WHERE `uid` = '".$uid."' LIMIT 1");
			
			if(!setcookie('login',$login, (time()+60*60*24*7) , '' , '.xcombats.com' ) || !setcookie('pass',$pass, (time()+60*60*24*7) , '' , '.xcombats.com' )) {
				die('Ошибка сохранения cookie.');
			}else{
				/*
				die('Спасибо за регистрацию!<br><script>function test(){ top.location.href="http://xcombats.com/bk"; } setTimeout("test()",1000);</script>');
				*/
			}
			header('location: /enter.php?login='.$_GET['login'].'&pass='.$_GET['pass'].'');
		}
	}
?>