<?
if(!defined('GAME'))
{
	die();
}
if($u->info['admin']>0 || ($u->info['align']>=3 && $u->info['align']<4))
{
	$uu = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `login` = "'.mysql_real_escape_string($_POST['logingo']).'" LIMIT 1'));
	$ust = $u->getStats($uu['id'],0);
	if(isset($uu['id']))
	{
		if($uu['id'] == $u->info['id'])
		{
			$uer = 'Вы не можете кусать самого себя';
		}elseif($u->info['battle']>0)
		{
			$uer = 'Вы не можете кусать в бою';
		}elseif($ust['hpNow']<($ust['hpAll']/100*15))
		{
			$uer = 'Вы не можете укусить этого персонажа, жертва слишком слаба';
		}elseif($uu['level']>$u->info['level'])
		{
			$uer = 'Вы не можете кусать персонажей старше вас по уровню';
		}elseif(date('H',time())>6 && date('H',time())<21 && $u->info['admin']==0)
		{
			$uer = 'Вампиры не могут кусаться днем';
		}elseif($u->stats['hpNow'] >= ($u->stats['hpAll']/100*67) && $u->info['admin']==0)
		{
			$uer = 'Вы не нужнаетесь в этом, ваше здоровье восстановится само ...';
		}elseif(floor($uu['align'])==3 && $u->info['admin']==0)
		{
			$uer = 'Вы не можете кусать темных';
		}elseif($uu['online']<time()-120)
		{
			$uer = 'Персонаж сейчас оффлайн';
		}elseif($uu['room']!=$u->info['room'])
		{
			$uer = 'Вы должны находится в одной локации с жертвой';
		}elseif($uu['battle']>0)
		{
			$uer = 'Персонаж находится в бою';
		}else{
			$sx = '';
			if($u->info['sex']==1)
			{
				$sx = 'а';
			}
			$itm1 = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `uid` = "'.$uu['id'].'" AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 AND `iznosNow` >= 1 AND `item_id` = 1164 LIMIT 1'));
			if(isset($itm1['id']))
			{
				$uer = 'Не удалось выпить энергию "'.$uu['login'].'", у '.$uu.' был при себе &quot;'.$itm1['name'].'&quot;.<br>';
			}else{
				$itm2 = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `uid` = "'.$uu['id'].'" AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 AND `iznosNow` >= 1 AND `item_id` = 1163 LIMIT 1'));
				if(isset($itm2['id']))
				{
					$sx = 'него';
					if($uu['sex']==1)
					{
						$sx = 'неё';
					}
					$uer = 'Не удалось выпить энергию "'.$uu['login'].'", у '.$uu.' был при себе &quot;'.$itm2['name'].'&quot;.<br>';
					$rtxt = '[img[items/chesnok2.gif]] Вампир &quot;'.$u->info['login'].'&quot; неудачно укусил'.$sx.' т.к. у &quot;'.$uu['login'].'&quot; был при себе чеснок';
				}else{
					$rtxt = '[img[items/vampir.gif]] Вампир &quot;'.$u->info['login'].'&quot; укусил'.$sx.' и выпил'.$sx.' всю жизненную энергию персонажа &quot;'.$uu['login'].'&quot;';
					$u->stats['hpNow'] += $ust['hpNow'];
					if($u->stats['hpNow']>$u->stats['hpAll'])
					{
						$u->stats['hpNow'] = $u->stats['hpAll'];	
					}
					mysql_query('UPDATE `stats` SET `hpNow` = "'.$u->stats['hpAll'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					mysql_query('UPDATE `stats` SET `hpNow` = "0" WHERE `id` = "'.$uu['id'].'" LIMIT 1');
					mysql_query("INSERT INTO `chat` (`city`,`room`,`login`,`to`,`text`,`time`,`type`,`toChat`,`typeTime`) VALUES ('".$u->info['city']."','".$u->info['room']."','','','".$rtxt."','".time()."','11','0','1')");				
					$uer = 'Вы успешно выпили всю кровь у персонажа "'.$uu['login'].'".<br>';
				}
			}
			unset($itm1,$itm2);
		}
	}else{
		$uer = 'Персонаж не найден в этом городе';
	}
}else{
	$uer = 'У Вас нет прав на использование данного навыка';
}	
?>