<?
if(!defined('GAME'))
{
	die();
}

if($st['usefromfile']=='veter1' && $u->info['battle'] > 0 && $u->info['hpNow'] >= 1)
{
	if($btl->info['team_win'] != -1 ) {
		$u->error = 'Использовать плащ возможно только во время боя';
	}else{
		
		$bu = mysql_fetch_array(mysql_query('SELECT `id` FROM `magic_act` WHERE `uid` = "'.$u->info['id'].'" AND `date` = "'.date('d.m.Y').'" LIMIT 1'));
		
		if(($itm['iznosMAX']-$itm['iznosNOW']) < $u->info['level'] ) {
			$u->error = 'Недостаточно прочности плаща... Необходимо '.$u->info['level'].' ед.!';
		}elseif(isset($bu['id'])) {
			$u->error = 'Использование плащ возможно 1 раз в день!';
		}else{
			
				$u->error = 'Вы успешно использовали заклинание &quot;Усиленные Рефлексы&quot; (Все тактики увеличены на 25, износ предмета -'.$u->info['level'].' ед.)';
				
				mysql_query('UPDATE `stats` SET `tactic1` = 25 , `tactic2` = 25 , `tactic3` = 25 , `tactic4` = 25 , `tactic5` = 25 , `tactic6` = 25 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
				
				$itm['iznosNOW'] += $u->info['level'];
				if( $itm['iznosNOW'] > $itm['iznosMAX'] ) {
					$itm['iznosNOW'] = $itm['iznosMAX'];
				}
				
				mysql_query('INSERT INTO `magic_act` (`uid`,`time`,`date`,`var`,`val`) VALUES ("'.$u->info['id'].'","'.time().'","'.date('d.m.Y').'","veter1","'.$u->info['battle'].'")');
				
				mysql_query('UPDATE `items_users` SET `iznosNOW` = "'.$itm['iznosNOW'].'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				
				//Лог боя
				$lastHOD = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$u->info['battle'].'" ORDER BY `id_hod` DESC LIMIT 1'));
				$id_hod = $lastHOD['id_hod'];
				if($lastHOD['type']!=6) {
					$id_hod++;
				}
				$txt = '<font color=#006699>'.$txt.'</font>';
				if($u->info['sex']==1) {
					$txt = '{u1} применила заклинание &quot;<b>Усиленные Рефлексы</b>&quot;.';
				}else{
					$txt = '{u1} применил заклинание &quot;<b>Усиленные Рефлексы</b>&quot;.';
				}
				mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$u->info['battle'].'","'.($id_hod).'","{tm1} '.$txt.'","login1='.$u->info['login'].'||t1='.$u->info['team'].'||time1='.time().'","","","","","6")');
			
		}
	}
}

?>