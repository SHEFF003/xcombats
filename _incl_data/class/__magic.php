<?php
if(!defined('GAME'))
{
	die();
}

if(isset($_POST['useitemon'])) {
	$_GET['login'] = $_POST['useitemon'];
}

class Magic {
	
	public $youuse = 0;
	public $c_magic = array(4174, 4175, 4176, 4177, 4178, 4179, 4180);
    public $e_magic = array(4185, 4186, 4187);
	//Ослабление после боя
	public function oslablenie($uid)
	{
		$ins = mysql_query('INSERT INTO `eff_users` (`id_eff`,`uid`,`name`,`data`,`timeUse`,`no_Ace`) VALUES ("5","'.$uid.'","Ослабление после боя","add_m10=-1000|add_m11=-1000","'.(time()-600+( 60 * 3 )).'",1)');
		if($ins)
		{
			return true;
		}else{
			return false;
		}
	}
	
	//Пристрастия (Обновляем эффект пристрастия и возможно добавляем новый уровень)
	public $pgtype = array(
		//Пристрастия на силу
		1	=> 1, //Зелье Могущества
		264	=> 1, //Снадобье Великана
		306 => 1, //Нектар Великана
		383 => 1, //Големский
		
		//Пристрастие на ловкость
		8	=> 2,
		265	=> 2,
		308 => 2, //Нектар Змеи
		385 => 2, //Големский
		
		//Пристрастие на интуицию
		7	=> 3,
		266	=> 3,
		307 => 3, //Нектар Предчувствия
		384 => 3, //Големский
		
		//Пристрастие на Интеллект
		9	=> 4,
		267	=> 4,
		309 => 4, //Нектар Разума
		333 => 4,
		405 => 4, //Големский
		
		//Пристрастие на Защиту от урона
		14	=> 5,
		27	=> 5,
		37	=> 5,
		361	=> 5,
		
		//Пристрастие на Защиту от колющего урона
		10 => 6,
		//Пристрастие на Защиту от рубящего урона
		12 => 7,
		//Пристрастие на Защиту от дробящего урона
		13 => 8,
		//Пристрастие на Защиту от режущего урона
		11 => 9,
		
		//Пристрастие на Защиту от магии
		28	=> 10,
		38	=> 10,
		362	=> 10,
		369 => 10,
		
		//Пристрастие на Защиту от огня
		272 => 11,
		370 => 11,
		//Пристрастие на Защиту от воды
		334 => 12,
		275 => 12,
		372 => 12,
		//Пристрастие на Защиту от воздуха
		274 => 13,
		373 => 13,
		//Пристрастие на Защиту от земли
		273 => 14,
		374 => 14,
		
		//Восстановление здоровья
		268 => 15,
		//Восстановление маны
		300 => 16,
		
		10000000000000 => 0
	);
	
	//Пристрастия (Снимается эффект)
	public $pgtype_second = array(		
		//Пристрастие на Защиту от урона
		14	=> array(6,7,8,9),
		27	=> array(6,7,8,9),
		37	=> array(6,7,8,9),
		361	=> array(6,7,8,9),
		
		//Пристрастие на Защиту от колющего урона
		10 => array(5,7,8,9),
		//Пристрастие на Защиту от рубящего урона
		12 => array(6,5,8,9),
		//Пристрастие на Защиту от дробящего урона
		13 => array(6,7,5,9),
		//Пристрастие на Защиту от режущего урона
		11 => array(6,7,8,5),
		
		//Пристрастие на Защиту от магии
		//28	=> 10,
		//38	=> 10,
		
		//Пристрастие на Защиту от огня
		272 => array( 10 , 11 , 12 , 13 , 14 ),
		370 => array( 10 , 11 , 12 , 13 , 14 ),
		//Пристрастие на Защиту от воды
		334 => array( 10 , 11 , 12 , 13 , 14 ),
		275 => array( 10 , 11 , 12 , 13 , 14 ),
		372 => array( 10 , 11 , 12 , 13 , 14 ),
		//Пристрастие на Защиту от воздуха
		274 => array( 10 , 11 , 12 , 13 , 14 ),
		373 => array( 10 , 11 , 12 , 13 , 14 ),
		//Пристрастие на Защиту от земли
		273 => array( 10 , 11 , 12 , 13 , 14 ),
		374 => array( 10 , 11 , 12 , 13 , 14 ),
		
		//Восстановление здоровья
		//268 => 15,
		//Восстановление маны
		//300 => 16,
		
		10000000000000 => 0
	);
	
	//Параметры пристрастия
	//Название , максимальный уровень , негативный эффект , сколько времени длится каждый уровень в сутках , имя негативного параметра
	public $pgpar = array(
		1 => array('Сила', 25, 29, 1.2, 's1', 301),
		2 => array('Ловкость', 25, 29, 1.2, 's2', 302),
		3 => array('Интуиция', 25, 29, 1.2, 's3', 303),
		4 => array('Интеллект', 25, 29, 1.2, 's5', 304),
		
		5 => array('Защита от урона', 25, 175, 1.2, 'za', 321),		
		6 => array('Защита от колющего урона', 25, 98, 1.2, 'za1', 322),
		7 => array('Защита от рубящего урона', 25, 98, 1.2, 'za2', 323),
		8 => array('Защита от дробящего урона', 25, 98, 1.2, 'za3', 324),
		9 => array('Защита от режущего урона', 25, 98, 1.2, 'za4', 325),
		
		10 => array('Защита от магии', 25, 175, 1.2, 'zm', 326),		
		11 => array('Защита от магии огня', 25, 98, 1.2, 'zm1', 327),
		12 => array('Защита от магии воды', 25, 98, 1.2, 'zm3', 328),
		13 => array('Защита от магии воздуха', 25, 98, 1.2, 'zm2', 329),
		14 => array('Защита от магии земли', 25, 98, 1.2, 'zm4', 330),
		
		15 => array('Восстановление HP', 25, 294, 1.2, 'speedhp', 331),
		16 => array('Восстановление MP', 25, 294, 1.2, 'speedmp', 332)
	);
	
	//Действия элика под пристрастием
	public $pgel = array(
		//сила
		1	=> array(5), //Зелье Могущества
		264	=> array(7),  //Снадобье Великана
		306 => array(7),
		383 => array(6),
		//ловкость
		8	=> array(5),
		265	=> array(7),
		308 => array(7),
		385 => array(6),
		//интуиция
		7	=> array(5),
		266	=> array(7),
		307 => array(7),
		384 => array(6),
		//Интеллект
		9	=> array(5),
		267	=> array(7),
		309 => array(7),
		333 => array(5),
		405 => array(6),
		//Защита от урона
		14	=> array(12),
		27	=> array(18),
		37	=> array(25),
		361 => array(45),
		//Защита от колющего урона
		10	=> array(12),
		//Защита от режущего урона
		11	=> array(12),
		//Защита от рубящего урона
		12	=> array(12),
		//Защита от дробящего урона
		13	=> array(12),
		//Защита от магии
		369 => array(8),
		28	=> array(18),
		38	=> array(25),
		362	=> array(45),
		
		//Защита от магии воды
		334 => array(18), //Снадобье Океанов
		//
		272 => array(12),
		273 => array(12),
		274 => array(12),
		275 => array(12),
		//
		370 => array(20),
		372 => array(20),
		373 => array(20),
		374 => array(20),
		//
		//Восстановление НР
		268 => array(75),
		//Восстановление МР
		300 => array(75)
	);
	
	//Эликсиры разгона пристрастий
	public $elrazgon = array( 405 => true , 383 => true , 384 => true , 385 => true );
	
