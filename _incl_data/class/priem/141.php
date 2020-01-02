<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Призрачная защита
	Следующий удар противника по вам нанесет на 75% меньше урона
*/
$pvr = array();
if( isset($pr_momental_this)) {
	$fx_moment = function(  $uid, $enemy, $j_id, $yron, $profil ) {
		if(!isset($btl->stats[$btl->uids[$uid]]['um_priem'][$j_id])) {
			global $u, $btl;
			if( $btl->stats[$btl->uids[$u2]]['effects'][$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][0]]['name'] == 'Бесчувственность' ) {
				$yron = $yron/10;
			}else{
				$yron = $yron/4;
			}
			$btl->priemAddLogFast( $uid, 0, "".$btl->stats[$btl->uids[$u2]]['effects'][$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][0]]['name']."",
				'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$uid]]['sex'] , NULL).'',
			0, time() );
			if( $yron < 0 ) {
				$yron = 1;
			}
			$btl->stats[$btl->uids[$uid]]['um_priem'][$j_id] = true;
		}
		return round($yron);
	};
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
		if( $uid == $uid2 ) {
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
		}elseif( $uid == $uid1 ) {
			$a = 2;
			$b = 1;
			$u1 = ${'uid2'};
			$u2 = ${'uid1'};
		}
		if( isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) ) {	
				mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].'" AND `uid` = "'.$uid.'" LIMIT 1');
				unset($btl->stats[$btl->uids[$uid]]['u_priem'][$j_id]);
		}
		//
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
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
		if( $uid == $uid2 ) {
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
		}elseif( $uid == $uid1 ) {
			$a = 2;
			$b = 1;
			$u1 = ${'uid2'};
			$u2 = ${'uid1'};
		}
		if( $a > 0 ) {
			$j = 0; $k = 0; $wp = 3;
			while($j < count($at['p'][$a]['atack'])) {
				if( isset($at['p'][$a]['atack'][$j]['yron']) && (
				$at['p'][$a]['atack'][$j][1] == 1 ||
				$at['p'][$a]['atack'][$j][1] == 4 ||
				$at['p'][$a]['atack'][$j][1] == 5 )) {
					//if( !isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) ) {
						//
						if( $btl->stats[$btl->uids[$u2]]['effects'][$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][0]]['name'] == 'Бесчувственность' ) {
							$at['p'][$a]['atack'][$j]['yron']['y'] = round($at['p'][$a]['atack'][$j]['yron']['y']/10);
							$at['p'][$a]['atack'][$j]['yron']['r'] = round($at['p'][$a]['atack'][$j]['yron']['r']/10);
							$at['p'][$a]['atack'][$j]['yron']['k'] = round($at['p'][$a]['atack'][$j]['yron']['k']/10);
							$at['p'][$a]['atack'][$j]['yron']['m_k'] = round($at['p'][$a]['atack'][$j]['yron']['m_k']/10);
							$at['p'][$a]['atack'][$j]['yron']['m_y'] = round($at['p'][$a]['atack'][$j]['yron']['m_y']/10);
						}else{
							$at['p'][$a]['atack'][$j]['yron']['y'] = round($at['p'][$a]['atack'][$j]['yron']['y']/4);
							$at['p'][$a]['atack'][$j]['yron']['r'] = round($at['p'][$a]['atack'][$j]['yron']['r']/4);
							$at['p'][$a]['atack'][$j]['yron']['k'] = round($at['p'][$a]['atack'][$j]['yron']['k']/4);
							$at['p'][$a]['atack'][$j]['yron']['m_k'] = round($at['p'][$a]['atack'][$j]['yron']['m_k']/4);
							$at['p'][$a]['atack'][$j]['yron']['m_y'] = round($at['p'][$a]['atack'][$j]['yron']['m_y']/4);
						}
						//
						if( $at['p'][$a]['atack'][$j]['yron']['y'] < 1 ) { $at['p'][$a]['atack'][$j]['yron']['y'] = 1; }
						if( $at['p'][$a]['atack'][$j]['yron']['r'] >= 0 ) { $at['p'][$a]['atack'][$j]['yron']['r'] = -1; }
						if( $at['p'][$a]['atack'][$j]['yron']['k'] < 1 ) { $at['p'][$a]['atack'][$j]['yron']['k'] = 1; }
						if( $at['p'][$a]['atack'][$j]['yron']['m_k'] < 1 ) { $at['p'][$a]['atack'][$j]['yron']['m_k'] = 1; }
						if( $at['p'][$a]['atack'][$j]['yron']['m_y'] < 1 ) { $at['p'][$a]['atack'][$j]['yron']['m_y'] = 1; }
						//						
						if( $pvr['used'] == 0 ) {
							$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->deleffm(141,'.(0+$uid).','.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].');
								$this->priemAddLog( '.$id.', '.$b.', '.$a.', '.$u2.', '.$u1.',
								"'.$btl->stats[$btl->uids[$u2]]['effects'][$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][0]]['name'].'",
								"{tm1} '.$btl->addlt($b , 17 , $btl->users[$btl->uids[$u2]]['sex'] , NULL).'",
							'.($btl->hodID + 1).' );';
						}
						//
						$at['p'][$a]['atack'][$j]['yron']['used'][] = array($j_id,$uid,$pvr['used']);
						$at['p'][$a]['atack'][$j]['yron']['kill'][] = array($j_id,$uid,$pvr['kill']);
						//
						$at['p'][$a]['priems']['kill'][$uid][$j_id] = true;
					//}
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
	$this->addEffPr($pl,$id);
}
unset($pvr);
?>