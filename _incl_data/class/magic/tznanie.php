<?
if(!defined('GAME'))
{
	die();
}

if(isset($po['finish_file']) && $po['finish_file']=='tznanie')
{
	//Добавляем слот
	mysql_query('UPDATE `actions` SET `val` = "cast" WHERE (`vals` = "1044" OR `vals` = "1045" OR `vals` = "1046" OR `vals` = "1047") AND `val` != "cast" AND `vars` = "read"  AND `uid` = "'.$u->info['id'].'" LIMIT 1');
}else{
	$tst = $u->testAction('`uid` = "'.$u->info['id'].'" AND `time` > '.time().' AND `vars` = "read" LIMIT 1',1);
	if(isset($tst['id']))
	{
		//Уже что-то изучаем
		$u->error = 'Так не пойдет, вы уже что-то изучаете';
	}else{
		$tst = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "read" AND `vals` = "'.$itm['item_id'].'" LIMIT 1',1);
		if(isset($tst['id']))
		{
			$u->error = 'Вы уже изучили данное знание';
		}else{
			$fn = ''; $tom_iz = 0;
			if(($itm['item_id']>=1045 && $itm['item_id']<=1047) || ($itm['item_id']>=4812 && $itm['item_id']<=4813))
			{
				$tst2 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "read" AND `vals` = "'.($itm['item_id']-1).'" LIMIT 1',1);
				if(!isset($tst2['id']))
				{
					$tom_iz = 1;
				}
				unset($tst2);
			}
			/*
			if( $itm['item_id'] == 4811 ) {
				//5 том знаний, нужен 1047
				$tst2 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "read" AND `vals` = "1047" LIMIT 1',1);
				if(!isset($tst2['id']))
				{
					$tom_iz = 1;
				}
				unset($tst2);
			}
			*/
			if($tom_iz==0)
			{
				if(($itm['item_id']>=1044 && $itm['item_id']<=1047) || ($itm['item_id']>=4811 && $itm['item_id']<=4813))
				{
					mysql_query('UPDATE `stats` SET `priemslot` = `priemslot` + 1 WHERE `id` = "'.$itm['uid'].'" LIMIT 1');
					$fn .= 'finish_file=tznanie';
				}
				$ins = mysql_query('INSERT INTO `eff_users` (`overType`,`id_eff`,`uid`,`name`,`timeUse`,`data`,`img2`,`no_Ace`) VALUES ("8","2","'.$u->info['id'].'","Изучение: '.$itm['name'].'","'.(time()+$st['timeRead']).'","'.$fn.'","'.$itm['img'].'","1")');
				if($ins)
				{
					$u->error = 'Вы начали изучать &quot;'.$itm['name'].'&quot;. Время изучения составит '.$u->timeOut($st['timeRead']).'';
					$u->addAction(time()+$st['timeRead'],'read',$itm['item_id']);
					mysql_query('UPDATE `items_users` SET `iznosNOW` = `iznosNOW` + 1 WHERE `id` = '.$itm['id'].' LIMIT 1');
				}else{
					$u->error = 'Что-то здесь не так';
				}
			}else{
				$u->error = 'Требует изучения предыдущего тома';
			}
			unset($tom_iz);
		}
	}
}
?>