<?
if(!defined('GAME'))
{
	die();
}
if($p['priemIskl']==1)
{
	$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
	if(isset($uu['id']))
	{
		if( ( $uu['align']<=1 || $uu['align']>=2 ) && $a == 1 )
		{
			$uer = 'Персонаж не является сотрудником Ордена Света';
		}elseif( ( $uu['align']<=3 || $uu['align']>=4 ) && $a == 3 )
		{
			$uer = 'Персонаж не является сотрудником Армады';
		}else{
			$upd = mysql_query('UPDATE `users` SET `align` = "0" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
			if($upd)
			{
				$sx = '';
				if($u->info['sex']==1)
				{
					$sx = 'а';
				}
				
				if($a == 1) {
					$zvna = 'паладина';
					$zvna2 = 'Паладина';
					$zvimg = '';
				}elseif($a == 3) {
					$zvna = 'тармана';
					$zvna2 = 'Тармана';
					$zvimg = 't';
				}
				
				mysql_query('UPDATE `users_delo` SET `hb` = "0" WHERE `uid` = "'.$uu['id'].'" AND `hb`!="0"');
				$rtxt = '[img[items/unpal'.$zvimg.'.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; лишил'.$sx.' &quot;'.$uu['login'].'&quot; звания &quot;'.$zvna2.'&quot;';
				mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
				$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; лишил'.$sx.' звания &quot;'.$zvna2.'&quot;.';
				mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
				$uer = 'Вы успешно сняли знак '.$zvna.' с персонажа "'.$uu['login'].'".<br>';
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