<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Огненный щит
	Следующий удар противника по вам нанесет на 50% меньше урона, 50% восстанавливает манну
*/
$pvr = array();
if( isset($pr_momental_this)) {
	$fx_moment = function(  $uid, $enemy, $j_id, $yron, $profil ) {
		if(!isset($btl->stats[$btl->uids[$uid]]['um_priem'][$j_id])) {
			global $u, $btl;
			$yron = $yron/2;
			$btl->priemAddLogFast( $uid, 0, "Огненный Щит",
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
				//mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].'" AND `uid` = "'.$uid.'" LIMIT 1');
				//unset($btl->stats[$btl->uids[$uid]]['u_priem'][$j_id]);
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
						$at['p'][$a]['atack'][$j]['yron']['y'] = floor($at['p'][$a]['atack'][$j]['yron']['y']/2);
						$at['p'][$a]['atack'][$j]['yron']['r'] = floor($at['p'][$a]['atack'][$j]['yron']['r']/2);
						$at['p'][$a]['atack'][$j]['yron']['k'] = floor($at['p'][$a]['atack'][$j]['yron']['k']/2);
						$at['p'][$a]['atack'][$j]['yron']['m_k'] = floor($at['p'][$a]['atack'][$j]['yron']['m_k']/2);
						$at['p'][$a]['atack'][$j]['yron']['m_y'] = floor($at['p'][$a]['atack'][$j]['yron']['m_y']/2);
						//
						if( $at['p'][$a]['atack'][$j]['yron']['y'] < 1 ) { $at['p'][$a]['atack'][$j]['yron']['y'] = 0; }
						if( $at['p'][$a]['atack'][$j]['yron']['r'] >= 0 ) { $at['p'][$a]['atack'][$j]['yron']['r'] = -1; }
						if( $at['p'][$a]['atack'][$j]['yron']['k'] < 1 ) { $at['p'][$a]['atack'][$j]['yron']['k'] = 0; }
						if( $at['p'][$a]['atack'][$j]['yron']['m_k'] < 1 ) { $at['p'][$a]['atack'][$j]['yron']['m_k'] = 0; }
						if( $at['p'][$a]['atack'][$j]['yron']['m_y'] < 1 ) { $at['p'][$a]['atack'][$j]['yron']['m_y'] = 0; }
						//						
						if( $pvr['used'] == 0 ) {
							//
							$pvr['mp'] = round($at['p'][$a]['atack'][$j]['yron']['y']);
							$pvr['mpNow'] = floor($btl->stats[$btl->uids[$u2]]['mpNow']);
							$pvr['mpAll'] = $btl->stats[$btl->uids[$u2]]['mpAll'];
							//
							$pvr['mpSee'] = '--';
							$pvr['mpTr'] = $pvr['mpAll'] - $pvr['mpNow'];
							//
							//
							if( $pvr['mpTr'] > 0 ) {
								//Требуется хилл
								if( $pvr['mpTr'] < $pvr['mp'] ) {
									$pvr['mp'] = $pvr['mpTr'];
								}
								$pvr['mpSee'] = '+'.$pvr['mp'];
							}
							$pvr['mpNow'] += $pvr['mp'];
							if( $pvr['mpNow'] > $pvr['mpAll'] ) {
								$pvr['mpNow'] = $pvr['mpAll'];
							}elseif( $pvr['mpNow'] < 0 ) {
								$pvr['mpNow'] = 0;
							}
							if( $pvr['mp'] == 0 ) {
								$pvr['mpSee'] = '--';
							}
							//
							$btl->stats[$btl->uids[$u2]]['mpNow'] = $pvr['mpNow'];
							$btl->users[$btl->uids[$u2]]['mpNow'] = $pvr['mpNow'];		
							mysql_query('UPDATE `stats` SET `mpNow` = "'.$btl->stats[$btl->uids[$u2]]['mpNow'].'" WHERE `id` = "'.$btl->users[$btl->uids[$u2]]['id'].'" LIMIT 1');
							//
							$pvr['healmp'] = ' на <b><font color=#006699>'.$pvr['mpSee'].'</font></b> ['.$pvr['mpNow'].'/'.$pvr['mpAll'].'] (Мана)';
							//
							$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->priemAddLog( '.$id.', '.$b.', '.$a.', '.$u2.', '.$u1.',
								"Огненный Щит",
								"{tm1} '.$btl->addlt($b , 17 , $btl->users[$btl->uids[$u2]]['sex'] , NULL).''.$pvr['healmp'].'",
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