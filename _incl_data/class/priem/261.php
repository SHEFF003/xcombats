<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Заряд: Поражение
*/
$pvr = array();
$pvr['mg'] = mysql_fetch_array(mysql_query('SELECT * FROM `eff_users` WHERE `uid` = "'.$btl->users[$btl->uids[$this->ue['id']]]['id'].'" AND `v2` = "260" AND `user_use` = "'.$u->info['id'].'" ORDER BY `id` DESC LIMIT 1'));
if( isset($pvr['mg']['id']) ) {	
	//Действие при клике
	//$pvr['hp'] = floor(144/3*$pvr['mg']['x']);
	/*$pvr['hp'] = 1;*/
			//
	$pvr['data'] = $this->lookStatsArray($pvr['mg']['data']);
			//
	/**/	
	if( $pvr['data']['add_mg2static_points'][0] < 1 ) {
		echo '<font color=red><b>Статика не собрала достаточного количества зарядов</b></font>';
		$cup = true;
	}else{
		$pvr['hp'] = floor($btl->stats[$btl->uids[$this->ue['id']]]['hpAll']-floor($btl->stats[$btl->uids[$this->ue['id']]]['hpNow']));
		//
		$pvr['hp'] = floor( ( $pvr['hp'] / 100 * rand( 1 , 3 ) ) * $pvr['data']['add_mg2static_points'][0] );
		
		$pvr['mx'] = array( 
			0 => 170,
			1 => 170,
			2 => 170,
			3 => 170,
			4 => 170,
			5 => 170,
			6 => 170,
			7 => 170,
			8 => 204,
			9 => 244,
			10 => 292,
			11 => 352,
			12 => 454,
			13 => 570,
			14 => 604,
			15 => 744,
			16 => 892,
			17 => 952,
			18 => 1054,
			19 => 1170,
			20 => 1292,
			21 => 1487
		);
		
		$pvr['mx'] = $pvr['mx'][$u->info['level']];
		
		//
		//$pvr['hp'] = floor($pvr['hp']/20*$u->stats['mg3']);//умелки
		//$pvr['hp'] = floor($pvr['hp']/200*$u->stats['s5']);//Интелект
		/*if( $btl->stats[$btl->uids[$u->info['enemy']]]['hpNow'] < floor($btl->stats[$btl->uids[$u->info['enemy']]]['hpAll']/100*30) ) {
			$pvr['hp'] = floor( $pvr['hp'] + ($pvr['hp']/100*(50*$pvr['mg']['x'])) );
		}*/

		$pvr['hp'] = $this->magatack( $u->info['id'], $this->ue['id'], $pvr['hp'], 'воздух', 1 );
		$pvr['promah_type'] = $pvr['hp'][3];
		$pvr['promah'] = $pvr['hp'][2];
		$pvr['krit'] = $pvr['hp'][1];
		$pvr['hp']   = $pvr['hp'][0];
		$pvr['hpSee'] = '--';
		$pvr['hpNow'] = floor($btl->stats[$btl->uids[$this->ue['id']]]['hpNow']);
		$pvr['hpAll'] = $btl->stats[$btl->uids[$this->ue['id']]]['hpAll'];
			
		if( $pvr['krit'] == true ) {
			if( $pvr['hp'] > $pvr['mx']*2 ) {
				$pvr['hp'] = $pvr['mx']*2;
			}
		}else{
			if( $pvr['hp'] > $pvr['mx'] ) {
				$pvr['hp'] = $pvr['mx'];
			}
		}
			
		//Используем проверку на урон приемов
		$pvr['hp'] = $btl->testYronPriem( $u->info['id'], $this->ue['id'], 21, $pvr['hp'], 6, true );
			
		$pvr['hpSee'] = '-'.$pvr['hp'];
		$pvr['hpNow'] -= $pvr['hp'];
		$btl->priemYronSave($u->info['id'],$this->ue['id'],$pvr['hp'],0);
			
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
				if(isset($btl->mcolor[$btl->mname['воздух']])) {
					$prv['color2'] = $btl->mcolor[$btl->mname['воздух']];
				}
				$prv['color'] = '000000';
				if(isset($btl->mncolor[$btl->mname['воздух']])) {
					$prv['color'] = $btl->mncolor[$btl->mname['воздух']];
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
		$btl->priemAddLog( $id, 1, 2, $u->info['id'], $this->ue['id'],
			'<font color^^^^#'.$prv['color2'].'>Заряд: Поражение</font>',
			$prv['text2'],
			($btl->hodID + 1)
		);
		
		//Добавляем прием
		//$this->addEffPr($pl,$id);
		//$this->addPriem($u->info['enemy'],$pl['id'],'atgm='.($pvr['hp']/16).'',2,77,4,$u->info['id'],3,'оледенение',0,0,1);
		
		//Удаляем оледенение
		$pvr['mg']['priem']['id'] = $pvr['mg']['id'];
		$btl->delPriem($pvr['mg'],$btl->users[$btl->uids[$this->ue['id']]],2);
		
		//Отнимаем тактики
		$this->mintr($pl);
	}
}else{
	echo '<font color=red><b>На персонаже нет Статики (Вашего заклятия)</b></font>';
	$cup = true;
}
unset($pvr);
?>