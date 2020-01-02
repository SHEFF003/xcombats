<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Элементарный заряд
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
			
			$pvr['ijk'] = 1;
			
			while( $pvr['ijk'] <= 4 ) {
				
				//Проверяем эффект
				$prv['j_priem'] = $btl->stats[$btl->uids[$u1]]['u_priem'][$j_id][0];
				$prv['priem_th'] = $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['id'];
				
				//действия
				$pvr['hp'] = rand(15,25);
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
				$pvr['hp'] = rand(15,25);
				if( $pvr['ijk'] == 1 ) {
					$pvr['mgtype'] = 5;
					//
					$pvr['color'] = '006699';
					$pvr['color2'] = '006699';
					//
					$pvr['mgtypename'] = 'огонь';
					$pvr['mgtypename2'] = 'Огонь';
				}elseif( $pvr['ijk'] == 2 ) {
					$pvr['mgtype'] = 6;
					//
					$pvr['color'] = '006699';
					$pvr['color2'] = '006699';
					//
					$pvr['mgtypename'] = 'воздух';
					$pvr['mgtypename2'] = 'Воздух';
				}elseif( $pvr['ijk'] == 3 ) {
					$pvr['mgtype'] = 7;
					//
					$pvr['color'] = '006699';
					$pvr['color2'] = '006699';
					//
					$pvr['mgtypename'] = 'вода';
					$pvr['mgtypename2'] = 'Вода';
				}elseif( $pvr['ijk'] == 4 ) {
					$pvr['mgtype'] = 8;
					//
					$pvr['color'] = '006699';
					$pvr['color2'] = '006699';
					//
					$pvr['mgtypename'] = 'земля';
					$pvr['mgtypename2'] = 'Земля';
				}
				$pvr['hp'] = $priem->magatack( $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['user_use'], $u1, $pvr['hp'], $pvr['mgtypename'], 0 );
				$pvr['promah_type'] = $pvr['hp'][3];
				$pvr['promah'] = $pvr['hp'][2];
				$pvr['krit'] = $pvr['hp'][1];
				$pvr['hp']   = $pvr['hp'][0];
				//
				$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data'] = $pvr['redata'];
				mysql_query('UPDATE `eff_users` SET `data` = "'.$pvr['redata'].'" WHERE `id` = "'.$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['id'].'" LIMIT 1');
				//
				$pvr['hp'] = floor($pvr['hp']);
				/*$pvr['hp'] = $priem->magatack( $u2, $u1, $pvr['hp'], 'вода', 0 );
				$pvr['promah_type'] = $pvr['hp'][3];
				$pvr['promah'] = $pvr['hp'][2];
				$pvr['krit'] = $pvr['hp'][1];
				$pvr['hp']   = $pvr['hp'][0];*/
				$pvr['hpSee'] = '--';
				$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u1]]['hpNow']);
				$pvr['hpAll'] = $btl->stats[$btl->uids[$u1]]['hpAll'];
				
				//Используем проверку на урон приемов
				$pvr['hp'] = $btl->testYronPriem( $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['user_use'], $u1, 12, $pvr['hp'], $pvr['mgtype'], true , false , 1);
				
				$pvr['hpSee'] = '-'.$pvr['hp'];
				$pvr['hpNow'] -= $pvr['hp'];
				$btl->priemYronSave($btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['user_use'],$u1,$pvr['hp'],0);
					
				if( $pvr['hpNow'] > $pvr['hpAll'] ) {
					$pvr['hpNow'] = $pvr['hpAll'];
				}elseif( $pvr['hpNow'] < 0 ) {
					$pvr['hpNow'] = 0;
				}
				
				$btl->stats[$btl->uids[$u1]]['hpNow'] = $pvr['hpNow'];	
					
				mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u1]]['hpNow'].'" WHERE `id` = "'.$u1.'" LIMIT 1');
				//$prv['text'] = $btl->addlt(1 , 19 , $btl->users[$btl->uids[$u2]]['sex'] , NULL);
				$prv['text'] = '{u2} утратил здоровье от &quot;{pr}<b>: '.$pvr['mgtypename2'].'</b>&quot;';
				
				//Цвет приема
				if( $pvr['promah'] == false ) {
					if( $pvr['krit'] == false ) {
						//$pvr['color2'] = '000000';
						//$pvr['color'] = '008000';
					}else{
						$pvr['color2'] = 'FF0000';
						$pvr['color'] = 'FF0000';
					}
				}else{
					$pvr['color2'] = '909090';
					$pvr['color'] = '909090';
				}
				
				$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$pvr['color'].'><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
				if( $pvr['promah_type'] == 2 ) {
					//$prv['text'] = $btl->addlt(1 , 20 , $btl->users[$btl->uids[$u2]]['sex'] , NULL);
					$prv['text2'] = '{tm1} '.$prv['text'].'. <font Color='.$pvr['color'].'><b>--</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']';
				}
				$prv['xx'] = '';
				if( $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['x'] > 1 ) {
					//$prv['xx'] = ' x'.$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['x'].'';
				}
				$btl->priemAddLog( $id, 1, 2, $u2, $u1,
					'<font color^^^^#'.$pvr['color2'].'>Элементарный заряд</font>',
					$prv['text2'],
					($btl->hodID)
				);
				$pvr['ijk']++;
			}
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
				'<font color^^^^#'.$prv['color2'].'>Элементарный заряд</font>',
				$prv['text2'],
				($btl->hodID + 1)
			);
			
			//Добавляем прием
			//$this->addEffPr($pl,$id);
			$this->addPriem($pvr['uid'],$pl['id'],'atgm='.floor($pvr['hp']/5).'',0,77,5,$u->info['id'],1,'ядовитые язвы',0,0,1);
			
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