<?php
header( 'Expires: Mon, 26 Jul 1970 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
header('Content-Type: text/html; charset=windows-1251');
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
{
	define('GAME',true);	
	include('_incl_data/__config.php');
	include('_incl_data/class/__db_connect.php');
	include('_incl_data/class/__user.php');
	
	if($u->info['repass'] > 0) {
		die();
	}
	
	if(isset($u->tfer['id']) && $u->info['align'] != 2)
	{
		if($u->tfer['finish1']==0 && $u->tfer['finish2']==0)
		{
			$js = ''; $mn = 0;
			if(isset($_POST['cancel2']))
			{
				$u->tfer['r0'] = time();
				$u->tfer['good1'] = 0;
				$u->tfer['good2'] = 0;
				$_POST['id'] = 'reflesh';
				mysql_query('UPDATE `transfers` SET `r0` = "'.$u->tfer['r0'].'",`r1` = "0",`r2` = "0",`good1` = "0",`good2` = "0" WHERE `id` = "'.$u->tfer['id'].'" LIMIT 1');
			}elseif(isset($_POST['start2']))
			{
				//сохраняем обмен
				if($u->tfer['good1']>0 && $u->tfer['good2']>0)
				{
					//завершаем обмен
					$u->tfer['cancel1'] = time();
					$u->tfer['cancel2'] = time();
					$u->tfer['finish1'] = time();
					$u->tfer['finish2'] = time();
					//меняем вещи + передаем КР и завершаем передачи, переход на лог передач
					$upd2 = mysql_query('UPDATE `transfers` SET `cancel1` = "'.$u->tfer['cancel1'].'",`cancel2` = "'.$u->tfer['cancel2'].'",`finish1` = "'.$u->tfer['finish1'].'",`finish2` = "'.$u->tfer['finish2'].'" WHERE `id` = "'.$u->tfer['id'].'" LIMIT 1');
					//Обмениваем деньги
					$mn1 = 0;
					$mn2 = 0;
					$inf = array();
					$inf[$u->tfer['uid1']] = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$u->tfer['uid1'].'" LIMIT 1'));
					$inf[$u->tfer['uid2']] = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$u->tfer['uid2'].'" LIMIT 1'));
					if($inf[$u->tfer['uid1']]['money']<$u->tfer['money1'])
					{
						$u->tfer['money1'] = $inf[$u->tfer['uid1']]['money'];
					}
					if($inf[$u->tfer['uid2']]['money']<$u->tfer['money2'])
					{
						$u->tfer['money2'] = $inf[$u->tfer['uid2']]['money'];
					}

					$mn1 = $u->tfer['money1'];
					$mn2 = $u->tfer['money2'];
					if($mn1<0){ $mn1 = 0; }
					if($mn2<0){ $mn2 = 0; }
					
					if($mn1>0)
					{
						//игрок 1 передает деньги игроку 2
						$upd = mysql_query('UPDATE `users` SET `money` = `money` - "'.$mn1.'" WHERE `id` = "'.$inf[$u->tfer['uid1']]['id'].'" LIMIT 1');
						if($upd)
						{
							$upd = mysql_query('UPDATE `users` SET `money` = `money` + "'.$mn1.'" WHERE `id` = "'.$inf[$u->tfer['uid2']]['id'].'" LIMIT 1');
							if($upd)
							{
								$u->addDelo(2,$inf[$u->tfer['uid1']]['id'],'&quot;<font color="green">System.transfer.MONEY</font>&quot;: Передано '.$mn1.' кр. персонажу &quot;'.$inf[$u->tfer['uid2']]['login'].'&quot; ('.$inf[$u->tfer['uid2']]['id'].').',time(),$u->info['city'],'System.transfer',0,0);
								$u->addDelo(2,$inf[$u->tfer['uid2']]['id'],'&quot;<font color="green">System.transfer.MONEY</font>&quot;: Получено '.$mn1.' кр. от персонажа &quot;'.$inf[$u->tfer['uid1']]['login'].'&quot; ('.$inf[$u->tfer['uid1']]['id'].').',time(),$u->info['city'],'System.transfer',0,0);
							}else{
								$u->addDelo(2,$inf[$u->tfer['uid1']]['id'],'&quot;<font color="green">System.transfer.MONEY</font>&quot;: Передано '.$mn1.' кр. персонажу &quot;'.$inf[$u->tfer['uid2']]['login'].'&quot; ('.$inf[$u->tfer['uid2']]['id'].'), ошибка во время передачи.',time(),$u->info['city'],'System.transfer',0,0);
							}
						}
					}
					
					if($mn2>0)
					{
						//игрок 2 передает деньги игроку 1
						$upd = mysql_query('UPDATE `users` SET `money` = `money` - "'.$mn2.'" WHERE `id` = "'.$inf[$u->tfer['uid2']]['id'].'" LIMIT 1');
						if($upd)
						{
							$upd = mysql_query('UPDATE `users` SET `money` = `money` + "'.$mn2.'" WHERE `id` = "'.$inf[$u->tfer['uid1']]['id'].'" LIMIT 1');
							if($upd)
							{
								$u->addDelo(2,$inf[$u->tfer['uid2']]['id'],'&quot;<font color="green">System.transfer.MONEY</font>&quot;: Передано '.$mn2.' кр. персонажу &quot;'.$inf[$u->tfer['uid1']]['login'].'&quot; ('.$inf[$u->tfer['uid1']]['id'].').',time(),$u->info['city'],'System.transfer',0,0);
								$u->addDelo(2,$inf[$u->tfer['uid1']]['id'],'&quot;<font color="green">System.transfer.MONEY</font>&quot;: Получено '.$mn2.' кр. от персонажа &quot;'.$inf[$u->tfer['uid2']]['login'].'&quot; ('.$inf[$u->tfer['uid2']]['id'].').',time(),$u->info['city'],'System.transfer',0,0);
							}else{
								$u->addDelo(2,$inf[$u->tfer['uid2']]['id'],'&quot;<font color="green">System.transfer.MONEY</font>&quot;: Передано '.$mn2.' кр. персонажу &quot;'.$inf[$u->tfer['uid1']]['login'].'&quot; ('.$inf[$u->tfer['uid1']]['id'].'), ошибка во время передачи.',time(),$u->info['city'],'System.transfer',0,0);
							}
						}
					}
					
					//Обмениваем предметы
					$sp = mysql_query('SELECT `u`.*,`m`.`price1`,`m`.`price2`,`m`.`name` FROM `items_users` AS `u` LEFT JOIN `items_main` AS `m` ON `m`.`id` = `u`.`item_id` WHERE (`u`.`uid`="'.$u->tfer['uid1'].'" OR `u`.`uid`="'.$u->tfer['uid2'].'") AND `u`.`delete`="0" AND `u`.`inOdet`="0" AND `u`.`inShop`="0" AND `u`.`inTransfer` > "0" AND `u`.`data` NOT LIKE "%|zazuby=%"');
					$nalog = 0; $fu = 0; $x = 0; $uus = array();
					while($pl = mysql_fetch_array($sp))
					{
						$x = $u->itemsX($pl['id'],$pl['uid']);
						$fu = $pl['uid'];						
						if($pl['uid']==$u->tfer['uid1'])
						{
							$pl['uid'] = $u->tfer['uid2'];
						}elseif($pl['uid']==$u->tfer['uid2'])
						{
							$pl['uid'] = $u->tfer['uid1'];
						}
						$us = $inf[$pl['uid']];
						$uus = $inf[$fu];
						if($pl['inTransfer']==1)
						{
							//подарок
							$pl['gift'] = $uus['login'];
						}else{
							//налог
							$nalog += 1;
						}						
						$upd = mysql_query('UPDATE `items_users` SET `uid` = "'.$pl['uid'].'",`gift` = "'.$pl['gift'].'" WHERE `id` = "'.$pl['id'].'" AND `inShop` = "0" AND `delete` < "1234567891" AND `inOdet` = "0" AND `data` NOT LIKE "%|zazuby=%"');
						if($upd)
						{
							if( $pl['2price'] == 0 && $pl['1price'] == 0 ) {
								$pl['2price'] = $pl['price2'];
							}
							if( $pl['1price'] == 0 && $pl['2price'] == 0) {
								$pl['1price'] = $pl['price1'];
							}
							$po = $u->lookStats($pl['data']);
							$i_s = '';
							if(isset($po['frompisher']) && $po['frompisher'] > 0) { $i_s = '[Предмет из подземелья]'; }
							//заносим в личные дела
							$u->addDelo(2,$fu,'&quot;<font color="green">System.transfer</font>&quot;: Предмет &quot;<b>'.$pl['name'].'</b> (стоимость: '.$pl['1price'].' кр. , '.$pl['2price'].' екр.) (x'.$x.')&quot; [itm:'.$pl['id'].'] '.$i_s.' был передан персонажу &quot;'.$us['login'].'&quot;('.$pl['uid'].'), Тип передачи: '.$pl['inTransfer'].'.',time(),$u->info['city'],'System.transfer',0,0);
							$u->addDelo(2,$pl['uid'],'&quot;<font color="green">System.transfer</font>&quot;: Персонаж &quot;'.$uus['login'].'&quot;('.$uus['id'].') передал предмет &quot;<b>'.$pl['name'].'</b> '.$i_s.' (стоимость: '.$pl['1price'].' кр. , '.$pl['2price'].' екр.) (x'.$x.')&quot; [itm:'.$pl['id'].'], Тип передачи: '.$pl['inTransfer'].'.',time(),$u->info['city'],'System.transfer',0,0);
						}else{
							echo 'Ошибка передачи предмета';
						}
					}
					$upd1 = mysql_query('UPDATE `items_users` SET `inTransfer` = "0" WHERE (`uid`="'.$u->tfer['uid1'].'" OR `uid`="'.$u->tfer['uid2'].'") AND `delete` < "1234567891" AND `inOdet`="0" AND `inShop`="0" AND `inTransfer` > "0" AND `data` NOT LIKE "%|zazuby=%"');
					if($upd1 && $upd2)
					{
						$js .= 'location = location;';
					}
					unset($upd1,$upd2,$fu,$nalog,$x,$us,$uus,$inf);
				}else{
					//подтверждение обмена
					$u->tfer['r0'] = time();
					if($u->tfer['uid1']==$u->info['id'])
					{
						$u->tfer['good1'] = time(); $mn = 1;
						mysql_query('UPDATE `transfers` SET `r0` = "'.$u->tfer['r0'].'",`r1` = "0",`r2` = "0",`good1` = "'.$u->tfer['good1'].'" WHERE `id` = "'.$u->tfer['id'].'" LIMIT 1');
					}else{
						$u->tfer['good2'] = time(); $mn = 1;
						mysql_query('UPDATE `transfers` SET `r0` = "'.$u->tfer['r0'].'",`r1` = "0",`r2` = "0",`good2` = "'.$u->tfer['good2'].'" WHERE `id` = "'.$u->tfer['id'].'" LIMIT 1');
					}
					$_POST['id'] = 'reflesh';
				}
			}elseif($_POST['id']=='sale' && isset($_POST['cancelid']) && $u->tfer['good1']==0 && $u->tfer['good2']==0)
			{
				$upd = mysql_query('UPDATE `items_users` SET `inTransfer` = "0" WHERE `id` = "'.mysql_real_escape_string($_POST['cancelid']).'" AND `uid`="'.$u->info['id'].'" AND `delete`="0" AND `inOdet`="0" AND `inShop`="0" AND `inTransfer` > "0" AND `data` NOT LIKE "%|zazuby=%" LIMIT 1');
				if($upd)
				{
					$u->tfer['r0'] = time();
					mysql_query('UPDATE `transfers` SET `r0` = "'.$u->tfer['r0'].'" WHERE `id` = "'.$u->tfer['id'].'" LIMIT 1');
					$_POST['id'] = 'reflesh';
				}
			}elseif($_POST['id']=='sale' && isset($_POST['itemid']) && $u->tfer['good1']==0 && $u->tfer['good2']==0)
			{
				$g = 1;
				$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`id` = "'.mysql_real_escape_string($_POST['itemid']).'" AND `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`inTransfer` = "0" AND `iu`.`data` NOT LIKE "%|zazuby=%" LIMIT 1'));
				if(isset($itm['id']))
				{
					$po = $u->lookStats($itm['data']);
					if(isset($po['sudba']) && $po['sudba']!='0')
					{
						$g = 0;	
					}
					if($itm['inTransfer']>0)
					{
						$g = 0;
					}
				}else{
					$g = 0;	
				}
				if($g==1)
				{
					if($_POST['saletype']==2)
					{
						$g = 2;	
					}else{
						$g = 1;	
					}
					mysql_query('UPDATE `items_users` SET `inTransfer` = "'.((int)$g).'",`inGroup` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
					$u->tfer['r0'] = time();
					mysql_query('UPDATE `transfers` SET `r0` = "'.$u->tfer['r0'].'" WHERE `id` = "'.$u->tfer['id'].'" LIMIT 1');
					$_POST['id'] = 'reflesh';	
				}
			}
			
			if($_POST['id']=='reflesh')
			{
				//обновление инвентаря
				$i = 1;
				while($i<=6)
				{
					$itmAll = ''; $itmAllSee = '';
					$itmAll = $u->genInv(5,'`iu`.`uid` = "'.$u->info['id'].'" AND `iu`.`data` NOT LIKE "%toclan='.$u->info['clan'].'#%" AND `iu`.`delete` = 0 AND `iu`.`inOdet` =0 AND `iu`.`inShop` = 0 AND `im`.`inRazdel` = "'.$i.'" AND `iu`.`inTransfer` = 0 ORDER BY `lastUPD` DESC');
					
					if($itmAll[0]==0)
					{
						$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">ПУСТО</td></tr>';
					}else{
						$itmAllSee = $itmAll[2];
					}
					$itmAllSee = str_replace("'","",$itmAllSee);
					$itmAllSee = str_replace('"','"',$itmAllSee);
					$itmAllSee = str_replace("\n",'',$itmAllSee);
					$itmAllSee = str_replace("\r",'',$itmAllSee);
					$js .= '$(\'#inv'.$i.'\').html(\''.$itmAllSee.'\');';
					$i++;
				}
				unset($itmAll,$itmAllSee);
			}
			if($_POST['id']=='minireflesh' || $_POST['id']=='reflesh')
			{
				//Мини обновление	
				if(($u->tfer['start2']>0 && $u->info['id']==$u->tfer['uid1']) || ($u->tfer['start1']>0 && $u->info['id']==$u->tfer['uid2']))
				{
					$js .= 's2g();';
				}
				//Обновляем предметы
				$f = 1;
				if($u->info['id']==$u->tfer['uid2'])
				{
					$f = 2;
				}
				if($u->tfer['r'.$f]!=$u->tfer['r0'])
				{
					function itmInfotf($pl,$cl)
					{
						global $u,$c,$code;
						$x = $u->itemsX($pl['id'],$pl['uid']);
						if($x>1)
						{
							$x = ' (x'.$x.')';
						}else{
							$x = '';	
						}
						$r = '';
						if($pl['uid']==$u->info['id'])
						{
							$r .= '<img width="13" height="13" onClick="cancelitm('.$pl['id'].');" class="clr" src="http://img.xcombats.com/i/clear.gif" />';
						}
						$r .= '<a href="items_info.php?id='.$pl['item_id'].'&rnd='.$code.'" target="_blank">'.$pl['name'].''.$x.'</a><br>';
						if($pl['inTransfer']==1)
						{
							$r .= '<img width="16" height="18" title="Этот предмет будет подарен" src="http://img.xcombats.com/i/podarok.gif" />';
						}else{
							$r .= '<small style="font-size:10px">(налог: 1кр.)</small>';
						}
						$r = '<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td width="50" align="center"><img src="http://img.xcombats.com/i/items/'.$pl['img'].'" class="tfii"/></td><td valign="top" class="tfid">'.$r.'</td></tr></table>';
						$r = '<div class="tfitm'.$cl.'">'.$r.'</div>';
						unset($x);
						return $r;
					}
					//Точно обновляем :)
					$itm = array(1=>'',2=>'');
					//предметы персонажа 1
					$sp = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->tfer['uid1'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`inTransfer` > "0" AND `iu`.`data` NOT LIKE "%|zazuby=%" ORDER BY `iu`.`lastUPD` DESC');	
					$cl = 2;
					while($pl = mysql_fetch_array($sp))
					{
						if($cl==2)
						{
							$cl = 1;
						}else{
							$cl = 2;	
						}
						$itm[1] .= itmInfotf($pl,$cl);
					}
					//предметы персонажа 2
					$sp = mysql_query('SELECT `im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->tfer['uid2'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`inTransfer` > "0" AND `iu`.`data` NOT LIKE "%|zazuby=%" ORDER BY `iu`.`lastUPD` DESC');	
					$cl = 2;
					while($pl = mysql_fetch_array($sp))
					{
						if($cl==2)
						{
							$cl = 1;
						}else{
							$cl = 2;	
						}
						$itm[2] .= itmInfotf($pl,$cl);
					}
					unset($cl);
					if($itm[1]=='')
					{
						$itm[1] = '&nbsp;';	
					}
					if($itm[2]=='')
					{
						$itm[2] = '&nbsp;';	
					}
					
					if($u->info['id']==$u->tfer['uid1'])
					{
						$js .= '$("#s2g3").html(\''.$itm[1].'\');$("#s2g2").html(\''.$itm[2].'\');';
					}else{
						$js .= '$("#s2g3").html(\''.$itm[2].'\');$("#s2g2").html(\''.$itm[1].'\');';	
					}
					$u->tfer['r'.$f] = $u->tfer['r0'];
					mysql_query('UPDATE `transfers` SET `r'.$f.'` = "'.$u->tfer['r'.$f].'" WHERE `id` = "'.$u->tfer['id'].'" LIMIT 1');
				}
				if(($u->tfer['good1']>0 && $u->info['id']==$u->tfer['uid1']) || ($u->tfer['good2']>0 && $u->info['id']==$u->tfer['uid2']))
				{
					$js .= '$(\'#btn1\').html(\'Обменять\');';
					if($u->tfer['good1']>0 && $u->tfer['good2']>0)
					{
						$js .= '$(\'#btn1\').attr(\'disabled\',\'\');';
					}else{
						$js .= '$(\'#btn1\').attr(\'disabled\',\'disabled\');';
					}
				}else{
					$js .= '$(\'#btn1\').html(\'Готов к обмену\');$(\'#btn1\').attr(\'disabled\',\'\');';
				}
				
				//Обновляем деньги в кассе :)
				if($u->tfer['uid1']==$u->info['id'])
				{
					if($u->tfer['good1']>0){	$js .= '$(\'#gd2\').css(\'display\',\'\');';	}else{	$js .= '$(\'#gd2\').css(\'display\',\'none\');';	} //вы
					if($u->tfer['good2']>0){	$js .= '$(\'#gd1\').css(\'display\',\'\');';	}else{ 	$js .= '$(\'#gd1\').css(\'display\',\'none\');';	}
					
					if(($u->tfer['good1']==0 && $u->tfer['good2']==0) || $mn == 1)
					{
						if( $u->tfer['money1'] != round($_POST['money'],2) ) {
							$u->tfer['money1'] = round($_POST['money'],2);
							if($u->tfer['money1']>$u->info['money'])
							{
								$u->tfer['money1'] = $u->info['money'];
							}
							if($u->tfer['money1']<0)
							{
								$u->tfer['money1'] = 0;
							}
							mysql_query('UPDATE `transfers` SET `money1` = "'.mysql_real_escape_string($u->tfer['money1']).'",`good1` = 0,`good2` = 0 WHERE `id` = "'.$u->tfer['id'].'" LIMIT 1');
						}
					}
				}else{
					if($u->tfer['good2']>0){	$js .= '$(\'#gd2\').css(\'display\',\'\');';	}else{	$js .= '$(\'#gd2\').css(\'display\',\'none\');';	} //вы
					if($u->tfer['good1']>0){	$js .= '$(\'#gd1\').css(\'display\',\'\');';	}else{ 	$js .= '$(\'#gd1\').css(\'display\',\'none\');';	}
					
					if(($u->tfer['good1']==0 && $u->tfer['good2']==0) || $mn == 1)
					{
						if( $u->tfer['money2'] != round($_POST['money'],2) ) {
							$u->tfer['money2'] = round($_POST['money'],2);	
							if($u->tfer['money2']>$u->info['money'])
							{
								$u->tfer['money2'] = $u->info['money'];
							}
							if($u->tfer['money1']<0)
							{
								$u->tfer['money1'] = 0;
							}
							mysql_query('UPDATE `transfers` SET `money2` = "'.mysql_real_escape_string($u->tfer['money2']).'",`good1` = 0,`good2` = 0 WHERE `id` = "'.$u->tfer['id'].'" LIMIT 1');
						}
					}
				}
				if($u->info['id']==$u->tfer['uid1'])
				{
					$js .= 'refmoney('.$u->round2($u->tfer['money2']).','.$u->round2($u->tfer['money1']).');';
				}else{
					$js .= 'refmoney('.$u->round2($u->tfer['money1']).','.$u->round2($u->tfer['money2']).');';	
				}
			}
			if($js!='')
			{
				echo '<script>'.$js.'</script>';	
			}
		}else{
			echo '<script>location="main.php?transfer&rnd='.$code.'";</script>';	
		}
	}else{
		echo '<script>location="main.php?transfer&exit_transfer&rnd='.$code.'";</script>';	
	}
}
?>