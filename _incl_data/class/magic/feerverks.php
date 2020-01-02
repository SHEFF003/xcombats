<?
if(!defined('GAME'))
{
	die();
}

if( $itm['magic_inci'] == 'feerverks' ) {
	//Феерверк
	if( $u->room['name'] != 'Центральная площадь' ) {
		$u->error = 'Вы не на Центральной Площади';
	}else{
		//$lif = mysql_fetch_array(mysql_query('SELECT `id` FROM `feerverks` WHERE `uid` = "'.$u->info['id'].'" AND `time` > "'.(time()-10).'" LIMIT 1'));
		if( !isset($lif['id']) ) {
			$po = $u->lookStats($itm['data']);
			$fid = 'fw04';
			if( isset($po['feerverk_eff']) ) {
				$fid = $po['feerverk_eff'];
			}
			mysql_query('INSERT INTO `feerverks` (`room`,`uid`,`time`,`fid`) VALUES ("'.$u->info['room'].'","'.$u->info['id'].'","'.time().'","'.$fid.'")');
			//
			if( $u->info['sex'] == 0 ) {
				$text = '[img[items/'.$itm['img'].']] <b>'.$u->info['login'].'</b> запустил фейерверк!';
			}else{
				$text = '[img[items/'.$itm['img'].']] <b>'.$u->info['login'].'</b> запустила фейерверк!';
			}
			//
			if( $po['feerverk_sound'] == 1 ) {
				$po['feerverk_sound'] = rand(8,10);
			}
			//
			mysql_query("INSERT INTO `chat` (`frv`,`sound`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`new`,`typeTime`) VALUES (
			'".$fid."','0','".$u->info['city']."','".$u->info['room']."','','','".$text."','".time()."','6','0','1','2')");
			//	
			if( rand(0,100) < 10 ) {
				// У персонажа легкая травма - "Ожог от фейерверка" еще 3 мин. 43 сек. 
				// 22.02.14 21:14  лосенка пострадала от фейерверка! :fingal:
				$spf = mysql_query('SELECT `id`,`login`,`sex` FROM `users` WHERE `online` > "'.(time()-120).'" AND `invis` = "0" AND `battle` = "0" AND `room` = "'.$u->info['room'].'" ORDER BY `online` DESC LIMIT 100');
				$fusr = array();
				while( $plf = mysql_fetch_array($spf) ) {
					//Иммунитет от травм, либо травма
					$nou = mysql_fetch_array(mysql_query('SELECT `id` FROM `eff_users` WHERE ( `id_eff` = "4" OR `id_eff` = "263" ) AND `uid` = "'.$plf['id'].'" AND `delete` = "0" LIMIT 1'));
					if( !isset($nou['id'])) {
						$fusr[] = $plf;
					}
				}
				unset($spf,$plf,$nou);
				$fusr = $fusr[rand(0, ( count($fusr) - 1 ) )];
				if( isset($fusr['id']) ) {
					//Пострадавший от фейерверка
					$ins = mysql_query('INSERT INTO `eff_users` (`overType`,`timeUse`,`hod`,`name`,`data`,`uid`, `id_eff`, `img2`, `timeAce`, `v1`) VALUES ("0","'.time().'","-1","Ожог от фейерверка","add_s'.rand(1,3).'=-'.rand(1,3).'|add_s'.rand(1,3).'=-'.rand(1,3).'","'.$fusr['id'].'", "4", "eff_travma1.gif","300", "1")');
					if( $fusr['sex'] == 0 ) {
						$text = '[img[items/travma.gif]] <b>'.$fusr['login'].'</b> пострадал от фейерверка! :fingal:';
					}else{
						$text = '[img[items/travma.gif]] <b>'.$fusr['login'].'</b> пострадала от фейерверка! :fingal:';
					}
					mysql_query("INSERT INTO `chat` (`sound`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`new`,`typeTime`) VALUES ('0','".$u->info['city']."','".$u->info['room']."','','','".$text."','".time()."','6','0','1','2')");
				}
				unset($fusr);
			}
			unset($text,$lif,$po);
			//
			mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');	
			//
			$u->error = 'Вы успешно запустили феерверк &quot;'.$itm['name'].'&quot;!';	
		}else{
			$u->error = 'Запускать феерверки возможно не чаще одного раза в 10 сек.';
		}
	}
}
?>