<?php
if(!isset($backdoor)) {
	define('GAME',true);
	
	//10:05 Внимание! Вы успешно пополнили свой игровой счёт на <b>0.13 ЕКР</b>. Приятной Вам игры!
	
	include('_incl_data/__config.php');
	include('_incl_data/class/__db_connect.php');
	include('_incl_data/class/__user.php');
	if(!isset($u->info['id']) || $u->info['admin'] == 0) {
		header('location: http://xcombats.com/');
		die();
	}
	
	class upload {
	 
	protected function __construct() { }
	 
	//static $save_path = '/var/www/bk2ru/data/www/xcombats.com/clan_prw/';
	static $save_path = 'clan_prw/';
	static $error = '';
	 
	static function saveimg($name,$max_mb = 2,$exts = 'jpg|png|jpeg|gif',$cnm = '',$mnw = 0,$mxw = 0,$mnh = 0,$mxh = 0) {
		if (isset($_FILES[$name])) {
			$f = &$_FILES[$name];			
			if(isset($f['tmp_name'])) {
				$width = 0;
				$height = 0;
				list($width, $height) = getimagesize($f['tmp_name']);				
			}
			
			if( $mnw != 0 && $mnw > $width ) {
				self::$error = 'Минимальная ширина картинки '.$mnw.' пикселей. (Размер этой картинки '.$width.'x'.$height.')';
			}elseif( $mxw != 0 && $mxw < $width ) {
				self::$error = 'Максимальная ширина картинки '.$mxw.' пикселей. (Размер этой картинки '.$width.'x'.$height.')';
			}elseif( $mnh != 0 && $mnh > $height ) {
				self::$error = 'Минимальная высота картинки '.$mnh.' пикселей. (Размер этой картинки '.$width.'x'.$height.')';
			}elseif( $mxh != 0 && $mxh < $height ) {
				self::$error = 'Максимальная высота картинки '.$mxh.' пикселей. (Размер этой картинки '.$width.'x'.$height.')';
			}elseif( !is_dir( self::$save_path ) ) {
				self::$error = 'Ошибка на стороне сервера!';
			}elseif (($f['size'] <= $max_mb*1024*1024) && ($f['size'] > 0)) {
				if (
					(preg_match('/\.('.$exts.')$/i',$f['name'],$ext))&&
					(preg_match('/image/i',$f['type']))
				) {
	
					$ext[1] = strtolower($ext[1]);
					$fn = uniqid('f_',true).'.'.$ext[1];
					$fn2 = uniqid('f_',true).'.gif';
					if( $cnm != '' ) {
						$fn = $cnm;
						$fn2 = $cnm;
					}
					if (move_uploaded_file($f['tmp_name'], self::$save_path . $fn)) {
						// система изменения размера , требуется Rimage
						//Rimage::resize(self::$save_path . $fn, self::$save_path . $fn2);
						//@unlink(self::$save_path . $fn); // удаление файла
						return array($fn2,$fn,self::$save_path . $fn);
					} else {
						self::$error = 'Ошибка загрузки файла';
					}
				} else {
					self::$error = 'Неверный тип файла. Допустимые типы : '.$exts.'';
				}
			} else {
				self::$error = 'Неверный размер файла. Максимальный размер файла '.$max_mb.' МБ';
			}
		} else {
			self::$error = 'Файл не найден';
		}
		return false;
	} // end saveimg
	 
	} // end class
	
	$ball = mysql_fetch_array(mysql_query('SELECT SUM(`ekr`) FROM `pay_operation` WHERE `uid` = "'.$u->info['id'].'" AND `good` > 0 LIMIT 1'));
	$ball = 0+$ball[0];
}else{
	$ball = mysql_fetch_array(mysql_query('SELECT SUM(`ekr`) FROM `pay_operation` WHERE `uid` = "'.$user['id'].'" AND `good` > 0 LIMIT 1'));
	$ball = 0+$ball[0];
}

$day1def = 50; //сколько екр. в день можно менять на кр.
$day2def = 1000 * ($u->info['level']-8); //сколько кр. в день можно менять на екр.

$day1 = $day1def;
$day2 = $day2def;

if( $day2 < 0 ) { $day2 = 0; }

$timetoday = strtotime(date('d.m.Y'));
//
$dc1 = mysql_fetch_array(mysql_query('SELECT SUM(`money2`) FROM `user_operation` WHERE `time` >= "'.$timetoday.'" AND `uid` = "'.$u->info['id'].'" AND `type` = "Обмен ЕКР на КР" LIMIT 1'));
$dc2 = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `user_operation` WHERE `time` >= "'.$timetoday.'" AND `uid` = "'.$u->info['id'].'" AND `type` = "Обмен КР на ЕКР" LIMIT 1'));
$dc1 = $dc1[0];
$dc2 = $dc2[0];

$day1 = round($day1+$dc1,2);
$day2 = round($day2+$dc2,2);

if($day1 < 0) { $day1 = 0; }
if($day2 < 0) { $day2 = 0; }


$b1 = 0; //бонус накопительный

$bt = mysql_fetch_array(mysql_query('SELECT * FROM `bank_table` ORDER BY `time` DESC LIMIT 1'));

$bns = array(
	array( 0 , 0 , 0 ),
	array( 3*10 , 1 , 0 ),
	array( 3*50 , 2 , 0 ),
	array( 3*100 , 3 , 0 ),
	array( 3*200 , 4 , 0 ),
	array( 3*300 , 5 , 0 ),
	array( 3*400 , 6 , 0 ),
	array( 3*500 , 7 , 0 ),
	array( 3*600 , 8 , 0 ),
	array( 3*700 , 9 , 0 ),
	array( 3*800 , 10 , 0 ),
	array( 3*900 , 11 , 0 ),
	array( 3*1000 , 13 , 1 ),
	array( 3*1100 , 15 , 2 ),
	array( 3*1200 , 17 , 3 ),
	array( 3*1300 , 19 , 4 ),
	array( 3*1500 , 21 , 5 ),
	array( 3*1700 , 23 , 6 ),
	array( 3*2000 , 25 , 7 ),
	array( 3*2500 , 27 , 8 ),
	array( 3*3000 , 30 , 9 )
);

$bns2 = array(
	array(0,0),
	array(3*10,1),
	array(3*20,2),
	array(3*30,3),
	array(3*40,4),
	array(3*50,5),
	array(3*60,6),
	array(3*70,7),
	array(3*80,8),
	array(3*90,9),
	array(3*100,10)
);

$i = 0;
while( $i < count($bns) ) {
	if( isset($bns[$i][0]) && $ball > $bns[$i][0] ) {
		$b1 = $i;
	}
	$i++;
}

if( isset($backdoor) ) {
	$i = 0;
	while( $i < count($bns2) ) {
		if( isset($bns2[$i][0]) && $pay['ekr'] >= $bns2[$i][0] ) {
			$b2 = $i;
		}
		$i++;
	}
}

