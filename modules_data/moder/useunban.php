<?
if(!defined('GAME'))
{
	die();
}
if($p['unbanned']==1)
{
	$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
	if(isset($uu['id']))
	{
		$upd = mysql_query('UPDATE `users` SET `banned` = "0" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
		if($upd)
		{
			$sx = '';
			if($u->info['sex']==1)
			{
				$sx = 'а';
			}
			if($uu['banned']>0)
			{
				mysql_query('UPDATE `users_delo` SET `hb` = "0" WHERE `uid` = "'.$uu['id'].'" AND `hb`!="0"');
				$rtxt = '[img[items/pal_button7.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; сн€л'.$sx.' закл€тие смерти с &quot;'.$uu['login'].'&quot;';
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
				$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; сн€л'.$sx.' закл€тие &quot;<b>смерти</b>&quot;.';
				mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
				$uer = '¬ы успешно сн€ли закл€тие смерти с персонажа "'.$uu['login'].'".<br>';
			}else{
				$uer = 'ѕерсонаж не заблокирован';
			}
		}else{
			$uer = 'Ќе удалось использовать данное закл€тие';
		}
	}else{
		$uer = 'ѕерсонаж не найден в этом городе';
	}
}else{
	$uer = '” ¬ас нет прав на использование данного закл€ти€';
}	
?>