<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Возмездие
	наносит урон противнику на 6*(лвл) ед. урона
*/
$pvr = array();
if( isset($pr_momental_this)) {
	$fx_moment = function(  $uid, $enemy, $j_id, $yron, $profil ) {
		global $u, $btl;
		if(!isset($btl->stats[$btl->uids[$uid]]['um_priem'][$j_id]) && isset($btl->stats[$btl->uids[$uid]]['u_priem'][$j_id])) {
			$btl->stats[$btl->uids[$uid]]['um_priem'][$j_id] = true;
							//Ответка
							$pvr['hp'] = 6*$btl->users[$btl->uids[$uid]]['level'];
							$pvr['hpSee'] = '--';
							$pvr['hpNow'] = floor($btl->stats[$btl->uids[$enemy]]['hpNow']);
							$pvr['hpAll'] = $btl->stats[$btl->uids[$enemy]]['hpAll'];
								
							//Используем проверку на урон приемов
							$pvr['hp'] = $btl->testYronPriem( $uid, $enemy, 12, $pvr['hp'], -1, true, true, 1 );							
							$pvr['hpSee'] = '-'.$pvr['hp'];
							$pvr['hpNow'] -= $pvr['hp'];
							$btl->takeYronNow($uid,$pvr['hp']);
								
							if( $pvr['hpNow'] > $pvr['hpAll'] ) {
								$pvr['hpNow'] = $pvr['hpAll'];
							}elseif( $pvr['hpNow'] < 0 ) {
								$pvr['hpNow'] = 0;
							}							
							$btl->stats[$btl->uids[$enemy]]['hpNow'] = $pvr['hpNow'];							
							mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$enemy]]['hpNow'].'" WHERE `id` = "'.$enemy.'" LIMIT 1');
							//
			//Удаляем прием
			//
			mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].'" AND `uid` = "'.$uid.'" LIMIT 1');
			unset($btl->stats[$btl->uids[$uid]]['u_priem'][$j_id]);
			//
			$btl->priemAddLogFast( $uid, $enemy, "Возмездие",
				'{tm1} '.$btl->addlt(1 , 17 , $btl->users[$btl->uids[$uid]]['sex'] , NULL).' на {u2}. <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']',
			1, time() );
			if( $yron < 0 ) {
				$yron = 1;
			}
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
					if( $pvr['used'] == 0 && !isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) && !isset($btl->stats[$btl->uids[$uid]]['um_priem'][$j_id]) ) {
						//
						$btl->stats[$btl->uids[$uid]]['um_priem'][$j_id] = true;
						//
						$at['p'][$a]['priems']['kill'][$uid][$j_id] = true;
						//
						$pvr['hp'] = 6*$btl->users[$btl->uids[$u2]]['level'];
						$pvr['hpSee'] = '--';
						$pvr['hpNow'] = floor($btl->stats[$btl->uids[$u1]]['hpNow']);
						$pvr['hpAll'] = $btl->stats[$btl->uids[$u1]]['hpAll'];
							
						//Используем проверку на урон приемов
						$pvr['hp'] = $btl->testYronPriem( $u2, $u1, 12, $pvr['hp'], -1, true );							
						$pvr['hpSee'] = '-'.$pvr['hp'];
						$pvr['hpNow'] -= $pvr['hp'];
						$btl->takeYronNow($u2,$pvr['hp']);
							
						if( $pvr['hpNow'] > $pvr['hpAll'] ) {
							$pvr['hpNow'] = $pvr['hpAll'];
						}elseif( $pvr['hpNow'] < 0 ) {
							$pvr['hpNow'] = 0;
						}							
						$btl->stats[$btl->uids[$u1]]['hpNow'] = $pvr['hpNow'];							
						mysql_query('UPDATE `stats` SET `hpNow` = "'.$btl->stats[$btl->uids[$u1]]['hpNow'].'" WHERE `id` = "'.$u1.'" LIMIT 1');
						//					
						$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->deleffm(226,'.(0+$uid).','.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].');
						$this->priemAddLog( '.$id.', '.$b.', '.$a.', '.$u2.', '.$u1.',
							"Возмездие",
							"{tm1} '.$btl->addlt($b , 17 , $btl->users[$btl->uids[$u2]]['sex'] , NULL).' на {u2}. <font Color=#006699><b>'.$pvr['hpSee'].'</b></font> ['.$pvr['hpNow'].'/'.$pvr['hpAll'].']",
						'.($btl->hodID + 1).' );';
						//
						$at['p'][$a]['atack'][$j]['yron']['used'][] = array($j_id,$uid,$pvr['used']);
						$at['p'][$a]['atack'][$j]['yron']['kill'][] = array($j_id,$uid,$pvr['kill']);
						//
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
	$this->addEffPr($pl,$id);
}
unset($pvr);
?>