if(!isset($backdoor)) {
		
	if(isset($_POST['do']) && $_POST['do'] == 'newShadow') {
		$o = mysql_fetch_array(mysql_query('SELECT * FROM `_obraz` WHERE `uid` = "'.$u->info['id'].'" AND `good` = 0 AND `cancel` = 0 AND `img` = "'.mysql_real_escape_string($_POST['ffinput']).'" LIMIT 1'));
		if(!isset($o['id'])) {
			$u->error = 'Данный образ не найден! Возможно он уже был подтвержден!';
		}elseif( $o['price'] > $u->info['money2'] ) {
			$u->error = 'На счету недостаточно ЕКР';
		}else{
			//
			$u->info['money2'] -= $o['price'];
			mysql_query('UPDATE `users` SET `money2` = "'.$u->info['money2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			//
			mysql_query('UPDATE `_obraz` SET `good` = "'.time().'" WHERE `id` = "'.$o['id'].'" LIMIT 1');
			if( $o['type'] == 1 || $o['type'] == 2 ) {
				//Личный или Клановый
				copy('clan_prw/'.$o['img'],'../img.xcombats.com/i/obraz/'.$o['sex'].'/buy'.$o['id'].'.gif');
			}elseif( $o['type'] == 3 ) {
				//Питомец
				copy('clan_prw/'.$o['img'],'../img.xcombats.com/pet/buy'.$o['id'].'.gif');
			}elseif( $o['type'] == 5 ) {
				//Смайлы
				copy('clan_prw/'.$o['img'],'../img.xcombats.com/i/smile/s'.$o['id'].'.gif');
			}
			//
			if( $_POST['ffsex'] == 1 ) {
				$o['sex'] = 1;
			}elseif( $_POST['ffsex'] == 0 ) {
				$o['sex'] = 0;
			}
			//
			if( $o['clan'] > 0 ) {
				$o['uid1'] = 0;
			}else{
				$o['uid1'] = $o['uid'];
			}
			//
			if( $o['type'] == 1 || $o['type'] == 2 ) {
				//Личный и клановый
				mysql_query('INSERT INTO `obraz` (
					`sex`,`uid`,`img`,`usr_add`,`clan`
				) VALUES (
					"'.$o['sex'].'","'.$o['uid1'].'","buy'.$o['id'].'.gif","'.$o['uid'].'","'.$o['clan'].'"
				)');
			}elseif( $o['type'] == 3 ) {
				//Питомца
				mysql_query('INSERT INTO `obraz_pet` (
					`uid`,`time`,`img`
				) VALUES (
					"'.$u->info['id'].'","'.time().'","'.mysql_real_escape_string('buy'.$o['id'].'.gif').'"
				)');
			}elseif( $o['type'] == 5 ) {
				//Смайлик
				$u->info['add_smiles'] .= ',s'.$o['id'].'';
				$u->info['add_smiles'] = ltrim($u->info['add_smiles'],',');
				mysql_query('UPDATE `users` SET `add_smiles` = "'.$u->info['add_smiles'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			}
			//
			if( $o['type'] == 1 ) {
				$u->error = 'Личный образ успешно установлен! Можете выбрать его в инвентаре, в разделе Образ.';
			}elseif( $o['type'] == 2 ) {
				$u->error = 'Клановый образ успешно установлен! Можете выбрать его в инвентаре, в разделе Образ.';
			}elseif( $o['type'] == 3 ) {
				$u->error = 'Личный образ питомца успешно установлен! Можете выбрать его в инвентаре, в разделе Звери.';
			}elseif( $o['type'] == 5 ) {
				$u->error = 'Личный смайлик успешно установлен! Можете выбрать его в разделе Личные смайлики. Код смайлика <b>:s'.$o['id'].':</b>';
			}
		}
	}elseif(isset($_FILES['img'])) {
		//Личный образ
		$ekr = 49.99;
		if( $u->info['money2'] < $ekr ) {
			echo '{"err":"На счету недостаточно ЕКР"}';
		}else{
			$obraz = 'f_shadow1_'.$u->info['id'].'-'.md5((time()-rand(0,1000)).'#shadow1').'.gif';
			//
			if($file = upload::saveimg('img',0.3,'gif',$obraz,120,120,220,220)) {
				//
				//$u->info['money2'] -= $ekr;
				//mysql_query('UPDATE `users` SET `money2` = "'.$u->info['money2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				//
				mysql_query('INSERT INTO `_obraz` ( `uid`,`time`,`price`,`good`,`cancel`,`img`,`sex`,`type`,`clan` ) VALUES (
					"'.$u->info['id'].'",
					"'.time().'",
					"'.mysql_real_escape_string($ekr).'",
					"0",
					"0",
					"'.mysql_real_escape_string($obraz).'",
					"'.$u->info['sex'].'",
					"1",
					"0"
				) ');
				echo '{"img":"'.$obraz.'"}';
			}else{
				echo '{"err":"'.upload::$error.'"}';
			}
		}
		die();
	}elseif(isset($_FILES['img5'])) {
		//Личный смайлик
		$ekr = 8.99;
		if( $u->info['money2'] < $ekr ) {
			echo '{"err":"На счету недостаточно ЕКР"}';
		}else{
			$obraz = 'f_smile_'.$u->info['id'].'-'.md5((time()-rand(0,1000)).'#smile').'.gif';
			//
			if($file = upload::saveimg('img5',0.03,'gif',$obraz,15,100,15,50)) {
				//
				//$u->info['money2'] -= $ekr;
				//mysql_query('UPDATE `users` SET `money2` = "'.$u->info['money2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				//
				mysql_query('INSERT INTO `_obraz` ( `uid`,`time`,`price`,`good`,`cancel`,`img`,`sex`,`type`,`clan` ) VALUES (
					"'.$u->info['id'].'",
					"'.time().'",
					"'.mysql_real_escape_string($ekr).'",
					"0",
					"0",
					"'.mysql_real_escape_string($obraz).'",
					"0",
					"5",
					"0"
				) ');
				echo '{"img":"'.$obraz.'"}';
			}else{
				echo '{"err":"'.upload::$error.'"}';
			}
		}
		die();
	}elseif(isset($_FILES['img2'])) {
		//Клановый образ
		$ekr = 149.99;
		if( $u->info['clan'] == 0 ) {
			echo '{"err":"Вы должны состоять в клане"}';
		}elseif( $u->info['money2'] < $ekr ) {
			echo '{"err":"На счету недостаточно ЕКР"}';
		}else{
			$obraz = 'f_shadow2_'.$u->info['id'].'-'.md5((time()-rand(0,1000)).'#shadow2').'.gif';
			//
			if($file = upload::saveimg('img2',0.3,'gif',$obraz,120,120,220,220)) {
				//
				//$u->info['money2'] -= $ekr;
				//mysql_query('UPDATE `users` SET `money2` = "'.$u->info['money2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				//
				mysql_query('INSERT INTO `_obraz` ( `uid`,`time`,`price`,`good`,`cancel`,`img`,`sex`,`type`,`clan` ) VALUES (
					"'.$u->info['id'].'",
					"'.time().'",
					"'.mysql_real_escape_string($ekr).'",
					"0",
					"0",
					"'.mysql_real_escape_string($obraz).'",
					"'.$u->info['sex'].'",
					"2",
					"'.$u->info['clan'].'"
				) ');
				echo '{"img":"'.$obraz.'"}';
			}else{
				echo '{"err":"'.upload::$error.'"}';
			}
		}
		die();
	}elseif(isset($_FILES['img4'])) {
		//Питомец образ
		$ekr = 14.99;
		if( $u->info['money2'] < $ekr ) {
			echo '{"err":"На счету недостаточно ЕКР"}';
		}else{
			$obraz = 'f_shadow3_'.$u->info['id'].'-'.md5((time()-rand(0,1000)).'#shadow3').'.gif';
			//
			if($file = upload::saveimg('img4',0.3,'gif',$obraz,120,120,220,220)) {
				//
				//$u->info['money2'] -= $ekr;
				//mysql_query('UPDATE `users` SET `money2` = "'.$u->info['money2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				//
				mysql_query('INSERT INTO `_obraz` ( `uid`,`time`,`price`,`good`,`cancel`,`img`,`sex`,`type`,`clan` ) VALUES (
					"'.$u->info['id'].'",
					"'.time().'",
					"'.mysql_real_escape_string($ekr).'",
					"0",
					"0",
					"'.mysql_real_escape_string($obraz).'",
					"'.$u->info['sex'].'",
					"3",
					"'.$u->info['clan'].'"
				) ');
				echo '{"img":"'.$obraz.'"}';
			}else{
				echo '{"err":"'.upload::$error.'"}';
			}
		}
		die();
	}elseif(isset($_POST['kr001'])) {
		//Обмен екр на кр.
		$kr = round($_POST['kr001'],2);
		if( $c['crtoecr'] < 0.01 ) {
			$u->error = 'Сегодня обменник закрыт.';
		}elseif( $day2 < 0.01 ) {
			$u->error = 'Сегодня для вас обмен закрыт, приходите завтра.';
		}elseif( $kr < round($c['crtoecr']/100,2) ) {
			$u->error = 'Минимальная сумма обмена '.round($c['crtoecr']/100,2).' КР.';
		}elseif( $kr > $day2 ) {
			$u->error = 'Вы можете обменять еще '.$day2.' КР сегодня.';
		}elseif( $kr > $u->info['money']) {
			$u->error = 'Недостаточно денег для обмена.';
		}else{
			$ekr = round($kr / $c['crtoecr'],2);
			$u->error = 'Вы успешно обменяли '.$kr.' КР на '.$ekr.' ЕКР.';
			//
			$u->info['money'] -= $kr;
			$u->info['money2'] += $ekr;
			//			
			mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'",`money2` = "'.$u->info['money2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			mysql_query('INSERT INTO `user_operation` ( `uid`,`time`,`money`,`money2`,`type`,`b1`,`b2` ) VALUES (
				"'.$u->info['id'].'","'.time().'","'.round(-$kr,2).'","'.round($ekr,2).'","Обмен КР на ЕКР","'.$u->info['money'].'","'.$u->info['money2'].'"
			)');
		}
		$dc1 = mysql_fetch_array(mysql_query('SELECT SUM(`money2`) FROM `user_operation` WHERE `time` >= "'.$timetoday.'" AND `uid` = "'.$u->info['id'].'" AND `type` = "Обмен ЕКР на КР" LIMIT 1'));
		$dc2 = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `user_operation` WHERE `time` >= "'.$timetoday.'" AND `uid` = "'.$u->info['id'].'" AND `type` = "Обмен КР на ЕКР" LIMIT 1'));
		$dc1 = $dc1[0];
		$dc2 = $dc2[0];		
		$day1 = round($day1def+$dc1,2);
		$day2 = round($day2def+$dc2,2);
	}elseif(isset($_POST['ekr2'])) {
		//Обмен екр на кр.
		$ekr = round($_POST['ekr2'],2);
		if( $c['ecrtocr'] < 0.01 ) {
			$u->error = 'Сегодня обменник закрыт.';
		}elseif( $day1 < 0.01 ) {
			$u->error = 'Сегодня для вас обмен закрыт, приходите завтра.';
		}elseif( $ekr < 0.01 ) {
			$u->error = 'Минимальная сумма обмена 0.01 ЕКР.';
		}elseif( $ekr > $day1 ) {
			$u->error = 'Вы можете обменять еще '.$day1.' ЕКР сегодня.';
		}elseif( $ekr > $u->info['money2']) {
			$u->error = 'Недостаточно денег для обмена.';
		}else{
			$kr = round($ekr * $c['ecrtocr'],2);
			$u->error = 'Вы успешно обменяли '.$ekr.' ЕКР на '.$kr.' КР.';
			//
			$u->info['money'] += $kr;
			$u->info['money2'] -= $ekr;
			//			
			mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'",`money2` = "'.$u->info['money2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			mysql_query('INSERT INTO `user_operation` ( `uid`,`time`,`money`,`money2`,`type`,`b1`,`b2` ) VALUES (
				"'.$u->info['id'].'","'.time().'","'.round($kr,2).'","'.round(-$ekr,2).'","Обмен ЕКР на КР","'.$u->info['money'].'","'.$u->info['money2'].'"
			)');
		}
		$dc1 = mysql_fetch_array(mysql_query('SELECT SUM(`money2`) FROM `user_operation` WHERE `time` >= "'.$timetoday.'" AND `uid` = "'.$u->info['id'].'" AND `type` = "Обмен ЕКР на КР" LIMIT 1'));
		$dc2 = mysql_fetch_array(mysql_query('SELECT SUM(`money`) FROM `user_operation` WHERE `time` >= "'.$timetoday.'" AND `uid` = "'.$u->info['id'].'" AND `type` = "Обмен КР на ЕКР" LIMIT 1'));
		$dc1 = $dc1[0];
		$dc2 = $dc2[0];		
		$day1 = round($day1def+$dc1,2);
		$day2 = round($day2def+$dc2,2);
	}elseif(isset($_POST['login'])) {
		//
		function en_ru($txt) {
			$g = false;
			$en = preg_match("/^(([0-9a-zA-Z _-])+)$/i", $txt);
			$ru = preg_match("/^(([0-9а-яА-Я _-])+)$/i", $txt);
			if(($ru && $en) || (!$ru && !$en)) {
				$g = true;
			}
			return $g;
		}
		//
		function testBad($txt) {
			$white = '-_ 0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNMЁЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮёйцукенгшщзхъфывапролджэячсмитьбю';
			$r = false;
			$i = 0;
			while( $i != -1 ) {
				if( isset($txt[$i]) ) {
					$g = false;
					$j = 0;
					while( $j != -1 ) {
						if(isset($white[$j])) {
							if( $white[$j] == $txt[$i] ) {
								$g = true;
							}
						}else{
							$j = -2;
						}
						$j++;
					}
					if( $g == false ) {
						$r = true;
					}
				}else{
					$i = -2;
				}
				$i++;
			}
			return $r;
		}
			
		function is_login($login) {
			$r = true;
			//
			$login = htmlspecialchars($login,NULL,'cp1251');
			//
			$bad = array(
				'Мусорщик' => 1,
				'Мироздатель' => 1
			);
			//
			$login_db = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `login` = "'.mysql_real_escape_string($login).'" LIMIT 1'));
			if( isset($login_db['id']) || isset($bad[$login]) ) {
				$r = false;
			}else{
				$true = true;
				//
				/*
				Логин может содержать от 2 до 16 символов, и состоять только из букв русского ИЛИ английского алфавита, цифр, символов '_', '-' и пробела. 
				Логин не может начинаться или заканчиваться символами '_', '-' или пробелом.
				*/
				//
				$login = str_replace('	',' ',$login);
				$login = str_replace('%',' ',$login);
				$login = str_replace('&nbsp;',' ',$login);
				//
				if( strlen($login) > 16 ) {
					$true = false;
				}elseif( strlen($login) < 2 ) {
					$true = false;
				}elseif( strripos($login,'  ') == true ) {
					$true = false;
				}elseif( substr($login,1) == ' ' || substr($login,-1) == ' ' ) {
					$true = false;
				}elseif( substr($login,1) == '-' || substr($login,-1) == '-' ) {
					$true = false;
				}elseif( substr($login,1) == '_' || substr($login,-1) == '_' ) {
					$true = false;
				}elseif( testBad($login) == true ) {
					$true = false;
				}elseif( en_ru(str_replace('ё','е',str_replace('Ё','Е',$login))) == true ) {
					$true = false;
				}
				//
				if( $true == false ) {
					$r = false;
				}else{
					$r = true;
				}
			}
			return $r;
		}
		$ekr = 14.99;
		if( $u->info['login'] == $_POST['login'] ) {
			$u->error = 'Выберите другой логин...';
		}elseif( $u->info['money2'] < $ekr ) {
			$u->error = 'Недостаточно средств.';
		}else{
			$login = htmlspecialchars($_POST['login'],NULL,'cp1251');
			if( is_login($login) == true ) {
				mysql_query('INSERT INTO `lastnames` ( `uid`,`login`,`newlogin`,`time` ) VALUES (
					"'.$u->info['id'].'","'.$u->info['login'].'","'.mysql_real_escape_string($login).'","'.time().'"
				)');
				$u->info['login_last'] = $u->info['login'];
				$u->info['login'] = $login;
				$u->info['money'] =- $ekr;
				//
				mysql_query("UPDATE `items_users` SET `data` = replace( `data` , 'sudba=".$u->info['login_last']."', 'sudba=".mysql_real_escape_string($u->info['login'])."') WHERE `data` LIKE '%sudba=".mysql_real_escape_string($u->info['login_last'])."%' AND `uid` = '".$u->info['id']."'");
				//				
				mysql_query('UPDATE `users` SET `login` = "'.mysql_real_escape_string($u->info['login']).'", `money2` = "'.$u->info['money2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				$u->error = 'Вы успешно сменили логин на &quot;'.$u->info['login'].'&quot; перезайдите в игру с главной страницы.';
			}else{
				$u->error = 'Выберите другой логин...';
			}
		}
	}elseif(isset($_GET['buy_ekr'])) {
		//
		$mrh_login = "28706";
		$mrh_pass1 = "ugmhd7vi";
		//
		$inv_id = 0;
		//
		$out_ekr = round($_POST['ekr'],2);
		if( $out_ekr < 0) {
			//
			$out_ekr = 0;
		}
		$out_summ = round($out_ekr*$bt['cur'],2);
		$inv_desc = 'Приобрести '.$out_ekr.' екр., персонаж №'.$u->info['id'].', дилер №'.round((int)$_POST['ref']).'';
		//
		$shp_item = 0;
		//
		//Бонус опытовый, первичный и накопительный
		$out_ekr0 = $out_ekr;
		//
		$out_ekr += round($out_ekr0/100*$bns[$b1][1],2);
		$i = 0;
		while( $i < count($bns2) ) {
			if( isset($bns2[$i][0]) && $out_ekr >= $bns2[$i][0] ) {
				$b2_2 = $i;
			}
			$i++;
		}
		$out_ekr += round($out_ekr0/100*$bns2[$b2_2][1],2);
		if($ball == 0) {
			$out_ekr += round($out_ekr0/100*20,2);
		}
		//
		//Добавляем в базу
		mysql_query('INSERT INTO `pay_operation` (
			`uid`,`bank`,`code`,`ekr`,`time`,`good`,`cur`,`var`,`val`,`ref`,`ref2`,`ip`,`date`
		) VALUES (
			"'.$u->info['id'].'","'.$u->bank['id'].'","'.mysql_real_escape_string((int)$_GET['code']).'","'.mysql_real_escape_string($out_ekr).'",
			"'.time().'","0","'.mysql_real_escape_string($cur['cur']).'","buy_ekr","0","'.mysql_real_escape_string($u->info['host_reg']).'",
			"'.mysql_real_escape_string((int)$_GET['ref']).'","'.mysql_real_escape_string(IP).'","'.date('Y-m-d H:i:s').'"
		)');
		
		$shp_item = mysql_insert_id();
		
		if($shp_item > 0) {
			//ожидаем оплаты
		}else{
			die('Ошибка в обработке платежа, обратитесь к Администрации');
		}
		if($out_ekr < 1) {
			die('Минимальная сумма покупки 1 екр.');
		}
		//
		// предлагаемая валюта платежа
		// default payment e-currency
		$in_curr = "";
		
		// язык
		// language
		$culture = "ru";
		
		// формирование подписи
		// generate signature
		$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item");
		
		// форма оплаты товара
		// payment form
		$url  = 'http://www.free-kassa.ru/merchant/cash.php?';
		$url .= 'MrchLogin='.$mrh_login.'&';
		$url .= 'OutSum='.$out_summ.'&';
		$url .= 'InvId='.$inv_id.'&';
		$url .= 'Desc='.$inv_desc.'&';
		$url .= 'SignatureValue='.$crc.'&';
		$url .= 'Shp_item='.$shp_item.'&';
		$url .= 'IncCurrLabel='.$in_curr.'&';
		$url .= 'Culture='.$culture.'&';
		//
		header('location: '.$url);
		die();
		print "<html>".
			  "<script type=\"text/javascript\" src=\"js/jquery.js\"></script><form id=\'F1\' action='http://www.free-kassa.ru/merchant/cash.php' method=POST>".
			  "Сумма платежа: ".$out_ekr." Екр. ".
			  "<input type=hidden name=MrchLogin value=$mrh_login>".
			  "<input type=hidden name=OutSum value=$out_summ>".
			  "<input type=hidden name=InvId value=$inv_id>".
			  "<input type=hidden name=Desc value='$inv_desc'>".
			  "<input type=hidden name=SignatureValue value=$crc>".
			  "<input type=hidden name=Shp_item value='$shp_item'>".
			  "<input type=hidden name=IncCurrLabel value=$in_curr>".
			  "<input type=hidden name=Culture value=$culture>".
			  "<input type=submit value='Оплатить'><Br>".
			  "(Все средства идут на развитие и улучшение игры)".
			  "</form><script>$('#F1').sumbit();</script></html>";
		die();
	}
	//цены
	$ekr_pet = 14.99;
	$ekr_shadow = 49.99;
	$ekr_clan_shadow = 149.99;
	$ekr_smile = 8.99;
	$ekr_login = 14.99;
	$ekr_align = 14.99;

?>
<HTML>
<HEAD>
  <title>Покупка ЕвроКредитов - Старый Бойцовский Клуб</title>
  <!--<link rel=stylesheet type="text/css" href="/i/main.css">-->
  <script type="text/javascript" src="http://xcombats.com/res/js/jquery-1.7.1.min.js"></script>
  <meta content="text/html; charset=windows-1251" http-equiv=Content-Type>
 <link href="http://img.xcombats.com/css/main.css" rel="stylesheet" type="text/css">
  <META Http-Equiv=Cache-Control Content=no-cache>
  <meta http-equiv=PRAGMA content=NO-CACHE>
  <META Http-Equiv=Expires Content=0>

  <style type="text/css">

            .t, .t tr, .t tr td { font-family: Times New Roman; font-size: 16px;
                    border: 1px solid black; border-collapse: collapse; text-align: center; vertical-align: top; }
            .t { border: 3px solid black; border-collapse: collapse;}
            .t .al { text-align: left; }
            .t .vam { vertical-align: middle; }
            .t .ac { text-align: center; }
            .t .b { font-weight: bold; }
            .t .p { padding: 0px 5px 0px 5px; }
            .t .btop { border-top: 3px solid black; border-collapse: collapse;}
            .t .bright { border-right: 3px solid black; border-collapse: collapse;}
            .t .bleft { border-left: 3px solid black; border-collapse: collapse;}
            .t .bbottom { border-bottom: 3px solid black; border-collapse: collapse;}
            .t .light { background: #D6F5D3; color: green; font-weight: bold;}

            </style>

</HEAD>
<body bgcolor="#dedede">
<div id="main">

<!--<br />
<h3>Покупка ЕвроКредитов</h3>
                             -->
<table style="width:98%; margin: auto;">
<!--
<tr><td colspan="2" style='font-weight:bold; color:red; padding: 10px 0 10px 0; font-size: 1.5em;'><center>Пополнение ЕКР временно приостановлено!</center></td></tr>
-->
  <? if($ball == 0) { ?>
  <tr><td colspan="2" style='font-weight:bold; color:brown; padding: 10px 0 10px 0; font-size: 1.2em;'><center style="color:red">Акция! При первом пополнении баланса,<br />Вы получаете дополнительно 20% ЕКР к сумме покупки БЕСПЛАТНО.</center></td></tr>
  <? }
  	
	if(isset($u->info['id'])) { ?>
  <center class="h3css"><? echo '<div style="padding:10px; border-bottom:1px solid #fff;">Персонаж: '.$u->microLogin($u->info['id'],1).'</div>';?></center>
  <? }
  if( $u->error != '' ) {
	 echo '<div style="padding:10px;"><b><font color="red">'.$u->error.'</font></b></div>'; 
  }
  ?>
  <tr>
  <td style="width: 500px; padding: 10px; vertical-align: top;">
    <fieldset style="border: 1px solid white; padding: 10px;margin-top:15px;">
    <b><span style='color:#8F0000;'>Ваш накопительный бонус:</span> <font color=green><?=$bns[$b1][1]?>% (<?=$ball?> ЕКР)</font></b>
    </fieldset>

    <fieldset style="width:480px; border: 1px solid white; padding: 10px;margin-top:15px; padding-bottom:10px;">
      <!--<legend style='font-weight:bold; color:#8F0000;'>Покупка ЕКР</legend>

      <form method="post" id="ekrform" action="ekr.php?buy_ekr=1" onsubmit="if(document.getElementById('ch_1').checked==false) {alert('Вы не согласились с пользовательским соглашением.');return false;} else {if(document.getElementById('ch_2').checked==false) {alert('Вы не согласились с условиями оплаты.');return false;};}; if(document.getElementById('ekr').value<1) {alert('Нельзя купить менее 1 ЕКР!');return false;};">
      <b>Сумма ЕКР:</b> <input type="text" name="ekr" id="ekr" value="" size="8" onchange="calc();" onkeyup="if(event.keyCode<35||event.keyCode>40) calc();"> &nbsp; <input type="submit" class="btn btn-success" value="Пополнить баланс"><br />

      <!--<input type="button" class="btn btn-primary" value="Оплатить с помощью VISA / MASTERCARD" onclick="$('#ekrform').attr('action','ekr_fk_go.php'); $('#ekrform').submit();" style="width: 461px;">--><script>
      function bonus_n(a) { var pr = <?=(0+$bns[$b1][1])?>; return (Math.floor( (a*pr/100) *100)/100);};
      function bonus_s(a) { if(a>=100) {pr=10;} else if(a>=90) {pr=9;} else if(a>=80) {pr=8;} else if(a>=70) {pr=7;} else if(a>=60) {pr=6;} else if(a>=50) {pr=5;} else if(a>=40) {pr=4;} else if(a>=30) {pr=3;} else if(a>=20) {pr=2;} else if(a>=10) {pr=1;} else {pr=0;} return (Math.floor( (a*pr/100) *100)/100);};
      function calc() {
        document.getElementById('ekr').value = document.getElementById('ekr').value.replace(/[^\d\.]+/g,'');
        //if(document.getElementById('ekr').value<0.1) document.getElementById('ekr').value=0.1;
        //if(document.getElementById('ekr').value>120) document.getElementById('ekr').value=120;
        var ekr = document.getElementById('ekr').value;
        if (ekr.match(/^[-\+]?[\d]+\.?[\d]*$/) === null) {ekr=0;}

        ekr4 = ekr = Math.round(ekr*100)/100;
		var ekr4 = ekr.toFixed(2);

		if(document.getElementById('ekr').value!=ekr) {
			document.getElementById('ekr').value=ekr;
		}
                                            //alert(ekr);
        var ekr2 = bonus_n(ekr);    //alert(ekr2);
        var ekr3 = bonus_s(ekr);              //alert(ekr3);
        var ekr7 = 0;
        ekr2 = Math.floor(ekr2*100)/100;
        ekr3 = Math.floor(ekr3*100)/100;
        var ekr7 = 0;
		<? if($ball == 0) { ?>
		ekr7 = Math.floor((ekr/5)*100)/100;
		<? } ?>
        //var ekrr = parseFloat(ekr) + parseFloat(ekr2) + parseFloat(ekr3);
        var ekrr = ekr + ekr2 + ekr3 + ekr7;
        ekrr = Math.round(ekrr*100)/100;

        //document.getElementById('calc').innerHTML = ekr+' + '+ekr2+' + '+ekr3+' = '+ekrr+' ЕКР';        
        document.getElementById('calc').innerHTML = 'Накопительный бонус: <font color=green>'+ekr2+' ЕКР</font><br />Оптовый бонус: <font color=green>'+ekr3+' ЕКР</font><? if($ball == 0) { ?><br />Акция на 1-ое пополнение: <font color=red>'+ekr7+' ЕКР</font><? } ?><br />Итого: <font color=green>'+ekrr+' ЕКР</font>';
        }
      calc();
      </script>

      <table style="border: 1px solid white; margin: auto; width: 400px;">
        <tr style="border-bottom: 1px solid white;"><td style="border-right: 1px solid white;padding:2px;">1 ЕКР</td><td style="padding:2px;"><?=round($bt['cur'],2)?> Рублей</td></tr>
        <tr style="border-bottom: 1px solid white;"><td style="border-right: 1px solid white;padding:2px;">1 ЕКР</td><td style="padding:2px;"><?=round($bt['cur']/$bt['USD'],2)?> Доллара *</td></tr>
              </table>
    <fieldset style="border: 1px solid white; padding: 20px 11px 21px 11px;margin-top:15px;">
      <legend style='font-weight:bold; color:#8F0000;'>Накопительные бонусы</legend>
            <table class="t" style="width: 100%; margin: auto;">
        <tr class="b"><td>ЕКР</td><td class="bright ">Бонус</td><td>ЕКР</td><td>Бонус</td></tr>
        <tr>
          <td class="">2 400 ЕКР</td><td class="bright ">10%</td>
          <td class="">9 000 ЕКР <img src="/res/img/medals/vip.gif" title="VIP" width=20 style="margin-top:2px;" /></td><td class="">30%</td></tr>
        <tr>
          <td class="">2 100 ЕКР</td><td class="bright ">9%</td>
          <td class="">7 500 ЕКР <img src="/res/img/medals/vip.gif" title="VIP" width=20 style="margin-top:2px;" /></td><td class="">27%</td></tr>
        <tr>
          <td class="">1 800 ЕКР</td><td class="bright ">8%</td>
          <td class="">6 000 ЕКР <img src="/res/img/medals/vip.gif" title="VIP" width=20 style="margin-top:2px;" /></td><td class="">25%</td></tr>
        <tr>
          <td class="">1 500 ЕКР</td><td class="bright ">7%</td>
          <td class="">5 100 ЕКР <img src="/res/img/medals/vip.gif" title="VIP" width=20 style="margin-top:2px;" /></td><td class="">23%</td></tr>
        <tr>
          <td class="">1 200 ЕКР</td><td class="bright ">6%</td>
          <td class="">4 500 ЕКР <img src="/res/img/medals/vip.gif" title="VIP" width=20 style="margin-top:2px;" /></td><td class="">21%</td></tr>
        <tr>
          <td class="">900 ЕКР</td><td class="bright ">5%</td>
          <td class="">3 900 ЕКР <img src="/res/img/medals/vip.gif" title="VIP" width=20 style="margin-top:2px;" /></td><td class="">19%</td></tr>
        <tr>
          <td class="">600 ЕКР</td><td class="bright ">4%</td>
          <td class="">3 600 ЕКР <img src="/res/img/medals/vip.gif" title="VIP" width=20 style="margin-top:2px;" /></td><td class="">17%</td></tr>
        <tr>
          <td class="">300 ЕКР</td><td class="bright ">3%</td>
          <td class="">3 300 ЕКР <img src="/res/img/medals/vip.gif" title="VIP" width=20 style="margin-top:2px;" /></td><td class="">15%</td></tr>
        <tr>
          <td class="">150 ЕКР</td><td class="bright ">2%</td>
          <td class="">3 000 ЕКР <img src="/res/img/medals/vip.gif" title="VIP" width=20 style="margin-top:2px;" /></td><td class="">13%</td></tr>
        <tr>
          <td class="">30 ЕКР</td><td class="bright ">1%</td>
          <td class="">2 700 ЕКР</td><td class="">11%</td></tr>
      </table>
    </fieldset>
     <!-- <small>
      * - зависит от текущих курсов валют<br />
      <b>Доставка игровой валюты производится в автоматическом режиме, сразу же  после оплаты!</b>
      <br /><br />
      При оплате могут возникать задержки на пополнение, обычно не более 1 часа. Если по истечению нескольких часов деньги так и не поступили на ваш баланс, то необходимо обратиться в <a href="http://www.free-kassa.ru/support.php" target="_blank">службу поддержки FREE-KASSA</a>.
      </small>
      <br /><br />
      </form>

      <small>
      <label><input type="checkbox" name="ch1" id="ch_1" /> Внимание! При пополнении баланса вы соглашаетесь с <a href="http://xcombats.com/encicl/law2.html" target="_blank">соглашением о предоставлении сервиса игры &laquo;Легендарный Бойцовский Клуб&raquo;</a>.</label>
      
      <br />
      <label><input type="checkbox" name="ch2" id="ch_2" /> Все комиссии платёжных систем Вы оплачиваете за свой счёт.</label>
      
      <br /><br />
      Если Вы очень хотели бы положить деньги на игровой счёт, но нет подходящей платёжной системы, можем посоветовать воспользоваться <a href="http://www.bestchange.ru" target="_blank">обменными пунктами</a>, либо воспользоваться услугами <b>дилеров</b>.
      <br /><br />
      <b>Пополняя свой игровой счёт, Вы тем самым спонсируете проект. ВСЕ ваши вложенные деньги в игру будут идти ТОЛЬКО на её же развитие.</b>
      </small>-->
	  
    </fieldset></td>


  <td style="padding: 10px; vertical-align: top;">
    <fieldset style="border: 1px solid white; padding: 10px;margin-top:15px;">
    <b><span style='color:#8F0000;'>Ваш баланс счёта: <font color=green><b><?=$u->info['money2']?> ЕКР</b></font> и <font color=black><b><?=$u->info['money']?> КР</b></font>.</span></b>
    </fieldset>

    <fieldset style="border: 1px solid white; padding: 10px;margin-top:15px;">
      <legend style='font-weight:bold; color:#8F0000;'>Обмен</legend>
      <form method="post" action="ekr.php" onsubmit="if(document.getElementById('ekr2').value><?=$day1?>) {alert('Сегодня вы можете еще обменять не более <?=$day1?> ЕКР');return false;} else if(document.getElementById('ekr2').value<0.01||document.getElementById('ekr2').value><?=$day1?>) {alert('За 1 раз Вы можете обменять сумму от 0.01 до <?=$day1?> ЕКР.');return false;} else {return confirm('Вы действительно хотите обменять '+document.getElementById('ekr2').value+' ЕКР на '+(document.getElementById('ekr2').value*<?=$c['ecrtocr']?>)+' КР ? В обратном направлении обмен с КР на ЕКР будет невозможен.');};">
      Обменять ЕКР на КР по курсу <b>1ЕКР=<?=$c['ecrtocr']?>КР</b>: &nbsp; <input style="font-size:10pt;padding:5px;" type="text" name="ekr2" id="ekr2" value="" size="5" placeholder="<?=$day1?> max" onchange="calc22();" onkeyup="if(event.keyCode<35||event.keyCode>40) calc22();"> &nbsp; <input type="submit" class="btnnew" name="submit" id="calc2" value="Обменять"><br />
      </form>
      <? if( $c['crtoecr'] > 0 ) { ?>
      <form method="post" action="ekr.php" onsubmit="if(document.getElementById('kr001').value<<?=round($c['crtoecr']/100,2)?>) {alert('Минимальная сумма обмена <?=round($c['crtoecr']/100,2)?> КР');return false;}else if(document.getElementById('kr001').value><?=$day2?>) {alert('Сегодня вы можете еще обменять не более <?=$day2?> КР');return false;} else {return confirm('Вы действительно хотите обменять '+document.getElementById('kr001').value+' КР на '+(Math.round(document.getElementById('kr001').value/10)/100)+' ЕКР? Отменить операцию обмена будет невозможно.');};">
      Обменять КР на ЕКР по курсу <b><?=$c['crtoecr']?>КР=1ЕКР</b>: <input type="text" style="font-size:10pt;padding:5px;margin-left:1px;" name="kr001" id="kr001" value="" placeholder="<?=$day2?> max" size="5" onchange="calc24();" onkeyup="if(event.keyCode<35||event.keyCode>40) calc23();"> &nbsp; <input type="submit" class="btnnew" name="submit" id="calc3" value="Обменять"><br />
      </form>
      <? } ?>
      <script>
      function calc23() {
          document.getElementById('kr001').value = document.getElementById('kr001').value.replace(/[^\d]+/g,'');
          var kr001 = document.getElementById('kr001').value;
          kr001 = Math.floor(kr001/10)*10;
          var kr001ekr = kr001/<?=$c['crtoecr']?>;
          //kr001ekr = kr001ekr.toFixed(2);

          //document.getElementById('kr001').value = kr001;
          
          document.getElementById('calc3').value = 'Обменять '+kr001+' КР на '+kr001ekr+' ЕКР';
          return kr001;
          }

      function calc24() {
          document.getElementById('kr001').value = calc23();
          if(document.getElementById('kr001').value==0) document.getElementById('kr001').value='';
          }

      calc24();
      function calc22() {
          document.getElementById('ekr2').value = document.getElementById('ekr2').value.replace(/[^\d\.]+/g,'');
          var ekre = document.getElementById('ekr2').value;
          if(ekre.match(/^[-\+]?[\d]+\.?[\d]*$/) === null) { ekre=0; }

          ekre = Math.floor(ekre*100)/100;
          var ekr4 = ekre.toFixed(2);

          if(document.getElementById('ekr2').value!=ekre) { document.getElementById('ekr2').value=ekr4; }
          var kre = parseFloat(ekre) * <?=$c['ecrtocr']?>;

          document.getElementById('calc2').value = 'Обменять '+ekre+' ЕКР на '+kre.toFixed(0)+' КР';
          }
      calc22();
      </script>
    </fieldset>

    <fieldset style="border: 1px solid white; padding: 18px 12px 18px 12px;margin-top:15px;">
      <legend style='font-weight:bold; color:#8F0000;'>Оптовые бонусы</legend>

      <table class="t" style="width: 500px; margin: auto;">
        <tr class="b light"><td>Бонус</td><td>1%</td><td>2%</td><td>3%</td><td>4%</td><td>5%</td></tr>
        <tr style="border-bottom: 2px solid black;"><td class="b">Сумма</td>
        <td>30 ЕКР</td>
        <td>60 ЕКР</td>
        <td>90 ЕКР</td>
        <td>120 ЕКР</td>
        <td>150 ЕКР</td></tr>
        <tr class="b light"><td class="b">Бонус</td><td>6%</td><td>7%</td><td>8%</td><td>9%</td><td>10%</td></tr>
        <tr><td class="b">Сумма</td>
        <td>180 ЕКР</td>
        <td>210 ЕКР</td>
        <td>240 ЕКР</td>
        <td>270 ЕКР</td>
        <td>300 ЕКР</td></tr>
      </table>
    </fieldset>

  </td>
</tr>

<!--
<tr><td colspan="2" style='font-weight:bold; padding: 10px; font-size: 0.8em;'><center>При оплате с кредитных карт могут возникать задержки на пополнение, обычно не более 1 часа. Так же часто происходит отказ в пополнении через кредитные карты, для этого необходимо выбрать в списке ДРУГОЙ сервис пополнения с кредитных карт, либо пополнять более мелкими частями.</center></td></tr>
-->

<tr><td colspan="2" style='font-weight:bold; color:#8F0000; padding: 10px 0 10px 0; font-size: 1.5em;'><center>Коммерческие услуги</center></td></tr>
<tr><td style="padding: 10px; vertical-align: top;">

    <!-- komplekt form -->
  				<div class="komplekt-form" id="theLayer" style="position: absolute; left: -300px; top: 160px; visibility:hidden; width: 150px; height:320px;">
    				<div class="form-title" id="titleBar">
    				 <label id="ftitle">Просмотр</label>
    				 <button type="button" class="close" onclick="document.getElementById('theLayer').style.visibility = 'hidden';return false" data-dismiss="modal" aria-hidden="true">x</button>
    				</div>
    				<div class="form">
              <form method="post" id="fform" action="ekr.php">
              <input type="hidden" name="do" value="newShadow" />
              <input type="hidden" id="ffinput" name="ffinput" value="" />
    			<center>
                <img src="" id="ffimg" width="120" height="220" style="margin-bottom: 5px;" /><br>
                 <div style="padding:5px;">
                  <select name="ffsex" id="ffsex">
                    <option value="0" <? if($u->info['sex'] == 0) { echo 'selected="selected"'; }?> >Мужской образ</option>
                    <option value="1" <? if($u->info['sex'] == 1) { echo 'selected="selected"'; }?> >Женский образ</option>
                  </select>
				</div>
                <div style="width:240px; margin-left:-6px;background:#cbc4aa;padding:5px 0px 5px 0px;border:1px solid #cbc4aa;background-color:#eee;border:1px solid #999;border:1px solid rgba(0,0,0,0.3);*border:1px solid #999;-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;outline:none;-webkit-box-shadow:0 3px 7px rgba(0,0,0,0.3);-moz-box-shadow:0 3px 7px rgba(0,0,0,0.3);box-shadow:0 3px 7px rgba(0,0,0,0.3);-webkit-background-clip:padding-box;-moz-background-clip:padding-box;background-clip:padding-box;">
                <input type="button" class="btn btn-success" value="Подтвердить" style="height: 28px; line-height: 20px; width: 100px;font-size:13px;" onclick="if(confirm('Действительно хотите купить это изображение?')) $('#fform').submit();" />
                <input type="button" class="btn btn-danger" value="Отменить" onclick="document.getElementById('theLayer').style.visibility = 'hidden';return false" style="height: 28px; line-height: 20px; width: 100px;font-size:13px;" />
             	</div>
              </center>
              </form>
    		  </div>
  			  </div>
		  <!-- end komplekt form -->
    <fieldset style="width:480px; border: 1px solid white; padding: 10px;margin-top:15px;">
      <legend style='font-weight:bold; color:#8F0000;'>Покупка личного образа</legend>
      <small>
      <b>Стоимость услуги: <?=$ekr_shadow?> ЕКР</b><br>
      Требования к персональному образу:<br />
      GIF-картинка размером 120x220 (ШхВ) и весом до 300 Кб.<br />
      <br>
      Выберите картинку: <input type="file" id="imgFile" class="btnnew" />
      <script>

      function showImgPreview(img) {
        document.getElementById('theLayer').style.visibility = "visible";
        document.getElementById('theLayer').style.left = 300;
        document.getElementById('theLayer').style.top = 300;
        $('img#ffimg').attr('src','http://xcombats.com/clan_prw/'+img);
        document.getElementById('ffinput').value = img;
        $('img#ffimg').attr('height','220');
        document.getElementById('theLayer').style.height = 290;
      }

      function showImgPreviewPet(img) {
        document.getElementById('theLayer').style.visibility = "visible";
        document.getElementById('theLayer').style.left = 300;
        document.getElementById('theLayer').style.top = 300;
        $('img#ffimg').attr('src','http://xcombats.com/clan_prw/'+img);
        $('img#ffimg').attr('height','40');
        //$('#theLayer').attr('height','120');
        document.getElementById('ffinput').value = img;
        document.getElementById('ffsex').style.display = 'none';
        document.getElementById('theLayer').style.height = 105;
        $('html, body').animate({scrollTop:0}, 'slow');
      }
	  
      function showImgPreviewSmile(img) {
        document.getElementById('theLayer').style.visibility = "visible";
        document.getElementById('theLayer').style.left = 300;
        document.getElementById('theLayer').style.top = 300;
        $('img#ffimg').attr('src','http://xcombats.com/clan_prw/'+img);
		$('img#ffimg').attr('width',null);
		$('img#ffimg').attr('height',null);
        document.getElementById('ffinput').value = img;
        document.getElementById('ffsex').style.display = 'none';
        document.getElementById('theLayer').style.height = 105;
        $('html, body').animate({scrollTop:0}, 'slow');
      }

      $('#imgFile').change(function(){
        var fd = new FormData();
        fd.append('type', 'person');
        fd.append('img', $('#imgFile')[0].files[0]);
        $.ajax({
          type: 'POST',
          url: 'ekr.php',
          data: fd,
          processData: false,
          contentType: false,
          dataType: "json", // поменять на json
          success: function(data) { if(data['img']!=undefined) { document.getElementById('ffsex').style.display = 'none';showImgPreview(data['img']) } else {alert(data['err']);} },
          error: function(data) { alert('Ошибка AJAX.') }
          });
        })
      </script>
      </small>
    </fieldset>

    


    
    <fieldset style="border: 1px solid white; padding: 10px;margin-top:15px; padding-bottom: 15px;">
      <legend style='font-weight:bold; color:#8F0000;'>Покупка образа питомца</legend>
      <small>
      <b>Стоимость услуги: <?=$ekr_pet?> ЕКР</b><br>
      Требования к образу питомца:<br />
      GIF-картинка размером 120x220 (ШхВ) и весом до 300 Кб.<br />
      <br>
      Выберите картинку: <input class="btnnew" type="file" id="imgFile4" />
      <script>

      $('#imgFile4').change(function(){
        var fd = new FormData();
        fd.append('type', 'person');
        fd.append('img4', $('#imgFile4')[0].files[0]);
        $.ajax({
          type: 'POST',
          url: 'ekr.php',
          data: fd,
          processData: false,
          contentType: false,
          dataType: "json", // поменять на json
          success: function(data) { if(data['img']!=undefined) { showImgPreviewPet(data['img']) } else {alert(data['err']);} },
          error: function(data) { alert('Ошибка AJAX.'); }
          });
        })
      </script>
      </small>
    </fieldset>
    
    
    
    
    <fieldset style="border: 1px solid white; padding: 10px;margin-top:15px; padding-bottom: 15px;">

      <legend style='font-weight:bold; color:#8F0000;'>Покупка личного смайлика</legend>
      <small>
      <b>Стоимость услуги: <?=$ekr_smile?> ЕКР</b><br>
      Требования к смайлику:<br />
      GIF-картинка размером от 15x15 до 95x45 (ШхВ) и весом до 30 Кб.<br />
      <br>
      Выберите картинку: <input class="btnnew" type="file" id="imgFile5" />
      <script>

      $('#imgFile5').change(function(){
        var fd = new FormData();
        fd.append('type', 'person');
        fd.append('img5', $('#imgFile5')[0].files[0]);
        $.ajax({
          type: 'POST',
          url: 'ekr.php',
          data: fd,
          processData: false,
          contentType: false,
          dataType: "json", // поменять на json
          success: function(data) { if(data['img']!=undefined) { showImgPreviewSmile(data['img']) } else {alert(data['err']);} },
          error: function(data) { alert('Ошибка AJAX.'); }
          });
        })
      </script>
      </small>
    </fieldset>
    


    


</td><td style="padding: 10px; vertical-align: top;">



    <fieldset style="border: 1px solid white; padding: 10px;margin-top:15px;">
      <legend style='font-weight:bold; color:#8F0000;'>Покупка кланового образа</legend>
      <small>
      <b>Стоимость услуги: <?=$ekr_clan_shadow?> ЕКР</b><br>
      Требования к клановому образу:<br />
      GIF-картинка размером 120x220 (ШхВ) и весом до 300 Кб.<br />
      <br>
      Выберите картинку: <input class="btnnew" type="file" id="imgFile2" />
      <script>

      $('#imgFile2').change(function(){
        var fd = new FormData();
        fd.append('type', 'person');
        fd.append('img2', $('#imgFile2')[0].files[0]);
        $.ajax({
          type: 'POST',
          url: 'ekr.php',
          data: fd,
          processData: false,
          contentType: false,
          dataType: "json", // поменять на json
          success: function(data) { if(data['img']!=undefined) { document.getElementById('ffsex').style.display = 'block'; showImgPreview(data['img']) } else {alert(data['err']);} },
          error: function(data) { alert('Ошибка AJAX.'); }
          });
        })
      </script>
      </small>
    </fieldset>


    <fieldset style="border: 1px solid white; padding: 10px;margin-top:15px;">
      <legend style='font-weight:bold; color:#8F0000;'>Смена имени персонажа</legend>
      <small>
      <b>Стоимость услуги: <?=$ekr_login?> ЕКР</b><br>
      Текущее имя: <?=$u->info['login']?><br>
      <form method="post" action="ekr.php" id="lform">
        <input type="hidden" name="do" value="changeLogin" />
        <input type="text" name="login" id="llogin" onkeyup="check_login();" size=35 placeholder="Введите новое имя.." style="font-size:10pt;padding:5px;margin: 5px 0 5px 0;" /> <span id="ajaxLogin"></span><br>
        <input type="button" class="btnnew" value="Сменить имя" onclick="if(confirm('Действительно хотите сменить имя?')) $('#lform').submit();" />
      </form>
      <script>
      function check_login() {
        $("#ajaxLogin").html('<b>Проверка доступности...</b>');
        $.ajax({
          url: "ajax_checklogin.php?login="+$('#llogin').val(),
          cache: false
          }).done(function( html ) {
            $("#ajaxLogin").html(html);
            });
        }
      </script>
      </small>
    </fieldset>
	<?
	if($_GET['align1']=='1') {
		$clan = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`align` FROM `clan` WHERE `id` = "'.$u->info['clan'].'" LIMIT 1'));
		$price = 14.99;
		
		if($u->info['money2'] < $price) {
			echo '<font color=red><b>У вас недостаточно средств :)</font>';
		}else{
			$u->info['money2'] -= $price;
			mysql_query('UPDATE `clan` SET `align` = "1" WHERE `id` = "'.$u->info['clan'].'" LIMIT 1');
			mysql_query('UPDATE `users` SET `align` = "1" WHERE `clan` = "'.$clan['id'].'"');
			mysql_query('UPDATE `users` SET `money2` = "'.$u->info['money2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			echo '<font color=red><b>Вы успешно сменили склонность клану <img src=http://'.$c['img'].'/i/align/align'.$clan['align'].'.gif><img src=http://'.$c['img'].'/i/clan/'.$clan['name'].'.gif>'.$clan['name'].'</font></b><br> <font color=green><b> С вас снятно <u>'.$price.'</u> ЕКР.</font></b>';
		}
	}
	
	elseif($_GET['align7']=='7') {
		$clan = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`align` FROM `clan` WHERE `id` = "'.$u->info['clan'].'" LIMIT 1'));	
		$price = 14.99;		
		if($u->info['money2'] < $price) {
			echo '<font color=red><b>У вас недостаточно средств :)</font>';
		}else{
			$u->info['money2'] -= $price;
			mysql_query('UPDATE `clan` SET `align` = "7" WHERE `id` = "'.$u->info['clan'].'" LIMIT 1');
			mysql_query('UPDATE `users` SET `align` = "7" WHERE `clan` = "'.$clan['id'].'"');
			mysql_query('UPDATE `users` SET `money2` = "'.$u->info['money2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			echo '<font color=red><b>Вы успешно сменили склонность клану <img src=http://'.$c['img'].'/i/align/align'.$clan['align'].'.gif><img src=http://'.$c['img'].'/i/clan/'.$clan['name'].'.gif>'.$clan['name'].'</font></b><br> <font color=green><b> С вас снятно <u>'.$price.'</u> ЕКР.</font></b>';
		}
	}
	
	elseif($_GET['align3']=='3') {
		$clan = mysql_fetch_array(mysql_query('SELECT `id`,`name`,`align` FROM `clan` WHERE `id` = "'.$u->info['clan'].'" LIMIT 1'));
		$price = 4.99;
		if($u->info['money2'] < $price) {
			echo '<font color=red><b>У вас недостаточно средств :)</font>';
		}elseif($clan['align'] == 3) {
			echo '<font color=red><b>У вашего клана Темная склонность...</font></b>';
		}else{
			$u->info['money2'] -= $price;
			mysql_query('UPDATE `clan` SET `align` = "3" WHERE `id` = "'.$u->info['clan'].'" LIMIT 1');
			mysql_query('UPDATE `users` SET `align` = "3" WHERE `clan` = "'.$clan['id'].'"');
			mysql_query('UPDATE `users` SET `money2` = "'.$u->info['money2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			echo '<font color=red><b>Вы успешно сменили склонность клану <img src=http://'.$c['img'].'/i/align/align'.$clan['align'].'.gif><img src=http://'.$c['img'].'/i/clan/'.$clan['name'].'.gif>'.$clan['name'].'</font></b><br> <font color=green><b> С вас снятно <u>'.$price.'</u> ЕКР.</font></b>';
		}
	}
	?>
	<? if($u->info['clan_prava'] == 'glava') { ?>
	<fieldset style="border: 1px solid white; padding: 10px;margin-top:15px;">
      <legend style='font-weight:bold; color:#8F0000;'>Изменение склонности клана:</legend>
	  <b>Стоимость услуги: <?=$ekr_align?> ЕКР</b><br>
 <img src = "http://img.xcombats.com/i/align/align1.gif"> -
 <input type=button value="Выбрать склонность" class="btnnew" onClick="location.href='ekr.php?align1=1'"> 
 - 
<b><u>Светлая</b></u> &nbsp;<br><hr><br>
 <img src = "http://img.xcombats.com/i/align/align7.gif"> -
 <input type=button value="Выбрать склонность" class="btnnew" onClick="location.href='ekr.php?align7=7'">
<b>- <u>Нейтральная</b></u> &nbsp;<br><hr><br>
 <img src = "http://img.xcombats.com/i/align/align3.gif"> -
 <input type=button value="Выбрать склонность" class="btnnew" onClick="location.href='ekr.php?align3=3'">
<b>- <u>Темная</b></u>  &nbsp;<br><hr><br>
    </fieldset>
	<?}?>
    
</td></tr>

<tr><td colspan="2">&nbsp;</td></tr>


</table>

</div>
</BODY>
</HTML>
<?
}
?>