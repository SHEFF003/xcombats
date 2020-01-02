<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Хлебнуть крови
	Следующий критический удар и 2 удара за ним восстанавливают здоровье
*/
$pvr = array();
if( isset($pr_tested_this) ) {
		$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		global $u, $btl;	
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
		//
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}elseif( isset($pr_used_this) && !isset($this->info['id'])  ) { 

}elseif( isset($pr_used_this)  ) { 
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		global $u, $btl;	
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
			$prv['j_priem'] = $btl->stats[$btl->uids[$u1]]['u_priem'][$j_id][0];
			$prv['priem_th'] = $btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['id'];
			$prv['data_re'] = $u->lookStats($btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data']);
			$j = 0; $k = 0; $wp = 3;
			while($j < count($at['p'][$a]['atack'])) {
				if( isset($at['p'][$a]['atack'][$j]['yron']) && (
				$at['p'][$a]['atack'][$j][1] == 4 ||
				$at['p'][$a]['atack'][$j][1] == 5 ||
				( $prv['data_re']['step'] > 0 && $at['p'][$a]['atack'][$j][1] == 1 ) )) {
					if( $prv['data_re']['step'] <= 2 && !isset($at['p'][$a]['priems']['kill'][$u1][$j_id]) ) {
						//
						if( $at['p'][$a]['atack'][$j][1] == 5 ) {
							$pvr['hp'] = round($at['p'][$a]['atack'][$j]['yron']['k']);
						}elseif( $at['p'][$a]['atack'][$j][1] == 4 ) {
							$pvr['hp'] = round($at['p'][$a]['atack'][$j]['yron']['k']/2);
						}elseif( $at['p'][$a]['atack'][$j][1] == 1 ) {
							$pvr['hp'] = round($at['p'][$a]['atack'][$j]['yron']['y']);
						}			
						//
						$at['p'][$a]['atack'][$j]['yron']['used'][] = array($j_id,$u1,$pvr['used']);
						//обновляем параметры
						//
						//
						$pvr['hp'] = round($pvr['hp']/2);
						$pvr['hp'] = $btl->hphe( $u1 , $pvr['hp'] , true );
						if( $btl->users[$btl->uids[$u2]] <= 7 ) {
							$pvr['hp'] = min( $pvr['hp'] , 89 );
						}elseif( $btl->users[$btl->uids[$u2]] == 8 ) {
							$pvr['hp'] = min( $pvr['hp'] , 107 );
						}elseif( $btl->users[$btl->uids[$u2]] == 8 ) {
							$pvr['hp'] = min( $pvr['hp'] , 107 );
						}elseif( $btl->users[$btl->uids[$u2]] == 9 ) {
							$pvr['hp'] = min( $pvr['hp'] , 128 );
						}elseif( $btl->users[$btl->uids[$u2]] >= 10 ) {
							$pvr['hp'] = min( $pvr['hp'] , 154 );
						}
						$pvr['hpSee'] = 0;
						$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u1]]['hpNow']);
						$pvr['hpAll'] = $btl->stats[$btl->uids[$u1]]['hpAll'];
						$pvr['hpTr'] = $pvr['hpAll'] - $pvr['hpNow'];
						if( $pvr['hpTr'] > 0 ) {
							//Требуется хилл
							if( $pvr['hpTr'] < $pvr['hp'] ) {
								$pvr['hp'] = $pvr['hpTr'];
							}
							$pvr['hpSee'] = '+'.$pvr['hp'];
							$pvr['hpNow'] += $pvr['hp'];
						}					
						if( $pvr['hpNow'] > $pvr['hpAll'] ) {
							$pvr['hpNow'] = $pvr['hpAll'];
						}elseif( $pvr['hpNow'] < 0 ) {
							$pvr['hpNow'] = 0;
						}
						//
						$btl->users[$btl->uids[$u1]]['last_hp'] = $pvr['hp'];
						//
						$prv['data_re'] = $u->lookStats($btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data']);
						if($prv['data_re']['step'] < 1) {
							if( $btl->users[$btl->uids[$u1]]['level'] == 7 ) {
								$prv['data_re']['add_s1'] += 10;
							}elseif( $btl->users[$btl->uids[$u1]]['level'] == 8 ) {
								$prv['data_re']['add_s1'] += 13;
							}else{
								$prv['data_re']['add_s1'] += 15;
							}
						}
						if( $prv['data_re']['step'] < 2 ) {
							$prv['data_re']['step']++;
						}else{
							$at['p'][$a]['atack'][$j]['yron']['kill'][] = array($j_id,$u1,$pvr['kill']);
							$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['hod'] = 4;
							$prv['data_re']['step']++;
						}
						//
						$btl->stats[$btl->uids[$u1]]['hpNow'] = $pvr['hpNow'];	
						mysql_query('UPDATE `stats` SET `last_hp` = "'.$btl->users[$btl->uids[$u1]]['last_hp'].'",`hpNow` = "'.$btl->stats[$btl->uids[$u1]]['hpNow'].'" WHERE `id` = "'.$u1.'" LIMIT 1');
						//	
						$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data'] = 'add_s1='.$prv['data_re']['add_s1'].'|step='.$prv['data_re']['step'].'';
						//
						mysql_query('UPDATE `eff_users` SET `hod` = "'.$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['hod'].'", `data` = "'.$btl->stats[$btl->uids[$u1]]['effects'][$prv['j_priem']]['data'].'" WHERE `id` = "'.$prv['priem_th'].'" LIMIT 1');
						//
						$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->priemAddLog( '.$id.', '.$a.', '.$b.', '.$u1.', '.$u2.',
							"Хлебнуть крови",
							"{tm1} '.$btl->addlt($a , 17 , $btl->users[$btl->uids[$u1]]['sex'] , NULL).' на <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']",
						'.($btl->hodID + 1).' );';	
						//
						//$at['p'][$a]['priems']['kill'][$u1][$j_id] = true;
					}
				}
				$j++;
			}	
		}
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}else{
	//Действие при клике
	if( !isset($this->uids) ) {
		$this->addEffPr($pl,$id);
	}
}
unset($pvr);
?>