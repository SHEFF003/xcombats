<?
if(!defined('GAME'))
{
	die();
}

if($st['usefromfile']=='bloodring' && $u->info['battle'] > 0 && $u->info['hpNow'] >= 1)
{
	if($btl->info['team_win'] != -1 ) {
		$u->error = 'Использовать кольцо возможно только во время боя';
	}elseif($btl->info['razdel'] != 5) {
		$u->error = 'Использование кольца возможно только в хаотичных поединках!';
	}elseif(ceil($u->info['tactic6']) < 15) {
		$u->error = 'Не хватает '.(15-ceil($u->info['tactic6'])).' <img width=8 height=8 src=http://img.xcombats.com/i/micro/hp.gif> для &quot;Кровавый сбор&quot;';
	}else{
		$bu = mysql_fetch_array(mysql_query('SELECT `id` FROM `eff_users` WHERE `v1` = "priem" AND `v2` = "228" AND `delete` = "0" LIMIT 1'));
		if(isset($bu['id'])) {
			$u->error = 'Использование кольца возможно 1 раз за бой!';
		}else{
			mysql_query('UPDATE `stats` SET `tactic6` = `tactic6` - 15 WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['tactic6'] -= 15;
			$u->addItem(3134,$u->info['id'],'|sudba='.$u->info['login']);
			$ins = mysql_query('INSERT INTO `eff_users` (`hod`,`v2`,`img2`,`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`,`v1`) VALUES
			("-1",228,"invoke_create_lesserbloodstone.gif",22,"'.$u->info['id'].'","Кровавый Сбор","","30","77","priem")');
			$u->error = 'Вы успешно использовали заклинание &quot;Кровавый сбор&quot;';
			
			//Лог боя
			$lastHOD = mysql_fetch_array(mysql_query('SELECT * FROM `battle_logs` WHERE `battle` = "'.$u->info['battle'].'" ORDER BY `id_hod` DESC LIMIT 1'));
			$id_hod = $lastHOD['id_hod'];
			if($lastHOD['type']!=6) {
				$id_hod++;
			}
			$txt = '<font color=#006699>'.$txt.'</font>';
			if($u->info['sex']==1) {
				$txt = '{u1} применила заклинание &quot;<b>Кровавый сбор</b>&quot;.';
			}else{
				$txt = '{u1} применил заклинание &quot;<b>Кровавый сбор</b>&quot;.';
			}
			mysql_query('INSERT INTO `battle_logs` (`time`,`battle`,`id_hod`,`text`,`vars`,`zona1`,`zonb1`,`zona2`,`zonb2`,`type`) VALUES ("'.time().'","'.$u->info['battle'].'","'.($id_hod).'","{tm1} '.$txt.'","login1='.$u->info['login'].'||t1='.$u->info['team'].'||time1='.time().'","","","","","6")');
		}
	}
}

?>