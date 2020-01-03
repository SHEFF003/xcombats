<?
if(!defined('GAME'))
{
	die();
}
if($p['m2']==1 || $p['citym2']==1)
{
	$tm = (int)$_POST['time'];
	if($tm!=30 && $tm!=60 && $tm!=180 && $tm!=360 && $tm!=720 && $tm!=1440 && $tm!=4320)
	{
		$uer = 'Неверно указано время наказания';
	}else{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if($uu['admin']>0 && $u->info['admin']==0)
			{
				$uer = 'Вы не можете накладывать заклятие форумного молчания на Ангелов';
			}elseif($uu['city']!=$u->info['city'] && $p['citym1']==0){
				$uer = 'Персонаж находится в другом городе';
			}elseif(floor($uu['align'])==$a && $uu['align']>$u->info['align'] && $u->info['admin']==0)
			{
				$uer = 'Вы не можете накладывать заклятие форумного молчания на старших по званию';
			}elseif($uu['id']==$u->info['id'] && $u->info['admin']==0){
				$uer = 'Вы не можете накладывать заклятие форумного молчания на самого себя';
			}else{
				$upd = mysql_query('UPDATE `users` SET `molch2` = "'.mysql_real_escape_string(time()+round($tm)*60).'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				if($upd)
				{
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = 'а';
					}
					$rtxt = '[img[items/sleepf.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; наложил'.$sx.' заклятие форумного молчания на &quot;'.$uu['login'].'&quot;, сроком '.$srok[$tm].'';
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; наложил'.$sx.' заклятие &quot;<b>форумного молчания</b>&quot; сроком '.$srok[$tm].'.';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$uer = 'Вы успешно наложили заклятие форумного молчания на персонажа "'.$uu['login'].'", сроком '.$srok[$tm].'.';
				}else{
					$uer = 'Не удалось использовать данное заклятие';
				}
			}
		}else{
			$uer = 'Персонаж не найден в этом городе';
		}
	}
}else{
	$uer = 'У Вас нет прав на использование данного заклятия';
}	
?>