	public function paguba( $eff ) {
		//global $u;	
		if(isset($this->pgtype[$eff['id2']])) {
			global $u;
			$re = '';
			
			$tp = $this->pgtype[$eff['id2']];
			$tp_sec = $this->pgtype_second[$eff['id2']];
			$v = $this->pgpar[$tp];
			$el = $this->pgel[$eff['id2']];
			$pgb = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `v1` = "pgb'.$tp.'" AND `delete` = "0" AND `uid` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));	
			if( is_array($tp_sec) ) {
				$i = 0;
				while( $i < count($tp_sec) ) {
					if( $tp_sec[$i] > 0 ) {
						$pgb_ref = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `v1` = "pgb'.$tp_sec[$i].'" AND `delete` = "0" AND `uid` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));	
						if( isset($pgb_ref['id']) ) {
							$lvl = explode('[',$pgb['name']); 
							$lvl = explode(']',$lvl[1]);
							$lvl = $lvl[0];
							mysql_query('UPDATE `eff_users` SET `deactiveTime` = "'.(time()+$eff['actionTime']).'", `timeUse` = "'.(time()+floor($lvl*$this->pgpar[$tp_sec[$i]][3]*86400)).'" WHERE `id` = "'.$pgb_ref['id'].'" LIMIT 1');
						}
					}
					$i++;
				}
				unset($pgb_ref,$i);
			}
			if(!isset($pgb['id'])) {
				//Пристрастия нет, но оно может появиться, шанс 10%
				$prc11 = 5;
				if( $this->elrazgon[$eff['id2']] == true ) {
					$prc11 = 101;
				}
				if(rand(0,100) < $prc11) {
					//Добавляем пристрастие
					$d = 'add_'.$v[4].'=-'.ceil($v[2]/$v[1]*1);
					mysql_query('INSERT INTO `eff_users` (`v1`,`overType`,`id_eff`,`uid`,`name`,`timeUse`,`data`,`no_Ace`,`deactiveTime`) VALUES ("pgb'.$tp.'","0","'.$v[5].'","'.$u->info['id'].'","Пагубное пристрастие [1]","'.(time()+floor($v[3]*86400)).'","'.$d.'","0","'.(time()+$eff['actionTime']).'")');
				}
			}else{
				$lvl = explode('[',$pgb['name']); 
				$lvl = explode(']',$lvl[1]);
				$lvl = $lvl[0]; 
				$prc11 = 7;
				if( $this->elrazgon[$eff['id2']] == true ) {
					$prc11 = 101;
				}
				//Пристрастия есть и возможно повысить его уровень 5% , если прошло 75% времени эликсира
				if(rand(0,100)  < $prc11 && $lvl < $v[1]) {
					//Добавляем новое пристрастие
					$lvl++;
					$d = 'add_'.$v[4].'=-'.ceil($v[2]/$v[1]*$lvl);
					mysql_query('DELETE FROM `eff_users` WHERE `id` = "'.$pgb['id'].'" LIMIT 1');
					mysql_query('INSERT INTO `eff_users` (`v1`,`overType`,`id_eff`,`uid`,`name`,`timeUse`,`data`,`no_Ace`,`deactiveTime`) VALUES ("pgb'.$tp.'","0","'.$v[5].'","'.$u->info['id'].'","Пагубное пристрастие ['.$lvl.']","'.(time()+floor($lvl*$v[3]*86400)).'","'.$d.'","0","'.(time()+$eff['actionTime']).'")');
					$pgb = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `v1` = "pgb'.$tp.'" AND `delete` = "0" AND `uid` = "'.$u->info['id'].'" RODER BY `id` DESC LIMIT 1'));			
				}else{
					//Обновляем заряд пристрастия
					mysql_query('UPDATE `eff_users` SET `deactiveTime` = "'.(time()+$eff['actionTime']).'", `timeUse` = "'.(time()+floor($lvl*$v[3]*86400)).'" WHERE `id` = "'.$pgb['id'].'" LIMIT 1');
				}
				
				
				//Перезаписываем эффект пристрастия
				$eff['mdata'] = $u->lookStats($eff['mdata']);
				$eff['mdata']['add_'.$v[4]] += floor($el[0]/$v[1]*$lvl);
				$eff['mdata'] = $u->impStats($eff['mdata']);
			}
		}
		return $eff;
	}





	public function inBattleLog($txt,$usr) {
		global $u;
		$lastHOD = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$u->info['battle'].'" ORDER BY `id_hod` DESC LIMIT 1'));
		if(isset($lastHOD['id'])) {
			$id_hod = $lastHOD['id_hod'];
			if($lastHOD['type']!=6) {
				$id_hod++;
			}
			mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$u->info['battle'].'","'.($id_hod).'","{tm1} '.$txt.'","login1='.$u->info['login'].'||t1='.$u->info['team'].'||login2='.$usr['login'].'||t2='.$usr['team'].'||time1='.time().'","","","","","6")');
		}
	}


	
	//Использование предмета
	public function useItems($id)
	{
		global $u, $c, $code, $btl, $e_magic, $c_magic;
		$itm = mysql_fetch_array(mysql_query('SELECT `iu`.`id` AS `iuid`,`im`.*,`iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid` = "'.$u->info['id'].'" AND `iu`.`inShop`="0" AND `iu`.`delete`="0" AND `iu`.`id` = "'.mysql_real_escape_string((int)$id).'" LIMIT 1'));
		$bs_is = mysql_fetch_array(mysql_query('SELECT * FROM FROM `bs_turnirs` WHERE `id` = "'.$u->info['inTurnir'].'" LIMIT 1'));
        if(isset($itm['id']))
		{
			
			if($itm['group'] == 1) {
				//Группа предметов
				if($u->itemsX($itm['id'])>1) {
					//вытаскиваем предмет из группы
					$u->unstack($itm['id'],1);
					/*$itm = mysql_fetch_array(mysql_query('SELECT `iu`.`id` AS `iuid`,`im`.*, `iu`.* FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`delete`="1000" AND `iu`.`inGroup` = "'.$itm['id'].'" LIMIT 1'));
					if(!isset($itm['id'])) {
						$this->error = 'Группа предметов ошибочна...';
					}else{
						$itm['delete'] = 0;
					}*/
				}
			}
			
			$st = $u->lookStats($itm['data']);

			if($itm['magic_chance'] > 0 && $itm['magic_chance'] < 100) {
			  $itm['magic_chance'] += floor($u->stats['s5']*3);
			  if($itm['magic_chance'] >= 99) { $itm['magic_chance'] = 99; }
			}
            if(isset($bs_is['id'])) {
              if(in_array($itm['item_id'], $e_magic) || in_array($itm['item_id'], $c_magic)) {
                $itm['magic_chance'] += $bs_is['users'];
                if($itm['magic_chance'] >= 99) { $itm['magic_chance'] = 99; }
              }
            }

			if($itm['iznosNOW'] >= $itm['iznosMAX']) {
				$u->error = 'Не осталось зарядов...';
			}elseif( $st['useOnlyInBattle'] == 1 && $u->info['battle'] == 0 ) {
				//Можно использовать только в поединке
				$u->error = 'Можно использовать только в поединке';
			}elseif( $u->info['battle'] > 0 && $itm['btl_zd'] > 0 ) {
				//Можно использовать только в поединке
				$u->error = 'Задержка использования еще '.$itm['btl_zd'].' ходов';
			}elseif( $st['useOnlyInBattle'] == 1 && $u->info['battle'] > 0 && $u->stats['hpNow'] < 1 ) {
				//Можно использовать только в поединке
				$u->error = 'Вы погибли, нельзя пользоваться свитками и магией';
			} elseif($itm['magic_chance'] > 0 && rand(0, 100) > $itm['magic_chance']) {
				$u->error = 'Каст &quot;'.$itm['name'].'&quot; сгорел';
				$itm['iznosNOW']++;
				if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
				  mysql_query('UPDATE `items_users` SET `inGroup` = 0, `delete` = 0 WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				}
				mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
				$u->addDelo(1, $u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;:<B>(КАСТ СГОРЕЛ)</b> Персонаж использовал &quot;'.$itm['name'].'&quot; ('.$us[1].') [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);				
			
            } elseif(isset($st['usefromfile'])) {
				
				//используем заклятие
				$st = $u->lookStats($itm['data']);
				if( isset($st['zazuby']) && $_GET['login'] != $u->info['login'] ) {
					//unset($st['useOnLogin']);
					$_GET['login'] = $u->info['login'];
				}
				$jl = $_GET['login'];
				$_GET['login'] = urlencode($_GET['login']);
				//используем на персонажа (все кроме себя)	
				$_GET['login'] = str_replace('%',' ',$_GET['login']);
				$_GET['login'] = str_replace('25','',$_GET['login']);
				$jl = str_replace('%',' ',$jl);
				$jl = str_replace('25','',$jl);
				
				if(isset($st['useOnLogin']) && $st['useOnLogin']==1) {
					if( $u->info['inTurnir'] == 0 ) {
						if( $u->info['battle'] > 0 ) {
							$usr = mysql_fetch_array(mysql_query('SELECT `st`.`atack`, `st`.`clone`, `u`.`bot_id`, `u`.`type_pers`,`u`.`inTurnir`,`st`.`zv`,`st`.`bot`,`st`.`hpNow`,`u`.`login`,`st`.`dnow`,`u`.`id`,`u`.`align`,`u`.`admin`,`u`.`clan`,`u`.`level`,`u`.`room`,`u`.`online`,`u`.`battle`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`city` = "'.$u->info['city'].'" AND `u`.`battle` = "'.$u->info['battle'].'" AND (`u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($jl).'") LIMIT 1'));
						}else{
							$usr = mysql_fetch_array(mysql_query('SELECT `st`.`atack`, `st`.`clone`, `u`.`bot_id`, `u`.`type_pers`,`u`.`inTurnir`,`st`.`zv`,`st`.`bot`,`st`.`hpNow`,`u`.`login`,`st`.`dnow`,`u`.`id`,`u`.`align`,`u`.`admin`,`u`.`clan`,`u`.`level`,`u`.`room`,`u`.`online`,`u`.`battle`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`city` = "'.$u->info['city'].'" AND (`u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($jl).'") LIMIT 1'));
						}
					}else{
						if( $u->info['battle'] > 0 ) {
							$usr = mysql_fetch_array(mysql_query('SELECT `st`.`atack`,`st`.`clone`,`u`.`bot_id`,`u`.`type_pers`,`u`.`inTurnir`,`st`.`zv`,`st`.`bot`,`st`.`hpNow`,`u`.`login`,`st`.`dnow`,`u`.`id`,`u`.`align`,`u`.`admin`,`u`.`clan`,`u`.`level`,`u`.`room`,`u`.`online`,`u`.`battle`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`city` = "'.$u->info['city'].'" AND `u`.`battle` = "'.$u->info['battle'].'" AND (`u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($jl).'") AND `u`.`inTurnir` > 0 LIMIT 1'));
						}else{
							$usr = mysql_fetch_array(mysql_query('SELECT `st`.`atack`,`st`.`clone`,`u`.`bot_id`,`u`.`type_pers`,`u`.`inTurnir`,`st`.`zv`,`st`.`bot`,`st`.`hpNow`,`u`.`login`,`st`.`dnow`,`u`.`id`,`u`.`align`,`u`.`admin`,`u`.`clan`,`u`.`level`,`u`.`room`,`u`.`online`,`u`.`battle`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`city` = "'.$u->info['city'].'" AND (`u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($jl).'") AND `u`.`inTurnir` > 0 LIMIT 1'));
						}
					}
				}
				
				if($st['usefromfile']==1) {
					if($u->info['battle'] > 0) {
						if(file_exists('../../_incl_data/class/magic/'.$itm['magic_inci'].'.php'))
						{
							require('../../_incl_data/class/magic/'.$itm['magic_inci'].'.php');
						}else{
							$u->error = 'Не удалось использовать ('.$itm['magic_inci'].'.b) #1';
						}
					}else{
						if(file_exists('_incl_data/class/magic/'.$itm['magic_inci'].'.php'))
						{
							require('_incl_data/class/magic/'.$itm['magic_inci'].'.php');
						}else{
							$u->error = 'Не удалось использовать ('.$itm['magic_inci'].') #2';
						}
					}
				}else{
					if($itm['useInBattle'] > 0) {
						if(file_exists('../../_incl_data/class/priems/'.$st['usefromfile'].'.php'))
						{
							require('../../_incl_data/class/priems/'.$st['usefromfile'].'.php');
						}else{
							$u->error = 'Не удалось использовать ('.$st['usefromfile'].'.) #3';
						}
					}else{
						$u->error = 'Не удалось использовать ('.$st['usefromfile'].'!) #4';
					}
				}
			}elseif($itm['type']==30)
			{
				//Эликсиры
				$goodUse = 0; $use = array();
				if(isset($st['moment']))
				{
					//Эликсир используется моментально (Восстановление НР или МР)
					if(isset($st['moment_hp'])) {
						//Восстанавливаем здоровье
						if($u->stats['hpNow']<$u->stats['hpAll']) {
							$goodUse = 1;
							$use['moment_hp'] = $st['moment_hp'];
							
							if($u->stats['hpNow']+$use['moment_hp']>$u->stats['hpAll']) {
								$use['moment_hp'] = ceil($u->stats['hpAll']-$u->stats['hpNow']);							
							}
							
							$u->error .= 'Вы восстановили '.($use['moment_hp']).' HP.<br>';
						}else{
							$u->error = 'Ваше здоровье и так полностью восстановлено';
							$goodUse = 0;
						}
					}	
					
					if(isset($st['moment_mp'])) {
						//Восстанавливаем здоровье
						if($u->stats['mpNow'] < $u->stats['mpAll']) {
							$goodUse = 1;
							$use['moment_mp'] = $st['moment_mp'];
							if($u->stats['mpNow']+$use['moment_mp'] > $u->stats['mpAll']) {
							  $use['moment_mp'] = ceil($u->stats['mpAll']-$u->stats['mpNow']);							
							}
							$u->error .= 'Вы восстановили '.($use['moment_mp']).' MP.<br />';
						} else {
							$u->error = 'Ваша манна и так полностью восстановлена';
							$goodUse = 0;
						}
					}	
					
					if($itm['iznosNOW']>=$itm['iznosMAX'])
					{
						$u->error = 'Эликсир был испорчен...';
						$goodUse = 0;
					}
						
					if(($u->info['align']==2 || $u->info['haos']>time()) && isset($st['nohaos']))
					{
						$goodUse = 0;
						$u->error = 'Хаосники не могут использовать данный эликсир';
					}
								
					//Заносим данные в БД
					if($goodUse==1)
					{
						$itm['iznosNOW']++;
						$upd = mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
						if($upd) {
							$u->stats['hpNow'] += $use['moment_hp'];
							$u->info['hpNow'] += $use['moment_hp'];
                            $u->stats['mpNow'] += $use['moment_mp'];
							$u->info['mpNow'] += $use['moment_mp'];
							if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
								mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
							}
							mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->info['hpNow'].'", `mpNow` = "'.$u->info['mpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							$u->addDelo(1,$u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж использовал эликсир &quot;'.$itm['name'].'&quot; (+'.$use['moment_hp'].' HP) [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);
							$this->youuse++;
							$u->error = 'Вы успешно использовали эликсир &quot;'.$itm['name'].'&quot;<br>'.$u->error.'';
						}else{
							$u->error = 'Не удалось использовать эликсир...';
						}
					}
				}else{
					//Эликсиры с продолжительным эффектом
					$goodUse = 1;
					if(($u->info['align']==2 || $u->info['haos']>time()) && isset($st['nohaos']))
					{
						$goodUse = 0;
						$u->error = 'Хаосники не могут использовать данный эликсир';
					}
					if($goodUse==1)
					{
						$upd1 = 1;
						$upd2 = 1;
						//добавляем эффект персонажу
						if(isset($st['onlyOne']))
						{
							//убираем прошлые эффекты
							$goodUse = 0;
							$upd1 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `id_eff` = "'.$itm['magic_inc'].'"');
							if($upd1)
							{
								$goodUse = 1;
							}
						}
						if(isset($st['oneType']))
						{
							//убираем прошлые эффекты
							$goodUse = 0;
							$upd2 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `overType` = "'.$itm['overType'].'"');
							if($upd1)
							{
								$goodUse = 1;
							}
						}
						if($goodUse == 1)
						{
							$us = $this->add_eff($u->info['id'],$itm['magic_inc']);
							if($us[0]==1)
							{
								$itm['iznosNOW']++;
								if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
									mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
								}
								mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
								$u->addDelo(1,$u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж использовал эликсир &quot;'.$itm['name'].'&quot; ('.$us[1].') [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);
								$this->youuse++;
								$u->error = 'Вы успешно использовали эликсир &quot;'.$itm['name'].'&quot;<br>'.$us[1].'';
							}else{
								$u->error = 'Не удалось использовать "'.$itm['name'].'"';
							}
						}else{
							$u->error = 'Не удалось использовать "'.$itm['name'].'"';
						}
					}
				}
				//---------------
			}elseif($itm['type']==29)
			{
				//используем заклятие
				$st = $u->lookStats($itm['data']);
				if( isset($st['zazuby']) && $_GET['login'] != $u->info['login'] ) {
					//unset($st['useOnLogin']);
					$_GET['login'] = $u->info['login'];
				}
				$jl = $_GET['login'];
				$_GET['login'] = urlencode($_GET['login']);
				//используем на персонажа (все кроме себя)	
				$_GET['login'] = str_replace('%',' ',$_GET['login']);
				$_GET['login'] = str_replace('25','',$_GET['login']);
				$jl = str_replace('%',' ',$jl);
				$jl = str_replace('25','',$jl);
				if($itm['magic_inci']=='nextuplvl') {
					if($itm['iznosNOW']>=$itm['iznosMAX']) {
						$u->error = 'Свиток был исполчен...';
					}elseif( $u->info['battle'] > 0 ) {
						//
						$u->error = 'Вы не можете использовать свиток в бою';
					}else{
						if( $u->info['twink'] > 0 ) {
							$u->error = 'Используйте свиток на основном персонаже';
						}else{
							mysql_query('UPDATE `users` SET `stopexp` = 0 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							mysql_query('UPDATE `users_twink` SET `stopexp` = 0 WHERE `uid` = "'.$u->info['id'].'" LIMIT 1');
							$itm['iznosNOW']++;
							if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
								mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
							}
							mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
						}
					}
				}elseif(isset($st['useOnLogin']) && $st['useOnLogin']==1)
				{
					if( $u->info['inTurnir'] == 0 ) {
						if( $u->info['battle'] > 0 ) {
							$usr = mysql_fetch_array(mysql_query('SELECT `st`.`atack`, `st`.`clone`, `u`.`bot_id`, `u`.`type_pers`,`u`.`inTurnir`,`st`.`zv`,`st`.`bot`,`st`.`hpNow`,`u`.`login`,`st`.`dnow`,`u`.`id`,`u`.`align`,`u`.`admin`,`u`.`clan`,`u`.`level`,`u`.`room`,`u`.`online`,`u`.`battle`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`city` = "'.$u->info['city'].'" AND `u`.`battle` = "'.$u->info['battle'].'" AND (`u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($jl).'") LIMIT 1'));
						}else{
							$usr = mysql_fetch_array(mysql_query('SELECT `st`.`atack`, `st`.`clone`, `u`.`bot_id`, `u`.`type_pers`,`u`.`inTurnir`,`st`.`zv`,`st`.`bot`,`st`.`hpNow`,`u`.`login`,`st`.`dnow`,`u`.`id`,`u`.`align`,`u`.`admin`,`u`.`clan`,`u`.`level`,`u`.`room`,`u`.`online`,`u`.`battle`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`city` = "'.$u->info['city'].'" AND (`u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($jl).'") LIMIT 1'));
						}
					}else{
						if( $u->info['battle'] > 0 ) {
							$usr = mysql_fetch_array(mysql_query('SELECT `st`.`atack`,`st`.`clone`,`u`.`bot_id`,`u`.`type_pers`,`u`.`inTurnir`,`st`.`zv`,`st`.`bot`,`st`.`hpNow`,`u`.`login`,`st`.`dnow`,`u`.`id`,`u`.`align`,`u`.`admin`,`u`.`clan`,`u`.`level`,`u`.`room`,`u`.`online`,`u`.`battle`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`city` = "'.$u->info['city'].'" AND `u`.`battle` = "'.$u->info['battle'].'" AND (`u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($jl).'") AND `u`.`inTurnir` > 0 LIMIT 1'));
						}else{
							$usr = mysql_fetch_array(mysql_query('SELECT `st`.`atack`,`st`.`clone`,`u`.`bot_id`,`u`.`type_pers`,`u`.`inTurnir`,`st`.`zv`,`st`.`bot`,`st`.`hpNow`,`u`.`login`,`st`.`dnow`,`u`.`id`,`u`.`align`,`u`.`admin`,`u`.`clan`,`u`.`level`,`u`.`room`,`u`.`online`,`u`.`battle`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`city` = "'.$u->info['city'].'" AND (`u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($jl).'") AND `u`.`inTurnir` > 0 LIMIT 1'));
						}
					}
					if(isset($usr['id']))
					{
						//заклятье нападения
						if($itm['iznosNOW']>=$itm['iznosMAX'])
						{
							$u->error = 'Свиток был исполчен...';
						}elseif($itm['magic_inci']=='snowball')
						{
							if( $u->info['battle'] == 0 ) {
								$usr = mysql_fetch_array(mysql_query('SELECT `st`.`clone`,`u`.`type_pers`,`u`.`bot_id`,`st`.`zv`,`u`.`inTurnir`,`st`.`bot`,`st`.`hpNow`,`u`.`login`,`st`.`dnow`,`u`.`id`,`u`.`align`,`u`.`admin`,`u`.`clan`,`u`.`level`,`u`.`room`,`u`.`online`,`u`.`battle`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`city` = "'.$u->info['city'].'" AND (`u`.`battle` = 0 OR `u`.`battle` = "'.$u->info['battle'].'") AND (`u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($jl).'") LIMIT 1'));
							}else{
								$usr = mysql_fetch_array(mysql_query('SELECT `st`.`clone`,`u`.`type_pers`,`u`.`bot_id`,`st`.`zv`,`u`.`inTurnir`,`st`.`bot`,`st`.`hpNow`,`u`.`login`,`st`.`dnow`,`u`.`id`,`u`.`align`,`u`.`admin`,`u`.`clan`,`u`.`level`,`u`.`room`,`u`.`online`,`u`.`battle`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON (`u`.`id` = `st`.`id`) WHERE `u`.`city` = "'.$u->info['city'].'" AND (`u`.`battle` = 0 OR `u`.`battle` = "'.$u->info['battle'].'") AND (`u`.`login`="'.mysql_real_escape_string($_GET['login']).'" OR `u`.`login`="'.mysql_real_escape_string($jl).'") AND `u`.`battle` = "'.$u->info['battle'].'" LIMIT 1'));
							}
							if($usr['battle']>0 && $u->info['battle']!=$usr['battle'])
							{
								$u->error = 'Персонаж находится в бою';
							}elseif($usr['battle']>0) {
								//Кидаемся в поединке
								if($usr['team'] != $u->info['team'] && $usr['hpNow'] > 0) {
									$txt = 1;
									
									//Отморозки
									if($usr['bot_id'] >= 439 && $usr['bot_id'] <= 460) {
										$txt = 500;
									}elseif($usr['bot_id'] == 291 ) {
										if($usr['hpNow'] < 961) {
											$txt = 1982;
										}elseif($usr['hpNow'] > 980 && $usr['hpNow'] < 1981) {
											$txt = 1982;
										}
									}elseif($usr['id'] == 1008 ) {
										$txt = $u->info['level']*250;
									}
									
									$usr['hpNow'] -= $txt;
									if($txt<0) {
										$txt = '+'.$txt;
									}elseif($txt==0) {
										$txt = '--';
									}else{
										$txt = '-'.$txt;
									}
									if($usr['hpNow']<0)
									{
										$usr['hpNow'] = 0;
									}
									$btl->stats[$btl->uids[$usr['id']]]['hpNow'] = $usr['hpNow'];
											$lastHOD = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$u->info['battle'].'" ORDER BY `id_hod` DESC LIMIT 1'));
											if(isset($lastHOD['id']))
											{
												$btl->stats[$btl->uids[$usr['id']]]['hpNow'] = floor($btl->stats[$btl->uids[$usr['id']]]['hpNow']);
												if($btl->stats[$btl->uids[$usr['id']]]['hpNow'] > $btl->stats[$btl->uids[$usr['id']]]['hpAll']) {
													$btl->stats[$btl->uids[$usr['id']]]['hpNow'] = $btl->stats[$btl->uids[$usr['id']]]['hpAll'];
												}
												if($btl->stats[$btl->uids[$usr['id']]]['hpNow'] < 1) {
													$btl->stats[$btl->uids[$usr['id']]]['hpNow'] = 0;
												}
												$id_hod = $lastHOD['id_hod'];
												if($lastHOD['type']!=6)
												{
													$id_hod++;
												}
												mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$usr['id']]]['hpNow'].'" WHERE `id` = "'.$usr['id'].'" LIMIT 1');
												$itm['iznosNOW']++;
												if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
													mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
												}
												mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
												$txt = '<font color=#006699>'.$txt.'</font>';
												if($u->info['sex']==1) {
														$txt = 'Удачливая {u1} бросила кусок снега в {u2}. <b>'.$txt.'</b> ['.$btl->stats[$btl->uids[$usr['id']]]['hpNow'].'/'.$btl->stats[$btl->uids[$usr['id']]]['hpAll'].']';
												}else{
														$txt = 'Удачливый {u1} бросил кусок снега в {u2}. <b>'.$txt.'</b> ['.$btl->stats[$btl->uids[$usr['id']]]['hpNow'].'/'.$btl->stats[$btl->uids[$usr['id']]]['hpAll'].']';
												}
												mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$u->info['battle'].'","'.($id_hod).'","{tm1} '.$txt.'","login1='.$u->info['login'].'||t1='.$u->info['team'].'||login2='.$usr['login'].'||t2='.$usr['team'].'||time1='.time().'","","","","","6")');
											}
									
									unset($txt);
								}else{
									$u->error = 'Нельзя использовать на данного персонажа';
								}
							}elseif($u->info['dnow']!=$usr['dnow'])
							{
								$u->error = 'Персонаж находится в другой комнате';
							}elseif($usr['id']==$u->info['id'])
							{
								$u->error = 'Нельзя кидаться в самого себя';
							}elseif($usr['online']<time()-520 && $usr['battle'] == 0 && $usr['bot'] == 0)
							{
								$u->error = 'Персонаж находится в реальном мире ;)';
							}elseif($usr['room']!=$u->info['room'])
							{
								$u->error = 'Персонаж находится в другой комнате';
							}elseif($usr['admin']>0 && $u->info['admin']==0)
							{
								$u->error = 'Нельзя кидаться в Ангелов';
							}else{
								$usr['hpNow'] -= 10;
								if($usr['hpNow']<0)
								{
									$usr['hpNow'] = 0;
								}
								$upd = mysql_query('UPDATE `stats` SET `hpNow` = "'.$usr['hpNow'].'" WHERE `id` = "'.$usr['id'].'" LIMIT 1');
								if($upd)
								{
									$sx = 'ый'; $sx2 = '';
									if($u->info['sex']==1)
									{
										$sx = 'ая'; $sx2 = 'а';
									}
									$itm['iznosNOW']++;
									if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
										mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
									}
									mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
									$urs_st = $u->getStats($usr['id']);
									$rtxt = '[img[items/snowball1.gif]] Удачлив'.$sx.' &quot;'.$u->info['login'].'&quot; бросил'.$sx2.' кусок снега в &quot;'.$usr['login'].'&quot;. <font color=red><b>-10</b></font> ['.floor($urs_st['hpNow']).'/'.$urs_st['hpAll'].']';
									mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");	
									unset($sx,$sx2);
								}else{
									$u->error = 'Не удалось кинуть снежок...';
								}								
							}
						}elseif($itm['magic_inci']=='atack')
						{	
											//заклятье нападения
						$usta = $u->getStats($usr['id'],0); // статы цели
						$rgd = $u->regen($usr['id'],$usta,1);
						$usta['hpNow'] = $rgd['hpNow'];
						$usta['mpNow'] = $rgd['mpNow'];
		                $minHp = floor($usta['hpAll']/100*33); // минимальный запас здоровья цели при котором можно напасть
						$batlatest = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.$usr['battle'].'" AND `team_win` = -1 LIMIT 1'));
						/*/echo '<script>alert("мзз - '.$minHp.' now = '.$usta['hpNow'].'");</script>';*/
							if( time() - $usr['timereg'] < 86400*5 )
							{
								$u->error = 'Нападать на новичков запрещается! Сейчас придет Мироздатель и превратит тебя в лягушку...';
							}elseif($usr['atack']==1 || $usr['atack'] > time())
							{
								$u->error = 'На персонаже метка нападения, нападайте через неё...';
							}elseif($u->info['dnow']!=$usr['dnow'])
							{
								$u->error = 'Персонаж находится в другой комнате (пещере)';
							}elseif($usr['inTurnirnew'] > 0)
							{
								$u->error = 'Персонаж принимает участие в турнире';
							}elseif($u->info['battle']>0)
							{
								$u->error = 'Вы уже находитесь в бою';
							}elseif($usr['id']==$u->info['id'])
							{
								$u->error = 'Нельзя нападать на самого себя';
							}elseif($usr['online']<time()-120)
							{
								$u->error = 'Персонаж не в сети';
							}elseif($usr['room']!=$u->info['room'] && $u->info['battle'] != $usr['battle'] && $u->info['battle'] > 0)
							{
								$u->error = 'Персонаж находится в другой комнате!';
							}elseif($usr['room']!=$u->info['room'])
							{
								$u->error = 'Персонаж находится в другой комнате';
							}elseif($batlatest['noatack']==1)
							{
								$u->error = 'Поединок защищен магией! Вы не можете вмешаться!';
							}elseif($minHp>$usta['hpNow'] && !isset($batlatest['id']))
							{
							//мало хп
								$u->error = 'Персонаж слишком слаб ('.floor($usta['hpNow']).'HP)';
							}elseif($u->info['noatack']!=0)
							{
							//мало хп
								$u->error = 'В этой комнате нападения запрещены.';
							}else{
								
								if($usr['type_pers'] > 0) {
									$kroww=$usr['type_pers'];
								}elseif($itm['item_id']=='2391'){
									$kroww=99;
								}else{
									$kroww=0;
								}
								
								$kulak = 0;
								if( $itm['item_id'] == 4404 ) {
									$kulak = 1;
								}
			                    if($u->info['inTurnir'] > 0) { $bsi = $u->info['inTurnir']; } else { $bsi = 0; }
								$atc = $this->atackUser($u->info['id'],$usr['id'],$usr['team'],$usr['battle'], 0, $kroww, $kulak, $bsi);
								if($atc > 0)
								{
									//отправляем системку в чат
									$sx = '';
									if($u->info['sex']==1)
									{
										$sx = 'а';
									}
									$itm['iznosNOW']++;
									if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
										mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
									}
									mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
									$rtxt = '[img[items/pal_button8.gif]] &quot;'.$u->info['login'].'&quot; использовал'.$sx.' магию нападения на персонажа &quot;'.$usr['login'].'&quot;.';
									mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('1','".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");	
									//напали, обновляем экран
									die('<script>top.frames[\'main\'].location = "main.php";</script>');
								}else{
									if( $u->error == '' ) {
										$u->error = 'Не удалось напасть на персонажа...';
									}
								}
							}
						}elseif($itm['magic_inci']=='cureHP') {
							//Реген НР,MP
								$po = $u->lookStats($itm['data']);
								if($u->info['battle'] > 0) {
									//используем свиток в поединке
									$bu = mysql_fetch_array(mysql_query('SELECT * FROM `spells` WHERE `btl` = "'.$u->info['battle'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
									if(isset($bu['id'])) {
										$u->error = 'Нельзя использовать свиток каждый ход...';
									}elseif( $usr['battle']!=$u->info['battle'] ) {
										$u->error = 'Неудалось использовать, персонаж в другом поединке...';
									}elseif( $usr['team']!=$u->info['team'] ) {
										$u->error = 'Неудалось использовать на противника...';
									}elseif( $usr['hpNow'] < 1 ) {
										$u->error = 'Неудалось использовать на мертвых...';
									}elseif( $u->info['hpNow'] < 1 ) {
										$u->error = 'Неудалось использовать, вы погибли...';
									}elseif( $po['magic_hpNow'] < 1 && $po['magic_mpNow'] < 1 ) {
										$u->error = 'Неудалось использовать, в магическом свитке нет магии...';
									}elseif(($usr['battle']==$u->info['battle'] && ($u->info['team']==$usr['team'] && ($po['magic_hpNow']>0 || $po['magic_mpNow']>0))) && $usr['hpNow']>=1 && $u->info['hpNow']>=1) {
										//кастуем
										if(isset($po['magic_hpNow'])) {
											
											mysql_query('INSERT INTO `spells` (`btl`,`uid`,`time`,`item_id`,`var`,`hod`) VALUES (
												"'.$u->info['battle'].'","'.$u->info['id'].'","'.time().'","'.$itm['item_id'].'","'.$itm['name'].'","1"
											)');
											
											$txt = $po['magic_hpNow'];
											
											if($btl->stats[$btl->uids[$usr['id']]]['hpAll']-$btl->stats[$btl->uids[$usr['id']]]['hpNow'] < $txt) {
												$txt = floor($btl->stats[$btl->uids[$usr['id']]]['hpAll']-$btl->stats[$btl->uids[$usr['id']]]['hpNow']);
											}
                                            //653 - 400
                                            //253
											//453 1306
											$gdhh = round($txt/$btl->stats[$btl->uids[$usr['id']]]['hpAll']*5,2);
											$gdhd = round($gdhh/$btl->users[$btl->uids[$usr['id']]]['tactic7']*100);
											if($gdhd > 100) {
											  $txt = floor($txt/100*$gdhd);
											}
											
											if($btl->users[$btl->uids[$usr['id']]]['tactic7'] >= 1) {
												if($txt > 0) {
													$btl->stats[$btl->uids[$usr['id']]]['hpNow'] += $txt;
													$btl->users[$btl->uids[$usr['id']]]['tactic7'] -= $gdhh;
													if($btl->users[$btl->uids[$usr['id']]]['tactic7'] < 0) {
														$btl->users[$btl->uids[$usr['id']]]['tactic7'] = 0;
													}
												}
												mysql_query('UPDATE `stats` SET `last_hp` = "'.$txt.'", `hpNow` = `hpNow` + '.$po['magic_hpNow'].', `tactic7` = "'.$btl->users[$btl->uids[$usr['id']]]['tactic7'].'" WHERE `id` = "'.$usr['id'].'" LIMIT 1');
												if($txt > 0) {
													$txt = '+'.$txt;
												} elseif($txt == 0){
													$txt = '--';
												}
											} else {
												$txt = '--';
											}
											$lastHOD = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$u->info['battle'].'" ORDER BY `id_hod` DESC LIMIT 1'));
											if(isset($lastHOD['id']))
											{
												$btl->stats[$btl->uids[$usr['id']]]['hpNow'] = floor($btl->stats[$btl->uids[$usr['id']]]['hpNow']);
												if($btl->stats[$btl->uids[$usr['id']]]['hpNow'] > $btl->stats[$btl->uids[$usr['id']]]['hpAll']) {
													$btl->stats[$btl->uids[$usr['id']]]['hpNow'] = $btl->stats[$btl->uids[$usr['id']]]['hpAll'];
												}
												if($btl->stats[$btl->uids[$usr['id']]]['hpNow'] < 1) {
													$btl->stats[$btl->uids[$usr['id']]]['hpNow'] = 0;
												}
												$id_hod = $lastHOD['id_hod'];
												if($lastHOD['type'] != 6)
												{
													$id_hod++;
												}
												$txt = '<font color=#006699>'.$txt.'</font>';
												if($u->info['id']==$usr['id']) {
													if($u->info['sex']==1) {
														$txt = '{u1} использовала &quot;<b>'.$itm['name'].'</b>&quot; на себя. <b>'.$txt.'</b> ['.$btl->stats[$btl->uids[$usr['id']]]['hpNow'].'/'.$btl->stats[$btl->uids[$usr['id']]]['hpAll'].']';
													}else{
														$txt = '{u1} использовал &quot;<b>'.$itm['name'].'</b>&quot; на себя. <b>'.$txt.'</b> ['.$btl->stats[$btl->uids[$usr['id']]]['hpNow'].'/'.$btl->stats[$btl->uids[$usr['id']]]['hpAll'].']';
													}
												}else{
													if($u->info['sex']==1) {
														$txt = '{u1} использовала &quot;<b>'.$itm['name'].'</b>&quot; на {u2}. <b>'.$txt.'</b> ['.$btl->stats[$btl->uids[$usr['id']]]['hpNow'].'/'.$btl->stats[$btl->uids[$usr['id']]]['hpAll'].']';
													}else{
														$txt = '{u1} использовал &quot;<b>'.$itm['name'].'</b>&quot; на {u2}. <b>'.$txt.'</b> ['.$btl->stats[$btl->uids[$usr['id']]]['hpNow'].'/'.$btl->stats[$btl->uids[$usr['id']]]['hpAll'].']';
													}
												}
												mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$u->info['battle'].'","'.($id_hod).'","{tm1} '.$txt.'","login1='.$u->info['login'].'||t1='.$u->info['team'].'||login2='.$usr['login'].'||t2='.$usr['team'].'||time1='.time().'","","","","","6")');
											}									
										}
										$itm['iznosNOW']++;
										mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
										
										// сообщение в лог боя
										$sx = 'ый'; $sx2 = '';
										if($u->info['sex']==1)
										{
											$sx = 'ая'; $sx2 = 'а';
										}
										$u->error = 'Свиток &quot;'.$itm['name'].'&quot; был успешно использован.';										
									}else{
										$u->error = 'Нельзя использовать на данного персонажа';
									}
								}elseif($u->info['dnow']!=$usr['dnow'])
								{
									$u->error = 'Персонаж находится в другой комнате [пещера]';
								}elseif($usr['online']<time()-120 && $usr['bot'] == 0)
								{
									$u->error = 'Персонаж находится в реальном мире';
								}elseif($usr['room']!=$u->info['room'])
								{
									$u->error = 'Персонаж находится в другой комнате';
								}elseif($usr['battle']!=$u->info['battle'])
								{
									$u->error = 'Персонаж находится в поединке';
								}else{
									//кастуем
									if(isset($po['magic_hpNow'])) {
										mysql_query('UPDATE `stats` SET `hpNow` = `hpNow` + '.$po['magic_hpNow'].' WHERE `id` = "'.$usr['id'].'" LIMIT 1');
									}									
									
									$itm['iznosNOW']++;
									mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
									
									if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
										mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
									}
									
									// сообщение в чат
									$sx = 'ый'; $sx2 = '';
									if($u->info['sex']==1)
									{
										$sx = 'ая'; $sx2 = 'а';
									}
									$u->error = 'Свиток &quot;'.$itm['name'].'&quot; был успешно использован.';
									if( $usr['id'] != $u->info['id'] ) {
										$rtxt = '[img[items/'.$itm['img'].']] Персонаж &quot;'.$u->info['login'].'&quot; использовал'.$sx2.' &quot;'.$itm['name'].'&quot; на &quot;'.$usr['login'].'&quot;.';
					                	mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");
									}else{
										$rtxt = '[img[items/'.$itm['img'].']] Персонаж &quot;'.$u->info['login'].'&quot; использовал'.$sx2.' &quot;'.$itm['name'].'&quot; на себя.';
					                	mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."','".$rtxt."','".time()."','6','0','1')");
									}
								}
						}elseif($itm['magic_inci']=='cureMP') {
							//Реген НР,MP
								$po = $u->lookStats($itm['data']);
								if($u->info['battle'] > 0) {
									//используем свиток в поединке
									$bu = mysql_fetch_array(mysql_query('SELECT * FROM `spells` WHERE `btl` = "'.$u->info['battle'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1'));
									if(isset($bu['id'])) {
										$u->error = 'Нельзя использовать свиток каждый ход...';
									}elseif(($usr['battle']==$u->info['battle'] && ($u->info['team']==$usr['team'] && ($po['magic_hpNow']>0 || $po['magic_mpNow']>0))) && $usr['hpNow']>1 && $u->info['hpNow']>=1) {
										//кастуем
										if(isset($po['magic_mpNow'])) {
											
											mysql_query('INSERT INTO `spells` (`btl`,`uid`,`time`,`item_id`,`var`,`hod`) VALUES (
												"'.$u->info['battle'].'","'.$u->info['id'].'","'.time().'","'.$itm['item_id'].'","'.$itm['name'].'","1"
											)');
											
											$txt = $po['magic_mpNow'];
											
											if($btl->stats[$btl->uids[$usr['id']]]['mpAll']-$btl->stats[$btl->uids[$usr['id']]]['mpNow'] < $txt) {
												$txt = floor($btl->stats[$btl->uids[$usr['id']]]['mpAll']-$btl->stats[$btl->uids[$usr['id']]]['mpNow']);
											}
											
											/*$gdhh = round($txt/$btl->stats[$btl->uids[$usr['id']]]['mpAll']*5);
											$gdhd = round($gdhh/$btl->users[$btl->uids[$usr['id']]]['tactic7']*100);
											if( $gdhd > 100 ) {
												$txt = floor($txt/100*$gdhd);
											}*/
											
											if($btl->users[$btl->uids[$usr['id']]]['tactic7'] >= 0) { // поставить >= 1 , если требует дух
												if($txt > 0) {
													$btl->stats[$btl->uids[$usr['id']]]['mpNow'] += $txt;
													$btl->users[$btl->uids[$usr['id']]]['tactic7'] -= $gdhh;
													if($btl->users[$btl->uids[$usr['id']]]['tactic7'] < 0) {
														$btl->users[$btl->uids[$usr['id']]]['tactic7'] = 0;
													}
												}
												mysql_query('UPDATE `stats` SET `mpNow` = `mpNow` + '.$txt.', `tactic7` = "'.$btl->users[$btl->uids[$usr['id']]]['tactic7'].'" WHERE `id` = "'.$usr['id'].'" LIMIT 1');
												if($txt>0) {
													$txt = '+'.$txt;
												}elseif($txt==0){
													$txt = '--';
												}
											} else {
												$txt = '--';
											}
											$lastHOD = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$u->info['battle'].'" ORDER BY `id_hod` DESC LIMIT 1'));
											if(isset($lastHOD['id']))
											{
												$btl->stats[$btl->uids[$usr['id']]]['mpNow'] = floor($btl->stats[$btl->uids[$usr['id']]]['mpNow']);
												if($btl->stats[$btl->uids[$usr['id']]]['mpNow'] > $btl->stats[$btl->uids[$usr['id']]]['mpAll']) {
													$btl->stats[$btl->uids[$usr['id']]]['mpNow'] = $btl->stats[$btl->uids[$usr['id']]]['mpAll'];
												}
												if($btl->stats[$btl->uids[$usr['id']]]['mpNow']<1) {
													$btl->stats[$btl->uids[$usr['id']]]['mpNow'] = 0;
												}
												$id_hod = $lastHOD['id_hod'];
												if($lastHOD['type']!=6)
												{
													$id_hod++;
												}
												$txt = '<font color=#006699>'.$txt.'</font>';
												if($u->info['id']==$usr['id']) {
													if($u->info['sex']==1) {
														$txt = '{u1} использовала &quot;<b>'.$itm['name'].'</b>&quot; на себя. <b>'.$txt.'</b> ['.$btl->stats[$btl->uids[$usr['id']]]['mpNow'].'/'.$btl->stats[$btl->uids[$usr['id']]]['mpAll'].'] (Мана)';
													}else{
														$txt = '{u1} использовал &quot;<b>'.$itm['name'].'</b>&quot; на себя. <b>'.$txt.'</b> ['.$btl->stats[$btl->uids[$usr['id']]]['mpNow'].'/'.$btl->stats[$btl->uids[$usr['id']]]['mpAll'].'] (Мана)';
													}
												}else{
													if($u->info['sex']==1) {
														$txt = '{u1} использовала &quot;<b>'.$itm['name'].'</b>&quot; на {u2}. <b>'.$txt.'</b> ['.$btl->stats[$btl->uids[$usr['id']]]['mpNow'].'/'.$btl->stats[$btl->uids[$usr['id']]]['mpAll'].'] (Мана)';
													}else{
														$txt = '{u1} использовал &quot;<b>'.$itm['name'].'</b>&quot; на {u2}. <b>'.$txt.'</b> ['.$btl->stats[$btl->uids[$usr['id']]]['mpNow'].'/'.$btl->stats[$btl->uids[$usr['id']]]['mpAll'].'] (Мана)';
													}
												}
												mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$u->info['battle'].'","'.($id_hod).'","{tm1} '.$txt.'","login1='.$u->info['login'].'||t1='.$u->info['team'].'||login2='.$usr['login'].'||t2='.$usr['team'].'||time1='.time().'","","","","","6")');
											}									
										}
										$itm['iznosNOW']++;
										mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
										
										// сообщение в лог боя
										$sx = 'ый'; $sx2 = '';
										if($u->info['sex']==1)
										{
											$sx = 'ая'; $sx2 = 'а';
										}
										$u->error = 'Свиток &quot;'.$itm['name'].'&quot; был успешно использован.';										
									}else{
										$u->error = 'Нельзя использовать на данного персонажа';
									}
								}elseif($u->info['dnow']!=$usr['dnow'])
								{
									$u->error = 'Персонаж находится в другой комнате [пещера]';
								}elseif($usr['online']<time()-120 && $usr['bot'] == 0)
								{
									$u->error = 'Персонаж находится в реальном мире';
								}elseif($usr['room']!=$u->info['room'])
								{
									$u->error = 'Персонаж находится в другой комнате';
								}elseif($usr['battle']!=$u->info['battle'])
								{
									$u->error = 'Персонаж находится в поединке';
								}else{
									//кастуем
									if(isset($po['magic_mpNow'])) {
										mysql_query('UPDATE `stats` SET `mpNow` = `mpNow` + '.$po['magic_mpNow'].' WHERE `id` = "'.$usr['id'].'" LIMIT 1');
									}									
									
									$itm['iznosNOW']++;
									mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
									
									if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
										mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
									}
									
									// сообщение в чат
									$sx = 'ый'; $sx2 = '';
									if($u->info['sex']==1)
									{
										$sx = 'ая'; $sx2 = 'а';
									}
									$u->error = 'Свиток &quot;'.$itm['name'].'&quot; был успешно использован.';
									if( $usr['id'] != $u->info['id'] ) {
										$rtxt = '[img[items/'.$itm['img'].']] Персонаж &quot;'.$u->info['login'].'&quot; использовал'.$sx2.' &quot;'.$itm['name'].'&quot; на &quot;'.$usr['login'].'&quot;.';
					                	mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");
									}else{
										$rtxt = '[img[items/'.$itm['img'].']] Персонаж &quot;'.$u->info['login'].'&quot; использовал'.$sx2.' &quot;'.$itm['name'].'&quot; на себя.';
					                	mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."','".$rtxt."','".time()."','6','0','1')");
									}
								}
						}elseif($itm['magic_inci']=='lech_1' or $itm['magic_inci']=='lech_2' or $itm['magic_inci']=='lech_3'){
						            
								if($u->info['battle'] > 0) {
									//используем свиток в поединке
									
								}elseif($u->info['dnow']!=$usr['dnow'])
								{
									$u->error = 'Персонаж находится в другой комнате [пещера]';
								}elseif($usr['online']<time()-120 && $usr['bot'] == 0)
								{
									$u->error = 'Персонаж находится в реальном мире';
								}elseif($usr['room']!=$u->info['room'])
								{
									$u->error = 'Персонаж находится в другой комнате';
								}elseif($usr['battle']!=$u->info['battle'])
								{
									$u->error = 'Персонаж находится в поединке';
								}else{
							
									$travm = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid`="'.$usr['id'].'" and `id_eff`="4" and `delete`="0" ORDER BY `v1` DESC LIMIT 1'));
									//$type_lechenie = $travm['v1'];
									$itm['magic_inci'] = 'lech_'.$travm['v1'];
									if($itm['magic_inci']=='lech_1'){
										$type_lechenie=1; // тип травмы котору лечим
										$text_msg='&quot;Легкой травмы&quot;';
										$text_msg2 = 'легких';
									}elseif($itm['magic_inci']=='lech_2'){
										$type_lechenie=2; // тип травмы котору лечим
										$text_msg='&quot;Средней травмы&quot;';
										$text_msg2 = 'средних';
									}elseif($itm['magic_inci']=='lech_3'){
										$type_lechenie=3; // тип травмы котору лечим
										$text_msg='&quot;Тяжелой травмы&quot;';
										$text_msg2 = 'тяжелых';
									}
									if($travm){
										$lech_aa = array( 0 , 2 , 3 , 4 );
										$travm_cep = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid`="'.$usr['id'].'" and `id_eff`="335" and `delete`="0" and `v1`="'.$type_lechenie.'" LIMIT 1'));
										if( $travm_cep['timeUse'] < time() - 360 ) {
											unset($travm_cep);
										}
										
										$cep_users = explode(',',$travm_cep['data']);
										$it_i = 0;
										$it_no = 0;
										$it_users = '';
										while( $it_i < count($cep_users) ) {
											$it_u = $cep_users[$it_i];
											if( $it_u == $u->info['id'] ) {
												$it_no = 1;
											}
											$it_u = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`level`,`align`,`clan`,`room` FROM `users` WHERE `id` = "'.$it_u.'" LIMIT 1'));
											if( isset($it_u['id']) ) {
												$it_users .= ', '.$it_u['login'].'';
											}
											$it_i++;
										}
										$it_u = ltrim($it_u,', ');
										
										$lech_co = round($travm_cep['x']+1);
										if( $it_no == 1 ) {
											$u->error = 'Вы уже один из лекарей';
										}elseif( $lech_co >= $lech_aa[$type_lechenie] ) {
									        mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$travm['id'].'" LIMIT 1');
									        $itm['iznosNOW']++;
									        mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
											if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
												mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
											}
											
											$rtxt = '[img[items/cure_g1.gif]] Лекарь &quot;'.$u->info['login'].'&quot; кинул цепь исцеления на игрока &quot;'.$usr['login'].'&quot;.';
					                        mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");	

											//mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$travm_cep['id'].'" LIMIT 1');
									        mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$usr['id'].'" AND `id_eff` = 4 AND `delete` = 0 LIMIT 1');
									        
											// сообщение в чат
											$u->error = 'Персонаж излечен от '.$text_msg.'.';
														$rtxt = '[img[items/cure'.$type_lechenie.'.gif]] Лекари &quot;'.$u->info['login'].$it_users.'&quot; вылечили от '.$text_msg.' игрока &quot;'.$usr['login'].'&quot;.';
					                                    mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");	
										}else{
											if( isset($travm_cep['id']) ) {
												$travm_cep['x']++;
												$u->error = 'Вы присоединились к цепи исцеления для &quot;'.$text_msg.'&quot; (исцеление '.$text_msg2.' травм)';
												mysql_query('UPDATE `eff_users` SET `x` = "'.$travm_cep['x'].'"
												,`data` = "'.$travm_cep['data'].','.$u->info['id'].'"
												WHERE `id` = "'.$travm_cep['id'].'" LIMIT 1');
											}else{
												$u->error = 'Вы создали цепь исцеления для &quot;'.$text_msg.'&quot; (исцеление '.$text_msg2.' травм), у остальных лекарей есть 5 минут, чтобы завершить заклинание';
												mysql_query('INSERT INTO `eff_users`
												(
													`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`user_use`,`v1`,`x`
												) VALUES (
													"335","'.$usr['id'].'","Цепь исцеления","'.$u->info['id'].'","28","'.time().'","'.$u->info['id'].'","'.$type_lechenie.'","1"
												)');
											}
											//mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$travm['id'].'" LIMIT 1');
									        $itm['iznosNOW']++;
									        mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
											if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
												mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
											}
											// сообщение в чат
											$rtxt = '[img[items/cure_g1.gif]] Лекарь &quot;'.$u->info['login'].'&quot; кинул цепь исцеления на игрока &quot;'.$usr['login'].'&quot;.';
					                        mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");	

										}
									}else{
										$u->error = 'Персонаж не имеет данной травмы.';
									}
								
								}

						        
						}elseif($itm['magic_inci']=='lech_free_1' or $itm['magic_inci']=='lech_free_2' or $itm['magic_inci']=='lech_free_3'){
						            
								if($u->info['battle'] > 0) {
									//используем свиток в поединке
									
								}elseif($u->info['dnow']!=$usr['dnow'])
								{
									$u->error = 'Персонаж находится в другой комнате [пещера]';
								}elseif($usr['online']<time()-120 && $usr['bot'] == 0)
								{
									$u->error = 'Персонаж находится в реальном мире';
								}elseif($usr['room']!=$u->info['room'])
								{
									$u->error = 'Персонаж находится в другой комнате';
								}elseif($usr['battle']!=$u->info['battle'])
								{
									$u->error = 'Персонаж находится в поединке';
								}else{
							
									$travm = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid`="'.$usr['id'].'" and `id_eff`="4" and `delete`="0" ORDER BY `v1` DESC LIMIT 1'));
									//$type_lechenie = $travm['v1'];
									//$itm['magic_inci'] = 'lech_free_'.$travm['v1'];
									if($itm['magic_inci']=='lech_free_1'){
										$type_lechenie=1; // тип травмы котору лечим
										$text_msg='&quot;Легкой травмы&quot;';
										$text_msg2 = 'легких';
									}elseif($itm['magic_inci']=='lech_free_2'){
										$type_lechenie=2; // тип травмы котору лечим
										$text_msg='&quot;Средней травмы&quot;';
										$text_msg2 = 'средних';
									}elseif($itm['magic_inci']=='lech_free_3'){
										$type_lechenie=3; // тип травмы котору лечим
										$text_msg='&quot;Тяжелой травмы&quot;';
										$text_msg2 = 'тяжелых';
									}
									if($travm['v1'] != $type_lechenie) {
										$u->error = 'Неподходящий свиток для данной травмы.';
									}elseif($travm['id']){
										$lech_aa = array( 0 , 0 , 0 , 0 );
										$travm_cep = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid`="'.$usr['id'].'" and `id_eff`="335" and `delete`="0" and `v1`="'.$type_lechenie.'" LIMIT 1'));
										if( $travm_cep['timeUse'] < time() - 360 ) {
											unset($travm_cep);
										}
										
										$cep_users = explode(',',$travm_cep['data']);
										$it_i = 0;
										$it_no = 0;
										$it_users = '';
										while( $it_i < count($cep_users) ) {
											$it_u = $cep_users[$it_i];
											if( $it_u == $u->info['id'] ) {
												$it_no = 1;
											}
											$it_u = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`level`,`align`,`clan`,`room` FROM `users` WHERE `id` = "'.$it_u.'" LIMIT 1'));
											if( isset($it_u['id']) ) {
												$it_users .= ', '.$it_u['login'].'';
											}
											$it_i++;
										}
										$it_u = ltrim($it_u,', ');
										
										$lech_co = round($travm_cep['x']+1);
										if( $it_no == 1 ) {
											$u->error = 'Вы уже один из лекарей';
										}elseif( $lech_co >= $lech_aa[$type_lechenie] ) {
									        mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$travm['id'].'" LIMIT 1');
									        $itm['iznosNOW']++;
									        mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
											if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
												mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
											}
											
											//$rtxt = '[img[items/cure_g1.gif]] Лекарь &quot;'.$u->info['login'].'&quot; кинул цепь исцеления на игрока &quot;'.$usr['login'].'&quot;.';
					                        //mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");	

											//mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$travm_cep['id'].'" LIMIT 1');
									        mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$usr['id'].'" AND `id_eff` = 4 AND `delete` = 0 LIMIT 1');
									        
											// сообщение в чат
											$u->error = 'Персонаж излечен от '.$text_msg.'.';
														$rtxt = '[img[items/cure'.$type_lechenie.'.gif]] Лекари &quot;'.$u->info['login'].$it_users.'&quot; вылечили от '.$text_msg.' игрока &quot;'.$usr['login'].'&quot;.';
					                                    mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");	
										}else{
											if( isset($travm_cep['id']) ) {
												$travm_cep['x']++;
												$u->error = 'Вы присоединились к цепи исцеления для &quot;'.$text_msg.'&quot; (исцеление '.$text_msg2.' травм)';
												mysql_query('UPDATE `eff_users` SET `x` = "'.$travm_cep['x'].'"
												,`data` = "'.$travm_cep['data'].','.$u->info['id'].'"
												WHERE `id` = "'.$travm_cep['id'].'" LIMIT 1');
											}else{
												$u->error = 'Вы создали цепь исцеления для &quot;'.$text_msg.'&quot; (исцеление '.$text_msg2.' травм), у остальных лекарей есть 5 минут, чтобы завершить заклинание';
												mysql_query('INSERT INTO `eff_users`
												(
													`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`user_use`,`v1`,`x`
												) VALUES (
													"335","'.$usr['id'].'","Цепь исцеления","'.$u->info['id'].'","28","'.time().'","'.$u->info['id'].'","'.$type_lechenie.'","1"
												)');
											}
											//mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$travm['id'].'" LIMIT 1');
									        $itm['iznosNOW']++;
									        mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
											if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
												mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
											}
											// сообщение в чат
											$rtxt = '[img[items/cure_g1.gif]] Лекарь &quot;'.$u->info['login'].'&quot; кинул цепь исцеления на игрока &quot;'.$usr['login'].'&quot;.';
					                        mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");	

										}
									}else{
										$u->error = 'Персонаж не имеет данной травмы.';
									}
								
								}

						        
						}else{
							if( $u->info['id'] != $usr['id'] ) {
								$lsct = mysql_fetch_array(mysql_query('SELECT `id_eff` FROM `eff_users` WHERE `uid` = "'.$usr['id'].'" AND `delete` = "0" AND (`overType` = "'.$itm['overType'].'" OR (`id_eff` = "'.$itm['magic_inc'].'" AND "'.(0+$st['onlyOne']).'" != "0")) LIMIT 1'));
								$lsct['good'] = 1;
								//Касты
								if( $lsct['id_eff'] >= 291 && $lsct['id_eff'] <= 296 && $itm['magic_inc'] >= 291 && $itm['magic_inc'] <= 296 ) {
									if( $lsct['id_eff'] > $itm['magic_inc'] ) {
										$u->error = 'На персонаже есть каст лучше этого!';
										$lsct['good'] = 0;
									}
								}
							}else{
								$lsct['good'] = 1;
							}
							//просто используем на персонажа
							if($lsct['good'] == 0) {
								
							} elseif($u->info['dnow'] != $usr['dnow']) {
							  $u->error = 'Персонаж находится в другой комнате [пещера]';
                            } elseif($usr['inTurnir'] != 0 && ($u->info['inTurnir'] != $usr['inTurnir'])) {
							  $u->error = 'Участвует в турнире Башни смерти...';
							} elseif($usr['id']==$u->info['id'] && isset($st['useOnlyUser'])) {
								$u->error = 'Нельзя использовать это заклятие на самого себя';
							}elseif($usr['online']<time()-120 && $usr['bot'] == 0)
							{
								$u->error = 'Персонаж находится в реальном мире ;)';
							}elseif($usr['room']!=$u->info['room'] && $usr['battle'] != $u->info['battle'] && $u->info['battle'] > 0)
							{
								$u->error = 'Персонаж находится в другой комнате ['.$usr['room'].' '.$u->info['room'].']';
							}elseif($usr['admin']>0 && $u->info['admin']==0 && isset($st['useNoAdmin']))
							{
								$u->error = 'Нельзя использовать данное заклятие на Ангелов';
							}elseif($usr['battle']>0 && $u->info['battle']!=$usr['battle'])
							{
								$u->error = 'Персонаж находится в бою';
							}elseif(($u->info['align']==2 || $u->info['haos']>time()) && isset($st['nohaos']))
							{
								$u->error = 'Хаосники не могут использовать данное заклятие';
							}else{
								//добавляем эффект персонажу
								$goodUse = 1;
								//
								$tpsm = 0;
								if( $itm['useInBattle'] == 0 && $usr['battle'] > 0 ) {
									//Нельзя юзать
									$goodUse = 0;
								}else{
									if( $usr['battle'] > 0 ) {
										global $btl;
										if($u->info['team'] != $btl->users[$btl->uids[$usr['id']]]['team'] ) {
											if( $itm['useInBattle'] == 1 ) {
												$goodUse = 0;
											}
											$tpsm = 2;
										}elseif($u->info['team'] == $btl->users[$btl->uids[$usr['id']]]['team'] ) {
											if( $itm['useInBattle'] == 2 ) {
												$goodUse = 0;
											}
											$tpsm = 1;
										}
									}
								}
								//
								if( $goodUse == 1 ) {
									if(isset($st['onlyOne']))
									{
										//убираем прошлые эффекты
										$goodUse = 0;
										$upd1 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$usr['id'].'" AND `delete` = "0" AND `id_eff` = "'.$itm['magic_inc'].'"');
										if($upd1)
										{										
											$goodUse = 1;
										}
									}
									if(isset($st['oneType']))
									{
										//убираем прошлые эффекты									
										$goodUse = 0;
										$upd2 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$usr['id'].'" AND `delete` = "0" AND `overType` = "'.$itm['overType'].'"');
										if($upd1)
										{
											$goodUse = 1;
										}
									}
								}
								//
								if( $goodUse == 1 && $itm['magic_inc'] == 'unclone' ) {
									//Переманить клона
									if($usr['clone'] > 0 && $usr['hpNow'] >= 1 && $usr['team'] != $u->info['team']) {
										mysql_query('UPDATE `stats` SET `team` = "'.$u->info['team'].'" WHERE `id` = "'.$usr['id'].'" LIMIT 1');
										if( $u->info['sex'] == 0 ) {
											$txt_m = '{u1} <b>переманил клона</b> {u2} на свою сторону.';
										}else{
											$txt_m = '{u1} <b>переманила</b> клона {u2} на свою сторону.';	
										}
										$this->inBattleLog($txt_m,$usr);
										mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.($itm['iznosNOW'] + 1).'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
										mysql_query('UPDATE `items_users` SET `btl_zd` = "1" WHERE `item_id` = "'.$itm['item_id'].'" AND `inOdet` > 0 AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" LIMIT 20');
										$u->error = 'Вы успешно использовали заклинание &quot;'.$itm['name'].'&quot; на &quot;'.$usr['login'].'&quot;';
									}else{
										$u->error = 'Вы не можете переманивать данного персонажа...';
									}
								}elseif($goodUse == 1)
								{
									$us = $this->add_eff($usr['id'],$itm['magic_inc']);
									if($us[0]==1) {
										$mmmid = mysql_insert_id();
										$itm['iznosNOW']++;
										mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
										if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
											mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
										}
										if($u->info['id']!=$usr['id'])
										{
											$u->addDelo(1,$u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж использовал заклинание &quot;'.$itm['name'].'&quot; ('.$us[1].') на персонажа &quot;'.$usr['login'].'&quot; (id'.$usr['id'].') [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);
											$u->addDelo(1,$usr['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж &quot;'.$u->info['login'].'&quot; (id'.$u->info['id'].') использовал заклинание &quot;'.$itm['name'].'&quot; ('.$us[1].') на персонажа [itm:'.$itm['id'].'].',time(),$usr['city'],'System.inventory',0,0);
											$u->error = 'Вы успешно использовали заклинание &quot;'.$itm['name'].'&quot; на персонажа &quot;'.$usr['login'].'&quot;<br>'.$us[1].'';
											$rtxt = '[img[items/'.$itm['img'].']] &quot;'.$u->info['login'].'&quot; использовал'.$sx.' заклинание &quot;'.$itm['name'].'&quot; на персонажа &quot;'.$usr['login'].'&quot;.';
											mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('1','".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");	
										}else{
											$u->addDelo(1,$u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж использовал заклинание &quot;'.$itm['name'].'&quot; ('.$us[1].') на персонажа самого себя [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);
											$u->error = 'Вы успешно использовали заклинание &quot;'.$itm['name'].'&quot; на самого себя<br>'.$us[1].'';
											
											$rtxt = '[img[items/'.$itm['img'].']] &quot;'.$u->info['login'].'&quot; использовал'.$sx.' заклинание &quot;'.$itm['name'].'&quot; на себя.';
											mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('1','".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."','".$rtxt."','".time()."','6','0','1')");	
											
										}
										//
										if( $usr['battle'] > 0 ) {
											//Заносим в лог
											$txt_m = '';
											if( $u->info['sex'] == 0 ) {
												if($u->info['id']!=$usr['id']) {
													$txt_m .= '{u1} воспользовался магией &quot;<b>'.$itm['name'].'</b>&quot; на {u2}.';
												}else{
													$txt_m .= '{u1} воспользовался магией &quot;<b>'.$itm['name'].'</b>&quot; на себя.';	
												}
											}else{
												if($u->info['id']!=$usr['id']) {
													$txt_m .= '{u1} воспользовалась магией &quot;<b>'.$itm['name'].'</b>&quot; на {u2}.';
												}else{
													$txt_m .= '{u1} воспользовалась магией &quot;<b>'.$itm['name'].'</b>&quot; на себя.';	
												}
											}
											$this->inBattleLog($txt_m,$usr);
											//
											//По завершению боя эффект должен слетать
											//306 - положительное , 307 - отрицательное
											if( $tpsm == 1 ) {
												//306
												mysql_query('UPDATE `eff_users` SET `v1` = "priem",`v2` = "306",`timeUse` = "77",`hod` = "-1",`img2` = "'.$itm['img'].'" WHERE `id` = "'.$mmmid.'" LIMIT 1');
											}elseif( $tpsm == 2 ) {
												//307
												mysql_query('UPDATE `eff_users` SET `v1` = "priem",`v2` = "307",`timeUse` = "77",`hod` = "-1",`img2` = "'.$itm['img'].'" WHERE `id` = "'.$mmmid.'" LIMIT 1');
											}
											//
										}
										//
										$this->youuse++;
									}else{
										$u->error = 'Не удалось использовать "'.$itm['name'].'" ... ('.$itm['magin_inc'].' and '.$itm['magic_inci'].', Эффект не добавлен)';
									}
								}else{
									$u->error = 'Не удалось использовать "'.$itm['name'].'"';
								}
							}
						}
					}else{
						$u->error = 'Персонаж "'.$jl.'" не найден в этом городе ('.$u->info['city'].')';
					}
				}elseif(isset($st['useOnItem']) && $st['useOnItem']==1)
				{
					//используем на предмет
					
				}else{
					//на себя
					$goodUse = 1;
					if(($u->info['align']==2 || $u->info['haos']>time()) && isset($st['nohaos']))
					{
						$goodUse = 0;
						$u->error = 'Хаосники не могут использовать данное заклятие';
					}
					
					if($itm['magic_inci'] == 'sanich2') {
						if($u->info['battle'] == 0) {						
							$sz = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "sanich2" AND `time` > '.(time()-1*60).' LIMIT 1',1);
							if(!isset($sz['id'])) {														
								$slech = 0;
								$strm = mysql_fetch_array(mysql_query('SELECT `id`,`v1` FROM `eff_users` WHERE `uid` = "'.$u->info['id'].'" AND `id_eff` = 4 AND `delete` = "0" LIMIT 1'));
								if(isset($strm['id'])) {
									if($strm['v1'] == 1) {
										$slech = 1;
									}elseif($strm['v1'] == 2) {
										$slech = 2;
									}elseif($strm['v1'] == 3) {
										$slech = 3;
									}
								}								
								if($slech > 0) {
									
									$goodUse = 0;
									
									$slf = 'легкой';
									if($slech == 2) {
										$slf = 'средней';
									}elseif($slech == 3) {
										$slf = 'тяжелой';
									}
	
									if($u->info['sex'] == 1) {
										$rtxt = '[img[items/'.$itm['img'].']] &quot;'.$u->info['login'].'&quot; использовала &quot;'.$itm['name'].' Саныча&quot; и исцеласб от '.$slf.' травмы.';
									}else{
										$rtxt = '[img[items/'.$itm['img'].']] &quot;'.$u->info['login'].'&quot; использовал &quot;'.$itm['name'].' Саныча&quot; и исцелился от '.$slf.' травмы.';
									}
									mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");	
								
									mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$strm['id'].'" LIMIT 1');
									$u->error = 'Вы успешно исцелились от '.$slf.' травмы.';
									$itm['iznosNOW'] += $slech;
									mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
									$u->addAction(time(),'sanich2',$slech);
								}else{
									$u->error = 'У персонажа нет физических увечий которые можно излечить';
								}
								
							}else{
								$u->error = 'Задержка использования '.$u->timeOut(($sz['time']+1*60)-time()).'.';
							}							
						}else{
							$u->error = 'Невозможно использовать в бою';
						}
					}elseif($itm['magic_inci'] == 'sanich1' && $u->info['battle'] > 0) {
						
						$sz = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "sanich1" AND `time` > '.(time()-3*60).' LIMIT 1',1);
						if(!isset($sz['id'])) {
							$goodUse = 0;
							$u->error = 'Вы успешно использовали &quot;'.$itm['name'].'&quot;';
							$z = 1;
							while($z <= 5) {
								$u->info['tactic'.$z]++;
								if($u->info['tactic'.$z] > 25) {
									$u->info['tactic'.$z] = 25;
								}elseif($u->info['tactic'.$z] < 0) {
									$u->info['tactic'.$z] = 0;
								}
								$z++;
							}
							if($u->info['sex'] == 1) {
								$this->inBattleLog('{u1} использовала &quot;<b>'.$itm['name'].' Саныча</b>&quot;.');
							}else{
								$this->inBattleLog('{u1} использовал &quot;<b>'.$itm['name'].' Саныча</b>&quot;.');
							}
							mysql_query('UPDATE `stats` SET `tactic1` = "'.$u->info['tactic1'].'",`tactic2` = "'.$u->info['tactic2'].'",`tactic3` = "'.$u->info['tactic3'].'",`tactic4` = "'.$u->info['tactic4'].'",`tactic5` = "'.$u->info['tactic5'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							$itm['iznosNOW']++;
							mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
							$u->addAction(time(),'sanich1',$u->info['battle']);
						}else{
							$u->error = 'Задержка использования '.$u->timeOut(($sz['time']+3*60)-time()).'.';
						}
										
					}elseif($itm['magic_inci'] == 'sanich3' && $u->info['battle'] > 0) {
						
						$sz = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "sanich3" AND `time` > '.(time()-3*60*60).' LIMIT 1',1);
						if(!isset($sz['id'])) {
							
							$usr = mysql_fetch_array(mysql_query('SELECT `u`.`id`,`u`.`level`,`s`.`hpNow`,`s`.`team`,`u`.`login`,`u`.`sex` FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `s`.`id` = `u`.`id` WHERE `u`.`id` = "'.$u->info['enemy'].'" AND `u`.`battle` = "'.$u->info['battle'].'" LIMIT 1')); 
							if(isset($usr['id']) && $usr['hpNow'] >= 1) {
								$iznslvl = $usr['level'];
								
								$goodUse = 0;
								$u->error = 'Вы успешно использовали &quot;'.$itm['name'].'&quot; на '.$usr['login'].'';
								
								if($u->info['sex'] == 1) {
									$this->inBattleLog('{u1} использовала &quot;<b>'.$itm['name'].' Саныча</b>&quot; на {u2}.',$usr);
								}else{
									$this->inBattleLog('{u1} использовал &quot;<b>'.$itm['name'].' Саныча</b>&quot; на {u2}.',$usr);
								}
								
								mysql_query('UPDATE `stats` SET `hpNow` = "'.$usr['hpNow'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
								mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->stats['hpNow'].'" WHERE `id` = "'.$usr['id'].'" LIMIT 1');
								$itm['iznosNOW'] += $iznslvl;
								mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
								$u->addAction(time(),'sanich3',$u->info['battle']);
							}else{
								$u->error = 'Нет подходящего противника';
							}
							
						}else{
							$u->error = 'Задержка использования '.$u->timeOut(($sz['time']+3*60*60)-time()).'.';
						}
										
					}elseif($itm['magic_inci']=='lech')
					{
						$goodUse = 0;
						    if($u->info['level']>=2 and $u->info['level']<=7){
							    $travm = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid`="'.$u->info['id'].'" and `id_eff`="4" and `delete`="0" LIMIT 1'));
                                    if($travm) {
										mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$travm['id'].'" LIMIT 1');
										$itm['iznosNOW']++;
										mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
										if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
											mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
										}
									}else{
										$u->error = 'У вас нету травмы.';
									}
							}else{
							    $u->error = 'Ваш уровень не подходит для использования свитка.';
							}
						}
									
					
					if($goodUse==1)
					{
						
						$upd1 = 1;
						$upd2 = 1;
						//добавляем эффект персонажу
						if(isset($st['onlyOne']))
						{
							//убираем прошлые эффекты
							$goodUse = 0;
							$upd1 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `id_eff` = "'.$itm['magic_inc'].'"');
							if($upd1)
							{
								$goodUse = 1;
							}
						}
						if(isset($st['oneType']))
						{
							//убираем прошлые эффекты
							$goodUse = 0;
							$upd2 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `overType` = "'.$itm['overType'].'"');
							if($upd1)
							{
								$goodUse = 1;
							}
						}
						
						if($itm['magic_inci']=='unclone')
						{
							//Свиток клонирования
							if( $u->info['hpNow'] < 1 ) {
								$u->error = 'Вам не удалось переманить клона...';
							}elseif( $u->info['battle'] == 0 ) {
								$u->error = 'Можно использовать только в поединке...';
							}else{
								//Преманиваем
								$u->error = 'Переманили...';
							}
						}elseif($itm['magic_inci']=='cloneMe')
						{
							//Свиток клонирования
							if( true == false ) {
								$u->error = 'Свитки клонирования запрещены в нашем проекте.';
							}elseif( $u->info['hpNow'] < 1 ) {
								$u->error = 'Вы успешно клонировали свой труп ;)';
							}elseif( $u->info['battle'] == 0 ) {
								$u->error = 'Можно использовать только в поединке...';
							}else{
								$bot_cou = mysql_fetch_array(mysql_query('SELECT COUNT(`id`) FROM `users` WHERE `login` LIKE "%'.$u->info['login'].' (клон%" AND `battle` = "'.$u->info['battle'].'" LIMIT 1'));
								$bot_cou = $bot_cou[0]+1;
								//Добавляем бота
								$clone = array(
									'id' => $u->info['id'],
									'login' => $u->info['login'].' (клон '.$bot_cou.')',
									'level' => $u->info['level'],
									'city' => $u->info['city'],
									'cityreg' => $u->info['cityreg'],
									'name' => $u->info['name'],
									'sex' => $u->info['sex'],
									'deviz' => $u->info['deviz'],
									'hobby' => $u->info['hobby'],
									'time_reg' => $u->info['time_reg'],
									'obraz' => $u->info['obraz'],
									'stats' => $u->info['stats'],
									'upLevel' => $u->info['upLevel'],
									'priems' => $u->info['priems'],
									'loclon' => true,
                                    'inTurnir' => $u->info['inTurnir']
								);
								$bot = $u->addNewbot(1,NULL,$clone,NULL,true);
								if( $bot > 0 ) {
									mysql_query('UPDATE `stats` SET `team` = "'.$u->info['team'].'",`hpNow` = "'.$u->stats['hpNow'].'",`mpNow` = "'.$u->stats['mpNow'].'" WHERE `id` = "'.$bot.'" LIMIT 1');
									mysql_query('UPDATE `users` SET `battle` = "'.$u->info['battle'].'" WHERE `id` = "'.$bot.'" LIMIT 1');
									//Доабвляем лог
									if( $u->info['sex'] == 0 ) {
										$txt_m = '{u1} использовал &quot;'.$itm['name'].'&quot; и <b>породил клона</b>.';
									}else{
										$txt_m = '{u1} использовала &quot;'.$itm['name'].'&quot; и <b>породила клона</b>.';
									}
									$this->inBattleLog($txt_m,NULL);
									if( $u->info['sex'] == 0 ) {
										$txt_m = '<b>'.$u->info['login'].' (клон '.$bot_cou.')</b>['.$u->info['level'].'] вмешался в поединок!';
									}else{
										$txt_m = '<b>'.$u->info['login'].' (клон '.$bot_cou.')</b>['.$u->info['level'].'] вмешалась в поединок!';
									}
									$this->inBattleLog($txt_m,$usr_m);
									mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.($itm['iznosNOW'] + 1).'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
									mysql_query('UPDATE `items_users` SET `btl_zd` = "1" WHERE `item_id` = "'.$itm['item_id'].'" AND `inOdet` > 0 AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" LIMIT 20');
									$u->error = 'Заклятие &quot;'.$itm['name'].'&quot; было успешно использовано';
								}else{
									$u->error = 'Неудалось использовать заклятие...';
								}
								unset($txt_m,$usr_m,$clone,$bot_cou,$bot);
							}
						
                        } elseif($itm['magic_inci'] == 'scan') {
                          if($u->info['inTurnir'] != 0) {
                            $sp = mysql_query('SELECT `st`.*, `u`.* FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `inTurnir` = "'.$u->info['inTurnir'].'"');
                            while($pl = mysql_fetch_array($sp)) {
                             $rrm = mysql_fetch_array(mysql_query('SELECT * FROM `bs_map` WHERE `x` = "'.$pl['x'].'" AND `y` = "'.$pl['y'].'"'));
                             $trnt .= $pl['login'].' Комната : '.$rrm['name'].', ';
                            }
                            $trnt = rtrim($trnt,', ');
                            $it_ = $u->addItem(2435, $u->info['id'], 'noodet=1|noremont=1|sudba='.$u->info['login'].'');
                            mysql_query('UPDATE `items_users` SET `use_text` = 500 WHERE `id` = "'.$it_.'" LIMIT 1');
                            mysql_query('INSERT INTO `items_text` (`item_id`,`time`,`login`,`text`,`city`,`x`,`type`) VALUES ("'.$it_.'","'.time().'","","'.mysql_real_escape_string($trnt).'","'.$u->info['city'].'","1","1")');
                            mysql_query('DELETE FROM `items_users` WHERE `id` = "'.$itm['id'].'" LIMIT 1');
                            $u->error = 'Вы получили выписку...';
                            
                          } else {
                            $u->error = 'Используется только в Башне смерти...';
                          }
                        }elseif($itm['magic_inci']=='tactic')
						{
							//Выдаем тактику
							if( $u->stats['hpNow'] >= 1 ) {
								$trtct = mysql_fetch_array(mysql_query('SELECT `id`,`time` FROM `battle_actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` = "use_cast_tactic" AND `btl` = "'.$u->info['battle'].'" AND `time` > "'.(time()-180).'" LIMIT 1'));
								if(isset($trtct['id'])) {
									$u->error = 'Задержка использования еще '.$u->timeOut($trtct['time']-time()+180);
								}else{
									mysql_query('INSERT INTO `battle_actions` (`uid`,`btl`,`time`,`vars`,`vals`) VALUES (
										"'.$u->info['id'].'","'.$u->info['battle'].'","'.time().'","use_cast_tactic",""
									)');
									$u->info['tactic'.$st['addtac']] += $st['addtacv'];
									mysql_query('UPDATE `stats` SET `tactic'.$st['addtac'].'` = "'.$u->info['tactic'.$st['addtac']].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
									//Доабвляем лог
									if( $u->info['sex'] == 0 ) {
										$txt_m = '{u1} применил заклинание &quot;<b>'.$itm['name'].'</b>&quot;.';
									}else{
										$txt_m = '{u1} применила заклинание &quot;<b>'.$itm['name'].'</b>&quot;.';
									}
									$this->inBattleLog($txt_m,NULL);
									mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.($itm['iznosNOW'] + 1).'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
									$u->error = 'Заклятие &quot;'.$itm['name'].'&quot; было успешно использовано';
								}
							}else{
								$u->error = 'Вы погибли...';
							}
						}elseif($itm['magic_inci']=='add_animal')
						{
							if($u->info['animal']>0)
							{
								$u->error = 'Не удалось использовать "'.$itm['name'].'", у Вас уже есть зверь.';
							}else{
								$anm = array('type'=>1,'name'=>'','obraz'=>'','stats'=>'','sex'=>0);
								
								//Выбираем тип зверя
								
								if($itm['name'] == 'Призвать Сову') {
									$anm['type'] = 2;
								}elseif($itm['name'] == 'Призвать Светляка') {
									$anm['type'] = 3;
								}elseif($itm['name'] == 'Призвать Кота') {
									$anm['type'] = 1;
								}elseif($itm['name'] == 'Призвать Чертяку') {
									$anm['type'] = 4;
								}elseif($itm['name'] == 'Призвать Свина') {
									$anm['type'] = 6;
								}elseif($itm['name'] == 'Призвать Пса') {
									$anm['type'] = 5;
								}
								
								if($anm['type']==1)
								{
									$anm['name'] = 'Кот';
									$anm['sex'] = 0;
									$anm['obraz'] = array(1=>'20864.gif',2=>'21301.gif',3=>'21139.gif',4=>'20427.gif');
									$anm['stats'] = 's1=2|s2=5|s3=2|s4=5|rinv=40|m9=5|m6=10';									
								}elseif($anm['type']==2)
								{
									$anm['name'] = 'Сова';
									$anm['sex'] = 1;
									$anm['obraz'] = array(1=>'21415.gif',2=>'21722.gif',3=>'21550.gif');
									$anm['stats'] = 's1=2|s2=2|s3=5|s4=5|rinv=40|m9=5|m6=10';									
								}elseif($anm['type']==3)
								{
									$anm['name'] = 'Светляк';
									$anm['sex'] = 0;
									$anm['obraz'] = array(1=>'22277.gif',2=>'22265.gif',3=>'22333.gif',4=>'22298.gif');
									$anm['stats'] = 's1=3|s2=10|s3=3|s4=4|rinv=40|m9=5|m6=10';									
								}elseif($anm['type']==4)
								{
									$anm['name'] = 'Чертяка';
									$anm['sex'] = 0;
									$anm['obraz'] = array(1=>'22177.gif',2=>'21976.gif',3=>'21877.gif');
									$anm['stats'] = 's1=5|s2=3|s3=3|s4=5|rinv=40|m9=5|m6=10';									
								}elseif($anm['type']==5)
								{
									$anm['name'] = 'Пес';
									$anm['sex'] = 0;
									$anm['obraz'] = array(1=>'22352.gif',2=>'23024.gif',3=>'22900.gif',4=>'22501.gif',5=>'22700.gif');
									$anm['stats'] = 's1=5|s2=3|s3=3|s4=5|rinv=40|m9=5|m6=10';									
								}elseif($anm['type']==6)
								{
									$anm['name'] = 'Свин';
									$anm['sex'] = 0;
									$anm['obraz'] = array(1=>'24000.gif',2=>'25000.gif',3=>'27000.gif',4=>'28000.gif');
									$anm['stats'] = 's1=5|s2=3|s3=3|s4=5|rinv=40|m9=5|m6=10';									
								}
								$anm['obraz'] = $anm['obraz'][rand(1,count($anm['obraz']))];
								$anm['obraz'] = str_replace('.gif','',$anm['obraz']);
								$anm['obraz'] = str_replace('.jpg','',$anm['obraz']);
								$anm['obraz'] = str_replace('.png','',$anm['obraz']);
								$ins = mysql_query('INSERT INTO `users_animal` (`type`,`name`,`uid`,`obraz`,`stats`,`sex`) VALUES ("'.$anm['type'].'","'.$anm['name'].'","'.$u->info['id'].'","'.$anm['obraz'].'","'.$anm['stats'].'","'.$anm['sex'].'")');
								if($ins)
								{
									
									$u->info['animal'] = mysql_insert_id();
									mysql_query('UPDATE `users` SET `animal` = "'.$u->info['animal'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
									$u->error = 'Вы успешно использовали "'.$itm['name'].'" и помните - &quot;Мы в ответе за тех, кого приручили&quot;.';
									$itm['iznosNOW']++;
									mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
									if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
										mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
									}
									$u->addDelo(1,$u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж использовал заклинание &quot;'.$itm['name'].'&quot; ('.$us[1].') [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);
								}else{
									$u->error = 'Не удалось использовать "'.$itm['name'].'", что-то здесь не так ...';
								}
							}
						}elseif($goodUse == 1)
						{
							if($itm['magic_inc'] == '') {
								$itm['magic_inc'] = $itm['magic_inci'];
							}
							$us = $this->add_eff($u->info['id'],$itm['magic_inc']);
							if($us[0]==1)
							{
								$itm['iznosNOW']++;
								mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
								if($itm['inGroup'] > 0 && $itm['delete'] == 0) {
									mysql_query('UPDATE `items_users` SET `inGroup` = "0", `delete` = "0" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
								}
								$u->addDelo(1,$u->info['id'],'&quot;<font color="maroon">System.inventory</font>&quot;: Персонаж использовал заклинание &quot;'.$itm['name'].'&quot; ('.$us[1].') [itm:'.$itm['id'].'].',time(),$u->info['city'],'System.inventory',0,0);
								$this->youuse++;
								$u->error = 'Вы успешно использовали заклинание &quot;'.$itm['name'].'&quot;<br>'.$us[1].'';
								$rtxt = '[img[items/'.$itm['img'].']] &quot;'.$u->info['login'].'&quot; использовал'.$sx.' заклинание &quot;'.$itm['name'].'&quot; на себя.';
								mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('1','".$u->info['city']."','".$u->info['room']."','','".$u->info['login']."','".$rtxt."','".time()."','6','0','1')");	
							}else{
								if($u->error != '') {
									$u->error = 'Не удалось использовать "'.$itm['name'].'"...<br>'.$u->error;
								}else{
									$u->error = 'Не удалось использовать "'.$itm['name'].'"...';
								}
							}
						}else{
							if($u->error != '') {
								$u->error = 'Не удалось использовать "'.$itm['name'].'"<br>'.$u->error;
							}else{
								$u->error = 'Не удалось использовать "'.$itm['name'].'"';
							}
						}
					}
				 //------------------------------
				}				
			}
			if( $goodUse == 1 ) {
				mysql_query('UPDATE `items_users` SET `lastUPD` = '.time().' WHERE `id` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT 1');
			}
		}else{
			$u->error = 'Предмет не найден в инвентаре';
		}
	}


	public function add_eff($uid,$id,$is_no = NULL)
	{
		$g = array(0=>0,1=>'');
		$eff = mysql_fetch_array(mysql_query('SELECT * FROM `eff_main` WHERE `id2` = "'.$id.'" LIMIT 1'));	
		
		if($is_no != NULL) {
			//добавляем эффект персонажу
			if($eff['onlyOne'] > 0)
			{
				//убираем прошлые эффекты
				$goodUse = 0;
				$upd1 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$uid.'" AND `delete` = "0" AND `id_eff` = "'.$eff['id2'].'"');
				if($upd1)
				{
					$goodUse = 1;
				}
			}
			if($st['oneType'] > 0)
			{
				//убираем прошлые эффекты
				$goodUse = 0;
				$upd2 = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$uid.'" AND `delete` = "0" AND `overType` = "'.$eff['overType'].'"');
				if($upd2)
				{
					$goodUse = 1;
				}
			}
		}
		if($goodUse == 1 || $is_no == NULL) {
			if(isset($eff['id2']))
			{
				$eff = $this->paguba($eff);
				$n = $eff['mname'];
				$d = $eff['mdata'];
				$ins = mysql_query('INSERT INTO `eff_users` (`overType`,`id_eff`,`uid`,`name`,`timeUse`,`data`,`no_Ace`) VALUES ("'.$eff['oneType'].'","'.$eff['id2'].'","'.$uid.'","'.$n.'","'.time().'","'.$d.'","'.$eff['noAce'].'")');
				if($ins)
				{
					$g[0] = 1;
					$g[1] = '...';
				}
			}
		}
		return $g;
	}
	
	//Проверка склонностей в поединке, куда вмешиваемся
	public function testAlignAtack( $u1 , $u2 , $btl_test ) {
		$r = true;
		if( $btl_test['type'] != 500 && $btl_test['dn_id'] == 0 && $btl_test['team_win'] == -1 ) { 
			$u1 = mysql_fetch_array(mysql_query('SELECT `id`,`align` FROM `users` WHERE `id` = "'.mysql_real_escape_string($u1).'" LIMIT 1'));
			$u2 = mysql_fetch_array(mysql_query('SELECT `id`,`team` FROM `stats` WHERE `id` = "'.mysql_real_escape_string($u2).'" LIMIT 1'));
			$u1['align'] = floor($u1['align']);
			$tm = $tm[$u1['team']];
			$no_align = array();
			if( $u1['align'] == 1 ) {
				$no_align[3] = true;
			}elseif( $u1['align'] == 3 ) {
				$no_align[1] = true;
			}
			
			$sp = mysql_query('SELECT `u`.`align`,`st`.`team` FROM `users` AS `u` LEFT JOIN `stats` AS `st` ON `st`.`id` = `u`.`id` WHERE `u`.`battle` = "'.$btl_test['id'].'" AND `st`.`team` != "'.$u2['team'].'"');
			while( $pl = mysql_fetch_array($sp) ) {
				if( $no_align[floor($pl['align'])] == true ) {
					$r = false;
				}
			}
		}
		return $r;
	}
	
	//Проверка травмы
	public function testTravma( $uid , $vals ) {
		$r = false;		
		$tr_pl = mysql_fetch_array(mysql_query('SELECT `id`,`v1` FROM `eff_users` WHERE `id_eff` = 4 AND `uid` = "'.$uid.'" AND `delete` = "0" ORDER BY `v1` DESC LIMIT 1'));
		if( isset($tr_pl['id']) && $tr_pl['v1'] >= $vals ) {
			$r = true;
		}
		return $r;
	}
	
	//создаем нападение на персонажа
	public function atackUser($uid1, $uid2, $tm, $btl, $addExp = 0, $type = 0, $kulak = 0, $bsid = 0) {
		global $u;
		$usr = mysql_fetch_array(mysql_query('SELECT `u`.*,`s`.* FROM `users` AS `u` LEFT JOIN `stats` AS `s` ON `u`.`id` = `s`.`id` WHERE `u`.`id` = "'.$uid2.'" LIMIT 1'));
		$btl_test = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.$btl.'" AND `team_win` = -1 LIMIT 1'));
		$good = 0;
		//Эффекты из-за которых нельзя нападать
		$efsno = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$uid2.'"
		AND `name` LIKE "%Лепим снежок%" AND `delete` = "0"
		LIMIT 1'));
		if( $btl_test['noatack'] > 0 || $btl_test['noinc'] > 0 ) {
			$u->error = 'Поединок защищен магией! Вы не можете вмешаться!';
		}elseif( isset($efsno['id']) && $efsno['delete'] > 0 ) {
			$u->error = 'Невозможно напасть, противник чем-то занят...';
		}elseif($this->testTravma($uid2 , 3) == true) {
			$u->error = 'Противник тяжело травмирован, нельзя напасть!';
		} elseif($this->testTravma($uid1 , 2) == true) {
			$u->error = 'Вы травмированы, нельзя напасть!';
        } elseif($bsid != 0 && ($u->info['x'] != $usr['x'] || $u->info['y'] != $usr['y'] )) {
            $u->error = 'Вы находитесь в разных комнатах...';
		} elseif($usr['clan'] != 0 && ($usr['clan'] == $u->info['clan'])) {
			$u->error = 'Чтите честь ваших сокланов.';
		} elseif($btl == 0) {
			$s01 = $u->getStats($uid1,0);
			if($s01['hpNow'] < floor($s01['hpAll']/100*33) ) {
				$u->error = 'Нельзя напасть, у противника не восстановилось здоровье';
			} else {
				$addExp += $usr['bbexp'];
				//нападаем на персонажа
				$ins = mysql_query('INSERT INTO `battle` (`kulak`,`city`,`time_start`,`players`,`timeout`,`type`,`invis`,`noinc`,`travmChance`,`typeBattle`,`addExp`,`money`, `inTurnir`) VALUES (
																		"'.$kulak.'",
																		"'.$u->info['city'].'",
																		"'.time().'",
																		"'.$u->info['login'].','.$usr['login'].'",
																		"180",
																		"'.$type.'",
																		"0",
																		"0",
																		"50",
																		"9",
																		"'.$addExp.'",
																		"0", "'.$bsid.'")');
				if($ins)
				{
					$btl_id = mysql_insert_id();
					//Обновляем НР и МР игрокам
					if($s01['level']<=7)
					{
						$s01['tactic7'] = floor(10/$s01['hpAll']*$s01['hpNow']);
					}elseif($s01['level']==8)
					{
						$s01['tactic7'] = floor(20/$s01['hpAll']*$s01['hpNow']);
					}elseif($s01['level']==9)
					{
						$s01['tactic7'] = floor(30/$s01['hpAll']*$s01['hpNow']);
					}elseif($s01['level']>=10)
					{
						$s01['tactic7'] = floor(40/$s01['hpAll']*$s01['hpNow']);
					}
					$s01 = array( 'hpAll' => $s01['hpAll'] , 'hpNow' => $s01['hpNow'] , 'mpAll' => $s01['mpAll'] , 'mpNow' => $s01['mpNow']  );
					$s02 = $u->getStats($uid2,0);
					if($s02['level']<=7)
					{
						$s02['tactic7'] = floor(10/$s02['hpAll']*$s02['hpNow']);
					}elseif($s02['level']==8)
					{
						$s02['tactic7'] = floor(20/$s02['hpAll']*$s02['hpNow']);
					}elseif($s02['level']==9)
					{
						$s02['tactic7'] = floor(30/$s02['hpAll']*$s02['hpNow']);
					}elseif($s02['level']>=10)
					{
						$s02['tactic7'] = floor(40/$s02['hpAll']*$s02['hpNow']);
					}
						
					//Духовность, спасение
					if( $s01['s7'] > 49 ) {
						mysql_query("
							INSERT INTO `eff_users` ( `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `timeAce`, `user_use`, `delete`, `v1`, `v2`, `img2`, `x`, `hod`, `bj`, `sleeptime`, `no_Ace`, `file_finish`, `tr_life_user`, `deactiveTime`, `deactiveLast`, `mark`, `bs`) VALUES
							( 22, '".$s01['id']."', 'Спасение', 'add_spasenie=1', 0, 77, 0, '".$s01['id']."', 0, 'priem', 324, 'preservation.gif', 1, -1, 'спасение', 0, 0, '', 0, 0, 0, 1, 0);
						");
					}					
					if( $s02['s7'] > 49 ) {
						mysql_query("
							INSERT INTO `eff_users` ( `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `timeAce`, `user_use`, `delete`, `v1`, `v2`, `img2`, `x`, `hod`, `bj`, `sleeptime`, `no_Ace`, `file_finish`, `tr_life_user`, `deactiveTime`, `deactiveLast`, `mark`, `bs`) VALUES
							( 22, '".$s02['id']."', 'Спасение', 'add_spasenie=1', 0, 77, 0, '".$s02['id']."', 0, 'priem', 324, 'preservation.gif', 1, -1, 'спасение', 0, 0, '', 0, 0, 0, 1, 0);
						");
					}
					//
					
					$s02 = array( 'hpAll' => $s02['hpAll'] , 'hpNow' => $s02['hpNow'] , 'mpAll' => $s02['mpAll'] , 'mpNow' => $s02['mpNow']  );				
					
					
					$upd2  = mysql_query('UPDATE `users` SET `battle`="'.$btl_id.'" WHERE `id` = "'.$uid1.'" OR `id` = "'.$uid2.'" LIMIT 2');
							 mysql_query('UPDATE `stats` SET `lider` = "'.$btl_id.'",`tactic7` = "'.$s01['tactic7'].'",`hpNow` = "'.$s01['hpNow'].'",`mpNow` = "'.$s01['mpNow'].'",`team`="1",`zv` = "0" WHERE `id` = "'.$uid1.'" LIMIT 1');
							 mysql_query('UPDATE `stats` SET `lider` = "'.$btl_id.'",`tactic7` = "'.$s02['tactic7'].'",`hpNow` = "'.$s02['hpNow'].'",`mpNow` = "'.$s02['mpNow'].'",`team`="2",`zv` = "0" WHERE `id` = "'.$uid2.'" LIMIT 1');
					
					if( $kulak > 0 || $btl_test['kulak'] > 0 ) {
						mysql_query('UPDATE `items_users` SET `inOdet` = "0" WHERE ( `uid` = "'.$uid1.'" OR `uid` = "'.$uid2.'" ) AND `delete` = "0"');
					}
					
					$good = $btl_id;
                    if($bsid != 0) {
                        $bs = mysql_fetch_array(mysql_query('SELECT * FROM `bs_turnirs` WHERE `id` = "'.$u->info['inTurnir'].'" LIMIT 1'));
                      if($u->info['sex'] == 0) {
                        $text = '<img src="http://img.xcombats.com/i/items/atackk.gif" /> {u1} напал на {u2} завязался бой <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
	                  } else {
	                    $text = '<img src="http://img.xcombats.com/i/items/atackk.gif" /> {u1} напала на {u2} завязался бой <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
	                  }
                      $usr_real = mysql_fetch_array(mysql_query('SELECT `id`, `login`, `align`, `clan`, `battle`, `level` FROM `users` WHERE (`inUser` = "'.$usr['id'].'" OR `id` = "'.$usr['id'].'") LIMIT 1'));
                      if(!isset($usr_real['id'])) { $usr_real = $usr; }
                      if(isset($usr_real['id'])) {
                        $usrreal = '';
                        if($usr_real['align'] > 0) { $usrreal .= '<img src=http://img.xcombats.com/i/align/align'.$usr_real['align'].'.gif width=12 height=15 >'; }
                        if($usr_real['clan'] > 0) { $usrreal .= '<img src=http://img.xcombats.com/i/clan/'.$usr_real['clan'].'.gif width=24 height=15 >'; }
                        $usrreal .= '<b>'.$usr_real['login'].'</b>['.$usr_real['level'].']<a target=_blank href=http://xcombats.com/info/'.$usr_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
                      } else {
                        $usrreal = '<i>Невидимка</i>[??]';
                      }
                      $me_real = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `inUser` = "'.$u->info['id'].'" AND `login` = "'.$u->info['login'].'" LIMIT 1'));
                      if(isset($me_real['id'])) {
					    $mereal = '';
					    if($me_real['align'] > 0) { $mereal .= '<img src=http://img.xcombats.com/i/align/align'.$me_real['align'].'.gif width=12 height=15 >'; }
					    if($me_real['clan'] > 0) { $mereal .= '<img src=http://img.xcombats.com/i/clan/'.$me_real['clan'].'.gif width=24 height=15 >'; }
					    $mereal .= '<b>'.$me_real['login'].'</b>['.$me_real['level'].']<a target=_blank href=http://xcombats.com/info/'.$me_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
				      } else {
					    $mereal = '<i>Невидимка</i>[??]';
				      }
                      $text = str_replace('{u1}', $mereal, $text);
				      $text = str_replace('{u2}', $usrreal, $text);
                      mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
					  "1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$bs['id'].'", "'.$bs['count'].'", "'.$bs['city'].'",
					  "'.round($bs['money']*0.85,2).'","'.$i.'")');
				      unset($text, $usrreal, $mereal, $usr_real, $me_real);
                    }
				}
			}
		}elseif( isset($btl_test['id']) && $btl_test['type'] == 500 && $usr['team'] == 1 ){
			$u->error = 'Нельзя сражаться на стороне монстров!';
		}elseif( isset($btl_test['id']) && $btl_test['invis'] > 0 ){
			$u->error = 'Нельзя вмешиваться в невидимый бой!';
		}elseif( $this->testAlignAtack( $uid1, $uid2, $btl_test) == false ) {
			$u->error = 'Нельзя помогать вражеским склонностям!';
		}elseif( $btl_test['noatack'] > 0 ) {
			$u->error = 'В этот поединок нельзя вмешиваться!';
		}else{
			//вмешиваемся в бой
			$upd = mysql_query('UPDATE `users` SET `battle`="'.$btl.'" WHERE `id` = "'.$uid1.'" LIMIT 1');
			if($upd)
			{
				
				if( $kulak > 0 || $btl_test['kulak'] > 0 ) {
					mysql_query('UPDATE `items_users` SET `inOdet` = "0" WHERE `uid` = "'.$uid1.'" AND `delete` = "0"');
				}
				
				$uid1st = $u->getStats($uid1);
				$uid1u = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`level`,`clan`,`align`,`sex` FROM `users` WHERE `id` = "'.$uid1.'" LIMIT 1'));
				
				if($uid1u['level']<=7)
				{
					$uid1st['tactic7'] = floor(10/$uid1st['hpAll']*$uid1st['hpNow']);
				}elseif($uid1u['level']==8)
				{
					$uid1st['tactic7'] = floor(20/$uid1st['hpAll']*$uid1st['hpNow']);
				}elseif($uid1u['level']==9)
				{
					$uid1st['tactic7'] = floor(30/$uid1st['hpAll']*$uid1st['hpNow']);
				}elseif($uid1u['level']>=10)
				{
					$uid1st['tactic7'] = floor(40/$uid1st['hpAll']*$uid1st['hpNow']);
				}else{
					$uid1st['tactic7'] = floor(10/$uid1st['hpAll']*$uid1st['hpNow']);
				}
				
				//Духовность, спасение
				if( $uid1st['s7'] > 49 ) {
					mysql_query("
						INSERT INTO `eff_users` ( `id_eff`, `uid`, `name`, `data`, `overType`, `timeUse`, `timeAce`, `user_use`, `delete`, `v1`, `v2`, `img2`, `x`, `hod`, `bj`, `sleeptime`, `no_Ace`, `file_finish`, `tr_life_user`, `deactiveTime`, `deactiveLast`, `mark`, `bs`) VALUES
						( 22, '".$uid1st['id']."', 'Спасение', 'add_spasenie=1', 0, 77, 0, '".$uid1st['id']."', 0, 'priem', 324, 'preservation.gif', 1, -1, 'спасение', 0, 0, '', 0, 0, 0, 1, 0);
					");
				}
				
				
				$btxt = '';
				if( $uid1u['align'] > 0 ) {
					$btxt = $btxt.'<img width=12 height=15 src=http://img.xcombats.com/i/align/align'.$uid1u['align'].'.gif >';
				}
				if( $uid1u['clan'] > 0 ) {
					$btxt = $btxt.'<img width=24 height=15 src=http://img.xcombats.com/i/clan/'.$uid1u['clan'].'.gif >';
				}
				$btxt = $btxt.'<b>{u1}</b>['.$uid1u['level'].']<a href=info/'.$uid1u['id'].' target=_blank ><img width=12 height=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
				if( $uid1u['sex'] == 1 ) {
					$btxt = $btxt.' вмешалась в поединок.';
				}else{
					$btxt = $btxt.' вмешался в поединок.';
				}
				
				if( $kulak > 0 ) {
					$btxt .= ' (Кулачное нападение)';
				}
				
				$lastHOD = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$btl.'" ORDER BY `id_hod` DESC LIMIT 1'));
				if(isset($lastHOD['id'])) {
					$id_hod = $lastHOD['id_hod'];
					if($lastHOD['type']!=6) {
						$id_hod++;
					}
					mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$btl.'","'.($id_hod).'","{tm1} '.$btxt.'","login1='.$uid1st['login'].'||t1='.$uid1st['team'].'||login2='.$uid1st['login'].'||t2='.$uid1st['team'].'||time1='.time().'","","","","","6")');
				}
				
				// Бафф Зверя animal_bonus ---------------------------------
						if($u->info['animal'] > 0) {
							$a = mysql_fetch_array(mysql_query('SELECT * FROM `users_animal` WHERE `uid` = "'.$u->info['id'].'" AND `id` = "'.$u->info['animal'].'" AND `pet_in_cage` = "0" AND `delete` = "0" LIMIT 1'));
							if(isset($a['id'])) {
								if($a['eda']>=1) {
									$anl = mysql_fetch_array(mysql_query('SELECT `bonus` FROM `levels_animal` WHERE `type` = "'.$a['type'].'" AND `level` = "'.$a['level'].'" LIMIT 1'));
									$anl = $anl['bonus'];
									
									$tpa = array(1=>'cat',2=>'owl',3=>'wisp',4=>'demon',5=>'dog',6=>'pig',7=>'dragon');
									$tpa2 = array(1=>'Кота',2=>'Совы',3=>'Светляка',4=>'Чертяки',5=>'Пса',6=>'Свина',7=>'Дракона');
									$tpa3 = array(1=>'Кошачья Ловкость',2=>'Интуиция Совы',3=>'Сила Стихий',4=>'Демоническая Сила',5=>'Друг',6=>'Полная Броня',7=>'Инферно');
									
									mysql_query('INSERT INTO `eff_users` (`hod`,`v2`,`img2`,`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`v1`,`user_use`) VALUES ("-1","201","summon_pet_'.$tpa[$a['type']].'.gif",22,"'.$u->info['id'].'","'.$tpa3[$a['type']].' ['.$a['level'].']","'.$anl.'","0","77","priem","'.$u->info['id'].'")');
									
									/*$anl = $u->lookStats($anl);
									
									$vLog = 'time1='.time().'||s1='.$u->info['sex'].'||t1='.$u->info['team'].'||login1='.$u->info['login'].'';
									$vLog .= '||s2=1||t2='.$u->info['team'].'||login2='.$a['name'].' (Зверь '.$u->info['login'].')';
									
									$mas1 = array('time'=>time(),'battle'=>$btl,'id_hod'=>1,'text'=>'','vars'=>$vLog,'zona1'=>'','zonb1'=>'','zona2'=>'','zonb2'=>'','type'=>'1');
									
									$ba = '';
									$i6 = 0;
									while($i6<count($u->items['add'])) {
										if(isset($anl['add_'.$u->items['add'][$i6]])) {
											if($anl['add_'.$u->items['add'][$i6]] > 0) {
												$ba .= $u->is[$u->items['add'][$i6]].': +'.$anl['add_'.$u->items['add'][$i6]].', ';
											}
										}
										$i6++;
									}
									$ba = trim($ba,', ');
									if($ba == '') {
										$ba = 'Эффект отсутсвует';
									}
									*/
									//$mas1['text'] = '{tm1} {u2} очнулся от медитации, и призвал заклятье &quot;<b>'.$tpa3[$a['type']].' ['.$a['level'].']</b>&quot; на {u1}. ('.$ba.')';
									//$nxtlg[count($nxtlg)] = $mas1;
									//mysql_query('UPDATE `users_animal` SET `eda` = `eda` - 1 WHERE `id` = "'.$a['id'].'" LIMIT 1');
									//$btl->add_log($mas1);
									//$btl->get_comment();*/
								}else{
									$u->send('',$u->info['room'],$u->info['city'],'',$u->info['login'],'<b>'.$a['name'].'</b> нуждается в еде...',time(),6,0,0,0,1);
								}
							}
						}
				// Бафф Зверя animal_bonus ---------------------------------
				$ltm = array(1=>2, 2=>1);
				mysql_query('UPDATE `stats` SET `hpNow` = "'.$uid1st['hpNow'].'",`mpNow` = "'.$uid1st['mpNow'].'",`team`="'.$ltm[$tm].'",`tactic7`="'.(0+$uid1st['tactic7']).'" WHERE `id` = "'.$uid1.'" LIMIT 1');
				$good = $btl;
				unset($uid1st);
                if($bsid != 0) {
                    $bs = mysql_fetch_array(mysql_query('SELECT * FROM `bs_turnirs` WHERE `id` = "'.$u->info['inTurnir'].'" LIMIT 1'));
                      if($u->info['sex'] == 0) {
                        $text = '<img src="http://img.xcombats.com/i/items/atackk.gif" /> {u1} вмешался в поединок против {u2} <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
	                  } else {
	                    $text = '<img src="http://img.xcombats.com/i/items/atackk.gif" /> {u1} вмешалась в поединок против {u2} <a target=_blank href=/logs.php?log='.$btl_id.' >»»</a>';
	                  }
                      $usr_real = mysql_fetch_array(mysql_query('SELECT `id`, `login`, `align`, `clan`, `battle`, `level` FROM `users` WHERE `inUser` = "'.$usr['id'].'" LIMIT 1'));
                      if(!isset($usr_real['id'])) { $usr_real = $usr; }
                      if(isset($usr_real['id'])) {
                        $usrreal = '';
                        if($usr_real['align'] > 0) { $usrreal .= '<img src=http://img.xcombats.com/i/align/align'.$usr_real['align'].'.gif width=12 height=15 >'; }
                        if($usr_real['clan'] > 0) { $usrreal .= '<img src=http://img.xcombats.com/i/clan/'.$usr_real['clan'].'.gif width=24 height=15 >'; }
                        $usrreal .= '<b>'.$usr_real['login'].'</b>['.$usr_real['level'].']<a target=_blank href=http://xcombats.com/info/'.$usr_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
                      } else {
                        $mereal = '<i>Невидимка</i>[??]';
                      }
                      $me_real = mysql_fetch_array(mysql_query('SELECT `id`,`login`,`align`,`clan`,`battle`,`level` FROM `users` WHERE `inUser` = "'.$u->info['id'].'" AND `login` = "'.$u->info['login'].'" LIMIT 1'));
                      if(isset($me_real['id'])) {
					    $mereal = '';
					    if($me_real['align'] > 0) { $mereal .= '<img src=http://img.xcombats.com/i/align/align'.$me_real['align'].'.gif width=12 height=15 >'; }
					    if($me_real['clan'] > 0) { $mereal .= '<img src=http://img.xcombats.com/i/clan/'.$me_real['clan'].'.gif width=24 height=15 >'; }
					    $mereal .= '<b>'.$me_real['login'].'</b>['.$me_real['level'].']<a target=_blank href=http://xcombats.com/info/'.$me_real['id'].' ><img width=12 hiehgt=11 src=http://img.xcombats.com/i/inf_capitalcity.gif ></a>';
				      } else {
					    $mereal = '<i>Невидимка</i>[??]';
				      }
                      $text = str_replace('{u1}', $mereal, $text);
				      $text = str_replace('{u2}', $usrreal, $text);
                      mysql_query('INSERT INTO `bs_logs` (`type`,`text`,`time`,`id_bs`,`count_bs`,`city`,`m`,`u`) VALUES (
					  "1", "'.mysql_real_escape_string($text).'", "'.time().'", "'.$bs['id'].'", "'.$bs['count'].'", "'.$bs['city'].'",
					  "'.round($bs['money']*0.85,2).'","'.$i.'")');
				      unset($text,$usrreal,$mereal,$usr_real,$me_real);
                    }
			}
		}
		return $good;
	}
	
	//Нападение на центральной площади
 public function magicCentralAttack() {
   global $c, $code, $u, $re;
 }
}

$magic = new Magic;

?>