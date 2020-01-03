<?
if(!defined('GAME'))
{
	die();
}
if($p['szatoch']==1 || $p['citym1']==1)
{
	$tm = (int)$_POST['time'];

		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{

			if($uu['admin']>0 && $u->info['admin']==0)
			{
				$uer = 'Вы не можете посадить Ангела.';
			}
			if($uu['city']!=$u->info['city'] && $p['citym1']==0){
				$uer = 'Персонаж находится в другом городе';
			}elseif(floor($uu['align'])==$a && $uu['align']>$u->info['align'] && $u->info['admin']==0)
			{
				$uer = 'Вы не можете посадить старших по званию';
			}elseif($uu['id']==$u->info['id'] && $u->info['admin']==0){
				$uer = 'Вы не можете посадить самого себя';
			}else{
				$upd = mysql_query('UPDATE `users` SET `jail` = "0", `room`="9", `city`="capitalcity" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				if($upd)
				{
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = 'а';
					}
					mysql_query('UPDATE `items_users` SET `delete` = "0" WHERE `uid` = '.$uu['id'].' AND `delete` = "1357908642"');
					$rtxt = '[img[items/jail_off.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; выпустил'.$sx.' из заточение &quot;'.$uu['login'].'&quot;.';
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; выпустил'.$sx.' из &quot;<b>заточение</b>&quot; .';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',6)");
					$uer = 'Вы успешно выпустили из тюрьмы персонажа "'.$uu['login'].'" .';
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