<?
if(!defined('GAME'))
{
	die();
}

if($p['useunalign']==1)
{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if($uu['align']>1 && $uu['align']<2 && $u->info['admin']==0)
			{
				$uer = 'Вы не можете использовать данное заклятие на Паладинов.<br>';
			}elseif($uu['align']>3 && $uu['align']<4 && $u->info['admin']==0)
			{
				$uer = 'Вы не можете использовать данное заклятие на Тарманов.<br>';
			}elseif($uu['admin']>0 && $u->info['admin']==0)
			{
				$uer = 'Вы не можете накладывать снятие запрета передач на Ангелов';
			}elseif($uu['city']!=$u->info['city'] && $p['citym1']==0){
				$uer = 'Персонаж находится в другом городе';
			}elseif($uu['id']==$u->info['id'] && $u->info['admin']==0){
				$uer = 'Вы не можете снять склонность с самого себя';
			}else{
				$upd = mysql_query('UPDATE `users` SET `align` = "0",`clan` = "0" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				if($upd)
				{
					$u->deleteAlign( $uu['align'] , $uu['id'] );
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = 'а';
					}
					$rtxt = '[img[items/palbuttondarkhc1.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; снял'.$sx.' склонность&frasl;клан с персонажа &quot;'.$uu['login'].'&quot;';
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; снял'.$sx.' склонность с персонажа.';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$uer = 'Вы успешно сняли склонность с персонажа "'.$uu['login'].'".';
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