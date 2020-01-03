<?
if(!defined('GAME'))
{
	die();
}
if($p['deletInfo']==1)
{
	$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
	if(isset($uu['id']))
	{
		if($uu['info_delete']==1 || $uu['info_delete']>time())
		{
			$upd = mysql_query('UPDATE `users` SET `info_delete` = 0 WHERE `id` = "'.$uu['id'].'" LIMIT 1');
			if($upd)
			{
				$sx = '';
				if($u->info['sex']==1)
				{
					$sx = 'а';
				}
				$rtxt = '[img[items/uncui.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; снял'.$sx.' заклятие обезличивание с персонажа &quot;'.$uu['login'].'&quot;';
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
				$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; снял'.$sx.' заклятие &quot;<b>обезличивание</b>&quot;.';
				mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
				$uer = 'Вы успешно сняли заклятие обезличивания с персонажа "'.$uu['login'].'".<br>';
			}else{
				$uer = 'Не удалось использовать данное заклятие';
			}
		}else{
			$uer = 'Персонаж не обезличен';
		}
	}else{
		$uer = 'Персонаж не найден в этом городе';
	}
}else{
	$uer = 'У Вас нет прав на использование данного заклятия';
}	
?>