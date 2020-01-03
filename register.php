<?php
/*header('location: reg.php');
die();*/
//30.05.2060 07:25:06

if(!isset($_GET['ref']) && isset($_COOKIE['ref_id'])) {
	$_GET['ref'] = $_COOKIE['ref_id'];
}

define('GAME',true);
include('_incl_data/__config.php');
include('_incl_data/class/__db_connect.php');
include('_incl_data/class/__chat_class.php');
include('_incl_data/class/__filter_class.php');

/*
if(isset($_GET['ekr_given'])) {
	$ekr = round(($_GET['ekr_given']/2),2);
	$id = $_GET['ekr_given_id'];
	$bnk = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
	$bnk2 = mysql_fetch_array(mysql_query('SELECT SUM(`shara`) FROM `bank` WHERE `uid` = "'.mysql_real_escape_string($bnk['uid']).'" LIMIT 1'));
	if($bnk2[0] > 0) {
		echo 'Деньги уже были переведены ранее, нельзя переводить несколько раз.';
	}else{
		mysql_query('UPDATE `bank` SET `shara` = `shara` + "'.mysql_real_escape_string($ekr).'", `moneyBuy` = `moneyBuy` + "'.mysql_real_escape_string($ekr).'",`money2` = `money2` + "'.mysql_real_escape_string($ekr).'" WHERE `id` = "'.mysql_real_escape_string($id).'" LIMIT 1');
		echo $ekr.' екр. (по новому курсу) передены в банк номер '.$bnk['id'].'';
	}
	die();
}
*/

if(isset($_GET['showcode'])) {
	include('show_reg_img/security.php');
	die();
}

if(!function_exists('GetRealIp')) {
	$ipban2 = GetRealIpTest();
}else{
	$ipban2 = GetRealIp();
}

$multer = 0;
$ppl = mysql_query('SELECT * FROM `logs_auth` WHERE `ip`!="" AND (`ip`="'.mysql_real_escape_string($ipban2).'" OR `ip`="'.mysql_real_escape_string($_COOKIE['ip']).'")');
while($spl = mysql_fetch_array($ppl)) {
	$multe = mysql_fetch_array(mysql_query('SELECT `id`,`timereg` FROM `users` WHERE `id` = "'.$spl['uid'].'" LIMIT 1'));
	if( $multe['timereg'] + 3600 > time() ) {
		$multer = $multe['timereg'] + 3600 - time();
	}
}

