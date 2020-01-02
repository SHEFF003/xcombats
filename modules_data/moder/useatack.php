<?
if(!defined('GAME'))
{
	die();
}

if($p['attack']==1)
{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if($u->room['noatack'] == 1) {
				$uer = 'В данной комнате запрещены нападения!';
			}elseif($uu['id'] == $u->info['id']) {
				$uer = 'Нападать на себя НЕЛЬЗЯ! :)';
			}elseif($uu['room'] != $u->info['room']) {
				$uer = 'ВЫ находитесь в разных комнатах<br>';
			}else{
				
				$ua = mysql_fetch_array(mysql_query('SELECT `s`.*,`u`.* FROM `stats` AS `s` LEFT JOIN `users` AS `u` ON `s`.`id` = `u`.`id` WHERE `s`.`id` = "'.mysql_real_escape_string($uu['id']).'" LIMIT 1'));
				if(isset($ua['id']) && $ua['online'] > time()-520) {
					
					$usta = $u->getStats($ua['id'],0); // статы цели
					$minHp = floor($usta['hpAll']/100*33); // минимальный запас здоровья цели при котором можно напасть
					
					if( $ua['battle'] > 0 ) {
						$uabt = mysql_fetch_array(mysql_query('SELECT * FROM `battle` WHERE `id` = "'.$ua['battle'].'" AND `team_win` = "-1" LIMIT 1'));
						if(!isset($uabt['id'])) {
							$ua['battle'] = 0;
						}
					}
					
					if( $ua['battle'] == 0 && $minHp > $usta['hpNow'] ) {
						$uer = 'Нельзя напасть, у противника не восстановилось здоровье';
					}elseif( isset($uabt['id']) && $uabt['type'] == 500 && $ua['team'] == 1 ) {
						$uer = 'Нельзя сражаться на стороне монстров!';
					}elseif( isset($uabt['id']) && $uabt['invis'] > 0 ) {
						$uer = 'Нельзя вмешиваться в невидимый бой!';
					}elseif( $magic->testAlignAtack( $u->info['id'], $ua['id'], $uabt) == false ) {
						$uer = 'Нельзя помогать вражеским склонностям!';
					}elseif( $magic->testTravma( $ua['id'] , 3 ) == true ) {
						$uer = 'Противник тяжело травмирован, нельзя напасть!';
					}elseif( $magic->testTravma( $u->info['id'] , 2 ) == true ) {
						$uer = 'Вы травмированы, нельзя напасть!';
					}elseif($ua['room']==$u->info['room'] && ($minHp <= $usta['hpNow'] || $ua['battle'] > 0))
					{
					
						mysql_query('UPDATE `stats` SET `hpNow` = "'.$usta['hpNow'].'",`mpNow` = "'.$usta['mpNow'].'" WHERE `id` = "'.$usta['id'].'" LIMIT 1');
				
						$magic->atackUser($u->info['id'],$ua['id'],$ua['team'],$ua['battle'],$ua['bbexp'],$ua['type_pers']);
					
						$sx = '';
						if($u->info['sex']==1)
						{
							$sx = 'а';
						}
						$rtxt = '[img[items/pal_button8.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; совершил'.$sx.' нападение на персонажа &quot;'.$uu['login'].'&quot;.';
						mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
						
						header('location: main.php');
					}
				}else{
					$uer = 'Персонаж должен находиться в онлайне';
				}
				
				/*$upd = mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$uu['id'].'" AND `name` LIKE "%травма"');
				if($upd)
				{
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = 'а';
					}
					$rtxt = '[img[items/cure3.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; излечил'.$sx.' персонажа &quot;'.$uu['login'].'&quot; от травм.';
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; излечил'.$sx.' от травм';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$uer = 'Вы успешно излечили персонажу "'.$uu['login'].'" от травм.';
				}else{
					$uer = 'Не удалось использовать данное заклятие';
				}*/
			}
		}else{
			$uer = 'Персонаж не найден в этом городе';
		}
}else{
	$uer = 'У Вас нет прав на использование данного заклятия';
}	
?>