<?
if(!defined('GAME'))
{
	die();
}

if($p['usealign7']==1 && $u->info['admin'] > 0)
{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if($u->testAlign( 7 , $uu['id'] ) == 0 ) {
				$uer = 'У персонажа стоит ограничение на смену склонности. Вы не можете выдать данную склонность!<br>';
			}elseif($uu['clan'] > 0) {
				$uer = 'Вы не можете использовать данное заклятие на персонажей с кланом.<br>';
			}elseif($uu['align'] > 0)
			{
				$uer = 'Вы не можете использовать данное заклятие на персонажей со склонностью.<br>';
			}else{
				$upd = mysql_query('UPDATE `users` SET `align` = "7" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				if($upd)
				{
					$u->insertAlign( 7 , $uu['id'] );
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = 'а';
					}
					$rtxt = '[img[items/palbuttonneutralsv3.gif]] '.$rang.' &quot;'.$u->info['cast_login'].'&quot; присвоил'.$sx.' нейтральную склонность персонажу &quot;'.$uu['login'].'&quot;';
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; приствоил'.$sx.' нейтральную склонность персонажу.';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$uer = 'Вы успешно присвоили нейтральную склонность персонажу "'.$uu['login'].'".';
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