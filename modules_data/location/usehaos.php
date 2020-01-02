<?
if(!defined('GAME'))
{
	die();
}
if($p['haos']==1)
{
	$tm = (int)$_POST['time'];
	$tmban = array(7=>'одна неделя',14=>'две недели',30=>'один месяц',60=>'два месяца',1=>'бессрочно');
	if($tm!=7 && $tm!=14 && $tm!=30 && $tm!=60 && ($tm!=1 || ($p['haosInf']==0 && $tm==1)))
	{
		$uer = 'Неверно указано время наказания';
	}else{
		$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
		if(isset($uu['id']))
		{
			if($uu['align']>1 && $uu['align']<2 && $u->info['admin']==0)
			{
				$uer = 'Вы не можете отправлять Паладина в хаос';
			}elseif($uu['align']>3 && $uu['align']<4 && $u->info['admin']==0)
			{
				$uer = 'Вы не можете отправлять Тармана в хаос';
			}elseif($uu['align']==2)
			{
				$uer = 'Персонаж был ранее отправлен в хаос';
			}elseif($uu['admin']>0 && $u->info['admin']==0)
			{
				$uer = 'Вы не можете отправлять Ангелов в хаос';
			}elseif($uu['city']!=$u->info['city'] && $p['citym1']==0){
				$uer = 'Персонаж находится в другом городе';
			}elseif(floor($uu['align'])==$a && $uu['align']>$u->info['align'] && $u->info['admin']==0)
			{
				$uer = 'Вы не можете накладывать заклятие на старших по званию';
			}elseif($uu['id']==$u->info['id'] && $u->info['admin']==0){
				$uer = 'Вы не можете накладывать заклятие на самого себя';
			}else{
				$th = time()+($tm*24*60*60);
				if($tm==1)
				{
					$th = 1;
				}
				$upd = mysql_query('UPDATE `users` SET `align` = "2",`clan` = "0",`haos` = "'.mysql_real_escape_string($th).'" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
				if($upd)
				{
					$sx = '';
					if($u->info['sex']==1)
					{
						$sx = 'а';
					}
					mysql_query('UPDATE `users_delo` SET `hb` = "0" WHERE `uid` = "'.$uu['id'].'" AND `hb`!="0"');
					$rtxt = '[img[items/pal_button4.gif]] '.$rang.' &quot;'.$u->info['login'].'&quot; отправил'.$sx.' персонажа &quot;'.$uu['login'].'&quot; в хаос на срок: '.$tmban[$tm].'';
					mysql_query("INSERT INTO `chat` (`new`,`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES (1,'".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','6','0','1')");				
					$rtxt = $rang.' &quot;'.$u->info['login'].'&quot; отправил'.$sx.' в &quot;<b>хаос</b>&quot; на срок: '.$tmban[$tm].'.';
					mysql_query("INSERT INTO `users_delo` (`uid`,`ip`,`city`,`time`,`text`,`login`,`type`) VALUES ('".$uu['id']."','".$_SERVER['REMOTE_ADDR']."','".$u->info['city']."','".time()."','".$rtxt."','".$u->info['login']."',0)");
					$uer = 'Вы успешно отправили персонажа "'.$uu['login'].'" в хаос на срок: '.$tmban[$tm].'.';
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