<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Подлечить
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
			$pvr['hp'] = rand(15,25);
			$pvr['hp'] = $priem->magatack( $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['user_use'], $u1, $pvr['hp'], 'тьма', 1 );
			$pvr['promah_type'] = $pvr['hp'][3];
			$pvr['promah'] = $pvr['hp'][2];
			$pvr['krit'] = $pvr['hp'][1];
			$pvr['hp']   = $pvr['hp'][0];
			/*//
			$pvr['data'] = $priem->lookStatsArray($btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data']);
			$pvr['di'] = 0;
			$pvr['dc'] = count($pvr['data']['atgm']);
			$pvr['rd'] = 0;
			$pvr['redata'] = '';
			while( $pvr['di'] < 4 ) {
				if( isset($pvr['data']['atgm'][($pvr['dc']-$pvr['di'])]) ) {
					if( $pvr['rd'] < 3 ) {
						$pvr['hp'] += $pvr['data']['atgm'][($pvr['dc']-$pvr['di'])];
						$pvr['redata'] = 'atgm='.$pvr['data']['atgm'][($pvr['dc']-$pvr['di'])].'|'.$pvr['redata'];
						$pvr['rd']++;
					}
				}
				$pvr['di']++;
			}*/
			//
			$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data'] = $pvr['redata'];
			mysql_query('UPDATE `eff_users` SET `data` = "'.$pvr['redata'].'" WHERE `id` = "'.$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['id'].'" LIMIT 1');
			//			
				//$btl->stats[$btl->uids[$this->ue['id']]]['hpNow'] = $pvr['hpNow'];
				//$btl->users[$btl->uids[$this->ue['id']]]['hpNow'] = $pvr['hpNow'];				
				//
				$sp81 = mysql_query('SELECT `id` FROM `users` WHERE `battle` = "'.$u->info['battle'].'"');
				while( $pl81 = mysql_fetch_array($sp81) ) {
					$pl82 = mysql_fetch_array(mysql_query('SELECT `id`,`hpNow`,`team` FROM `stats` WHERE `id` = "'.$pl81['id'].'" LIMIT 1'));
					if($pl82['hpNow'] >= 1 && $btl->stats[$btl->uids[$pl82['id']]]['hpNow'] >= 1) {
						$rand_user[] = $pl82['id'];
					}
				}				
				$rand_user = $rand_user[rand(0,(count($rand_user)-1))];
				//
				$pvr['hp'] = rand(20,30);
				$pvr['hp'] = $btl->testYronPriem( $btl->stats[$btl->uids[$rand_user]]['effects'][$prv['j_priem']]['user_use'], $rand_user, 12, $pvr['hp'], -1, true , false , 0);
				//
				$pvr['rndu']['id'] = $rand_user;
				$pvr['color'] = '006699';
				$pvr['color2'] = '006699';
				$pvr['hpSee'] = '--';
				$pvr['hp'] = round($pvr['hp']/2);
				if( $pvr['hp'] > 0 ) {
					$pvr['hpSee'] = '+'.$pvr['hp'];
				}else{
					$pvr['hp'] = 0;
				}
				$btl->stats[$btl->uids[$pvr['rndu']['id']]]['hpNow'] += $pvr['hp'];
				$btl->users[$btl->uids[$pvr['rndu']['id']]]['hpNow'] += $pvr['hp'];
				mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$pvr['rndu']['id']]]['hpNow'].'" WHERE `id` = "'.$pvr['rndu']['id'].'" LIMIT 1');
				$pvr['hpNow'] = floor($btl->stats[$btl->uids[$pvr['rndu']['id']]]['hpNow']);
				$pvr['hpAll'] = $btl->stats[$btl->uids[$pvr['rndu']['id']]]['hpAll'];
				//
				//$btl->priemAddLogFast( $pvr['rndu']['id'], 0, "<font color^^^^#".$pvr['color2'].">Темное ранение</font>",
				//	'{tm1} ... <font Color=#'.$pvr['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
				//1, time() );
				//
				$prv['text2'] = '{tm1} {u1} подвергся действию &quot;<b>Подлечить</b>&quot; и восстановил здоровье. <font Color=#'.$pvr['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
				//
				$btl->priemAddLog( $id, 1, 2, $pvr['rndu']['id'], $u1,
					'<font color^^^^#'.$prv['color2'].'>Подлечить</font>',
					$prv['text2'],
					($btl->hodID)
				);				
				//
			
			//
			
		}
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}else{
	
	/*$pvr['uen'] = $u->info['enemy'];
	//
	$pvr['uid'] = $u->info['enemy'];
	$pvr['hp'] = floor(112);
	$pvr['hp'] = $this->magatack( $u->info['id'], $pvr['uid'], $pvr['hp'], 'вода', 0 );
	$pvr['promah_type'] = $pvr['hp'][3];
	$pvr['promah'] = $pvr['hp'][2];
	$pvr['krit'] = $pvr['hp'][1];
	$pvr['hp']   = $pvr['hp'][0];
	//
	$prv['color2'] = '000000';
	$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
	$prv['text2'] = '{tm1} '.$prv['text'].'.';
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $pvr['uid'],
		'<font color^^^^#'.$prv['color2'].'>Ядовитое Облако [11]</font>',
		$prv['text2'],
		($btl->hodID + 1)
	);
			
	//Добавляем прием
	//$this->addEffPr($pl,$id);
	$this->addPriem($pvr['uid'],$pl['id'],'atgm='.floor($pvr['hp']/5).'',0,77,5,$u->info['id'],1,'ядовитоеоблако',0,0,1);*/
			
	//Отнимаем тактики
	//$this->mintr($pl);
	//
	//
	$pvr['rx'] = rand(30,50);
	$pvr['rx'] = floor($pvr['rx']/10);
	$pvr['xx'] = 0;
	$pvr['ix'] = 0;
	while( $pvr['ix'] < rand(4, 6)) {
		if( $btl->stats[$pvr['ix']]['hpNow'] > 0 && $btl->users[$pvr['ix']]['team'] != $u->info['team'] && $pvr['xx'] < $pvr['rx'] && $pvr['uen'] != $btl->users[$pvr['ix']]['id'] ) {
			$pvr['dxx']++;
		}
		$pvr['ix']++;
	}
	$pvr['rx'] = $pvr['dxx'];
	$pvr['xx'] = 0;
	$pvr['ix'] = 0;
	while( $pvr['ix'] < rand(4, 6)) {
		if( $btl->stats[$pvr['ix']]['hpNow'] > 0 && $btl->users[$pvr['ix']]['team'] != $u->info['team'] && $pvr['xx'] < $pvr['rx'] && $pvr['uen'] != $btl->users[$pvr['ix']]['id'] ) {
			//
			$pvr['uid'] = $btl->users[$pvr['ix']]['id'];
			$pvr['hp'] = floor(112/$pvr['dxx']);
			$pvr['hp'] = $this->magatack( $u->info['id'], $pvr['uid'], $pvr['hp'], 'вода', 1 );
			$pvr['promah_type'] = $pvr['hp'][3];
			$pvr['promah'] = $pvr['hp'][2];
			$pvr['krit'] = $pvr['hp'][1];
			$pvr['hp']   = $pvr['hp'][0];
			//
			$prv['color2'] = '000000';
			$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
			$prv['text2'] = '{tm1} '.$prv['text'].'.';
			$btl->priemAddLog( $id, 1, 2, $u->info['id'], $pvr['uid'],
				'<font color^^^^#'.$prv['color2'].'>Темное ранение</font>',
				$prv['text2'],
				($btl->hodID + 1)
			);
			
			//Добавляем прием
			//$this->addEffPr($pl,$id);
			$this->addPriem($pvr['uid'],$pl['id'],'atgm='.floor($pvr['hp']/5).'',0,77,5,$u->info['id'],1,'Темное ранение',0,0,1);
			
			//Отнимаем тактики
			//$this->mintr($pl);
			//
			$pvr['xx']++;
		}
		$pvr['ix']++;
	}

}

unset($pvr);
?>