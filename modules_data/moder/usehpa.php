<?
if(!defined('GAME'))
{
	die();
}

if($p['heal'] == 1)
{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if($uu['city']!=$u->info['city'] && $p['citym1']==0){
				$uer = 'Персонаж находится в другом городе';
			}elseif($uu['battle']>0){
				$uer = 'Персонаж находится в поединке';
			}else{
				$upd = mysql_query('UPDATE `stats` SET `hpNow` = `hpNow` + "1200" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				if($upd)
				{
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = 'а';
					}
					$rtxt = '[img[items/cureHP120.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; восстановил'.$sx.' здоровье персонажа &quot;'.$uu['login'].'&quot;';
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$uer = 'Вы успешно восстановили здоровье персонажа "'.$uu['login'].'".';
				}else{
					$uer = 'Не удалось использовать данное заклятие';
				}
			}
		}else{
			$uer = 'Персонаж не найден в этом городе';
		}
}else{
	$uer = 'У Вас нет прав на использование данного заклятия';
}	
?>