/* Регистрация AJAX */
if( isset($_POST['id']) && $multer == 0 ) {
	session_start();
	include('_incl_data/class/__reg.php');
	include('_incl_data/class/__user.php');
	$rt = '';
	//
	$gd = array( 0,0,0,0,0,0,0,0,0,0,0 );
	$reg_d = array(
		0 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['login']),NULL,'cp1251'),
		1 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['pass']),NULL,'cp1251'),
		2 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['pass2']),NULL,'cp1251'),
		3 => (int)$_POST['dd'],
		4 => (int)$_POST['mm'],
		5 => (int)$_POST['yy'],
		6 => (int)$_POST['sex'],
		7 => (int)$_POST['rules'],
		8 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['mail']),NULL,'cp1251'),
		9 => (int)$_POST['keycode'],
		10 => (int)$_POST['align']
	);
	//Проверка логина
	//Запрещенные логины
	$error = '';
	$good = 1;
	$nologin = array(0=>'ангел',1=>'angel',2=>'администрация',3=>'administration',4=>'Комментатор',5=>'Мироздатель',6=>'Мусорщик',7=>'Падальщик',8=>'Повелитель',9=>'Архивариус',10=>'Пересмешник',11=>'Волынщик',12=>'Лорд Разрушитель',13=>'Милосердие',14=>'Справедливость',15=>'Искушение',16=>'Вознесение');
					$blacklist = "!@#$%^&*()\+Ёё|/'`\"";
					$sr = '_-йцукенгшщзхъфывапролджэячсмитьбюё1234567890';
					$i = 0;
					while($i<count($nologin))
					{
						if(preg_match("/".$nologin[$i]."/i",$filter->mystr($reg_d[0])))
						{
							$error = 'Выберите, пожалуйста, другой ник.'; $_POST['step'] = 1; $i = count($nologin);
						}
						$i++;
					}
					$reg_d[0] = str_replace('  ',' ',$reg_d[0]);
					//Логин от 2 до 20 символов
					if(strlen($reg_d[0])>20) 
					{ 
						$error = 'Логин должен содержать не более 20 символов.'; $_POST['step'] = 1;
					}
					if(strlen($reg_d[0])<2) 
					{ 
						$error = 'Логин должен содержать не менее 2 символов.'; $_POST['step'] = 1;
					}
					//Один алфавит
					$er = $r->en_ru($reg_d[0]);
					if($er==true)
					{
						$error = 'В логине разрешено использовать только буквы одного алфавита русского или английского. Нельзя смешивать.'; $_POST['step'] = 1;
					}
					//Запрещенный символы
					if(strpos($sr,$reg_d[0]))
					{
						$error = 'Логин содержит запрещенные символы.'; $_POST['step'] = 1;
					}				
					//Персонажи в базе
					$log = mysql_fetch_array(mysql_query('SELECT `id` from `users` where `login`="'.mysql_real_escape_string($reg_d[0]).'" LIMIT 1'));
					$log2 = mysql_fetch_array(mysql_query('SELECT `id` from `lastNames` where `login`="'.mysql_real_escape_string($reg_d[0]).'" LIMIT 1'));
					if(isset($log['id']) || isset($log2['id']))
					{
						$error = 'Логин '.$reg_d[0].' уже занят, выберите другой.'; $_POST['step'] = 1;
					}
					//Разделители
					if(substr_count($reg_d[0],' ')+substr_count($reg_d[0],'-')+substr_count($reg_d[0],'_')>2)
					{
						$error = 'Не более двух разделителей одновременно (пробел, тире, нижнее подчеркивание).'; $_POST['step'] = 1;
					}
					$reg_d[0] = trim($reg_d[0],' ');	
					if($error != '') {
						$gd[0] = $error;
						$good = 0;
					}else{
						$gd[0] = 1;
					}
					//проверяем пароль
					$error = '';
					if(strlen($reg_d[1])<6 || strlen($reg_d[1])>30)
					{
						$error = 'Длина пароля не может быть меньше 6 символов или более 30 символов.'; $_POST['step'] = 2;
					}
					if($reg_d[1]!=$reg_d[2])
					{
						$error = 'В анкете пароль нужно ввести дважды, для проверки. Во второй раз вы его ввели неверно, будьте внимательнее.'; $_POST['step'] = 2;
					}
					if(preg_match('/'.$reg_d[0].'/i',$reg_d[1]))
					{
						$error = 'Пароль содержит элементы логина.'; $_POST['step'] = 2;
					}
					if( $reg_d[1] != $reg_d[2] ) {
						$error = 'Пароли не совпадают.'; $_POST['step'] = 2;
					}
					if($_POST['step']!=2)
					{
						$stp = 3; $noup = 0;
					}
					if($error != '') {
						$gd[1] = $error;
						$good = 0;
					}else{
						$gd[1] = 1;
					}
					
					//Проверка даты
					$error = '';
					$ddmmyy = array(
						'',
						'January',
						'February',
						'March',
						'April',
						'May',
						'June',
						'July',
						'August',
						'September',
						'October',
						'November',
						'December'
					);
					
					$tstd = date('d.m.Y',strtotime(''.$reg_d[3].' '.$ddmmyy[$reg_d[4]].' '.$reg_d[5].''));
					if( $reg_d[3] < 10 ) {
						$reg_d[3] = '0'.$reg_d[3];
					}
					if( $reg_d[4] < 10 ) {
						$reg_d[4] = '0'.$reg_d[4];
					}
					if( $tstd != ''.$reg_d[3].'.'.$reg_d[4].'.'.$reg_d[5].'' ) {
						$error = 'Ошибка в написании дня рождения.';
					}
					if($error != '') {
						$gd[2] = $error;
						$good = 0;
					}else{
						$gd[2] = 1;
					}
					
					if( $reg_d[7] != 1 ) {
						$error = 'Примите соглашение и дайте разрешение на возможность рассылки информации на ваш E-mail';
					}
					if($error != '') {
						$gd[3] = $error;
						$good = 0;
					}else{
						$gd[3] = 1;
					}
					
					$error = '';
					//проверяем e-mail
					if(strlen($reg_d[8])<6 || strlen($reg_d[8])>50)
					{
						$error = 'E-mail не может быть короче 6-х символов и длинее 50-ти.'; $_POST['step'] = 3;
					}
					
					if(!preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $reg_d[8]))
					{
						$error = 'Вы указали явно ошибочный E-mail.<br>'; $_POST['step'] = 3;
					}
					if($error != '') {
						$gd[4] = $error;
						$good = 0;
					}else{
						$gd[4] = 1;
					}
					
					$error = '';
					//проверяем ключа
					if($reg_d[9] != $_SESSION['code'])
					{
						$error = 'Неверно указан код подтверждения ['.$_SESSION['code'].']'; $_POST['step'] = 3;
					}
					
					if($error != '') {
						$gd[5] = $error;
						$good = 0;
					}else{
						$gd[5] = 1;
					}
	
	if( $good == 1 ) {
		if( $reg_d[6] == 2 ) {
			$reg_d[6] = 1;
		}else{
			$reg_d[6] = 0;
		}
		if( $reg_d[10] == 1 ) {
			$reg_d[10] = 1;
		}elseif( $reg_d[10] == 2 ) {
			$reg_d[10] = 3;
		}elseif( $reg_d[10] == 3 ) {
			$reg_d[10] = 7;
		}else{
			$reg_d[10] = 0;
		}
		//
		$reg_d[10] = 0;
		//
		//Регистрируем
		/*
		0 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['login']),NULL,'cp1251'),
		1 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['pass']),NULL,'cp1251'),
		2 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['pass2']),NULL,'cp1251'),
		3 => (int)$_POST['dd'],
		4 => (int)$_POST['mm'],
		5 => (int)$_POST['yy'],
		6 => (int)$_POST['sex'],
		7 => (int)$_POST['rules'],
		8 => htmlspecialchars(iconv('UTF-8', 'windows-1251', $_POST['mail']),NULL,'cp1251'),
		9 => (int)$_POST['keycode']
		*/
		//Создаем персонажа
		if($_POST['refu'] > 0) {
			$ref = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.mysql_real_escape_string($_POST['refu']).'" LIMIT 1'));
			if(isset($ref['id'])) {
				$_POST['ref'] = $ref['id'];
			}else{
				$_POST['ref'] = 0;
			}
			unset($ref,$_POST['refu']);
		}
		mysql_query('INSERT INTO `users` (`name`,`align`,`real`,`login`,`host_reg`,`pass`,`ip`,`ipreg`,`city`,`cityreg`,`room`,`timereg`,
		`activ`,`mail`,`bithday`,`sex`,`fnq`,`battle`
		) VALUES (
			"",
			"'.$reg_d[10].'",
			"1",
			"'.mysql_real_escape_string($reg_d[0]).'",
			"'.mysql_real_escape_string(0+(int)$_POST['ref']).'",
			"'.mysql_real_escape_string(md5($reg_d[1])).'",
			"'.mysql_real_escape_string(IP).'",
			"'.mysql_real_escape_string(IP).'",
			"capitalcity",
			"capitalcity",
			"0",
			"'.time().'",			
			"0",
			"'.mysql_real_escape_string($reg_d[8]).'",
			"'.mysql_real_escape_string($reg_d[3].'.'.$reg_d[4].'.'.$reg_d[5]).'",
			"'.mysql_real_escape_string($reg_d[6]).'",
			"0",
			"0"
		)');	
				
		$uid = mysql_insert_id();
		if( $uid > 0 ) {
				
			if(isset($_COOKIE['from'])) {
				mysql_query('INSERT INTO `from` (`type`,`ip`,`uid`,`time`,`val`) VALUES ( "0", "'.GetRealIpTest().'","'.$uid.'", "'.time().'", "'.mysql_real_escape_string($_COOKIE['from']).'" ) ');
			}	
			//Сообщения при регистрации от паладинов.
			$pal = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `align` > 1 AND `align` < 1.99 AND `online` > "'.(time()-120).'" ORDER BY `online` DESC LIMIT 1'));

			if(isset($pal['id'])) {
     			//отсылаем сообщение от $pal['login']
				$paltext = 'Приветствую '.$reg_d[0].'. Если у Вас возникли затруднения с игровым процессом, обращайтесь ко мне в любой момент!';
				mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`new`) VALUES ('capitalcity','0','".$pal['login']."','".$reg_d[0]."','".$paltext."','".time()."','3','0','1')");
			}else{
    			//Не знаю что делать, пусть будет просто приветствие :) 
				$text = 'Приветствую <b>'.$reg_d[0].'</b>. Если у Вас возникли затруднения с игровым процессом, обращайтесь к любому Паладину или игроку!';
				mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`new`) VALUES ('capitalcity','0','','".$reg_d[0]."','".$text."','".time()."','3','0','1')");
			}
				
			//$text = 'Приветствую <b>'.$reg_d[0].'</b>. Если у Вас возникли затруднения с игровым процессом, я всегда рад вам помочь!';
			//mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`new`) VALUES ('capitalcity','0','','".$reg_d[0]."','".$text."','".time()."','6','0','1')");
	
			//Рубаха
			$re = $u->addItem(1,$uid);
			if( $re > 0 ) {
				mysql_query('UPDATE `items_users` SET `gift` = "Мироздатель" WHERE `id` = "'.$re.'" LIMIT 1');
			}
			//Штаны
			$re = $u->addItem(73,$uid);
			if( $re > 0 ) {
				mysql_query('UPDATE `items_users` SET `gift` = "Мусорщик" WHERE `id` = "'.$re.'" LIMIT 1');
			}
			
			mysql_query("INSERT INTO `items_users` (`item_id`, `1price`, `2price`, `3price`, `4price`, `uid`, `use_text`, `data`, `inOdet`, `inShop`, `inGroup`, `delete`, `iznosNOW`, `iznosMAX`, `gift`, `gtxt1`, `gtxt2`, `kolvo`, `geniration`, `magic_inc`, `maidin`, `lastUPD`, `timeOver`, `overType`, `secret_id`, `time_create`, `time_sleep`, `dn_delete`, `inTransfer`, `post_delivery`, `lbtl_`, `bexp`, `so`, `blvl`, `pok_itm`, `btl_zd`) VALUES
			 (4737, 1.00, 0.00, 0.00, 0.00, ".$uid.", 0, 'moment=1|moment_mp=250|nohaos=1|musor=2|noremont=1|fromshop=1|sudba=1|nostransfer=1', 0, 0, 0, 0, 0.00, 100.0000, '', '', '', 1, 2, 'elicsir_mp -w500', 'capitalcity', ".time().", 0, 0, '', ".time().", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);");
			mysql_query("INSERT INTO `items_users` (`item_id`, `1price`, `2price`, `3price`, `4price`, `uid`, `use_text`, `data`, `inOdet`, `inShop`, `inGroup`, `delete`, `iznosNOW`, `iznosMAX`, `gift`, `gtxt1`, `gtxt2`, `kolvo`, `geniration`, `magic_inc`, `maidin`, `lastUPD`, `timeOver`, `overType`, `secret_id`, `time_create`, `time_sleep`, `dn_delete`, `inTransfer`, `post_delivery`, `lbtl_`, `bexp`, `so`, `blvl`, `pok_itm`, `btl_zd`) VALUES
			 (4736, 1.00, 0.00, 0.00, 0.00, ".$uid.", 0, 'moment=1|moment_hp=250|nohaos=1|musor=2|noremont=1|fromshop=1|sudba=1|nostransfer=1', 0, 0, 0, 0, 0.00, 100.0000, '', '', '', 1, 2, 'elicsir_mp -w250', 'capitalcity', ".time().", 0, 0, '', ".time().", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);");
			
			mysql_query("INSERT INTO `items_users` (`item_id`, `1price`, `2price`, `3price`, `4price`, `uid`, `use_text`, `data`, `inOdet`, `inShop`, `inGroup`, `delete`, `iznosNOW`, `iznosMAX`, `gift`, `gtxt1`, `gtxt2`, `kolvo`, `geniration`, `magic_inc`, `maidin`, `lastUPD`, `timeOver`, `overType`, `secret_id`, `time_create`, `time_sleep`, `dn_delete`, `inTransfer`, `post_delivery`, `lbtl_`, `bexp`, `so`, `blvl`, `pok_itm`, `btl_zd`) VALUES (724, 1.00, 0.00, 0.00, 0.00, ".$uid.", 0, 'nosale=1|notransfer=1|sudba=1|moment=1|moment_hp=100|nohaos=1|musor=2|noremont=1|fromshop=1', 0, 0, 0, 0, 0.00, 100.0000, '', '', '', 1, 2, 'elicsir_hp -w100', 'capitalcity', '".time()."', 0, 0, '', '".time()."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);");
			mysql_query("INSERT INTO `items_users` (`item_id`, `1price`, `2price`, `3price`, `4price`, `uid`, `use_text`, `data`, `inOdet`, `inShop`, `inGroup`, `delete`, `iznosNOW`, `iznosMAX`, `gift`, `gtxt1`, `gtxt2`, `kolvo`, `geniration`, `magic_inc`, `maidin`, `lastUPD`, `timeOver`, `overType`, `secret_id`, `time_create`, `time_sleep`, `dn_delete`, `inTransfer`, `post_delivery`, `lbtl_`, `bexp`, `so`, `blvl`, `pok_itm`, `btl_zd`) VALUES (4736, 1.00, 0.00, 0.00, 0.00, ".$uid.", 0, 'nosale=1|notransfer=1|sudba=1|moment=1|moment_hp=250|nohaos=1|musor=2|noremont=1|fromshop=1', 0, 0, 0, 0, 0.00, 100.0000, '', '', '', 1, 2, 'elicsir_mp -w250', 'capitalcity', '".time()."', 0, 0, '', '".time()."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);");
			mysql_query("INSERT INTO `items_users` (`item_id`, `1price`, `2price`, `3price`, `4price`, `uid`, `use_text`, `data`, `inOdet`, `inShop`, `inGroup`, `delete`, `iznosNOW`, `iznosMAX`, `gift`, `gtxt1`, `gtxt2`, `kolvo`, `geniration`, `magic_inc`, `maidin`, `lastUPD`, `timeOver`, `overType`, `secret_id`, `time_create`, `time_sleep`, `dn_delete`, `inTransfer`, `post_delivery`, `lbtl_`, `bexp`, `so`, `blvl`, `pok_itm`, `btl_zd`) VALUES (2137, 1.00, 0.00, 0.00, 0.00, ".$uid.", 0, 'nosale=1|notransfer=1|sudba=1|nosale=1|notransfer=1|sudba=1|noremont=1|add_maxves=250|fromshop=1', 0, 0, 0, 0, 0.00, 1.0000, '', '', '', 1, 2, '', 'capitalcity', '".time()."', 0, 0, '', '".time()."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);");
			//
			if($reg_d[6] == 1) {
				mysql_query("INSERT INTO `items_users` (`item_id`, `1price`, `2price`, `3price`, `4price`, `uid`, `use_text`, `data`, `inOdet`, `inShop`, `inGroup`, `delete`, `iznosNOW`, `iznosMAX`, `gift`, `gtxt1`, `gtxt2`, `kolvo`, `geniration`, `magic_inc`, `maidin`, `lastUPD`, `timeOver`, `overType`, `secret_id`, `time_create`, `time_sleep`, `dn_delete`, `inTransfer`, `post_delivery`, `lbtl_`, `bexp`, `so`, `blvl`, `pok_itm`, `btl_zd`) VALUES (4906, 1.00, 0.00, 0.00, 0.00, ".$uid.", 0, 'nosale=1|notransfer=1|sudba=1|usefromfile=1|giftsee=2|tr_sex=1|noremont=1|fromshop=1', 0, 0, 0, 0, 0.00, 100.0000, 'Красота', '', '', 1, 2, '', 'capitalcity', '".time()."', 0, 0, '', '".time()."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);");
				mysql_query("INSERT INTO `items_users` (`item_id`, `1price`, `2price`, `3price`, `4price`, `uid`, `use_text`, `data`, `inOdet`, `inShop`, `inGroup`, `delete`, `iznosNOW`, `iznosMAX`, `gift`, `gtxt1`, `gtxt2`, `kolvo`, `geniration`, `magic_inc`, `maidin`, `lastUPD`, `timeOver`, `overType`, `secret_id`, `time_create`, `time_sleep`, `dn_delete`, `inTransfer`, `post_delivery`, `lbtl_`, `bexp`, `so`, `blvl`, `pok_itm`, `btl_zd`) VALUES (4907, 1.00, 0.00, 0.00, 0.00, ".$uid.", 0, 'nosale=1|notransfer=1|sudba=1|usefromfile=1|giftsee=2|tr_sex=1|noremont=1|fromshop=1', 0, 0, 0, 0, 0.00, 100.0000, 'Красота', '', '', 1, 2, '', 'capitalcity', '".time()."', 0, 0, '', '".time()."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);");
			}
			mysql_query("INSERT INTO `items_users` (`item_id`, `1price`, `2price`, `3price`, `4price`, `uid`, `use_text`, `data`, `inOdet`, `inShop`, `inGroup`, `delete`, `iznosNOW`, `iznosMAX`, `gift`, `gtxt1`, `gtxt2`, `kolvo`, `geniration`, `magic_inc`, `maidin`, `lastUPD`, `timeOver`, `overType`, `secret_id`, `time_create`, `time_sleep`, `dn_delete`, `inTransfer`, `post_delivery`, `lbtl_`, `bexp`, `so`, `blvl`, `pok_itm`, `btl_zd`) VALUES (3140, 20.00, 0.00, 0.00, 0.00, ".$uid.", 0, 'nosale=1|notransfer=1|sudba=1|nohaos=1|onlyOne=1|oneType=34|musor=2|noremont=1 |fromshop=1', 0, 0, 0, 0, 0.00, 5.0000, '', '', '', 1, 2, '300', 'capitalcity', '".time()."', 0, 34, '', '".time()."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);");
			mysql_query("INSERT INTO `items_users` (`item_id`, `1price`, `2price`, `3price`, `4price`, `uid`, `use_text`, `data`, `inOdet`, `inShop`, `inGroup`, `delete`, `iznosNOW`, `iznosMAX`, `gift`, `gtxt1`, `gtxt2`, `kolvo`, `geniration`, `magic_inc`, `maidin`, `lastUPD`, `timeOver`, `overType`, `secret_id`, `time_create`, `time_sleep`, `dn_delete`, `inTransfer`, `post_delivery`, `lbtl_`, `bexp`, `so`, `blvl`, `pok_itm`, `btl_zd`) VALUES (2418, 10.00, 0.00, 0.00, 0.00, ".$uid.", 0, 'nosale=1|notransfer=1|sudba=1|nohaos=1|onlyOne=1|oneType=29|musor=2|noremont=1|fromshop=1', 0, 0, 0, 0, 0.00, 10.0000, '', '', '', 1, 2, '268', 'capitalcity', '".time()."', 0, 29, '', '".time()."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);");
						
			mysql_query('UPDATE `users` SET `online` = "'.time().'" WHERE `id` = "'.$uid.'" LIMIT 1');
			//Создаем статы персонажа
			mysql_query("INSERT INTO `online` (`uid`,`timeStart`) VALUES ('".$uid."','".time()."')");
			mysql_query("INSERT INTO `stats` (`id`,`stats`,exp) VALUES ('".$uid."','s1=3|s2=3|s3=3|s4=3|rinv=40|m9=5|m6=10',0)");	
			
			//мульты
			$ipm1 = mysql_fetch_array(mysql_query('SELECT * FROM `logs_auth` WHERE `uid` = "'.mysql_real_escape_string($uid).'" AND `ip`!="'.mysql_real_escape_string(IP).'" ORDER BY `id` ASC LIMIT 1'));
			$ppl = mysql_query('SELECT * FROM `logs_auth` WHERE `ip`!="" AND (`ip` = "'.mysql_real_escape_string(IP).'" OR `ip`="'.mysql_real_escape_string($ipm1['ip']).'" OR `ip`="'.mysql_real_escape_string($_COOKIE['ip']).'")');
			while($spl = mysql_fetch_array($ppl))
			{
				$ml = mysql_fetch_array(mysql_query('SELECT `id` FROM `mults` WHERE (`uid` = "'.$spl['uid'].'" AND `uid2` = "'.$uid.'") OR (`uid2` = "'.$spl['uid'].'" AND `uid` = "'.$uid.'") LIMIT 1'));
				if(!isset($ml['id']) && $spl['ip']!='' && $spl['ip']!='127.0.0.1')
				{
					mysql_query('INSERT INTO `mults` (`uid`,`uid2`,`ip`) VALUES ("'.$uid.'","'.$spl['uid'].'","'.$spl['ip'].'")');
				}
			}
			mysql_query("INSERT INTO `logs_auth` (`country`,`uid`,`ip`,`browser`,`type`,`time`,`depass`) VALUES ('".mysql_real_escape_string($_SERVER["HTTP_CF_IPCOUNTRY"])."','".$uid."','".mysql_real_escape_string(IP)."','".mysql_real_escape_string($_SERVER['HTTP_USER_AGENT'])."','1','".time()."','')");
			
			//Обновяем таблицы
			mysql_query("UPDATE `users` SET `country_reg` = '".mysql_real_escape_string($_SERVER["HTTP_CF_IPCOUNTRY"])."',`online`='".time()."',`ip` = '".mysql_real_escape_string(IP)."' WHERE `uid` = '".$uid."' LIMIT 1");
			
			if(!setcookie('login',$reg_d[0], (time()+60*60*24*7) , '' , '.xcombats.com' ) || !setcookie('pass',md5($reg_d[1]), (time()+60*60*24*7) , '' , '.xcombats.com' )) {
				die('Ошибка сохранения cookie.');
			}else{
				/*
				die('Спасибо за регистрацию!<br><script>function test(){ top.location.href="http://xcombats.com/bk"; } setTimeout("test()",1000);</script>');
				*/
			}
			
			setcookie('login',$reg_d[0],time()+60*60*24*7,'',$c['host']);
			setcookie('pass',md5($reg_d[1]),time()+60*60*24*7,'',$c['host']);
			setcookie('login',$reg_d[0],time()+60*60*24*7);
			setcookie('pass',md5($reg_d[1]),time()+60*60*24*7);
			
			//Начало обучения
			$humor = array(
				0 => array(
					':maniac: Сильно не бейте ;)',':beggar: Будет попрошайничать - бейте!',':pal: Возможно светлый!',
					':vamp: Возможно темный!',':susel: Судя по здоровенному бицепсу - это мужик!',':duel: И сразу же кинулся в бой!',
					':friday: Не долго думав он начал искать собутыльника!',':doc: Лекарь: Новичок! Да, да! Ты! Если тебя сломают - у знахаря тебя соберут обратно!'
				),
				1 => array(
					':maniac: Помните! Девочек не бьем ;)',':nail: Она красит ногти, не отвлекайте все сразу ;)',':pal: Возможно светлая!',
					':vamp: Возможно темная!',':rev: Судя по красивой одежде - это женщина!',':hug: И сразу же кинулась всех целовать!',
					':angel2: Ангел сошел с небес...'
				)
			);
			$humor = $humor[(int)$reg_d[6]];
			$u->send('','','','','','В нашем мире появился новый игрок <b>' . htmlspecialchars($reg_d[0],NULL,'cp1251') . '</b>[0]<a href=http://xcombats.com/info/'.$uid.' target=_blank ><img src=http://img.xcombats.com/i/inf_capitalcity.gif style=vertical-align:baseline; ></a>! '.$humor[rand(0,count($humor)-1)].'',time(),6,0,0,0,1,0);
			
			//header('location: http://xcombats.com/bk');
		}
	}
	
	if( $good == 1 ) {
		$gd[6] = 1;
	}
	
	$rt .= '["'.$gd[0].'","'.$gd[1].'","'.$gd[2].'","'.$gd[3].'","'.$gd[4].'","'.$gd[5].'","'.$gd[6].'","'.$gd[7].'","'.$gd[8].'","'.$gd[9].'","'.$gd[10].'"]';
	//
	die($rt);
}

