<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Оледенение: Разбить!
*/
$pvr = array();
$pvr['mg'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$btl->users[$btl->uids[$u->info['enemy']]]['id'].'" AND `bj` = "оледенение" AND `user_use` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
if( isset($pvr['mg']['id']) ) {	
	//Действие при клике
	//$pvr['hp'] = floor(144/3*$pvr['mg']['x']);
	$pvr['hp'] = 1;
			//
			$pvr['data'] = $this->lookStatsArray($pvr['mg']['data']);
			$pvr['di'] = 0;
			$pvr['dc'] = count($pvr['data']['atgm']);
			$pvr['rd'] = 0;
			while( $pvr['di'] < 4 ) {
				if( isset($pvr['data']['atgm'][($pvr['dc']-$pvr['di'])]) ) {
					if( $pvr['rd'] < 3 ) {
						$pvr['hp'] += $pvr['data']['atgm'][($pvr['dc']-$pvr['di'])];
						$pvr['rd']++;
					}
				}
				$pvr['di']++;
			}
			//
	
	
	//$pvr['hp'] = 444;
	$pvr['hp'] = $pvr['hp']*3;
	$pvr['hp'] += floor($pvr['hp']/100*$u->stats['mg3']);//умелки
	$pvr['hp'] += floor($pvr['hp']/100*($u->stats['s5']*0.15));//Интелект , было 0.33
	if( $btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'] < floor($btl->stats[$btl->uids[$u->info['enemy']]]['hpAll']/100*33) ) {
		$pvr['hp'] = floor( $pvr['hp'] + ($pvr['hp']/100*(50*$pvr['mg']['x'])) );
	}
	$pvr['hp'] = $this->magatack( $u->info['id'], $u->info['enemy'], $pvr['hp'], 'вода', 1 );
	$pvr['promah_type'] = $pvr['hp'][3];
	$pvr['promah'] = $pvr['hp'][2];
	$pvr['krit'] = $pvr['hp'][1];
	$pvr['hp']   = $pvr['hp'][0];
	$pvr['hpSee'] = '--';
	$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u->info['enemy']]]['hpNow']);
	$pvr['hpAll'] = $btl->stats[$btl->uids[$u->info['enemy']]]['hpAll'];
		
	//Используем проверку на урон приемов
	$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $u->info['enemy'], 21, $pvr['hp'], 7, true );
		
	$pvr['hpSee'] = '-'.$pvr['hp'];
	$pvr['hpNow'] -= $pvr['hp'];
	$btl->priemYronSave($u->info['id'],$u->info['enemy'],$pvr['hp'],0);
		
	if( $pvr['hpNow'] > $pvr['hpAll'] ) {
		$pvr['hpNow'] = $pvr['hpAll'];
	}elseif( $pvr['hpNow'] < 0 ) {
		$pvr['hpNow'] = 0;
	}
		
	$btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'] = $pvr['hpNow'];
		
	mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'].'" WHERE `id` = "'.$u->info['enemy'].'" LIMIT 1');
		
	$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
	
	//Цвет приема
	if( $pvr['promah'] == false ) {
		if( $pvr['krit'] == false ) {
			$prv['color2'] = '006699';
			if(isset($btl->mcolor[$btl->mname['вода']])) {
				$prv['color2'] = $btl->mcolor[$btl->mname['вода']];
			}
			$prv['color'] = '000000';
			if(isset($btl->mncolor[$btl->mname['вода']])) {
				$prv['color'] = $btl->mncolor[$btl->mname['вода']];
			}
		}else{
			$prv['color2'] = 'FF0000';
			$prv['color'] = 'FF0000';
		}
	}else{
		$prv['color2'] = '909090';
		$prv['color'] = '909090';
	}
	
	$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
	if( $pvr['promah_type'] == 2 ) {
		$prv['text'] = $btl->addlt(1 , 20 , $btl->users[$btl->uids[$u->info['id']]]['sex'] , NULL);
		$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$prv['color'].'><b>--</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
	}
	$btl->priemAddLog( $id, 1, 2, $u->info['id'], $u->info['enemy'],
		'<font color^^^^#'.$prv['color2'].'>Оледенение: Разбить!</font>',
		$prv['text2'],
		($btl->hodID + 1)
	);
	
	//Добавляем прием
	//$this->addEffPr($pl,$id);
	//$this->addPriem($u->info['enemy'],$pl['id'],'atgm='.($pvr['hp']/16).'',2,77,4,$u->info['id'],3,'оледенение',0,0,1);
	
	//Удаляем оледенение
	$pvr['mg']['priem']['id'] = $pvr['mg']['id'];
	$btl->delPriem($pvr['mg'],$btl->users[$btl->uids[$u->info['enemy']]],2);
	
	//Отнимаем тактики
	$this->mintr($pl);
}else{
	echo '<font color=red><b>На персонаже нет оледенения (Вашего заклятия)</b></font>';
	$cup = true;
}
unset($pvr);
?>