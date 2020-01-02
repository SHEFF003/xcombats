<?
if(!defined('GAME'))
{
	die();
}
if($p['unbtl']==1)
{
	
	function inBattleLog($txt,$usr) {
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
	
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" ORDER BY `id` ASC LIMIT 1'));
		if(isset($uu['id']))
		{
			if($uu['battle']==0)
			{
				$uer = 'ѕерсонаж не находитс€ в поединке<br>';
			}else{
				$uu['battle222'] = $uu['battle'];
				$uu['battle'] = 0;
				$upd = mysql_query('UPDATE `users` SET `battle` = "'.$uu['battle'].'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				if($upd)
				{
					mysql_query('UPDATE `stats` SET
					`regHP` = "'.time().'"
					,`team` = 0
					,`battle_yron` = 0
					,`battle_exp` = 0
					WHERE `id` = "'.$uu['id'].'" LIMIT 1');
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = 'а';
					}
					inBattleLog('{tm1} '.$rang.' &quot;<b>'.$u->info['cast_login'].'</b>&quot; выпустил персонажа &quot;<b>'.$uu['login'].'</b>&quot; из поединка.');
					$rtxt = '[img[items/pal_buttonn.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; выпустил'.$sx.' персонажа &quot;'.$uu['login'].'&quot; из поединка';
					//mysql_query("UPDATE `chat` SET `delete` = 1 WHERE `login` = '".$uu['login']."' LIMIT 1000");
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; выпустил'.$sx.' персонажа из поединка є<b>'.$uu['battle222'].'</b>.';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$uer = '¬ы успешно выпустили персонажа "'.$uu['login'].'" из поединка.';
				}else{
					$uer = 'Ќе удалось использовать данное закл€тие';
				}
			}
		}else{
			$uer = 'ѕерсонаж не найден в этом городе';
		}
}else{
	$uer = '” ¬ас нет прав на использование данного закл€ти€';
}	
?>