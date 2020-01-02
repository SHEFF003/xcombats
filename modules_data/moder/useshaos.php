<?
if(!defined('GAME'))
{
	die();
}
if($p['shaos']==1)
{
	$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
	if(isset($uu['id']))
	{
		$upd = mysql_query('UPDATE `users` SET `haos` = "0",`align` = "0" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
		if($upd)
		{
			$sx = '';
			if($u->info['sex']==1)
			{
				$sx = 'а';
			}
			if($uu['admin']>0 && $u->info['admin']==0)
			{
				$uer = 'Вы не можете использовать данное заклятие на Ангелов.<br>';
			}elseif($uu['align']>1 && $uu['align']<2 && $u->info['align']>3 && $u->info['align']<4 && $u->info['admin']==0)
			{
				$uer = 'Вы не можете использовать данное заклятие на Паладинов.<br>';
			}elseif($uu['align']>3 && $uu['align']<4 && $u->info['align']>1 && $u->info['align']<2 && $u->info['admin']==0)
			{
				$uer = 'Вы не можете использовать данное заклятие на Тарманов.<br>';
			}elseif($uu['align']==2 || $uu['haos']>0)
			{
				$rtxt = '[img[items/pal_button5.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; выпустил'.$sx.' персонажа &quot;'.$uu['login'].'&quot; из хаоса';
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
				$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; выпустил'.$sx.' из &quot;<b>хаоса</b>&quot;.';
				mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
				$uer = 'Вы успешно выпустили персонажа "'.$uu['login'].'" из хаоса.<br>';
			}else{
				$uer = 'Персонаж не в хаосе';
			}
		}else{
			$uer = 'Не удалось использовать данное заклятие';
		}
	}else{
		$uer = 'Персонаж не найден в этом городе';
	}
}else{
	$uer = 'У Вас нет прав на использование данного заклятия';
}	
?>