<?
if(!defined('GAME'))
{
	die('/index.php');
}

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

if($_SERVER['HTTP_REFERER'] == 'http://btl4.net/' && $_COOKIE['b4n'] != '2') {
	setcookie('b4n','1',time()+60*60*24*3);
}


/*
if(IP != '') {
	die('Регистрация временно отключена. Идет восстановление персонажей.');
}
*/

class register
{
	public function testLogin($v)
	{
		
	}
	
	public function en_ru($txt)
	{
		$g = false;
		$en = preg_match("/^(([0-9a-zA-Z _-])+)$/i", $txt);
		$ru = preg_match("/^(([0-9а-яА-Я _-])+)$/i", $txt);
		if(($ru && $en) || (!$ru && !$en))
		{
			$g = true;
		}
		return $g;
	}
	public function testStep()
	{
		global $c,$reg,$error,$filter,$chat,$reg_d,$noup,$youip;
		$stp = 1;
		if(isset($_POST['step']) && isset($reg['id']))
		{
			$upd = '';
			
			$lr = mysql_fetch_array(mysql_query('SELECT `id`,`ipreg` FROM `users` WHERE `cityreg`="capitalcity" AND `timereg`>"'.(time()-60*60*1).'" AND `ipreg` = "'.mysql_real_escape_string(IP).'" LIMIT 1'));
			if(isset($_COOKIE['reg_capitalcity']) || (int)$_COOKIE['reg_capitalcity']>time() || isset($lr['id']))
			{
				$error .= 'Недавно с вашего IP уже регистрировался персонаж. С одного IP адреса разрешена регистрация персонажей не чаще, чем раз в час. Попробуйте позже.<br>'; $_POST['step'] = 1;
			}
			
			if($error=='')
			{
				
				$reg_bonus = false;
				/*if(isset($_POST['register_code']))
				{
					$cd = mysql_fetch_array(mysql_query('SELECT * FROM `register_code` WHERE `code` = "'.mysql_real_escape_string($_POST['register_code']).'" AND `time_finish` = "0" AND `use` = "0" LIMIT 1'));
					if(isset($cd['id']) && $cd['use']==0)
					{
						$reg_bonus = true;
						$upd = mysql_query('UPDATE `register_code` SET `use` = "'.$reg['id'].'",`time_start`="'.time().'" WHERE `id` = "'.$cd['id'].'" LIMIT 1');
						if($upd && $reg['id']>0)
						{
							$uz = mysql_fetch_array(mysql_query('SELECT `id`,`login` FROM `users` WHERE `id` = "'.mysql_real_escape_string($cd['uid']).'" LIMIT 1'));
							if(!isset($uz['id']))
							{
								$uz['login'] = '<i>Невидимка</i>';
							}
							$error .= 'Вы успешно активировали приглашение от '.$uz['login'].'. Теперь вы можете зарегистрироваться. Код действует только на эту регистрацию.<br>';
							mysql_query("UPDATE `items_users` SET `data`='info=Код приглашения: <b>".$cd['code']."</b><br>При утери приглашения регистрация по данному коду будет запрещена.<br><div style=\"color:brown;\">Кто-то регестрируется по вашему приглашению. Дата: ".date('d.n.Y H:i',time())."</div>' WHERE `secret_id` = '".$cd['code']."' LIMIT 1");
							$cd['use'] = $reg['id'];
							
							if($cd['time_create']<time()-60*60)
							{
								$error .= 'Регистрационный код просрочен.<br>';
								$reg_bonus = false;
							}
						}else{
							$error .= 'Ошибка подтверждения. Попробуйте позже...';
						}					
					}else{
						$error .= 'Регистрационный код был использован ранее, либо не существует.<br>';
					}
				}	
				
				$cd = mysql_fetch_array(mysql_query('SELECT * FROM `register_code` WHERE `use` = "'.$reg['id'].'" AND `time_finish` = "0" LIMIT 1'));
				if(!isset($cd['id']))
				{
					$reg_bonus = false;
					$error .= 'Предмет не найден. (приглашение в инвентаре пользователя)<br>';
				}else{
					$reg_bonus = true;
					$cdi = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `secret_id` = "'.$cd['code'].'" AND `delete` = "0" AND `inShop` = "0" LIMIT 1'));
					if(!isset($cdi['id']))
					{
						$reg_bonus = false;
						$error .= 'Предмет не найден.';
					}
				}	
				
				
				//$reg_bonus = true;
				
				//регистрация требует приглашения
				if($reg_bonus==false)
				{
					$error .= '<form action="register.php" method="post">Регистрация только по приглашению. Введите код приглашения:<br><input name="register_code" type="text" style="width:200px;"> <input type="submit" value="Ввести код"></form>'; $_POST['step'] = 1;
				}
				*/
			}
			
			$reg_bonus = true;
			
			if($_POST['step']>1)
			{
				//Запрещенные логины
				$nologin = array(0=>'ангел',1=>'angel',2=>'администрация',3=>'administration',4=>'Комментатор',5=>'Мироздатель',6=>'Мусорщик',7=>'Падальщик',8=>'Повелитель',9=>'Архивариус',10=>'Пересмешник',11=>'Волынщик',12=>'Лорд Разрушитель',13=>'Милосердие',14=>'Справедливость',15=>'Искушение',16=>'Вознесение');
				$blacklist = "!@#$%^&*()\+Ёё|/'`\"";
				$sr = '_-йцукенгшщзхъфывапролджэячсмитьбюё1234567890';
				$i = 0;
				while($i<count($nologin))
				{
					if(preg_match("/".$nologin[$i]."/i",$filter->mystr($reg_d[0])))
					{
						$error .= 'Выберите, пожалуйста, другой ник.<br>'; $_POST['step'] = 1; $i = count($nologin);
					}
					$i++;
				}
				$reg_d[0] = str_replace('  ',' ',$reg_d[0]);
				//Логин от 2 до 20 символов
				if(strlen($reg_d[0])>20) 
				{ 
					$error .= 'Логин должен содержать не более 20 символов.<br>'; $_POST['step'] = 1;
				}
				if(strlen($reg_d[0])<2) 
				{ 
					$error .= 'Логин должен содержать не менее 2 символов.<br>'; $_POST['step'] = 1;
				}
				//Один алфавит
				$er = $this->en_ru($reg_d[0]);
				if($er==true)
				{
					$error .= 'В логине разрешено использовать только буквы одного алфавита русского или английского. Нельзя смешивать.<br>'; $_POST['step'] = 1;
				}
				//Запрещенный символы
				if(strpos($sr,$reg_d[0]))
				{
					$error .= 'Логин содержит запрещенные символы.<br>'; $_POST['step'] = 1;
				}				
				//Персонажи в базе
				$log = mysql_fetch_array(mysql_query('SELECT `id` from `users` where `login`="'.mysql_real_escape_string($reg_d[0]).'" LIMIT 1'));
				$log2 = mysql_fetch_array(mysql_query('SELECT `id` from `lastNames` where `login`="'.mysql_real_escape_string($reg_d[0]).'" LIMIT 1'));
				if(isset($log['id']) || isset($log2['id']))
				{
					$error .= 'Логин '.$reg_d[0].' уже занят, выберите другой.<br>'; $_POST['step'] = 1;
				}
				//Разделители
				if(substr_count($reg_d[0],' ')+substr_count($reg_d[0],'-')+substr_count($reg_d[0],'_')>2)
				{
					$error .= 'Не более двух разделителей одновременно (пробел, тире, нижнее подчеркивание).<br>'; $_POST['step'] = 1;
				}
				$reg_d[0] = trim($reg_d[0],' ');				
				
				
				if($_POST['step']!=1)
				{
					$stp = 2; $noup = 0;
				}
			}
			if($_POST['step']>2)
			{
				//проверяем пароль
				if(strlen($reg_d[1])<6 || strlen($reg_d[1])>30)
				{
					$error .= 'Длина пароля не может быть меньше 6 символов или более 30 символов.<br>'; $_POST['step'] = 2;
				}
				if($reg_d[1]!=$reg_d[2])
				{
					$error .= 'В анкете пароль нужно ввести дважды, для проверки. Во второй раз вы его ввели неверно, будьте внимательнее.<br>'; $_POST['step'] = 2;
				}
				if(preg_match('/'.$reg_d[0].'/i',$reg_d[1]))
				{
					$error .= 'Пароль содержит элементы логина.<br>'; $_POST['step'] = 2;
				}
				if($_POST['step']!=2)
				{
					$stp = 3; $noup = 0;
				}
			}
			if($_POST['step']>3)
			{
				//проверяем e-mail
				if(strlen($reg_d[3])<6 || strlen($reg_d[3])>50)
				{
					$error .= 'E-mail не может быть короче 6-х символов и длинее 50-ти.<br>'; $_POST['step'] = 3;
				}
				
				if(!preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $reg_d[3]))
				{
					$error .= 'Вы указали явно ошибочный E-mail.<br>'; $_POST['step'] = 3;
				}
				
				$reg_d[4] = $chat->str_count($reg_d[4],30);
				$reg_d[5] = $chat->str_count($reg_d[5],30);
				
				if($_POST['step']!=3)
				{
					$stp = 4; $noup = 0;
				}
			}
			if($_POST['step']>4)
			{
				//Имя, Пол, Город, Девиз и т.д.
				$er = $this->en_ru($reg_d[6]);
				if($er==true || strlen($reg_d[6])<2)
				{
					$error .= 'Укажите ваше реальное имя!<br>'; $_POST['step'] = 4;
				}
				
				$reg_d[6] = $chat->str_count($reg_d[6],90);
				$reg_d[7] = round($reg_d[7]);
				$reg_d[8] = round($reg_d[8]);
				$reg_d[9] = round($reg_d[9]);
				
				if($reg_d[7]<1 || $reg_d[7]>31 || $reg_d[8]<1 || $reg_d[8]>12 || $reg_d[9]<1920 || $reg_d[9]>2006)
				{
					$error .= 'Ошибка в написании дня рождения.<br>'; $_POST['step'] = 4;
				}
				
				if($reg_d[15]!=0 && $reg_d[15]!=1)
				{
					$error .= 'Вы указали не верный пол.<br>'; $_POST['step'] = 4;
				}
				
				if($reg_d[14]!='Black' && $reg_d[14]!='Blue' && $reg_d[14]!='Fuchsia' && $reg_d[14]!='Gray' && $reg_d[14]!='Green' && $reg_d[14]!='Maroon' && $reg_d[14]!='Navy' && $reg_d[14]!='Olive' && $reg_d[14]!='Purple' && $reg_d[14]!='Teal' && $reg_d[14]!='Orange' && $reg_d[14]!='Chocolate' && $reg_d[14]!='DarkKhaki' && $reg_d[14]!='SandyBrown')
				{
					$error .= 'Вы указали не верный цвет сообщения в чате.<br>'; $_POST['step'] = 4;
				}				
				
				if($_POST['step']!=4)
				{
					$stp = 5; $noup = 0;
				}			
			}
			if($_POST['step']>5)
			{
				//Соглашение с законами 
				if(!isset($_POST['law_'.$reg['id']]) || $_POST['law_'.$reg['id']]!='on')
				{
					$error .= 'Извините, без принятия правил нашего клуба, вы не можете зарегистрировать свой персонаж.<br>'; $_POST['step'] = 5;
				}
				
				if(!isset($_POST['law2_'.$reg['id']]) || $_POST['law2_'.$reg['id']]!='on')
				{
					$error .= 'Извините, без принятия <u>Соглашения о предоставлении сервиса игры '.$c['title'].'</u>, вы не можете зарегистрировать персонаж.<br>'; $_POST['step'] = 5;
				}
				
				if($_POST['code']!=$_SESSION['code'] || $_SESSION['code']<100 || $_POST['code']=='')
				{
					$error .= 'Ошибка введения кода.<br>'; $_POST['step'] = 5;
				}
				
				if($_POST['step']!=5)
				{
					//завершение регистрации и редирект в игру
					
					if($filter->spamFiltr($reg_d[13])!=0)
					{
						$reg_d[13] = '';
					}
					if($filter->spamFiltr($reg_d[10])!=0)
					{
						$reg_d[10] = '';
					}
					if($filter->spamFiltr($reg_d[6])!=0)
					{
						$reg_d[6] = '';
					}
					
					/*$mbid = mysql_fetch_array(mysql_query('select min(t1.id + 1)
from users t1
where t1.id + 1 not in (select id from users where id > 9999) AND t1.id > 9998'));
					//$mbid1 = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `id` = "'.$mbid[0].'" LIMIT 1'));
					//$mbid2 = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `id` = "'.$mbid[0].'" LIMIT 1'));
					//if(isset($mbid1['id']) && isset($mbid2['id'])) {
						*/
					$mbid = 'NULL';
					/*}else{
						/* чистим возможные данные */
						/*if($mbid [0]> 0) {
							mysql_query('DELETE FROM `items_users` WHERE `uid` = "'.$mbid[0].'" LIMIT 1');
							mysql_query('DELETE FROM `eff_users` WHERE `uid` = "'.$mbid[0].'" LIMIT 1');
							mysql_query('DELETE FROM `bank` WHERE `uid` = "'.$mbid[0].'" LIMIT 1');
							mysql_query('DELETE FROM `users_delo` WHERE `uid` = "'.$mbid[0].'" LIMIT 1');
							mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$mbid[0].'" LIMIT 1');
						}
						$mbid = $mbid[0];
					}*/
					
					if($_COOKIE['b4n'] == '1') {
						setcookie('b4n','2',time()+60*60*24*3);
						$reg['referal'] = 'btl4.net';
					}elseif(isset($_COOKIE['hstreger'])) {
						$reg['referal'] = $_COOKIE['hstreger'];
					}
					
					$ins = mysql_query("INSERT INTO `users` (`activ`,`fnq`,`host_reg`,`room`,`login`,`pass`,`ipreg`,`ip`,`city`,`cityreg`,`a1`,`q1`,`mail`,`name`,`bithday`,`sex`,`city_real`,`icq`,`icq_hide`,`deviz`,`chatColor`,`timereg`) VALUES (
					'0',
					'0',
					'".mysql_real_escape_string($reg['referal'])."',
					'0',
					'".$reg_d[0]."',
					'".md5($reg_d[1])."',
					'".IP."',
					'".IP."',
					'capitalcity',
					'capitalcity',
					'".$reg_d[4]."',
					'".$reg_d[5]."',
					'".$reg_d[3]."',
					'".$reg_d[6]."',
					'".$reg_d[7].".".$reg_d[8].".".$reg_d[9]."',
					'".$reg_d[15]."',
					'".$reg_d[10]."',
					'".$reg_d[11]."',
					'".$reg_d[12]."',
					'".$reg_d[13]."',
					'".$reg_d[14]."',
					'".time()."')");
					if($ins)
					{
						$uid = mysql_insert_id();
						
						$refer = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`banned`,`admin`,`level` FROM `users` WHERE `id` = "'.mysql_real_escape_string($reg['referal']).'" LIMIT 1'));
						if(isset($refer['id'])) {
							mysql_query("INSERT INTO `items_users` (`gift`,`uid`,`item_id`,`data`,`iznosMAX`,`geniration`,`maidin`,`time_create`) VALUES ('".$refer['login']."','".$uid."','3199','noodet=1|items_in_file=sunduk_new|var_id=1|open=1|noremont=1|nodelete=1|nosale=1',1,2,'capitalcity',".time().")");
							$text = '<font color=red>Вы стали воспитанником игрока &quot;'.$refer['login'].'&quot;! В инвентаре (раздел -прочее-) вы найдете вспомогательные предметы.</font>';
							mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('capitalcity','0','','".$reg_d[0]."','".$text."','".time()."','11','0')");
						}
						
						mysql_query("INSERT INTO `online` (`uid`,`timeStart`) VALUES ('".$uid."','".time()."')");
						mysql_query("INSERT INTO `stats` (`id`,`stats`) VALUES ('".$uid."','s1=3|s2=3|s3=3|s4=3|rinv=40|m9=5|m6=10')");
						//Добавляем предметы
						mysql_query("INSERT INTO `items_users` (`gift`,`uid`,`item_id`,`data`,`iznosMAX`,`geniration`,`maidin`,`time_create`) VALUES ('Мироздатель','".$uid."','1','add_hpAll=3',10,2,'capitalcity',".time().")");
						mysql_query("INSERT INTO `items_users` (`gift`,`uid`,`item_id`,`data`,`iznosMAX`,`geniration`,`maidin`,`time_create`) VALUES ('Мусорщик','".$uid."','73','add_mib3=1|add_mab3=1|add_mib4=1|add_mab4=1',20,2,'capitalcity',".time().")");
						mysql_query("INSERT INTO `items_users` (`uid`,`item_id`,`data`,`iznosMAX`,`geniration`,`maidin`,`time_create`) VALUES ('".$uid."','724','moment=1|sudba=".mysql_real_escape_string($reg_d[0])."|moment_hp=100|nohaos=1|musor=2|noremont=1',100,2,'capitalcity',".time().")");
						mysql_query("INSERT INTO `items_users` (`uid`,`item_id`,`data`,`iznosMAX`,`geniration`,`maidin`,`time_create`) VALUES ('".$uid."','865','tr_lvl=1|sudba=".mysql_real_escape_string($reg_d[0])."|useOnLogin=1|musor=1|noremont=1',50,2,'capitalcity',".time().")");
						mysql_query("INSERT INTO `items_users` (`uid`,`item_id`,`data`,`iznosMAX`,`geniration`,`maidin`,`time_create`) VALUES ('".$uid."','4014','sudba=".mysql_real_escape_string($reg_d[0])."|noremont=1|usefromfile=1|musor=1|nodelete=1|nosale=1|expUpg=300000',1,2,'capitalcity',".time().")");
						
						/*
						$text = 'Администрация проекта: Желаем приятного общения, великих побед и незабываемых впечатлений в нашей с вами игре! :-)';
						mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('capitalcity','0','','".$reg_d[0]."','".$text."','".time()."','11','0')");
						$text = 'Вы получили предмет [img[items/pot_cureHP100_20.gif]][1] &quot;Зелье Жизни&quot;, он находится в инвентаре, в разделе &quot;эликсиры&quot;';
						mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('capitalcity','0','','".$reg_d[0]."','".$text."','".time()."','11','0')");
						$text = 'Вы получили предмет [img[items/pal_button8.gif]][1] &quot;Нападение&quot;, он находится в инвентаре, в разделе &quot;заклятия&quot;';
						mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('capitalcity','0','','".$reg_d[0]."','".$text."','".time()."','11','0')");
						$text = 'Вы получили предмет [img[items/qsvit_hran.gif]][1] &quot;Свиток Обучения&quot;, он находится в инвентаре, в разделе &quot;заклятия&quot;. <b><font color=red>Использовав данный свиток Вы получите +300.000 ед. опыта</font></b>';
						mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`) VALUES ('capitalcity','0','','".$reg_d[0]."','".$text."','".time()."','11','0')");
						*/
						
						if(isset($_COOKIE['login']) || isset($_COOKIE['pass']))
						{
							setcookie('login','',time()-60*60*24,'',$c['host']);
							setcookie('pass','',time()-60*60*24,'',$c['host']);
						}
						setcookie('login',$reg_d[0],time()+60*60*24*7,'',$c['host']);
						setcookie('pass',md5($reg_d[1]),time()+60*60*24*7,'',$c['host']);
						setcookie('auth',md5($reg_d[1].'AUTH'.IP),time()+60*60*24*365,'',$c['host']);
						setcookie('reg_capitalcity',true,time()+60*60,'',$c['host']);
						$chat->send('',1,'capitalcity','','','Вас приветствует новичок: [login:'.$reg_d[0].']',time(),12,1,0,0);
						mysql_query("UPDATE `users` SET `online`='".time()."' WHERE `uid` = '".$uid."' LIMIT 1");
						mysql_query("UPDATE `register_code` SET `reg_id`='".$uid."',`time_finish`='".time()."' WHERE `id` = '".$cd['id']."' LIMIT 1");
						mysql_query("UPDATE `items_users` SET `delete`='".time()."' WHERE `secret_id` = '".$cd['code']."' LIMIT 1");
						mysql_query('DELETE FROM `register` WHERE `id` = "'.$reg['id'].'" LIMIT 1');
						header('location: /bk');						
						die('Регистарция прошла успешно...');
					}else{
						$error .= 'Ошибка регистрации. Попробуйте позже...<br>';
					}
				}			
			}
		}
		return $stp;
	}
}

$r = new register;
?>