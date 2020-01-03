<?
if(!defined('GAME')) { die(); }

if($u->room['file'] == 'newtower') {
	include('_incl_data/class/__zv.php');
	$bs = mysql_fetch_array(mysql_query('SELECT * FROM `bs_turnirs` WHERE `id` = "'.$u->info['inTurnir'].'" LIMIT 1'));
	$bmid = 0;
	if(!isset($bs['id'])  || $bs['status'] == 0) { die('Турнир не найден.'); }
	
	function b_act($uid, $val, $time) {
		global $bs;
		$ins = mysql_query('INSERT INTO `bs_actions` (`bid`,`count`,`time`,`uid`,`val`) VALUES (
			"'.$bs['id'].'","'.$bs['count'].'","'.$time.'","'.$uid.'","'.$val.'"
		)');
		return $ins;
	}
	
	function test_users_life() {
		global $u,$bs;
		//Проверка бага с юзерами и архивариусами
		$sp = mysql_query('SELECT `id`,`lose`,`nich` FROM `users` WHERE `inTurnir` = "'.$bs['id'].'"');
		$users = 0;
		$arhiv = 0;
		while( $pl = mysql_fetch_array($sp) ) {
			$test = mysql_fetch_array(mysql_query('SELECT `id` FROM `users` WHERE `inUser` = "'.$pl['id'].'" AND `real` > 0 AND `inUser` > 0 LIMIT 1'));
			if(isset($test['id'])) {
				//Игрок
				$users++;
			}else{
				//Архив
				$arhiv++;
			}
		}
		if( ($bs['users'] - $bs['users_finish']) != $users || $bs['arhiv'] != $arhiv ) {
			$bs['users_finish'] = $bs['users'] - $users;
			$bs['arhiv'] = $arhiv;
			mysql_query('UPDATE `bs_turnirs` SET `users_finish` = "'.$bs['users_finish'].'", `arhiv` = "'.$bs['arhiv'].'" WHERE `id` = "'.$bs['id'].'" LIMIT 1');
		}		
	}
	
	test_users_life();
	
	function b_test($uid,$val,$count = false,$sort = 'ORDER BY `id` DESC') {
		global $bs;
		if($count == false ) {
			$r = mysql_fetch_array(mysql_query('SELECT * FROM `bs_actions` WHERE `bid` = "'.$bs['id'].'" AND `count` = "'.$bs['count'].'" AND `uid` = "'.$uid.'" AND `val` = "'.$val.'" '.$sort.' LIMIT 1'));
		}else{
			$r = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `bs_actions` WHERE `bid` = "'.$bs['id'].'" AND `count` = "'.$bs['count'].'" AND `uid` = "'.$uid.'" AND `val` = "'.$val.'" '.$sort.' LIMIT 1'));
			$r = $r[0];
		}
		return $r;
	}
	
	//ПОднять предмет
	if( isset($_GET['takeit']) ) {
		$itm = mysql_fetch_array(mysql_query('SELECT * FROM `bs_items` WHERE `bid` = "'.$bs['id'].'" AND `count` = "'.$bs['count'].'" AND `id` = "'.mysql_real_escape_string($_GET['takeit']).'" LIMIT 1'));
		if( isset($itm['id']) ) {
			if( $itm['x'] == 0 && $itm['y'] == 0 ) {
				$itm['x'] = $u->info['x'];
				$itm['y'] = $u->info['y'];
			}
			if( $itm['x'] == $u->info['x'] && $itm['y'] == $u->info['y'] ) {
				$itmb = mysql_fetch_array(mysql_query('SELECT `id`,`img`,`name` FROM `items_main` WHERE `id` = "'.$itm['item_id'].'" LIMIT 1'));
				if( isset($itmb['id']) ) {
					$tact = b_test($u->info['id'],'take_itm');
					if( $tact['time'] > time() ) {
						$error2 = 'Нельзя поднимать предметы так часто, ждите еще '.($tact['time'] - time()).' сек.';
					}else{
						b_act($u->info['id'],'take_itm',time()+3);
						$error2 = 'Вы подняли предмет &quot;'.$itmb['name'].'&quot;';
						$u->addItem( $itmb['id'] , $u->info['id'] );
						mysql_query('DELETE FROM `bs_items` WHERE `id` = "'.$itm['id'].'" LIMIT 1');
					}
				}else{
					$error2 = 'Предмет не найден...';
				}
			}else{
				$error2 = 'Предмет не найден в комнате с вами...';
			}
		}else{
			$error2 = 'Предмет не найден, кто-то оказался быстрее...';
		}
	}
	
	//Предметы БС
	$bs_items = '';
	$sp = mysql_query('SELECT * FROM `bs_items` WHERE `bid` = "'.$bs['id'].'" AND `count` = "'.$bs['count'].'" AND `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'"');
	while( $pl = mysql_fetch_array($sp) ) {
		$itm = mysql_fetch_array(mysql_query('SELECT `id`,`img`,`name` FROM `items_main` WHERE `id` = "'.$pl['item_id'].'" LIMIT 1'));
		if( isset($itm['id']) ) {
			$bs_items .= '<a href="?takeit='.$pl['id'].'"><img title="Поднять '."\r".$itm['name'].'" src="http://img.xcombats.com/i/items/'.$itm['img'].'"></a>';
		}
	}
	if( $bs_items != '' ) {
		$bs_items = '<br><div><b>Предметы в комнате:</b><br><br>'.$bs_items.'</div>';
	}
	
	//Данные комнаты
	$map = array(
		'name' 	=> 'Название локации',
			'up' 		=> array( 'i' => 'i' , 'js' => 'onclick="return false"' ),
			'left' 		=> array( 'i' => 'i' , 'js' => 'onclick="return false"' ),
			'right' 	=> array( 'i' => 'i' , 'js' => 'onclick="return false"' ),
			'down' 		=> array( 'i' => 'i' , 'js' => 'onclick="return false"' )
	);
	$title_locs = array(
		'up' => '',
		'left' => '',
		'right' => '',
		'down' => ''
	);
	
	
	if( isset($_GET['attack']) ) {
		$usr = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_GET['attack']).'" AND `inUser` = 0 AND `room` = "362" LIMIT 1'));
		if( isset($usr['id']) ) {
			$usr_real = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `login` = "'.mysql_real_escape_string($_GET['attack']).'" AND `inUser` = "'.$usr['id'].'" LIMIT 1'));
			if( !isset($usr_real['id']) ) {
				$usr_real = $usr;
			}
			$sts = mysql_fetch_array(mysql_query('SELECT `id`,`x`,`y`,`team`,`hpNow` FROM `stats` WHERE `id` = "'.$usr['id'].'" LIMIT 1'));
			if( $sts['x'] != $u->info['x'] || $sts['y'] != $u->info['y'] ) {
				$error = 'Вы должны находиться в одной комнате';
			}elseif( $usr_real['login'] == $u->info['login'] ) {
				$error = 'Нельзя нападать на самого себя';
			}else{
				$tbtl = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.$usr['battle'].'" AND `team_win` = "-1" LIMIT 1'));
				if( !isset($tbtl['id']) && $usr['battle'] > 0 ) {
					$usr['battle'] = 0;
					$sts['team'] = 0;
					mysql_query('UPDATE `users` SET `battle` = 0 WHERE `id` = "'.$usr['id'].'" LIMIT 1');
					if( $sts['hpNow'] < 1 ) {
						mysql_query('UPDATE `stats` SET `hpNow` = 1 WHERE `id` = "'.$usr['id'].'" LIMIT 1');
					}
				}
					
				$btl_id = $magic->atackUser($u->info['id'],$usr['id'],$sts['team'],$usr['battle']);
				if( $btl_id > 0 ) {
					mysql_query('UPDATE `battle` SET `inTurnir` = "'.$bs['id'].'",`timeout` = "30" WHERE `id` = "'.$btl_id.'" LIMIT 1');
				}
				$me_real = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `inUser` = "'.$u->info['id'].'" AND `login` = "'.$u->info['login'].'" LIMIT 1'));
				if( $usr['battle'] > 0 ) {
					$usr['battle'] = mysql_fetch_array(mysql_query('SELECT `id` FROM `battle` WHERE `id` = "'.$usr['battle'].'" AND `team_win` = "-1" LIMIT 1'));	
					if( isset($usr['battle']['id']) ) {
						$usr['battle'] = $usr['battle']['id'];
					}else{
						$usr['battle'] = 0;
					}
				}
				if( $usr['battle'] > 0 ) {
					//Заносим в лог БС
					if( $u->info['sex'] == 0 ) {
						$text = '{u1} вмешался в поединок против {u2} <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
					}else{
						$text = '{u1} вмешалась в поединок против {u2} <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
					}
				}else{
					//Заносим в лог БС
					if( $u->info['sex'] == 0 ) {
						$text = '{u1} напал на {u2} завязался бой <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
					}else{
						$text = '{u1} напала на {u2} завязался бой <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
					}
				}
				if( isset($usr_real['id'])) {
					$usrreal = '';
					if( $usr_real['align'] > 0 ) {
						$usrreal .= '<img src=http://img.xcombats.com/i/align/align'.$usr_real['align'].'.gif width=12 height=15 >';
					}
					if( $usr_real['clan'] > 0 ) {
						$usrreal .= '<img src=http://img.xcombats.com/i/clan/'.$usr_real['clan'].'.gif width=24 height=15 >';
					}
					$usrreal .= '<b>'.$usr_real['login'].'</b>['.$usr_real['level'].']<a target=_blank href=/info/'.$usr_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
				}else{
					$mereal = '<i>Невидимка</i>[??]';
				}
				if( isset($me_real['id']) ) {
					$mereal = '';
					if( $me_real['align'] > 0 ) {
						$mereal .= '<img src=http://img.xcombats.com/i/align/align'.$me_real['align'].'.gif width=12 height=15 >';
					}
					if( $me_real['clan'] > 0 ) {
						$mereal .= '<img src=http://img.xcombats.com/i/clan/'.$me_real['clan'].'.gif width=24 height=15 >';
					}
					$mereal .= '<b>'.$me_real['login'].'</b>['.$me_real['level'].']<a target=_blank href=/info/'.$me_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
				}else{
					$mereal = '<i>Невидимка</i>[??]';
				}
				$text = str_replace('{u1}',$mereal,$text);
				$text = str_replace('{u2}',$usrreal,$text);
				//Добавляем в лог БС
				mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
					"1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$bs['id'].'", "'.$bs['count'].'", "'.$bs['city'].'",
					"'.round($bs['money']*0.85,2).'","'.$i.'"
				)');
				//
				unset($text,$usrreal,$mereal,$usr_real,$me_real);
				//
				header('location: main.php');
			}
		}else{
			$error = 'Персонаж не найден в этом турнире';
		}
	}
	$box = mysql_fetch_array(mysql_query('SELECT * FROM `bs_map` WHERE `mid` = "'.mysql_real_escape_string($bmid).'" AND `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'" LIMIT 1'));
	if( !isset($box['id']) ) {
		$box2 = mysql_fetch_array(mysql_query('SELECT * FROM `bs_map` WHERE `mid` = "'.mysql_real_escape_string($bmid).'" AND `y` = "'.$u->info['x'].'" AND `x` = "'.$u->info['y'].'" LIMIT 1'));
		if( isset($box2['id']) ) {
			$u->info['x'] = $box2['y'];
			$u->info['y'] = $box2['x'];
			mysql_query('UPDATE `stats` SET `x` = "'.$u->info['x'].'",`y` = "'.$u->info['y'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$bot = $box2;
		}
		unset($box2);
	}
	if( !isset($box['id']) ) {
		//Клетка не найдена
		$map['name'] = 'Неизвестная локация: '.$u->info['x'].' . '.$u->info['y'];
		if( $u->info['x'] == 0 && $u->info['y'] == 0 ) {
			mysql_query('UPDATE `stats` SET `x` = "-3",`y` = "-8" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			header('location: main.php');
			die();
		}
	}else{
		//Действия на клетке
		$goto = false;
		if( isset($_GET['up']) ) {
			if( $box['up'] == 0 ) {
				$error = 'Проход не существует';
			}else{
				if( $box['up'] > 1 ) {
					$goto = mysql_fetch_array(mysql_query('SELECT `id` FROM `bs_map` WHERE `mid` = "'.mysql_real_escape_string($bmid).'" AND `id` = "'.mysql_real_escape_string($box['up']).'" LIMIT 1'));
					if( isset($goto['id']) ) {
						$goto = array( $goto['x'], $goto['y'] );
					}
				}else{
					$goto = array( $box['x'] - 1, $box['y'] );
				}
			}
		}elseif( isset($_GET['down']) ) {
			if( $box['down'] == 0 ) {
				$error = 'Проход не существует';
			}else{
				if( $box['down'] > 1 ) {
					$goto = mysql_fetch_array(mysql_query('SELECT `id` FROM `bs_map` WHERE `mid` = "'.mysql_real_escape_string($bmid).'" AND `id` = "'.mysql_real_escape_string($box['down']).'" LIMIT 1'));
					if( isset($goto['id']) ) {
						$goto = array( $goto['x'], $goto['y'] );
					}
				}else{
					$goto = array( $box['x'] + 1, $box['y'] );
				}
			}
		}elseif( isset($_GET['left']) ) {
			if( $box['left'] == 0 ) {
				$error = 'Проход не существует';
			}else{
				if( $box['left'] > 1 ) {
					$goto = mysql_fetch_array(mysql_query('SELECT `id` FROM `bs_map` WHERE `mid` = "'.mysql_real_escape_string($bmid).'" AND `id` = "'.mysql_real_escape_string($box['left']).'" LIMIT 1'));
					if( isset($goto['id']) ) {
						$goto = array( $goto['x'], $goto['y'] );
					}
				}else{
					$goto = array( $box['x'] , $box['y']  - 1);
				}
			}
		}elseif( isset($_GET['right']) ) {
			if( $box['right'] == 0 ) {
				$error = 'Проход не существует';
			}else{
				if( $box['right'] > 1 ) {
					$goto = mysql_fetch_array(mysql_query('SELECT `id` FROM `bs_map` WHERE `mid` = "'.mysql_real_escape_string($bmid).'" AND `id` = "'.mysql_real_escape_string($box['right']).'" LIMIT 1'));
					if( isset($goto['id']) ) {
						$goto = array( $goto['x'], $goto['y'] );
					}
				}else{
					$goto = array( $box['x'] , $box['y']  + 1);
				}
			}
		}
		if( $goto != false ) {
			$stop_s = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `id_eff` = 2 AND `timeUse` > "'.time().'" LIMIT 1'));
			if($u->info['timeGo'] < time() ) {
			  if(!$stop_s['id']) {
				$trap = mysql_fetch_array(mysql_query('SELECT * FROM `bs_trap` WHERE `bid` = "'.$bs['id'].'" AND `login` != "'.$u->info['login'].'" AND `count` = "'.$bs['count'].'" AND `x` = "'.$goto[0].'" AND `y` = "'.$goto[1].'" LIMIT 1'));
				if( isset($trap['id']) ) {
					if(rand(0, 100) < $trap['chance'] || $trap['chance'] == 0 ) {
						//Добавляем эффект и отнимаем НР
						$ptntew = rand(1, 10)*60;
						mysql_query('INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`img2`) VALUES (
							"2","'.$u->info['id'].'","Путы (Ловушка '.$trap['login'].')","puti='.(time()+$ptntew).'","1","'.(time()+$ptntew).'","chains.gif"
						) ');
						if( $u->stats['hpNow'] > floor($u->stats['hpAll']/100*33)) {
							$trap_hpmin = round($u->stats['hpNow']/2);
							$u->stats['hpNow'] = $u->stats['hpNow']-$trap_hpmin;
							mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->stats['hpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						}
						if( $u->stats['hpNow'] < 0 ) {
							$u->stats['hpNow'] = 0;
						}elseif( $u->stats['hpNow'] > $u->stats['hpAll'] ) {
							$u->stats['hpNow'] = $u->stats['hpAll'];
						}
						//Заносим в чат
						$rtxt = '[img[items/trap.gif]] Персонаж &quot;'.$u->info['login'].'&quot; угодил в ловушку поставленную &quot;'.$trap['login'].'&quot;. <b>-'.$trap_hpmin.'</b> ['.round($u->stats['hpNow']).'/'.round($u->stats['hpAll']).']';
						mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`,`new`) VALUES ('".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1','1')");	
						//Заносим в лог БС
						$me_real = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `inUser` = "'.$u->info['id'].'" AND `login` = "'.$u->info['login'].'" LIMIT 1'));
						//Заносим в лог БС
						if( $u->info['sex'] == 0 ) {
							$text = '{u1} угодил в ловушку поставленную {u2}';
						}else{
							$text = '{u1} угодила в ловушку поставленную {u2}';
						}
						if( isset($trap['id'])) {
							$usrreal = '';
							if( $trap['align'] > 0 ) {
								$usrreal .= '<img src=http://img.xcombats.com/i/align/align'.$trap['align'].'.gif width=12 height=15 >';
							}
							if( $trap['clan'] > 0 ) {
								$usrreal .= '<img src=http://img.xcombats.com/i/clan/'.$trap['clan'].'.gif width=24 height=15 >';
							}
							$usrreal .= '<b>'.$trap['login'].'</b>['.$trap['level'].']<a target=_blank href=/info/'.$trap['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
						}else{
							$mereal = '<i>Невидимка</i>[??]';
						}
						if( isset($me_real['id']) ) {
							$mereal = '';
							if( $me_real['align'] > 0 ) {
								$mereal .= '<img src=http://img.xcombats.com/i/align/align'.$me_real['align'].'.gif width=12 height=15 >';
							}
							if( $me_real['clan'] > 0 ) {
								$mereal .= '<img src=http://img.xcombats.com/i/clan/'.$me_real['clan'].'.gif width=24 height=15 >';
							}
							$mereal .= '<b>'.$me_real['login'].'</b>['.$me_real['level'].']<a target=_blank href=/info/'.$me_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
						}else{
							$mereal = '<i>Невидимка</i>[??]';
						}
						$text = str_replace('{u1}',$mereal,$text);
						$text = str_replace('{u2}',$usrreal,$text);
						//Добавляем в лог БС
						mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
							"2", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$bs['id'].'", "'.$bs['count'].'", "'.$bs['city'].'",
							"'.round($bs['money']*0.85,2).'","'.$i.'"
						)');
						//
						unset($text,$usrreal,$mereal,$usr_real,$me_real);
						//Удаляем ловушку
						mysql_query('DELETE FROM `bs_trap` WHERE `id` = "'.$trap['id'].'" LIMIT 1');
					}
				}
				mysql_query('UPDATE `stats` SET `x` = "'.$goto[0].'" , `y` = "'.$goto[1].'" , `timeGo` = "'. ( time() + $box['timeGo'] ) .'" , `timeGoL` = "'. ( time() ) .'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				header('location: /main.php');
				die();
			  } else {
				$error = "Вы в ловушке...";
			  }
			}else{
				$error = 'Вы не можете так быстро перемещаться...';
			}
		}
		//Данные клетки
		$map['name'] = $box['name'];
		if( $box['up'] > 0 ) {
			$map['up']['i'] = '';
			$map['up']['js'] = '';
		}
		if( $box['left'] > 0 ) {
			$map['left']['i'] = '';
			$map['left']['js'] = '';
		}
		if( $box['right'] > 0 ) {
			$map['right']['i'] = '';
			$map['right']['js'] = '';
		}
		if( $box['down'] > 0 ) {
			$map['down']['i'] = '';
			$map['down']['js'] = '';
		}
	}
	if( $box['up'] > 0 ) {
		if( $box['up'] > 1 ) {
			$title_locs['up'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `bs_map` WHERE `mid` = "'.$box['mid'].'" AND `id` = "'.$box['up'].'" LIMIT 1'));
		}else{
			$title_locs['up'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `bs_map` WHERE `mid` = "'.$box['mid'].'" AND `x` = "'.($box['x']-1).'" AND `y` = "'.$box['y'].'" LIMIT 1'));
		}
		$title_locs['up'] = $title_locs['up']['name'];
	}
	if( $box['down'] > 0 ) {
		if( $box['down'] > 1 ) {
			$title_locs['down'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `bs_map` WHERE `mid` = "'.$box['mid'].'" AND `id` = "'.$box['down'].'" LIMIT 1'));
		}else{
			$title_locs['down'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `bs_map` WHERE `mid` = "'.$box['mid'].'" AND `x` = "'.($box['x']+1).'" AND `y` = "'.$box['y'].'" LIMIT 1'));
		}
		$title_locs['down'] = $title_locs['down']['name'];
	}
	if( $box['left'] > 0 ) {
		if( $box['left'] > 1 ) {
			$title_locs['left'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `bs_map` WHERE `mid` = "'.$box['mid'].'" AND `id` = "'.$box['left'].'" LIMIT 1'));
		}else{
			$title_locs['left'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `bs_map` WHERE `mid` = "'.$box['mid'].'" AND `y` = "'.($box['y']-1).'" AND `x` = "'.$box['x'].'" LIMIT 1'));
		}
		$title_locs['left'] = $title_locs['left']['name'];
	}
	if( $box['right'] > 0 ) {
		if( $box['right'] > 1 ) {
			$title_locs['right'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `bs_map` WHERE `mid` = "'.$box['mid'].'" AND `id` = "'.$box['right'].'" LIMIT 1'));
		}else{
			$title_locs['right'] = mysql_fetch_array(mysql_query('SELECT `id`,`name` FROM `bs_map` WHERE `mid` = "'.$box['mid'].'" AND `y` = "'.($box['y']+1).'" AND `x` = "'.$box['x'].'" LIMIT 1'));
		}
		$title_locs['right'] = $title_locs['right']['name'];
	}
?>
<script type="text/javascript" src="js/jquery.js"></script>
<script>if(top.$('#autoRefOnline').attr('checked') == true && top.$('#chcf10').attr('checked') != true) {top.chat.reflesh();}</script>
<div style="margin-right:20px;">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <? if(isset($u->error) && $u->error != ''){ ?>
  <tr>
    <td>
		<p style="float:right;">&nbsp;<? if(isset($u->error)){ echo '<font color="red"><b>'.$u->error.'</b></font>'; } ?></p>
    </td>
    </tr>
  <? } ?>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
            	<? if(isset($error2)){ echo '<font color="red"><b>'.$error2.'</b></font>'; } ?>
            	<?
					echo $zv->userInfo();
					$sp = mysql_query('SELECT * FROM `bs_trap` WHERE `bid` = "'.$bs['id'].'" AND `count` = "'.$bs['count'].'" AND `login` = "'.$u->info['login'].'" AND `x` = "'.$u->info['x'].'" AND `y` = "'.$u->info['y'].'"');
					while($pl = mysql_fetch_array($sp)) {
						echo '<img title="Ваша ловушка. Вероятность: '.$pl['chance'].'%'."\r".''.date('d.m.Y H:i',$pl['time']).'" src="http://img.xcombats.com/i/items/trap.gif">';
					}
					if(isset($u->stats['puti'])) {
						echo '<br><img src="http://img.xcombats.com/i/items/chains.gif"> Вы не можете передвигаться еще '.$u->timeOut($u->stats['puti']-time()).' ';
					}
					echo '<script>top.lafstReg['.$u->info['id'].'] = 0; top.startHpRegen("main",'.$u->info['id'].','.(0+$u->stats['hpNow']).','.(0+$u->stats['hpAll']).','.(0+$u->stats['mpNow']).','.(0+$u->stats['mpAll']).','.(time()-$u->info['regHP']).','.(time()-$u->info['regMP']).','.(0+$u->rgd[0]).','.(0+$u->rgd[1]).',1);</script>';
					echo $bs_items;
				?>
            </td>
            <td width="530" height="260" align="right" valign="top"><table width="100%"  border="0" align="right" cellpadding="10" cellspacing="0">
              <tr align="right" valign="top">
                <td>
                <? if(isset($error)){ echo '<font color="red"><b>'.$error.'</b></font>'; } ?>
                <h3 style="text-align:right">&nbsp; <?=$map['name']?>  &nbsp;</h3>
                <?
				if( $box['img'] != '' ) {
					echo '<img src="http://img.xcombats.com/i/tower/'.$box['img'].'">';
				}
				?>
                </td>
                <td width="80">
                <div align="center" style="padding:13px 5px 15px 5px;">
                	<img style="cursor:pointer" onclick="top.atackTower();" src="http://img.xcombats.com/attack.gif" width="66" height="24" />
                </div>
                  <table width="80" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><table width="80"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td colspan="3" align="center"><img src="http://img.xcombats.com/move/navigatin_46.gif" width="80" height="4" /></td>
                      </tr>
                      <tr>
                        <td colspan="3" align="center"><table width="80"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><img src="http://img.xcombats.com/move/navigatin_48.gif" width="9" height="8" /></td>
                            <td width="100%" bgcolor="#000000">
                            <div style="position:relative">
                            	<div style="position:absolute;top:-14px;left:-8px;"><? echo $goLis; ?></div>
                                <script>$('#locobobr').css('display','none');</script>
                            </div>
                            </td>
                            <td><img src="http://img.xcombats.com/move/navigatin_50.gif" width="7" height="8" /></td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><img src="http://img.xcombats.com/move/navigatin_51.gif" width="31" height="8" /></td>
                          </tr>
                          <tr>
                            <td><img src="http://img.xcombats.com/move/navigatin_54.gif" width="9" height="20" /><img src="http://img.xcombats.com/move/navigatin_55i.gif" width="22" height="20" border="0" /></td>
                          </tr>
                          <tr>
                            <td><a <?='title="'.$title_locs['left'].'"'?> <?=$map['left']['js']?> href="?left"><img src="http://img.xcombats.com/move/navigatin_59<?=$map['left']['i']?>.gif" width="21" height="20" border="0" /></a><img src="http://img.xcombats.com/move/navigatin_60.gif" width="10" height="20" border="0" /></td>
                          </tr>
                          <tr>
                            <td><img src="http://img.xcombats.com/move/navigatin_63.gif" width="11" height="21" /><img src="http://img.xcombats.com/move/navigatin_64i.gif" width="20" height="21" border="0" /></td>
                          </tr>
                          <tr>
                            <td><img src="http://img.xcombats.com/move/navigatin_68.gif" width="31" height="8" /></td>
                          </tr>
                        </table></td>
                        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><a <?='title="'.$title_locs['up'].'"'?> <?=$map['up']['js']?> href="?up"><img src="http://img.xcombats.com/move/navigatin_52<?=$map['up']['i']?>.gif" width="19" height="22" border="0" /></a></td>
                          </tr>
                          <tr>
                            <td><a href="main.php"><img src="http://img.xcombats.com/move/navigatin_58.gif" width="19" height="33" border="0" o="o" /></a></td>
                          </tr>
                          <tr>
                            <td><a <?='title="'.$title_locs['down'].'"'?> <?=$map['down']['js']?> href="?down"><img src="http://img.xcombats.com/move/navigatin_67<?=$map['down']['i']?>.gif" width="19" height="22" border="0" /></a></td>
                          </tr>
                        </table></td>
                        <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><img src="http://img.xcombats.com/move/navigatin_53.gif" width="30" height="8" /></td>
                          </tr>
                          <tr>
                            <td><img src="http://img.xcombats.com/move/navigatin_56i.gif" width="21" height="20" border="0" /><img src="http://img.xcombats.com/move/navigatin_57.gif" width="9" height="20" /></td>
                          </tr>
                          <tr>
                            <td><img src="http://img.xcombats.com/move/navigatin_61.gif" width="8" height="21" /><a <?='title="'.$title_locs['right'].'"'?> <?=$map['right']['js']?> href="?right"><img src="http://img.xcombats.com/move/navigatin_62<?=$map['right']['i']?>.gif" width="22" height="21" border="0" /></a></td>
                          </tr>
                          <tr>
                            <td><img src="http://img.xcombats.com/move/navigatin_65i.gif" width="21" height="20" border="0" /><img src="http://img.xcombats.com/move/navigatin_66.gif" width="9" height="20" /></td>
                          </tr>
                          <tr>
                            <td><img src="http://img.xcombats.com/move/navigatin_69.gif" width="30" height="8" /></td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
                  </tr>
                </table>
                  <table  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td nowrap="nowrap" id="moveto"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                      </table></td>
                    </tr>
                  </table>
                  <!-- <br /><span class="menutop"><nobr>Картинная галерея 2</nobr></span>--></td>
              </tr>
            </table></td>
          </tr>
        </table>
        Всего живых участников на данный момент: <b><?=($bs['users'])?></b><? if($bs['arhiv'] > 0) { ?> + <b><?=$bs['arhiv']?></b><? } ?>...
        <br />
        Тип турнира: <?
		$typbs = array(
			'Обычный',
			'Светлый',
			'Темный',
			'Быстрый',
			'Медленный',
			'Жадный',
			'Яростный',
			'Без НР'
		);
		echo $typbs[$bs['type_btl']];
		?>, 
        История текущего <a target="_blank" href="/towerlog.php?id=<?=$bs['count']?>">турнира</a>.
        </td>
      </tr>
    </table></td>
  </tr>
</table>
</div>
<?
}
?>