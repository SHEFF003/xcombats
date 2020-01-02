<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Хватка льда
*/
$pvr = array();
if( isset($pr_used_this) && isset($pr_moment) ) {
	//Каждый ход
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		global $u, $btl, $priem;	
		//
		//Параметры приема
		$pvr['used'] = 0;
		//		
		$uid1 = $btl->atacks[$id]['uid1'];
		$uid2 = $btl->atacks[$id]['uid2'];			
		if( $uid == $uid1 ) {
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
		}elseif( $uid == $uid2 ) {
			$a = 2;
			$b = 1;
			$u1 = ${'uid2'};
			$u2 = ${'uid1'};
		}
		if( $a > 0 ) {
			
			//Проверяем эффект
			$prv['j_priem'] = $btl->stats[$btl->uids[$u1]]['u_priem'][$j_id][0];
			$prv['priem_th'] = $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['id'];
			
			//действия
			$pvr['data'] = $priem->lookStatsArray($btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data']);
			$pvr['di'] = 0;
			$pvr['dc'] = count($pvr['data']['atgm']);
			$pvr['rd'] = 0;
			$pvr['redata'] = '';
			if( $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['hod'] < 5 ) {
				$pvr['redata'] = '|add_notactic=1|add_nousepriem=1';
				//
				$pvr['x5'] = mysql_fetch_array(mysql_query('SELECT `id`,`x`,`hod` FROM `eff_users` WHERE `uid` = "'.$u1.'" AND `v2` = 191 AND `delete` = 0 LIMIT 1'));
				if( !isset($pvr['x5']['id']) ) {
					$priem->addPriem($u1,191,'add_antishock=1',0,77,5,$u1,5,'иммунитеткошеломить');	
				}
				//
			}
			//
			$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data'] = $pvr['redata'];
			mysql_query('UPDATE `eff_users` SET `data` = "'.$pvr['redata'].'" WHERE `id` = "'.$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['id'].'" LIMIT 1');
			//
		}
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}else{
	$pvr['mg'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$btl->users[$btl->uids[$u->info['enemy']]]['id'].'" AND `bj` = "оледенение" AND `user_use` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
	if( isset($pvr['mg']['id']) ) {	
		//
		$prv['color2'] = '000000';
		$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
		$prv['text2'] = '{tm1} '.$prv['text'].'.';
		
		$pvr['x5'] = mysql_fetch_array(mysql_query('SELECT `id`,`x`,`hod` FROM `eff_users` WHERE `uid` = "'.$this->ue['id'].'" AND `v2` = 191 AND `delete` = 0 LIMIT 1'));
		if( !isset($pvr['x5']['id']) ) {
			$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
				'<font color^^^^#'.$prv['color2'].'>Хватка Льда</font>',
				$prv['text2'],
				($btl->hodID + 1)
			);
			
			//Добавляем прием
			//$this->addEffPr($pl,$id);
			$this->addPriem($this->ue['id'],$pl['id'],'',0,77,5,$u->info['id'],1,'хваткальда',0,0,1);
		}else{
			$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
				'<font color^^^^#'.$prv['color2'].'>Хватка Льда</font>',
				$prv['text2'].' (Цель полностью защищена от шока)',
				($btl->hodID + 1)
			);
		}
		//Отнимаем тактики
		//$this->mintr($pl);
	}else{
		echo '<font color=red><b>На персонаже нет оледенения (Вашего заклятия)</b></font>';
		$cup = true;
	}
}

unset($pvr);
?>