<?
if(!defined('GAME'))
{
	die();
}

if($st['usefromfile']=='bloodring2' && $u->info['battle'] > 0 && $u->info['hpNow'] >= 1)
{
	if($btl->info['team_win'] != -1 ) {
		$u->error = 'Использовать кольцо возможно только во время боя';
	}elseif($btl->info['razdel'] != 5) {
		$u->error = 'Использование кольца возможно только в хаотичных поединках!';
	}elseif(ceil($u->info['tactic6']) < 5) {
		$u->error = 'Не хватает '.(5-ceil($u->info['tactic6'])).' <img width=8 height=8 src=http://img.xcombats.com/i/micro/hp.gif> для &quot;Поглотить Кровь&quot;';
	}else{
		if($st['td_cast_data'] != date('d.m.Y')) {
			$st['td_cast_data'] = date('d.m.Y');
			$st['td_cast'] = 0;
		}
		
		if($st['td_cast'] > 5) {
			$u->error = 'Использование кольца возможно не более 6 раз в сутки!';
		}else{
			$bu = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `v1` = "priem" AND `v2` = "229" AND `delete` = "0" LIMIT 1'));
			
			$st['td_cast']++;
			
			$stimp = $u->impStats($st);
			mysql_query('UPDATE `items_users` SET `data` = "'.mysql_real_escape_string($stimp).'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
			
			mysql_query('UPDATE `stats` SET `tactic6` = `tactic6` - 5 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['tactic6'] -= 5;
			
			//$u->addItem(3136,$u->info['id'],'|sudba='.$u->info['login']);
			
			//Если эффект есть, тогда добавляем к нему +1 каст (Максимум 6)
			if(isset($bu['id'])) {
				if($bu['x'] < 6) {
					mysql_query('UPDATE `eff_users` SET `x` = `x` + 1 WHERE `id` = "'.$bu['id'].'" LIMIT 1');
				}
			}else{
				$ins = mysql_query('INSERT INTO `eff_users` (`file_finish`,`hod`,`v2`,`img2`,`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`v1`) VALUES
				("bloodring2_end","-1",229,"invoke_create_bloodstone.gif",22,"'.$u->info['id'].'","Поглотить Кровь","","30","77","priem")');
			}
			
			$u->error = 'Вы успешно использовали заклинание &quot;Поглотить Кровь&quot;<br>В случаи победы Вы получите &quot;Кровавый Рубин&quot;';
			
			//Лог боя
			$lastHOD = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$u->info['battle'].'" ORDER BY `id_hod` DESC LIMIT 1'));
			$id_hod = $lastHOD['id_hod'];
			if($lastHOD['type']!=6) {
				$id_hod++;
			}
			$txt = '<font color=#006699>'.$txt.'</font>';
			if($u->info['sex']==1) {
				$txt = '{u1} применила заклинание &quot;<b>Поглотить Кровь</b>&quot;.';
			}else{
				$txt = '{u1} применил заклинание &quot;<b>Поглотить Кровь</b>&quot;.';
			}
			mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$u->info['battle'].'","'.($id_hod).'","{tm1} '.$txt.'","login1='.$u->info['login'].'||t1='.$u->info['team'].'||time1='.time().'","","","","","6")');
		}
	}
}

?>