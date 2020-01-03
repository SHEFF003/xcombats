<?php
if(!defined('GAME'))
{
	die();
}
	if(!function_exists('send_mime_mail')) {
		function send_mime_mail($name_from, // имя отправителя
						   $email_from, // email отправителя
						   $name_to, // имя получателя
						   $email_to, // email получателя
						   $data_charset, // кодировка переданных данных
						   $send_charset, // кодировка письма
						   $subject, // тема письма
						   $body // текст письма
						   )
		   {
		  $to = mime_header_encode($name_to, $data_charset, $send_charset)
						 . ' <' . $email_to . '>';
		  $subject = mime_header_encode($subject, $data_charset, $send_charset);
		  $from =  mime_header_encode($name_from, $data_charset, $send_charset)
							 .' <' . $email_from . '>';
		  if($data_charset != $send_charset) {
			$body = iconv($data_charset, $send_charset, $body);
		  }
		  $headers = "From: $from\r\n";
		  $headers .= "Content-type: text/html; charset=$send_charset\r\n";
		
		  return mail($to, $subject, $body, $headers);
		}
	
		function mime_header_encode($str, $data_charset, $send_charset) {
		  if($data_charset != $send_charset) {
			$str = iconv($data_charset, $send_charset, $str);
		  }
		  return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
		}
	}

