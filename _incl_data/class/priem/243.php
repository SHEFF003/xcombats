<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Пылающий Взрыв
*/
$pvr = array();
$pvr['mg'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$btl->users[$btl->uids[$this->ue['id']]]['id'].'" AND `bj` = "пожирающеепламя" AND `user_use` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
if( isset($pvr['mg']['id']) ) {	
	//Действие при клике
	$pvr['hp'] = 0;
	$pvr['data'] = $this->lookStatsArray($pvr['mg']['data']);
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
	}
	
	$pvr['hp23'] = $pvr['hp'];
	
	$pvr['hp'] = round($pvr['hp']*5);
	$pvr['hp'] = round($pvr['hp']/100*33);
	
	$pvr['hp24'] = $pvr['hp'];
	
	//$pvr['hp'] = floor($pvr['hp']/20*$u->stats['mg3']);//умелки
	//$pvr['hp'] = floor($pvr['hp']/200*$u->stats['s5']);//Интелект
	/*if( $btl->stats[$btl->uids[$this->ue['id']]]['hpNow'] < floor($btl->stats[$btl->uids[$this->ue['id']]]['hpAll']/100*33) ) {
		$pvr['hp'] = floor( $pvr['hp'] + ($pvr['hp']/100*(33*$pvr['mg']['x'])) );
	}*/
	
	/*
	$pvr['hp_test'] = $this->magatack( $u->info['id'], $this->ue['id'], $pvr['hp'], 'огонь', 1 );
	$pvr['promah_type'] = $pvr['hp_test'][3];
	$pvr['promah'] = $pvr['hp_test'][2];
	$pvr['krit'] = $pvr['hp_test'][1];
	*/
	//$pvr['hp']   = $pvr['hp_test'][0];
	

	$pvr['hpSee'] = '--';
	$pvr['hpNow'] = floor($btl->stats[$btl->uids[$this->ue['id']]]['hpNow']);
	$pvr['hpAll'] = $btl->stats[$btl->uids[$this->ue['id']]]['hpAll'];
		
	//Используем проверку на урон приемов
	$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $this->ue['id'], 21, $pvr['hp'], 7, true );
	
	$pvr['hp25'] = $pvr['hp'];
		
	$pvr['hpSee'] = '-'.$pvr['hp'];
	$pvr['hpNow'] -= $pvr['hp'];
	$btl->priemYronSave($u->info['id'],$this->ue['id'],$pvr['hp'],0);
		
	$pvr['hp26'] = $pvr['hp'];
		
	if( $pvr['hpNow'] > $pvr['hpAll'] ) {
		$pvr['hpNow'] = $pvr['hpAll'];
	}elseif( $pvr['hpNow'] < 0 ) {
		$pvr['hpNow'] = 0;
	}
		
	$btl->stats[$btl->uids[$this->ue['id']]]['hpNow'] = $pvr['hpNow'];
		
	mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$this->ue['id']]]['hpNow'].'" WHERE `id` = "'.$this->ue['id'].'" LIMIT 1');
		
	$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
	
	//Цвет приема
	if( $pvr['promah'] == false ) {
		if( $pvr['krit'] == false ) {
			$prv['color2'] = '006699';
			if(isset($btl->mcolor[$btl->mname['огонь']])) {
				$prv['color2'] = $btl->mcolor[$btl->mname['огонь']];
			}
			$prv['color'] = '000000';
			if(isset($btl->mncolor[$btl->mname['огонь']])) {
				$prv['color'] = $btl->mncolor[$btl->mname['огонь']];
			}
		}else{
			$prv['color2'] = 'FF0000';
			$prv['color'] = 'FF0000';
		}
	}else{
		$prv['color2'] = '909090';
		$prv['color'] = '909090';
	}
	
	$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].'] (Базовый урон: '.$pvr['hp23'].'/'.$pvr['hp24'].'/'.$pvr['hp25'].'/'.$pvr['hp26'].' ед.)';
	if( $pvr['promah_type'] == 2 ) {
		$prv['text'] = $btl->addlt(1 , 20 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
		$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>--</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
	}
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
		'<font color^^^^#'.$prv['color2'].'>Пылающий Взрыв</font>',
		$prv['text2'],
		($btl->hodID + 1)
	);
	
		//
		//Действие при клике	
		//$pvr['rx'] = rand(80,80);
		//$pvr['rx'] = floor($pvr['rx']/10);
		$pvr['uen'] = $this->ue['id'];
		$pvr['rx'] = 4;
		$pvr['xx'] = 0;
		$pvr['ix'] = 0;
		while( $pvr['ix'] < count($btl->users) ) {
			if( $btl->stats[$pvr['ix']]['hpNow'] > 0 && $btl->users[$pvr['ix']]['team'] != $u->info['team'] && $pvr['xx'] < $pvr['rx'] && $pvr['uen'] != $btl->users[$pvr['ix']]['id'] ) {
				//
				$pvr['uid'] = $btl->users[$pvr['ix']]['id'];
				$pvr['hp'] = floor($pvr['hp']);
				/*
				$pvr['hp'] = $this->magatack( $u->info['id'], $pvr['uid'], $pvr['hp'], 'огонь', 0 );
				$pvr['promah_type'] = $pvr['hp'][3];
				$pvr['promah'] = $pvr['hp'][2];
				$pvr['krit'] = $pvr['hp'][1];
				$pvr['hp']   = $pvr['hp'][0];
				*/
				$pvr['hpSee'] = '--';
				$pvr['hpNow'] = floor($btl->stats[$btl->uids[$pvr['uid']]]['hpNow']);
				$pvr['hpAll'] = $btl->stats[$btl->uids[$pvr['uid']]]['hpAll'];
					
				//Используем проверку на урон приемов
				$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $pvr['uid'], 21, $pvr['hp'], 6, true );
					
				$pvr['hpSee'] = '-'.$pvr['hp'];
				$pvr['hpNow'] -= $pvr['hp'];
				$btl->priemYronSave($u->info['id'],$pvr['uid'],$pvr['hp'],0);
				
				$this->mg2static_points( $pvr['uid'] , $btl->stats[$btl->uids[$pvr['uid']]] );
					
				if( $pvr['hpNow'] > $pvr['hpAll'] ) {
					$pvr['hpNow'] = $pvr['hpAll'];
				}elseif( $pvr['hpNow'] < 0 ) {
					$pvr['hpNow'] = 0;
				}
					
				$btl->stats[$btl->uids[$pvr['uid']]]['hpNow'] = $pvr['hpNow'];
					
				mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$pvr['uid']]]['hpNow'].'" WHERE `id` = "'.$pvr['uid'].'" LIMIT 1');
				
				//
				if( $pvr['promah'] == false ) {
					if( $pvr['krit'] == false ) {
						$prv['color2'] = '006699';
						if(isset($btl->mcolor[$btl->mname['огонь']])) {
							$prv['color2'] = $btl->mcolor[$btl->mname['огонь']];
						}
						$prv['color'] = '000000';
						if(isset($btl->mncolor[$btl->mname['огонь']])) {
							$prv['color'] = $btl->mncolor[$btl->mname['огонь']];
						}
					}else{
						$prv['color2'] = 'FF0000';
						$prv['color'] = 'FF0000';
					}
				}else{
					$prv['color2'] = '909090';
					$prv['color'] = '909090';
				}
				//
				
				//
				//$prv['color2'] = $btl->mcolor[$btl->mname['земля']];
				$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);	
				if( $pvr['promah_type'] == 2 ) {
					$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>--</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
				}else{
					$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
				}
				$btl->priemAddLog( $id, 1, 2, $u->info['id'], $pvr['uid'],
					'<font color^^^^#'.$prv['color2'].'>Пылающий Взрыв</font>',
					$prv['text2'],
					($btl->hodID + 1)
				);
				
				//Добавляем прием
				//$this->addEffPr($pl,$id);
				//$this->addPriem($pvr['uid'],$pl['id'],'atgm='.floor($pvr['hp']/5).'',0,77,5,$u->info['id'],1,'ядовитоеоблако',0,0,1);
				
				//Отнимаем тактики
				//$this->mintr($pl);
				//
				$pvr['xx']++;
			}
			$pvr['ix']++;
		}
	
	//Добавляем прием
	//$this->addEffPr($pl,$id);
	//$this->addPriem($this->ue['id'],242,'add_notactic=1|add_nousepriem=1',2,77,2,$u->info['id'],3,'пылающийужас',0,0,1);
	
	//Удаляем оледенение
	$pvr['mg']['priem']['id'] = $pvr['mg']['id'];
	$btl->delPriem($pvr['mg'],$btl->users[$btl->uids[$this->ue['id']]],2);
	
	//Отнимаем тактики
	$this->mintr($pl);
}else{
	echo '<font color=red><b>На персонаже нет пожирающего пламени (Вашего заклятия)</b></font>';
	$cup = true;
}
unset($pvr);
?>