/* Данные регистрации */
$reg_id = microtime();
$reg_id = str_replace(' ','.',$reg_id);
$reg_id = str_replace('.','',$reg_id);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Регистрация в мире &laquo;Бойцовского Клуба&raquo;</title>
<script type="text/javascript" src="scripts/jquery.js"></script>
<script type="text/javascript" src="scripts/psi.js"></script>
<link rel="stylesheet" href="styles/register.css?<?=time()?>" type="text/css" media="screen"/>
<link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="3D3D3B">
<!-- -->
<link href="http://img.xcombats.com/css/reg.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	FONT-FAMILY: Verdana, Arial, Helvetica, Tahoma, sans-serif;
}
.style5 {color: #990000}
.style7 {color: #364875}
.style6 {color: #DFD3A3;
	font-size: 9px;
}
.стиль3 {color: #666666}
.стиль4 {
	color: #FF0000;
	font-weight: bold;
}
.mmg {
	FONT-SIZE: 8pt; FONT-FAMILY: Verdana, Arial, Helvetica, Tahoma, sans-serif; padding-bottom:8px;
}
html, body { height:100%; }
.imagebacked {
	padding-left: 26px; /* image-width */
	background-repeat: no-repeat;
}
-->
</style>
<link rel="Stylesheet" type="text/css" href="/scripts/wSelect.css" />
<link rel="Stylesheet" type="text/css" href="/scripts/demo.css" />
<script type="text/javascript" src="/scripts/wSelect.js"></script>
<script language="JavaScript"> 
function CheckValue(a) {
	var b = '';
	for (i = '0'; i < a.value.length; i++) {
	var c = a.value.substring(i, i+1);
	if ((c >= 'A' && c <= 'Z') || (c >= 'a' && c <= 'z') ||
	(c.charCodeAt(0) >= 1040 && c.charCodeAt(0) <= 1103)
	|| (c == ' ') || (c == '-') || (c == '_'))
	{
		b += c;
	}
	}
	if (a.value != b) { a.value = b; }
}
</script>
<div align="center" style="height:100%">
	<!--<table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="205" align="center" valign="middle" background="http://xcombats.com/inx/summer_line.jpg">
            <img src="http://xcombats.com/inx/summer_logo.jpg" width="428" height="205">
        </td>
      </tr>
	</table>-->
<table width="900" height="100%" style="margin-bottom:-43px" border="0" cellspacing="0" cellpadding="0">
	<tr>
	 <td width="29" valign="top" background="http://img.xcombats.com/i/regImg5.jpg"><img src="http://img.xcombats.com/i/regImg4.jpg" width="29" height="256"></td>
	 <td width="118" valign="top" bgcolor="#f2e5b1"><img src="http://img.xcombats.com/i/regImg2.jpg" width="118" height="257"></td>
	 <td valign="top" bgcolor="#f2e5b1" align="left">
	 	<br>
	 	<p align="left"><img src="http://img.xcombats.com/i/regImg1.gif" width="198" height="26" /></p>
	 	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	 	  <tr>
        <td><form id="register_main" method="post" action="/register">
          <input name="reg_id" id="reg_id" type="hidden" value="<?=$reg_id?>" />
          <div style="padding:20px;">
          	<? if( $multer > 0 ) { ?>
            <div><b><font color=red>Вы недавно регистрировались с этого IP. Попробуйте через час.</font></b></div>
            <? } ?>
            <div style="padding-right:25px;"><!-- -->
              <div style="padding-bottom:5px;"> <span style="display:inline-block;width:230px;"><font color=red>*</font> Имя вашего персонажа (login):</span> &nbsp;
                <script>psi.inputPrint('register_login<?=$reg_id?>','register_login<?=$reg_id?>',null,null,null,'psi_input1','width:191px;');</script>
                <span style="display:inline-block;width:15px;"> <span id="login_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'>
                  <div id="login_error_text">&nbsp;</div>
                  </span></span> </span> </div>
                  <div style="padding-left:16px; color: #847167;" class="mmg">Логин может содержать от 2 до 15 символов, и состоять только из букв русского ИЛИ английского алфавита, цифр, символов '_', '-' и пробела. 
Логин не может начинаться или заканчиваться символами '_', '-' или пробелом.</div>
              
              
              <!-- <div align="center"><span style="color:#09F"></span></div>
              <div style="padding-bottom:5px;">
              <span style="display:inline-block;width:230px;"><font color=red>*</font> Склонность персонажа:</span>
                <div style="display:inline-block;">
                <div id="1register_align<?=$reg_id?>" align="center" class="psi_input1_none psi_list" style="width:191px;margin-left:10px;">
              	<select tabindex="1" name="register_align<?=$reg_id?>" id="register_align<?=$reg_id?>">
                	<option value="4" data-icon="http://img.xcombats.com/i/align/align0.gif">Без склонности</option>
                    <option value="3" data-icon="http://img.xcombats.com/i/align/align7.gif">Нейтральная</option>
                    <option value="2" data-icon="http://img.xcombats.com/i/align/align3.gif">Тёмная</option>
                    <option value="1" data-icon="http://img.xcombats.com/i/align/align1.gif">Светлая</option>
              	</select>
                </div>
                </div>
              </div>				
			  <script type="text/javascript">
                $('select').wSelect();        
                $('#register_align<?=$reg_id?>').change(function() {
                  console.log($(this).val());
                });				
              </script>
              -->
              <div style="padding-bottom:5px;"> <span style="display:inline-block;width:230px;"><font color=red>*</font> Пароль</span> &nbsp;
                <script>psi.inputPrint('register_pass<?=$reg_id?>','register_pass<?=$reg_id?>',null,null,'password','psi_input1','width:191px;');</script>
                <span style="display:inline-block;width:15px;"> <span id="pass_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'>
                  <div id="pass_error_text">&nbsp;</div>
                  </span></span> </span> </div>
              <div style="padding-bottom:5px;"> <span style="display:inline-block;width:230px;"><font color=red>*</font> Пароль еще раз</span> &nbsp;
                <script>psi.inputPrint('register_pass2<?=$reg_id?>','register_pass2<?=$reg_id?>',null,null,'password','psi_input1','width:191px;');</script>
                <span style="display:inline-block;width:15px;"> &nbsp; </span> </div>
              <div style="padding-left:16px; color: #847167;" class="mmg"><span class="style5">Внимание!</span> Ставьте пароль от персонажа, который будет отличаться от тех, что вы вводили на других проектах. Это значительно уменьшит шансы что вашего персонажа взломают.</div>
              <div align="center"><span style="color:#09F"></span></div>
              <div style="padding-bottom:5px;"> <span style="display:inline-block;width:230px;"><font color=red>*</font> Ваш пол</span> <span style="padding-left:15px;"> <small class="cp radio1txt" id="register_sex<?=$reg_id?>block1" value="1">
                <div style="display:inline-block;">
				<script>psi.radioPring('register_sex<?=$reg_id?>','register_sex<?=$reg_id?>',true,null,'Мужской');</script>
                </small> &nbsp; &nbsp; &nbsp; <small class="cp radio1txt" id="register_sex<?=$reg_id?>block2" value="2">
                  <script>psi.radioPring('register_sex<?=$reg_id?>','register_sex<?=$reg_id?>',true,2,'Женский');
                        psi.radioPress('register_sex<?=$reg_id?>',$('#register_sex<?=$reg_id?>_1'),2);
                        </script>
                  </div>
                  </small> &nbsp; &nbsp;&nbsp; </span> <span style="display:inline-block;width:15px;"> <span id="sex_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'>
                    <div id="sex_error_text">&nbsp;</div>
                    </span></span> </span> </div>
              <div style="padding-left:16px; color: #847167;" class="mmg"><span class="style5">Внимание!</span> Пол персонажа должен соответствовать реальному полу игрока.</div>
              <div style="padding-bottom:5px;"> <span style="display:inline-block;width:230px;"><font color=red>*</font> День рождения</span> &nbsp;
                <div style="display:inline-block;">
                <div id="1register_dd<?=$reg_id?>" align="center" class="psi_input1_none psi_list" style="width:43px;">
                  <select name="register_dd<?=$reg_id?>" id="register_dd<?=$reg_id?>">
                    <?
                    $i = 1;
                    while( $i <= 31 ) {
                        $j = $i;
                        if( $j < 10 ) {
                            $j = '0'.$j;	
                        }
                    ?>
                    <option value="<?=$i?>">
                      <?=$j?>
                      </option>
                    <?
                        $i++;
                    }
                    unset($i,$j);
                    ?>
                    </select>
                  </div>
                <div id="1register_mm<?=$reg_id?>" align="center" class="psi_input1_none psi_list" style="width:43px;">
                  <select name="register_mm<?=$reg_id?>" id="register_mm<?=$reg_id?>">
                    <?
                    $i = 1;
                    while( $i <= 12 ) {
                        $j = $i;
                        if( $j < 10 ) {
                            $j = '0'.$j;	
                        }
                    ?>
                    <option value="<?=$i?>">
                      <?=$j?>
                      </option>
                    <?
                        $i++;
                    }
                    unset($i,$j);
                    ?>
                    </select>
                  </div>
                <div id="1register_yyyy<?=$reg_id?>" align="center" class="psi_input1_none psi_list" style="width:60px;">
                  <select name="register_yyyy<?=$reg_id?>" id="register_yyyy<?=$reg_id?>">
                    <?
                    $i = date('Y') - 10;
                    while( $i >= date('Y') - 80 ) {
                        $j = $i;
                    ?>
                    <option value="<?=$i?>">
                      <?=$j?>
                      </option>
                    <?
                        $i--;
                    }
                    unset($i,$j);
                    ?>
                    </select>
                  </div>
                  </div>
                <span style="display:inline-block;width:15px;"> <span id="bd_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'>
                  <div id="bd_error_text">&nbsp;</div>
                  </span></span> </span> </div>
                  <div style="padding-left:16px; color: #847167;" class="mmg"><span class="style5">Внимание!</span> Дата рождения должна быть правильной, она используется в игровом процессе. Анкеты с неправильной датой будут удаляться без предупреждения.</div>
              <div style="padding-bottom:5px;"> <span style="display:inline-block;width:230px;"><font color=red>*</font> E-mail</span> &nbsp;
                <script>psi.inputPrint('register_mail<?=$reg_id?>','register_mail<?=$reg_id?>',null,null,null,'psi_input1','width:191px;');</script>
                <span style="display:inline-block;width:15px;"> <span id="mail_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'>
                  <div id="mail_error_text">&nbsp;</div>
                  </span></span> </span> </div>
                  <div style="padding-left:16px; color: #847167;" class="mmg">Используется только для регистрации и восстановления (в случае его утери) доступа к персонажу. Он нигде не отображается и не используется в целях спама.</div>
              <div style="padding-bottom:5px;"> <span style="display:inline-block;width:230px;"><font color=red>*</font> Код с картинки</span> &nbsp; <img src="show_reg_img/security.php?register_id=<?=str_replace(' ','0',microtime())?>" width="107" height="26" style="display:inline-block; vertical-align:bottom;">
                <script>psi.inputPrint('register_key<?=$reg_id?>','register_key<?=$reg_id?>',null,null,null,'psi_input1','width:81px;');</script>
                <span style="display:inline-block;width:15px;"> <span id="key_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'>
                  <div id="key_error_text">&nbsp;</div>
                  </span></span> </span> </div>
              <br>
              <div title="Я принимаю все правила и соглашения, а так-же разрешаю оповещать меня по E-mail"> <small class="cp" id="register_rules<?=$reg_id?>block">
                <span style="display:inline-block;"><script>psi.checkPring('register_rules<?=$reg_id?>','register_rules<?=$reg_id?>',true);</script></span>
                <span style="display:inline-block;">&nbsp;Я ознакомился с Соглашением о предоставлении сервиса игры и законами<br>
                &nbsp;&nbsp;&nbsp; &nbsp; &nbsp;"Старый Бойцовский Клуб (XCombats)" и согласен по всем пунктам.</small> <span style="display:inline-block;width:15px;"> <span id="rules_error" style="display:none;" class='tip'><a tabindex="1"><em>?</em></a><span class='answer'>
                  <div id="rules_error_text">&nbsp;</div>
                  </span></span> </span> </div></span>
              <br>
              <div align="center">
                <input onclick="top.location.href='/';" class="btnnew2" value="Вернуться" type="button"> <input class="btnnew" onClick="psi.testForm();" type="button" value="Зарегистрироваться">
                </div>
				<center></center>
              <!-- -->
              </div>
            </div>
          <input type="hidden" value="<?=round((int)$_GET['ref'])?>" name="refu<?=$reg_id?>" id="refu<?=$reg_id?>">
        </form></td>
        </tr>
  </table>
	</td>
	<td width="139" valign="top" bgcolor="#f2e5b1"><img height=144 src="http://img.xcombats.com/i/regImg3.jpg" width=139 border=0></td>
	<td width="23" valign="top" background="http://img.xcombats.com/i/regImg6.jpg">&nbsp;</td>
</tr>
 </table>
<TABLE height="44" cellSpacing=0 cellPadding=0 width="100%" border=0>
	<TBODY>
		<TR>
			<TD width="100%" height=13 background="http://img.xcombats.com/i/regImg7.jpg"></TD>
		</TR>
		<TR>
			<TD width="100%" bgColor=#000000 height=21>
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td width="10%" scope="col"></td>
						<td width="81%" scope="col"><div align="center"><NOBR><span class="style6">Все права защищены © <?=date('Y')?> «Старый БК»</span></NOBR></div></td>
						<td width="9%" scope="col">&nbsp;</td>
					</tr>
				</table>
			</TD>
		</TR>
	</TBODY>
</TABLE>
</div>
<!-- -->
<script>
psi.startTestingData(<?=$reg_id?>);
</script>
</body>
</html>