if($u->room['file']=='an/bank')
{
	$noc = 60; //120 kr = 1 ekr.
	$con = 20; //1 екр. = 30 кр.
	function getNum($v)
	{
		$plid = $v;
		$pi = iconv_strlen($plid);
		if($pi<5)
		{
			$i = 0;
			while($i<=5-$pi)
			{
				$plid = '0'.$plid;
				$i++;
			}
		}
		return $plid;
	}
	function getNumId($v)
	{
		$plid = $v;
		$array = str_split($plid);
		$ends=0;
		$result='';
		for($i=0,$end=(count($array)-1);$i<=$end;$i++){
		   if($array[$i]==0 and $ends==0){$array[$i]='';}else{$ends=1;}
		   $result.=$array[$i];
		}
		//print_r($array);
		return $result;
	}
	
	if($u->info['allLock'] > time()) {
		$u->bank = false;	
	}
	
	$re2 = '';
	if(isset($_GET['enter']) && !isset($u->bank['id']))
	{
		$bank = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" AND `id` = "'.mysql_real_escape_string((int)$_POST['bank']).'" LIMIT 1'));
		if(!isset($bank['id']))
		{
			$re2 = 'Неверный номер счета.';
		}elseif($bank['pass']!=$_POST['pass'])
		{
			$pl = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `time` > "'.(time()-60*60).'" AND `vars` = "bank_bad_pass_'.mysql_real_escape_string($bank['id']).'" LIMIT 5'));
			if($pl[0]>=3)
			{
				$re2 = 'Ваш счет был заблокирован на 1 час';
			}else{
				if($pl[0]==0)
				{
					$re2 = 'Неверный номер счета или пароль. Если вы трижды введете неверный номер счета или пароль, счет будет заблокирован на час';
				}else{
					$pp = array(0=>'ок',1=>'ка',2=>'ки',3=>'ки');
					$re2 = 'Неверный номер счета или пароль. У вас осталось '.(3-$pl[0]).' попыт'.$pp[3-$pl[0]].', в противном случаи счет будет заблокирован на час';
				}
				mysql_query('INSERT INTO `actions` (`uid`,`time`,`city`,`room`,`vars`,`ip`) VALUES ("'.$u->info['id'].'","'.time().'","'.$u->info['city'].'","'.$u->info['room'].'","bank_bad_pass_'.mysql_real_escape_string($bank['id']).'","'.mysql_real_escape_string($_SERVER['HTTP_X_REAL_IP']).'")');
			}
		}else{
			
			if($u->info['allLock'] > time()) {
				echo '<script>setTimeout(function(){alert("Вам запрещено пользоваться услугами банка до '.date('d.m.y H:i',$u->info['allLock']).'")},250);</script>';
			}else{			
				//вошли!
				$bank['useNow'] = time()+12*60*60;
				mysql_query('UPDATE `bank` SET `useNow` = "0" WHERE `id` != "'.$bank['id'].'" AND `uid` = "'.$u->info['id'].'" AND `useNow`!="0" LIMIT 1');
				mysql_query('UPDATE `bank` SET `useNow` = "'.$bank['useNow'].'" WHERE `id` = "'.$bank['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
				mysql_query('INSERT INTO `actions` (`uid`,`time`,`city`,`room`,`vars`,`ip`) VALUES ("'.$u->info['id'].'","'.time().'","'.$u->info['city'].'","'.$u->info['room'].'","bank_good_pass_'.mysql_real_escape_string($bank['id']).'","'.mysql_real_escape_string($_SERVER['HTTP_X_REAL_IP']).'")');
				$u->bank = $bank;
			}
		}		
	}elseif(isset($_GET['res']))
	{
	//echo $_GET['schet'].'<br>';
		$b_pass = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" AND `id` = "'.mysql_real_escape_string(getNumId($_GET['schet'])).'" ORDER BY `id` DESC LIMIT 1'));
		if($b_pass['repass'] >= time())
		{
			$re2 = 'Номера счетов и пароли к ним можно выслать только один раз в сутки';
		}else{
			mysql_query('INSERT INTO `actions` (`uid`,`time`,`city`,`room`,`vars`,`ip`) VALUES ("'.$u->info['id'].'","'.time().'","'.$u->info['city'].'","'.$u->info['room'].'","bank_res","'.mysql_real_escape_string($_SERVER['HTTP_X_REAL_IP']).'")');
			$re2 = 'Выслан номер счета и пароль на email, указанный в анкете';
			mysql_query('UPDATE `bank` SET `repass` = "'.(time()+24*3600).'" WHERE `id` = "'.$b_pass['id'].'" LIMIT 1');
			send_mime_mail('Бойцовский Клуб - Support',
               'support@xcombats.com',
               ''.$u->info['login'].'',
               $u->info['mail'],
               'CP1251',  // кодировка, в которой находятся передаваемые строки
               'KOI8-R', // кодировка, в которой будет отправлено письмо
               'Восстановление пароля от счета в банке персонажа '.$u->info['login'].'',
               "Номер счета: ".getNum($b_pass['id'])."<br>Пароль: ".$b_pass['pass'].'<br><br>С уважением,<br>Администрация Бойцовского Клуба');
			
		}
	}elseif(isset($_GET['open']) && !isset($u->bank['id']))
	{
		if( $_POST['rdn01'] == 2 && ($u->info['level'] >= 8 || $u->info['money4'] < 15 )) {
			$re2 = 'Недостаточно зубов!';
		}elseif($u->info['money']>=3 || ($u->info['level'] < 8 && $u->info['money4'] >= 15 ))
		{
			if( $_POST['pass1'] == '' || $_POST['pass1'] == ' ' ) {
				$re2 = 'Вы не указали пароль!';
			}elseif( $_POST['pass1'] != $_POST['pass2'] ) {
				$re2 = 'Пароли не совпадают!';
			}elseif( $u->info['money'] - 3 < 0 && $_POST['rdn01'] != 2 ) {
				$re2 = 'У вас недостаточно кр.';
			}elseif($u->info['align']!=2)
			{
				$pass = rand(10000,91191);
				$pass = htmlspecialchars($_POST['pass1'],NULL,'cp1251');
				$ins = mysql_query('INSERT INTO `bank` (`uid`,`create`,`pass`) VALUES ("'.$u->info['id'].'","'.time().'","'.$pass.'")');
				if($ins)
				{
					$bank = mysql_insert_id();
					if( $u->info['level'] < 8 && $_POST['rdn01'] == 2 ) {
						$u->info['money4'] -= 15;
					}else{
						$u->info['money'] -= 3;
					}
					$upd = mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'",`money4` = "'.$u->info['money4'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					$re2 = 'Счет №<b>'.getNum($bank).'</b> был успешно открыт.<br>Пароль от счета: <b>'.$pass.'</b><br><small><br>(Сменить пароль можно в разделе "Управление счетом" после авторизации)';
					$u->addDelo(3,$u->info['id'],'Вы успешно открыли счет №'.getNum($bank).'',time(),$u->info['city'],'Bank.System',3,0,'');
				}else{
					$re2 = 'Банк отказал в получении банковского счета.';
				}				
			}else{
				$re2 = 'Хаосники не могут создавать новые счета в банке.';
			}
		}else{
			if( $u->info['level'] < 8 ) {
				$re2 = 'Для открытия счета необходимо иметь при себе <b>3.00 кр.</b> или <b>'.$u->zuby(15).'</b>';
			}else{
				$re2 = 'Для открытия счета необходимо иметь при себе <b>3.00 кр.</b>';
			}
		}
	}elseif(isset($_GET['exit']) && isset($u->bank['id']))
	{
		$u->bank = false;
		mysql_query('UPDATE `bank` SET `useNow` = "0" WHERE `uid` = "'.$u->info['id'].'" AND `useNow`!="0" LIMIT 1');
	}
	
	if($u->info['allLock'] > time()) {
		$u->bank = false;	
	}
	
	if(isset($u->bank['id']))
	{
		if(isset($_POST['sd4']) && $u->newAct($_POST['sd4']))
		{
			if(isset($_POST['transfer_kredit2']) && $u->info['admin']>0)
			{
				//перевод екредитов с одного счета на другой
				$ub = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `id` = "'.mysql_real_escape_string((int)$_POST['num2']).'" LIMIT 1'));
				if(isset($ub['id']) && $ub['id']!=$u->bank['id'])
				{
					$ut = mysql_fetch_array(mysql_query('SELECT `id`,`level`,`city`,`room`,`login` FROM `users` WHERE `id` = "'.mysql_real_escape_string($ub['uid']).'" LIMIT 1'));
					if($ut['level']>=0 || $ut['id']==$u->info['id'] || $u->info['admin']>0)
					{
						$mn = floor((int)($_POST['tansfer_sum2']*100));
						$mn = round(($mn/100),2);
						$prc = 0;
						$mn += $prc;
						if($u->bank['money2']>=$mn)
						{
							if($mn<0.01 || $mn>1000000000)
							{
								$re2 = 'Неверно указана сумма';
							}else{
								$upd = mysql_query('UPDATE `bank` SET `money2` = "'.mysql_real_escape_string($u->bank['money2']-$mn).'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
								if($upd)
								{
									$u->bank['money2'] -= $mn;
									$ub['money2'] += $mn-$prc;
									
									mysql_query('UPDATE `users` SET `catch` = `catch` + "'.floor($mn-$prc).'" WHERE `id` = "'.$ut['id'].'" LIMIT 1');
									mysql_query('UPDATE `users` SET `frg` = `frg` + '.floor($mn).' WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
									
									mysql_query('UPDATE `bank` SET `money2` = "'.mysql_real_escape_string($ub['money2']).'" WHERE `id` = "'.$ub['id'].'" LIMIT 1');
									$re2 = 'Вы удачно перевели <b>'.($mn-$prc).' екр.</b> (комиссия <b>'.$prc.' екр.</b>) на счет №'.getNum($ub['id']).' персонажу &quot;<b>'.$ut['login'].'</b>&quot;';
									$u->addDelo(3,$ut['id'],'Получено <b>'.($mn-$prc).' екр.</b> со счета №'.getNum($u->bank['id']).' от персонажа &quot;'.$u->info['login'].'&quot;, комиссия <b>'.$prc.' екр.</b> <i>(Итого: '.$ub['money1'].' кр., '.$ub['money2'].' екр.)</i>',time(),$ut['city'],'Bank.System',mysql_real_escape_string($mn-$prc),0,$ub['id']);
									$u->addDelo(3,$u->info['id'],'Передано <b>'.($mn-$prc).' екр.</b> на счет №'.getNum($ub['id']).' персонажу &quot;'.$ut['login'].'&quot;, комиссия <b>'.$prc.' екр.</b> <i>(Итого: '.$u->bank['money1'].' кр., '.$u->bank['money2'].' екр.)</i>',time(),$u->info['city'],'Bank.System',0,mysql_real_escape_string($mn),$u->bank['id']);
									$log = '&quot;'.$u->info['login'].'&quot;&nbsp;['.$u->info['level'].'] перевел со своего банковского счета №'.$u->bank['id'].' на счет №'.$ub['id'].' к персонажу &quot;'.$ut['login'].'&quot;&nbsp;['.$ut['level'].'] '.($mn-$prc).' екр.';
									$u->addDelo(1,$u->info['id'],$log,time(),$u->info['city'],'Bank.System',0,0,'');
									$u->addDelo(1,$ut['id'],$log,time(),$ut['city'],'Bank.System',0,0,'');
									if($ut['id']!=$u->info['id'])
									{
										$alg = '';
										if($u->info['align']==50)
										{
											$alg = '<img src=http://img.xcombats.com/i/align/align50.gif >';
										}
										$text = '&quot;'.$alg.'[login:'.$u->info['login'].']&quot; перевел'.($u->info['sex']==0?"":"а").' вам <b>'.($mn-$prc).' екр.</b> со своего банковского счета №'.getNum($u->bank['id']).' на ваш банковский счет №'.getNum($ub['id']).'.';
										
										mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('".$ut['city']."','".$ut['room']."','','".$ut['login']."','".$text."','".time()."','12','0','1')");
									}
								}else{
									$re2 = 'Не удалось выполнить операцию';
								}
							}
						}else{
							$re2 = 'У вас нет <b>'.$mn.' екр.</b> на счете';
						}
					}else{
						$re2 = 'Нельзя перевести кредиты на этот счет';
					}
				}else{
					$re2 = 'Нельзя перевести кредиты на этот счет';
				}
			}elseif(isset($_POST['transfer_kredit']) && $u->info['align']!=2)
			{
				//перевод кредитов с одного счета на другой
				if($u->info['level']>=4 || $u->info['admin']>0)
				{
					$ub = mysql_fetch_array(mysql_query('SELECT * FROM `bank` WHERE `id` = "'.mysql_real_escape_string((int)$_POST['num']).'" LIMIT 1'));
					if(isset($ub['id']) && $ub['id']!=$u->bank['id'])
					{
						$ut = mysql_fetch_array(mysql_query('SELECT `id`,`level`,`city`,`room`,`login` FROM `users` WHERE `id` = "'.mysql_real_escape_string($ub['uid']).'" LIMIT 1'));
						if($ut['level']>=4 || $ut['id']==$u->info['id'] || $u->info['admin']>0)
						{
							$mn = floor((int)($_POST['tansfer_sum']*100));
							$mn = round(($mn/100),2);
							$prc = round($mn*3/100,2);
							$mn += $prc;
							if($u->bank['money1']>=$mn)
							{
								if($mn<0.01 || $mn>1000000000)
								{
									$re2 = 'Неверно указана сумма';
								}else{
									$upd = mysql_query('UPDATE `bank` SET `money1` = "'.mysql_real_escape_string($u->bank['money1']-$mn).'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
									if($upd)
									{
										$u->bank['money1'] -= $mn;
										$ub['money1'] += $mn-$prc;
										mysql_query('UPDATE `bank` SET `money1` = "'.mysql_real_escape_string($ub['money1']).'" WHERE `id` = "'.$ub['id'].'" LIMIT 1');
										$re2 = 'Вы удачно перевели <b>'.($mn-$prc).' кр.</b> (комиссия <b>'.$prc.' кр.</b>) на счет №'.getNum($ub['id']).' персонажу &quot;<b>'.$ut['login'].'</b>&quot;';
										$u->addDelo(3,$ut['id'],'Получено <b>'.($mn-$prc).' кр.</b> со счета №'.getNum($u->bank['id']).' от персонажа &quot;'.$u->info['login'].'&quot;, комиссия <b>'.$prc.' кр.</b> <i>(Итого: '.$ub['money1'].' кр., '.$ub['money2'].' екр.)</i>',time(),$ut['city'],'Bank.System',mysql_real_escape_string($mn-$prc),0,$ub['id']);
										$u->addDelo(3,$u->info['id'],'Передано <b>'.($mn-$prc).' кр.</b> на счет №'.getNum($ub['id']).' персонажу &quot;'.$ut['login'].'&quot;, комиссия <b>'.$prc.' кр.</b> <i>(Итого: '.$u->bank['money1'].' кр., '.$u->bank['money2'].' екр.)</i>',time(),$u->info['city'],'Bank.System',0,mysql_real_escape_string($mn),$u->bank['id']);
										$log = '&quot;'.$u->info['login'].'&quot;&nbsp;['.$u->info['level'].'] перевел со своего банковского счета №'.$u->bank['id'].' на счет №'.$ub['id'].' к персонажу &quot;'.$ut['login'].'&quot;&nbsp;['.$ut['level'].'] '.($mn-$prc).' кр. Дополнительно снято '.$prc.' кр. за услуги банка.';
										$u->addDelo(1,$u->info['id'],$log,time(),$u->info['city'],'Bank.System',0,0,'');
										$u->addDelo(1,$ut['id'],$log,time(),$ut['city'],'Bank.System',0,0,'');
										if($ut['id']!=$u->info['id'])
										{
											$text = '&quot;[login:'.$u->info['login'].']&quot; перевел'.($u->info['sex']==0?"":"а").' вам <b>'.($mn-$prc).' кр.</b> со своего банковского счета №'.getNum($u->bank['id']).' на ваш банковский счет №'.getNum($ub['id']).'.';
											mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$ut['city']."','".$ut['room']."','','".$ut['login']."','".$text."','".time()."','6','0','1')");
										}
									}else{
										$re2 = 'Не удалось выполнить операцию';
									}
								}
							}else{
								$re2 = 'У вас нет <b>'.$mn.' кр.</b> на счете';
							}
						}else{
							$re2 = 'Нельзя перевести кредиты на этот счет';
						}
					}else{
						$re2 = 'Нельзя перевести кредиты на этот счет';
					}
				}else{
					$re2 = 'Передача кредитов возможна только с 4-го уровня';
				}
			}elseif($u->info['align']!=2 && $u->info['haos'] < time() && $u->info['haos'] != 1 && $u->info['align'] !=50 && isset($_POST['convert_kredit']) && 1 == 2) {
				//обменять кр. на екр.
				if($u->info['palpro'] > time()) {
					$mn = ceil((int)($_POST['convert_sum2']*100));
					$mn = round(($mn/100),2);
					$mne = round($mn/$noc,2);
					$mn = round(($mn/100*103+5),2);
					$sm = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "bank_kr_to_ekr_['.date('d.m.Y',time()).']" ORDER BY `id` DESC LIMIT 1',1);
					$sm_lim = 50;
					if(isset($sm['id']) && $sm['vals']+$mne > $sm_lim) {
						if($sm['vals'] < $sm_lim) {
							$re2 = 'На сегодня Вы можете обменять еще на <b>'.($sm_lim-$sm['vals']).' екр.</b>. (Примерно '.round( ( ($sm_lim-$sm['vals'])*$noc ) ,2).' кр.), текущий обмен на <b>'.$mne.' екр.</b>.';
						}else{
							$re2 = 'На сегодня Вы исчерпали свой лимит обмена кр. на екр. ('.$sm_lim.' екр.)';
						}
					}elseif($mn > 0 && $mne > 0 && $mn >= round((0.01*($noc*1.03)+5),2)) {
						if($u->bank['money1'] >= $mn) {
							if(!isset($sm['id'])) {			
								$u->addAction(time(),'bank_kr_to_ekr_['.date('d.m.Y').']',$mne);
							}else{
								mysql_query('UPDATE `actions` SET `vals` = "'.($sm['vals']+$mne).'" WHERE `id` = "'.$sm['id'].'" LIMIT 1');
							}
							$re2 = 'Вы успешно обменяли <b>'.$mn.' кр.</b> на <b>'.$mne.' екр.</b>';
							$u->bank['money1'] -= $mn;
							$u->bank['money2'] += $mne;			
											
							mysql_query('UPDATE `users` SET `catch` = `catch` + "'.round($mne,2).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
												
							mysql_query('UPDATE `bank` SET `money1` = "'.mysql_real_escape_string($u->bank['money1']).'", `money2` = "'.mysql_real_escape_string($u->bank['money2']).'" WHERE `id` = "'.mysql_real_escape_string($u->bank['id']).'" LIMIT 1');
										$log = '&quot;'.$u->info['login'].'&quot;&nbsp;['.$u->info['level'].'] обменял <b>'.$mn.' кр.</b> на <b>'.$mne.' екр.</b>, Банковский счет №'.$u->bank['id'].'.';
										$u->addDelo(1,$u->info['id'],$log,time(),$u->info['city'],'Bank.System',0,0,'');
										$u->addDelo(3,$u->info['id'],'Вы успешно обменяли <b>'.ceil((int)($_POST['convert_sum2']*100/100)).' кр.</b> на <b>'.$mne.' екр.</b>, комиссия <b>'.round((ceil((int)($_POST['convert_sum2']*100/100))/100*3+5),2).' кр.</b> <i>(Итого: '.$u->bank['money1'].' кр., '.$u->bank['money2'].' екр.)</i>',time(),$u->info['city'],'Bank.System',0,0,$u->bank['id']);
						}else{
							$re2 = 'У вас нет <b>'.$mn.' кр.</b> на счете';
						}
					}else{
						$re2 = 'Минимальная сумма для обмена составляет '.round((0.01*($noc*1.03)+5),2).' кр.';
					}
				}else{
					$re2 = 'Вы должны пройти проверку на чистоту у Паладинов или Тарманов.';
				}
			}elseif(isset($_POST['convert_ekredit']))
			{
				//обменять екр. на кр.
				$mn = ceil((int)($_POST['convert_sum']*100));
				$mn = round(($mn/100),2);
				if($u->bank['money2']>=$mn)
				{
					if($mn<0.01 || $mn>1000000000)
					{
						$re2 = 'Неверно указана сумма';
					}else{
						$upd = mysql_query('UPDATE `bank` SET `money1` = "'.mysql_real_escape_string($u->bank['money1']+($mn*$con)).'",`money2` = "'.mysql_real_escape_string($u->bank['money2']-$mn).'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
						if($upd)
						{
							$u->bank['money1'] += $mn*$con;
							$u->bank['money2'] -= $mn;
							$u->addDelo(3,$u->info['id'],'Вы обменяли <b>'.$mn.' екр.</b> на <b>'.($mn*$con).' кр.</b>, комиссия <b>0 кр.</b> <i>(Итого: '.$u->bank['money1'].' кр., '.$u->bank['money2'].' екр.)</i>',time(),$u->info['city'],'Bank.System',0,mysql_real_escape_string($mn*$con),$u->bank['id']);
							$re2 = 'Вы удачно обменяли <b>'.$mn.' екр.</b> на <b>'.($mn*$con).' кр.</b>';
						}else{
							$re2 = 'Не удалось выполнить операцию';
						}
					}
				}else{
					$re2 = 'У вас нет <b>'.$mn.' екр.</b> на счете';
				}
			}elseif(isset($_POST['get_kredit']))			
			{
				//положить деньги на счет
				$mn = floor((int)($_POST['get_sum']*100));
				$mn = round(($mn/100),2);
				if($u->bank['money1']>=$mn)
				{
					if($mn<0.01 || $mn>1000000000)
					{
						$re2 = 'Неверно указана сумма';
					}else{
						$upd = mysql_query('UPDATE `users` SET `money` = "'.mysql_real_escape_string($u->info['money']+$mn).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						if($upd)
						{
							$u->bank['money1'] -= $mn;
							$u->info['money']  += $mn;
							mysql_query('UPDATE `bank` SET `money1` = "'.mysql_real_escape_string($u->bank['money1']).'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
							$u->addDelo(3,$u->info['id'],'Вы сняли со счета <b>'.$mn.' кр.</b>, комиссия <b>0 кр.</b> <i>(Итого: '.$u->bank['money1'].' кр., '.$u->bank['money2'].' екр.)</i>',time(),$u->info['city'],'Bank.System',0,0,$u->bank['id']);
							$re2 = 'Вы удачно сняли со счета <b>'.$mn.' кр.</b>';
						}else{
							$re2 = 'Не удалось выполнить операцию';
						}
					}
				}else{
					$re2 = 'У вас нет <b>'.$mn.' кр.</b> на счете';
				}
			}elseif(isset($_POST['add_kredit']))			
			{
				//положить деньги на счет
				$mn = floor((int)($_POST['add_sum']*100));
				$mn = round(($mn/100),2);
				if($u->info['money']>=$mn)
				{
					if($mn<0.01 || $mn>1000000000)
					{
						$re2 = 'Неверно указана сумма';
					}else{
						$upd = mysql_query('UPDATE `users` SET `money` = "'.mysql_real_escape_string($u->info['money']-$mn).'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						if($upd)
						{
							$u->bank['money1'] += $mn;
							$u->info['money']  -= $mn;
							mysql_query('UPDATE `bank` SET `money1` = "'.mysql_real_escape_string($u->bank['money1']).'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
							$u->addDelo(3,$u->info['id'],'Вы положили на счет <b>'.$mn.' кр.</b>, комиссия <b>0 кр.</b> <i>(Итого: '.$u->bank['money1'].' кр., '.$u->bank['money2'].' екр.)</i>',time(),$u->info['city'],'Bank.System',0,0,$u->bank['id']);
							$re2 = 'Вы удачно положили на свой счет <b>'.$mn.' кр.</b>';
						}else{
							$re2 = 'Не удалось выполнить операцию';
						}
					}
				}else{
					$re2 = 'У вас нет при себе <b>'.$mn.' кр.</b>';
				}
			}elseif(isset($_POST['change_psw2']))
			{
				//смена пароля счета
				$sm = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vals` = "id='.$u->bank['id'].'&new_pass='.$u->bank['pass'].'" AND `vars` = "bank_new_pass" AND `time` > "'.(time()-24*60*60).'" LIMIT 1',1);
				if($_POST['new_psw1']!=$_POST['new_psw2'])
				{
					$re2 = 'Пароли не совпадают';
				}elseif(iconv_strlen($_POST['new_psw1'])<6 || iconv_strlen($_POST['new_psw1'])>32)
				{
					$re2 = 'Пароль не может быть короче 6 или длинее 32 символов';
				}elseif(isset($sm['id']))
				{
					$re2 = 'Нельзя менять пароль чаще одного раза в день';
				}else{
					//меняем
					$upd = mysql_query('UPDATE `bank` SET `pass` = "'.mysql_real_escape_string($_POST['new_psw1']).'" WHERE `id` = "'.$u->bank['id'].'" LIMIT 1');
					if($upd)
					{
						$u->addAction(time(),'bank_new_pass','id='.$u->bank['id'].'&new_pass='.$_POST['new_psw1'].'');
						$u->bank['pass'] = $_POST['new_psw1'];
						$re2 = 'Пароль от счета №<b>'.getNum($u->bank['id']).'</b> был успешно изменен<br>Новый пароль: <b>'.$u->bank['pass'].'</b>';
						$u->addDelo(3,$u->info['id'],'Был изменен пароль от счета.',time(),$u->info['city'],'Bank.System',0,0,$u->bank['id']);
					}else{
						$re2 = 'Вам отказали в смене пароля';
					}
				}
			}	
		}
	}
	
	if($re!=''){ echo '<div align="right"><font color="red"><b>'.$re.'</b></font></div>'; } ?>
	<style type="text/css"> 
	
	.pH3			{ COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
	.class_ {
		font-weight: bold;
		color: #C5C5C5;
		cursor:pointer;
	}
	.class_st {
		font-weight: bold;
		color: #659BA3;
		cursor:pointer;
	}
	.class__ {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
	}
	.class__st {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
		font-size: 10px;
	}
	.class_old {
		font-weight: bold;
		color: #919191;
		cursor:pointer;
	}
	.class__old {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #838383;
		font-size: 10px;
	}	
	</style>
	<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><div align="center">
        <div align="center" class="pH3">
          <h3>Банк<br /></h3>
        </div>
        </div></td>
        <td width="200">
          <div style="float:right;">
          <table cellspacing="0" cellpadding="0">
            <tr>
              <td width="100%">&nbsp;</td>
              <td><table  border="0" cellpadding="0" cellspacing="0">
                  <tr align="right" valign="top">
                    <td><!-- -->
                        <? echo $goLis; ?>
                        <!-- -->
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                                <tr>
                                  <td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
                                  <td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=2.180.0.236&rnd=<? echo $code; ?>';" title="<? thisInfRm('2.180.0.236',1); ?>">Страшилкина улица</a></td>
                                </tr>
                            </table></td>
                          </tr>
                      </table></td>
                  </tr>
              </table></td>
            </tr>
          </table>
        </div></td>
      </tr>
    </table>
    <TABLE width="100%" cellspacing="0" cellpadding="4">
	<TR>
	<form name="F1" method="post">
	<TD valign="top" align="left">
	<!--Магазин--></TD>
	</FORM>
	</TR>
	<TR>
	  <TD valign="top" align="left">
        <? if($re2!=''){ echo '<div align="left"><font color="red">'.$re2.'</font></div><br>'; }
		if(!isset($u->bank['id']))
		{
		?>
        Мы предоставляем следующие услуги:
        <OL>
        <LI>Открытие счета<LI>Возможность положить/снять кредиты/еврокредиты со счета
        <LI>Перевести кредиты/еврокредиты с одного счета на другой
        <LI>Обменный пункт. Обмен еврокредитов на кредиты
        </OL>
        <script type="text/javascript" src="js/jquery.js"></script>
		<script>
		function hidecreatefx() {
			if( $('#hidecreate').css('display') != 'none' ) {
				$('#hidecreate').css('display','none');
			}else{
				$('#hidecreate').css('display','');
			}
		}
		</script>
        <FORM action="main.php?open&rnd=<? echo $code; ?>" method="POST">
        Хотите открыть свой счет? Услуга платная: <INPUT onclick="hidecreatefx();" TYPE="button" value="Открыть счет">
        <div id="hidecreate" style="display:none">
        <FIELDSET style="width:300px;"><LEGEND><B>Открытие счета</B> </LEGEND>
        <small>
		<? if ($u->info['level'] < 8) { ?>
        <center>
        	<input name="rdn01" type="radio" value="1"> <b>3.00 кр.</b> &nbsp; &nbsp; <input name="rdn01" type="radio" value="2"> <?=$u->zuby(15)?> &nbsp; &nbsp; &nbsp;
        </center>
        <hr />
        <? }else{
		?>
        <center>
        	<input checked="checked" name="rdn01" type="radio" value="1"> <b>3.00 кр.</b> &nbsp; &nbsp; &nbsp;
        </center>
        <hr />
		<?
		} ?>
		<style>
        fieldset {
            border:1px solid #AEAEAE;
        }
        hr {
            border:0;
            border-bottom:1px solid #aeaeae;	
        }
        </style>
        <table width="300" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>Пароль счета:</td>
            <td><INPUT style='width:90;' type="password" value="" name="pass1"></td>
          </tr>
          <tr>
            <td>Еще раз:</td>
            <td><INPUT style='width:90;' type="password" value="" name="pass2"></td>
          </tr>
        </table>
        </small>
        <center>
        	<INPUT TYPE="submit" value="Открыть счет">
        </center>
        </FIELDSET>
        </div>
        </FORM>
        <form action="main.php?enter&rnd=<? echo $code; ?>" method="POST">
        <br />
        <FIELDSET style="width:300px;"><LEGEND><B>Управление счетом</B> </LEGEND>
        <TABLE width="300">
        <TR><TD valign=top>
        <TABLE>
        <TR><TD>Номер счета</td> <TD colspan=2><select name="bank" size=0 style="width: 90px">
        	<?
			$sp = mysql_query('SELECT * FROM `bank` WHERE `uid` = "'.$u->info['id'].'" AND `block` = "0"');
			while($pl = mysql_fetch_array($sp))
			{
			?>
            <option value="<? echo $pl['id']; ?>" selected="selected"><? echo getNum($pl['id']); ?></option>
            <?
			}
			?>
        </select></td></tr>
        <TR><TD>Пароль</td><td> <INPUT style='width:90;' type="password" value="" name="pass"></td>
        </tr>
        <TR><TD colspan=3 align=center><INPUT TYPE="submit" value="Войти"></td></tr>
        </TABLE>
        </TD>
        </TABLE>
        </FIELDSET>
        </form>
		<form method=GET action='main.php'>
		<input type=hidden name='res' value=<? echo $code; ?>>
        <br />
        <br />
        Забыли пароль? Можно его выслать на email, номер счета:<input type=text name='schet'> <input type="submit" value="Выслать" /></TD>
	    </form>
	 </TR>
	</TABLE>	
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
    <?
	}else{
	
?>
<style>
.pay td {
	width:50px;
}
.pay td img{
	display:block;
	margin:1px 0 0 0;
}
.pay td:hover img{
	margin:0 0 1px 0;
}
.pay td:hover img {
	filter:progid:DXImageTransform.Microsoft.Alpha(opacity=80); /* IE 5.5+*/
	-moz-opacity: 0.8; /* Mozilla 1.6 и ниже */
	-khtml-opacity: 0.8; /* Konqueror 3.1, Safari 1.1 */
	opacity: 0.8; /* CSS3 - Mozilla 1.7b +, Firefox 0.9 +, Safari 1.2+, Opera 9 */
	cursor:pointer;
}
</style>
<!-- управление счетом -->
<FORM action="main.php" method="POST">
<INPUT TYPE=hidden name="sd4" value="<? echo $u->info['nextAct']; ?>">
<TABLE width=100%>
<TR>
<TD valign=top width=30%><H4>Управление счетом</H4> &nbsp;
<b>Счёт №:</b> <? echo getNum($u->bank['id']); ?> <a href="?exit=<? echo $code; ?>" title="Окончить работу c текущим счетом">[x]</a><br>
</TD>
<TD valign=top align=center width=40%>
<TABLE><TR><TD>
<FIELDSET><LEGEND><B>У вас на счете</B> </LEGEND>
<TABLE>
<TR><TD>Кредитов:</TD><TD><B><? echo $u->round2($u->bank['money1']); ?></B></TD></TR>
<TR><TD>Еврокредитов:</TD>
<TD><B><? echo $u->round2($u->bank['money2']); ?></B></TD>
</TR>
<TR><TD colspan=2><HR></TD></TR>
<TR><TD>При себе наличных:</TD><TD><B><? echo $u->round2($u->info['money']); ?> кр.</B></TD></TR>
</TABLE>
</FIELDSET>
</TD></TR></TABLE>
</TD>
<TD valign=top align=right width=30%><FONT COLOR=red>Внимание!</FONT> Некоторые услуги банка платные, о размере взымаемой комиссии написано в соответствующем разделе.</TD>
</TR>
</TABLE>
<style>
fieldset {
	border:1px solid #AEAEAE;
}
hr {
	border:0;
	border-bottom:1px solid #aeaeae;	
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" valign="top"><table width="100%" cellspacing="5">
      <tr>
        <td width="50%" valign="top"><fieldset style="background-color:#DDEAD7"">
         <legend><img src="http://img.xcombats.com/i/align/align50.gif" width="12" height="15" /> <b style="color:#5F3710">Приобретение Екр. онлайн</b> </legend>
<style>
#pay_btn {
	background-color:#0099FF;
	color:#0FF;
	cursor:pointer;	
}
#pay_btn:hover {
	background-color:#CCC;
	color:#FFF;
	cursor:pointer;	
}
</style>
Сумма екр.: <input id="pay_in" style="padding-left:2px;width:77px;" value="1.00">
<input id="pay_btn" name="pay_btn" value="Оплатить" type="button" onclick="window.open('/pay.back.php?ekr='+$('#pay_in').val()+'&code=1&ref=0','_blank');" style="padding:5px;" />
</div>
<div id="pay_block_see" style="display:none;padding-top:5px;border-top:1px solid #AEAEAE;"></div>
</fieldset></td>
      </tr> 
      <tr>
        <td valign="top" width="50%"><fieldset>
         <legend><b>Пополнить счет</b> </legend>
          Сумма
          <input type="text" name="add_sum" id="add_sum" size="6" maxlength="10" />
          кр.
          <input type="submit" name="add_kredit" value="Положить кредиты на счет" onclick="if(Math.round(document.getElementById('add_sum')).value==0) {alert('Укажите сумму и номер счета'); return false;} else {return confirm('Вы хотите положить на свой счет '+(Math.floor(document.getElementById('add_sum').value*100)/100).toFixed(2)+' кр. ?')}" />
          <br />
        </fieldset></td>
      </tr>
      <tr>
        <td valign="top"><fieldset>
          <legend><b>Перевести кредиты на другой счет</b> </legend>
          Сумма
          <input id="vl1" value="" type="text" name="tansfer_sum" size="6" maxlength="10" />
          кр.<br />
          Номер счета куда перевести кредиты
          <input value="" type="text" id="vl2" name="num" size="12" maxlength="15" />
          <br />
          <input type="submit" name="transfer_kredit" value="Перевести кредиты на другой счет" onclick="if(Math.round(document.getElementById('vl1')).value==0 || Math.round(document.getElementById('vl2').value)==0) {alert('Укажите сумму и номер счета'); return false;} else {return confirm('Вы хотите перевести со своего счета '+(Math.floor(document.getElementById('vl1').value*100)/100).toFixed(2)+' кр. на счет номер '+Math.floor(document.getElementById('vl2').value)+' ?')}" />
          <br />
          <small>Комиссия составляет <b>3.00 %</b> от суммы, но не менее <b>1.00 кр</b>.</small>
        </fieldset></td>
      </tr>
      <tr>
        <td valign="top"><fieldset>
          <legend><b>Обменный пункт</b> </legend>
          Обменять еврокредиты на кредиты.<br />
          Курс <b>1 екр.</b> = <b><? echo $con; ?>.00 кр.</b><br />
          Сумма
          <input type="text" name="convert_sum" id="convert_sum" size="6" maxlength="10" />
          екр.
          <input type="submit" name="convert_ekredit" value="Обменять" <? /*onclick="return confirm('Вы хотите обменять '+(Math.floor(document.getElementById('convert_sum').value*100)/100).toFixed(2)+' екр. на '+(Math.floor(document.getElementById('convert_sum').value*100)/100*<? echo (0+$con); ?>).toFixed(2)+' кр. ?');" */ ?> />
        </fieldset></td>
      </tr>
      <? if($u->info['align']!=2 && $u->info['haos'] < time() && $u->info['haos'] != 1 && $u->info['align'] !=50 && 1 == 2) { ?>
      <tr>
        <td valign="top"><fieldset style="background-color:#DDEAD7">
          <legend><b>Обменный пункт</b> </legend>
          Обменять кредиты на еврокредиты.<br />
          Курс <b><? echo $noc; ?> кр.</b> = <b>1.00 екр.</b><br />
          Сумма
          <input type="text" name="convert_sum2" id="convert_sum2" size="6" maxlength="10" />
          кр.
          <br />
          <small>Комиссия составляет <b>3.00 %</b> от суммы, а так-же <b>5.00 кр</b>.</small>
          <input type="submit" name="convert_kredit" value="Обменять" onclick="return confirm('Вы хотите обменять '+(5+Math.floor((document.getElementById('convert_sum2').value)*103)/100).toFixed(2)+' кр. на '+(Math.floor(document.getElementById('convert_sum2').value*100)/100/<? echo $noc; ?>).toFixed(2)+' екр. ?');" />
        </fieldset></td>
      </tr>
      <? }
      if($u->info['admin']>1000)
	  {
	  ?>
      <tr>
        <td valign="top"><fieldset>
          <legend><b>Перевести еврокредиты на другой счет</b> </legend>
          Сумма
          <input id="vl12" value="" type="text" name="tansfer_sum2" size="6" maxlength="10" />
          екр.<br />
          Номер счета куда перевести кредиты
          <input value="" type="text" id="vl22" name="num2" size="12" maxlength="15" />
          <br />
          <input type="submit" name="transfer_kredit2" value="Перевести еврокредиты на другой счет" onclick="if(Math.round(document.getElementById('vl12')).value==0 || Math.round(document.getElementById('vl22').value)==0) {alert('Укажите сумму и номер счета'); return false;} else {return confirm('Вы хотите перевести со своего счета '+(Math.floor(document.getElementById('vl12').value*100)/100).toFixed(2)+' екр. на счет номер '+Math.floor(document.getElementById('vl22').value)+' ?')}" />
          <br />
          Комиссия составляет <b>0.00 %</b> от суммы, но не менее <b>0.01 екр</b>.
        </fieldset></td>
      </tr>
      <? } ?>
      <tr>
        <td valign="top"><fieldset>
          <legend><b>Настройки</b> </legend>
          У вас разрешена высылка номера счета и пароля на email. Если вы не уверены в своем email, или убеждены, что не забудете свой номер счета и пароль к нему, то можете запретить высылку пароля на email. Это убережет вас от кражи кредитов с вашего счета в случае взлома вашего email. Но если вы сами забудете свой номер счета и/или пароль, вам уже никто не поможет!<br />
          <input type="submit" name="stop_send_email2" value="Запретить высылку пароля на email" />
          <hr />
          <b>Сменить пароль</b><br />
          <table>
            <tr>
              <td>Новый пароль</td>
              <td><input type="password" name="new_psw1" /></td>
            </tr>
            <tr>
              <td>Введите новый пароль повторно</td>
              <td><input type="password" name="new_psw2" /></td>
            </tr>
          </table>
          <input type="submit" name="change_psw2" value="Сменить пароль" />
          <br />
          <div id="keypad4" align="center" style="display: none;"></div>
        </fieldset></td>
      </tr>
      <tr>
        <td valign="top">&nbsp;</td>
      </tr>
    </table>
    </td>
    <td width="50%" valign="top"><table width="100%" align="left" cellspacing="5">
      <tr>
        <td valign="top" width="50%"><fieldset>
          <legend><b>Снять со счета</b> </legend>
          Сумма
          <input type="text" name="get_sum" id="get_sum" size="6" maxlength="10" />
          кр.
          <input type="submit" name="get_kredit" value="Снять кредиты со счета" onclick="if(Math.round(document.getElementById('get_sum')).value==0) {alert('Укажите сумму и номер счета'); return false;} else {return confirm('Вы хотите снять со своего счета '+(Math.floor(document.getElementById('get_sum').value*100)/100).toFixed(2)+' кр. ?')}" />
          <br />
        </fieldset></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td valign="top"><fieldset>
          <legend><b>Курс еврокредита к мировой валюте</b> </legend>
          <table width="100%" border="0" cellpadding="2" cellspacing="0">
            <?
			$pl = mysql_fetch_array(mysql_query('SELECT * FROM `bank_table` ORDER BY `time` DESC LIMIT 1'));
			if(isset($pl['id'])) {
			?>
            <tr>
              <td><small>Данные на <b><?=date('d.m.y H:i',$pl['time'])?></b> без учета комиссий</small></td>
            </tr>
            <?
				$pl['RUB'] = 1;
				
				$i = 0;
				$true = array(
					array('USD', 'долларов США'),
					array('EUR', 'ЕВРО'),
					array('RUB','российских рублей'),
					array('UAH','укр. гривен'),
					array('BYR','белорусских рублей'),
					array('AZN','азербайджанских манат'),
					array('GBP','англ. фунтов стерлингов')
				);
				while($i < count($true)) {
			?>
            <tr>
              <td><span>1 екр. = </span><span style="display:inline-block;width:100px"><b><?=round( ($pl['cur']/$pl[$true[$i][0]]) , 4 )?></b></span><span><?=$true[$i][1]?></span></td>
            </tr>
            <?
					$i++;
				}
			}else{
			?>
            <tr>
              <td><small><center><font color=grey>Не удалось получить информацию</font></center></small></td>
            </tr>
            <? } ?>
          </table>
        </fieldset></td>
        </tr><tr>
        <td valign="top"><fieldset>
          <legend><b>Последние операции</b> </legend>
          <table width="100%" border="0" cellpadding="2" cellspacing="0">
            <?
			$sp = mysql_query('SELECT * FROM `users_delo` WHERE `uid` = "'.$u->info['id'].'" AND `dop` = "'.$u->bank['id'].'" AND `type` = "3" ORDER BY `time` DESC LIMIT 21');
			while($pl = mysql_fetch_array($sp))
			{
			?>
            <tr>
              <td><small><? echo '<font color="green">'.date('d.m.Y H:i',$pl['time']).'</font> '; echo $pl['text']; ?></small></td>
            </tr>
            <?
			}
			?>
          </table>
        </fieldset></td>
      </tr>

    </table></td>
  </tr>
</table>
</FORM>
<small>Сумма указанная в окне оповещения и суммы взымаемая\начисляемая могут различаться.</small>
<?
	}
}
?>