<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Поступь смерти
	Увеличивает урон за каждое попадание на 1*(лвл) не более 10*(лвл)
*/
$pvr = array();
if( isset($pr_used_this) && isset($pr_moment) && !isset($btl->info['id']) ) {
		
}elseif( isset($pr_tested_this) ) {
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
		if( isset($at['p'][$a]['priems']['used_good'][$uid][$j_id]) ) {
			$prv['j'] = $at['p'][$a]['priems']['used_good'][$uid][$j_id];
			if( $at['p'][$a]['atack'][$prv['j']][1] == 1 || $at['p'][$a]['atack'][$prv['j']][1] == 4 || $at['p'][$a]['atack'][$prv['j']][1] == 5 ) {
				//print_r($j_id);
				//$btl->stats[$btl->uids[$u1]]['u_priem'][$j_id][1]
				$prv['j_priem'] = $btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][0];
				$prv['priem_th'] = $btl->stats[$btl->uids[$uid]]['effects'][$prv['j_priem']]['id'];
				$btl->stats[$btl->uids[$uid]]['effects'][$prv['j_priem']]['hod'] = 3;
				$prv['data_re'] = $u->lookStats($btl->stats[$btl->uids[$uid]]['effects'][$prv['j_priem']]['data']);
				
				if($prv['data_re']['step'] < 10) {
					$prv['data_re']['add_maxAtack'] += $btl->users[$btl->uids[$uid]]['level'];
					$prv['data_re']['step'] += $at['p'][$a]['priems']['used_good_x'][$uid][$j_id];
					if( $prv['data_re']['step'] > 10 ) {
						$prv['data_re']['step'] = 10;
					}
				}
				$btl->stats[$btl->uids[$uid]]['effects'][$prv['j_priem']]['data'] = 'add_maxAtack='.$prv['data_re']['add_maxAtack'].'|step='.$prv['data_re']['step'].'';
				
				mysql_query('UPDATE `eff_users` SET `hod` = "3",`x` = "'.($prv['data_re']['step']).'",`data` = "'.$btl->stats[$btl->uids[$uid]]['effects'][$prv['j_priem']]['data'].'" WHERE `id` = "'.$prv['priem_th'].'" LIMIT 1');
			}
		}
		//
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}elseif( isset($pr_used_this) && isset($btl) ) {
	
}elseif( isset($pr_used_this) ) { 
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
			$j = 0; $k = 0; $wp = 3;
			$prv['j_priem'] = $btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][0];
			while($j < count($at['p'][$a]['atack'])) {
				if( isset($at['p'][$a]['atack'][$j]['yron']) && (
				$at['p'][$a]['atack'][$j][1] == 1 ||
				$at['p'][$a]['atack'][$j][1] == 4 ||
				$at['p'][$a]['atack'][$j][1] == 5 )) {
					if( $pvr['used'] == 0 /*&& !isset($at['p'][$a]['priems']['kill'][$uid][$j_id])*/ ) {
						//
						$at['p'][$a]['atack'][$j]['yron']['y'] += 0;
						$at['p'][$a]['atack'][$j]['yron']['r'] -= 0;
						$at['p'][$a]['atack'][$j]['yron']['k'] += 0;
						$at['p'][$a]['atack'][$j]['yron']['m_y'] += 0;
						$at['p'][$a]['atack'][$j]['yron']['m_k'] += 0;
						//
						if( $btl->stats[$btl->uids[$uid]]['effects'][$prv['j_priem']]['x'] < 2 ) {
							$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->priemAddLog( '.$id.', '.$a.', '.$b.', '.$u1.', '.$u2.',
								"Поступь смерти",
								"{tm1} '.$btl->addlt($a , 17 , $btl->users[$btl->uids[$u1]]['sex'] , NULL).'",
							'.($btl->hodID + 1).' );';	
						}
						//
						$at['p'][$a]['priems']['used_good'][$uid][$j_id] = $j;
						$at['p'][$a]['priems']['used_good_x'][$uid][$j_id]++;
						//
						$at['p'][$a]['atack'][$j]['yron']['used'][] = array($j_id,$uid,$pvr['used']);
						$at['p'][$a]['atack'][$j]['yron']['kill'][] = array($j_id,$uid,$pvr['kill']);
						//
						$at['p'][$a]['priems']['kill'][$uid][$j_id] = true;
					}
				}
				$j++;
			}	
		}
		//Удаляем прием
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}else{
	//Действие при клике
	$this->addEffPr($pl,$id);
}
unset($pvr);
?>